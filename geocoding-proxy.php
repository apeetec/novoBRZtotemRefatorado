<?php
/**
 * Proxy para geocodificação de cidades
 * Contorna problemas de CORS ao fazer requisições para APIs de geocodificação
 */

// Configurações de segurança
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Verifica se é uma requisição OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Verifica se o parâmetro cidade foi fornecido
if (!isset($_GET['cidade']) || empty($_GET['cidade'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Parâmetro cidade é obrigatório']);
    exit();
}

$cidade = sanitize_text_field($_GET['cidade']);
$formato = sanitize_text_field($_GET['formato'] ?? 'json');

/**
 * Sanitiza texto de entrada
 */
function sanitize_text_field($str) {
    return trim(strip_tags($str));
}

/**
 * Faz requisição HTTP com timeout
 */
function fazer_requisicao($url, $timeout = 10) {
    $context = stream_context_create([
        'http' => [
            'timeout' => $timeout,
            'user_agent' => 'Mozilla/5.0 (compatible; CidadeLocator/1.0)',
            'header' => "Accept: application/json\r\n"
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        throw new Exception('Falha na requisição HTTP');
    }
    
    return $response;
}

/**
 * Normaliza nome da cidade para melhorar busca
 */
function normalizar_cidade($cidade) {
    // Remove acentos e caracteres especiais
    $cidade = remove_accents($cidade);
    
    // Converte para lowercase para busca case-insensitive
    $cidade = strtolower(trim($cidade));
    
    // Remove prefixos comuns
    $prefixos = ['cidade de ', 'municipio de ', 'vila '];
    foreach ($prefixos as $prefixo) {
        if (strpos($cidade, $prefixo) === 0) {
            $cidade = substr($cidade, strlen($prefixo));
        }
    }
    
    return $cidade;
}

/**
 * Remove acentos de uma string
 */
function remove_accents($string) {
    $accents = [
        'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
        'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ç' => 'c', 'ñ' => 'n',
        'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'Ä' => 'A',
        'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
        'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ô' => 'O', 'Ö' => 'O',
        'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
        'Ç' => 'C', 'Ñ' => 'N'
    ];
    
    return strtr($string, $accents);
}

/**
 * Sistema de cache para coordenadas
 */
function obter_do_cache($cidade) {
    $cache_file = __DIR__ . '/cache/geocoding_' . md5($cidade) . '.json';
    
    if (file_exists($cache_file) && (time() - filemtime($cache_file) < 86400)) { // 24h
        $content = file_get_contents($cache_file);
        return json_decode($content, true);
    }
    
    return null;
}

function salvar_no_cache($cidade, $dados) {
    $cache_dir = __DIR__ . '/cache';
    if (!is_dir($cache_dir)) {
        @mkdir($cache_dir, 0755, true);
    }
    
    $cache_file = $cache_dir . '/geocoding_' . md5($cidade) . '.json';
    @file_put_contents($cache_file, json_encode($dados));
}

/**
 * Geocodifica usando OpenStreetMap Nominatim
 */
function geocodificar_nominatim($cidade) {
    $queries = [
        urlencode($cidade . ', Brazil'),
        urlencode($cidade . ', SP, Brazil'),
        urlencode($cidade . ', São Paulo, Brazil'),
        urlencode($cidade . ', Brasil'),
        urlencode($cidade)
    ];
    
    foreach ($queries as $query) {
        $url = "https://nominatim.openstreetmap.org/search?q={$query}&format=json&addressdetails=1&limit=3&countrycodes=br";
        
        try {
            $response = fazer_requisicao($url, 8);
            $data = json_decode($response, true);
            
            if ($data && count($data) > 0) {
                // Procura por resultados que sejam cidades brasileiras
                foreach ($data as $result) {
                    if (isset($result['address']['country_code']) && 
                        $result['address']['country_code'] === 'br' &&
                        (isset($result['address']['city']) || 
                         isset($result['address']['town']) || 
                         isset($result['address']['municipality']))) {
                        
                        return [
                            'latitude' => floatval($result['lat']),
                            'longitude' => floatval($result['lon']),
                            'fonte' => 'nominatim',
                            'display_name' => $result['display_name'] ?? ''
                        ];
                    }
                }
            }
            
            // Rate limiting para Nominatim
            usleep(500000); // 0.5 segundos
            
        } catch (Exception $e) {
            error_log("Erro Nominatim para {$cidade} (query: {$query}): " . $e->getMessage());
        }
    }
    
    return null;
}

/**
 * Geocodifica usando API do IBGE (cidades brasileiras)
 */
function geocodificar_ibge($cidade) {
    try {
        // Busca por municipios no IBGE
        $cidade_encoded = urlencode($cidade);
        $url = "https://servicodados.ibge.gov.br/api/v1/localidades/municipios";
        
        $response = fazer_requisicao($url, 10);
        $municipios = json_decode($response, true);
        
        if (!$municipios) {
            return null;
        }
        
        $cidade_normalizada = normalizar_cidade($cidade);
        
        // Busca exata
        foreach ($municipios as $municipio) {
            $nome_municipio = normalizar_cidade($municipio['nome']);
            if ($nome_municipio === $cidade_normalizada) {
                return geocodificar_municipio_ibge($municipio);
            }
        }
        
        // Busca parcial
        foreach ($municipios as $municipio) {
            $nome_municipio = normalizar_cidade($municipio['nome']);
            if (strpos($nome_municipio, $cidade_normalizada) !== false || 
                strpos($cidade_normalizada, $nome_municipio) !== false) {
                return geocodificar_municipio_ibge($municipio);
            }
        }
        
        return null;
        
    } catch (Exception $e) {
        error_log("Erro IBGE para {$cidade}: " . $e->getMessage());
        return null;
    }
}

/**
 * Geocodifica um município específico do IBGE
 */
function geocodificar_municipio_ibge($municipio) {
    // Para municipios do IBGE, usamos o Nominatim com informações mais específicas
    $estado = $municipio['microrregiao']['mesorregiao']['UF']['sigla'];
    $nome_completo = $municipio['nome'] . ', ' . $estado . ', Brazil';
    
    return geocodificar_nominatim($nome_completo);
}

/**
 * Geocodifica usando ViaCEP (para cidades brasileiras)
 */
function geocodificar_viacep($cidade) {
    // ViaCEP não tem geocodificação direta, mas podemos usar para validar
    $query = urlencode($cidade);
    $url = "https://viacep.com.br/ws/{$query}/json/";
    
    try {
        // Esta função seria mais complexa na implementação real
        // Por enquanto retorna null
        return null;
    } catch (Exception $e) {
        error_log("Erro ViaCEP para {$cidade}: " . $e->getMessage());
        return null;
    }
}

/**
 * Geocodifica usando coordenadas fixas de backup (agora mais inteligente)
 */
function geocodificar_backup($cidade) {
    $coordenadas_backup = [
        // Capitais e grandes cidades
        'são paulo' => [-46.6333, -23.5505],
        'rio de janeiro' => [-43.1729, -22.9068],
        'brasília' => [-47.8825, -15.7942],
        'salvador' => [-38.5014, -12.9714],
        'fortaleza' => [-38.5267, -3.7319],
        'belo horizonte' => [-43.9378, -19.9208],
        'manaus' => [-60.0261, -3.1190],
        'curitiba' => [-49.2731, -25.4284],
        'recife' => [-34.8817, -8.0476],
        'porto alegre' => [-51.2197, -30.0346],
        'goiânia' => [-49.2539, -16.6869],
        'belém' => [-48.5044, -1.4558],
        
        // Região metropolitana de SP
        'guarulhos' => [-46.5333, -23.4628],
        'campinas' => [-47.0608, -22.9056],
        'são bernardo do campo' => [-46.5653, -23.6914],
        'santo andré' => [-46.5386, -23.6633],
        'osasco' => [-46.7917, -23.5329],
        'sorocaba' => [-47.4581, -23.5015],
        'ribeirão preto' => [-47.8103, -21.1775],
        'santos' => [-46.3336, -23.9618],
        'mauá' => [-46.4614, -23.6678],
        'diadema' => [-46.6227, -23.6861],
        'carapicuíba' => [-46.8356, -23.5222],
        'piracicaba' => [-47.6492, -22.7253],
        'bauru' => [-49.0747, -22.3147],
        'jundiaí' => [-46.8843, -23.1864],
        'franca' => [-47.4006, -20.5386],
        'são josé dos campos' => [-45.8869, -23.2237],
        'araraquara' => [-48.1758, -21.7947],
        'presidente prudente' => [-51.3890, -22.1256],
        'americana' => [-47.3308, -22.7394],
        'taubaté' => [-45.5556, -23.0264],
        'são carlos' => [-47.8947, -22.0175],
        'marília' => [-49.9456, -22.2139],
        'limeira' => [-47.4017, -22.5647],
        'sumaré' => [-47.2667, -22.8219],
        'suzano' => [-46.3111, -23.5425],
        'taboão da serra' => [-46.7583, -23.6086],
        'barueri' => [-46.8761, -23.5106],
        'embu das artes' => [-46.8522, -23.6489],
        'são vicente' => [-46.3917, -23.9631],
        'praia grande' => [-46.4028, -24.0058],
        'itaquaquecetuba' => [-46.3481, -23.4864],
        'guarujá' => [-46.2564, -23.9936],
        'francisco morato' => [-46.7456, -23.2814],
        'itapevi' => [-46.9389, -23.5489],
        'franco da rocha' => [-46.7286, -23.3258],
        'são caetano do sul' => [-46.5564, -23.6181],
        'mogi das cruzes' => [-46.1881, -23.5225],
        'ferraz de vasconcelos' => [-46.3681, -23.5425],
        'indaiatuba' => [-47.2178, -23.0922],
        'cotia' => [-46.9189, -23.6039],
        'itapecerica da serra' => [-46.8486, -23.7169],
        'hortolândia' => [-47.2200, -22.8583],
        'jacareí' => [-45.9661, -23.3053],
        'leme' => [-47.3900, -22.1856],
        'várzea paulista' => [-46.8281, -23.2114],
        
        // Cidades que estavam com erro
        'tatuí' => [-47.5706, -23.3547],
        'barretos' => [-48.5682, -20.5569],
        'santa bárbara d\'oeste' => [-47.4139, -22.7514],
        'santa barbara d\'oeste' => [-47.4139, -22.7514],
        'santa barbara doeste' => [-47.4139, -22.7514],
        'paulínia' => [-47.1542, -22.7611],
        'paulinia' => [-47.1542, -22.7611],
        
        // Outras cidades importantes
        'são luís' => [-44.3028, -2.5387],
        'são gonçalo' => [-43.0539, -22.8267],
        'maceió' => [-35.7353, -9.6658],
        'duque de caxias' => [-43.3117, -22.7856],
        'natal' => [-35.2094, -5.7945],
        'teresina' => [-42.8019, -5.0892],
        'campo grande' => [-54.6464, -20.4697],
        'nova iguaçu' => [-43.4514, -22.7592],
        'joão pessoa' => [-34.8641, -7.1195],
        'jaboatão dos guararapes' => [-35.0149, -8.1128],
        'contagem' => [-44.0536, -19.9317],
        'uberlândia' => [-48.2772, -18.9186]
    ];
    
    $cidade_normalizada = normalizar_cidade($cidade);
    
    // Busca exata primeiro
    if (isset($coordenadas_backup[$cidade_normalizada])) {
        $coords = $coordenadas_backup[$cidade_normalizada];
        return [
            'latitude' => $coords[1],
            'longitude' => $coords[0],
            'fonte' => 'backup'
        ];
    }
    
    // Busca parcial inteligente
    $melhor_match = null;
    $melhor_score = 0;
    
    foreach ($coordenadas_backup as $nome => $coords) {
        // Calcula score de similaridade
        $score = 0;
        
        // Match exato em qualquer palavra
        $palavras_cidade = explode(' ', $cidade_normalizada);
        $palavras_backup = explode(' ', $nome);
        
        foreach ($palavras_cidade as $palavra) {
            if (strlen($palavra) > 2) { // Ignora palavras muito pequenas
                foreach ($palavras_backup as $palavra_backup) {
                    if ($palavra === $palavra_backup) {
                        $score += 100; // Match exato de palavra
                    } elseif (strpos($palavra_backup, $palavra) !== false) {
                        $score += 50; // Palavra contém
                    } elseif (strpos($palavra, $palavra_backup) !== false) {
                        $score += 30; // Palavra está contida
                    }
                }
            }
        }
        
        // Match de substring
        if (strpos($nome, $cidade_normalizada) !== false) {
            $score += 20;
        } elseif (strpos($cidade_normalizada, $nome) !== false) {
            $score += 15;
        }
        
        // Similaridade usando levenshtein para nomes próximos
        $distancia = levenshtein($cidade_normalizada, $nome);
        if ($distancia <= 3 && strlen($cidade_normalizada) > 4) {
            $score += (10 - $distancia * 2);
        }
        
        if ($score > $melhor_score && $score >= 50) { // Threshold aumentado para 50
            $melhor_score = $score;
            $melhor_match = [
                'latitude' => $coords[1],
                'longitude' => $coords[0],
                'fonte' => 'backup_similar',
                'match_score' => $score,
                'matched_city' => $nome
            ];
        }
    }
    
    return $melhor_match;
}

// Processo principal
try {
    $cidade_original = $cidade;
    $cidade_normalizada = normalizar_cidade($cidade);
    
    // 1. Verifica cache primeiro
    $resultado = obter_do_cache($cidade_normalizada);
    
    if (!$resultado) {
        // 2. Tenta geocodificar usando IBGE (para cidades brasileiras)
        $resultado = geocodificar_ibge($cidade);
        
        // 3. Se falhar, tenta usando Nominatim com várias variações
        if (!$resultado) {
            $resultado = geocodificar_nominatim($cidade);
        }
        
        // 4. Se ainda falhar, tenta com cidade normalizada
        if (!$resultado && $cidade !== $cidade_normalizada) {
            $resultado = geocodificar_nominatim($cidade_normalizada);
        }
        
        // 5. Como último recurso, tenta usando coordenadas de backup
        if (!$resultado) {
            $resultado = geocodificar_backup($cidade);
        }
        
        // Salva no cache se encontrou resultado
        if ($resultado) {
            salvar_no_cache($cidade_normalizada, $resultado);
        }
    } else {
        // Adiciona fonte do cache
        $resultado['fonte'] = 'cache_' . ($resultado['fonte'] ?? 'unknown');
    }
    
    if ($resultado) {
        echo json_encode([
            'success' => true,
            'cidade' => $cidade_original,
            'cidade_normalizada' => $cidade_normalizada,
            'latitude' => $resultado['latitude'],
            'longitude' => $resultado['longitude'],
            'coordenadas' => [$resultado['longitude'], $resultado['latitude']], // [lon, lat] para D3.js
            'fonte' => $resultado['fonte'],
            'display_name' => $resultado['display_name'] ?? '',
            'timestamp' => time(),
            'cached' => strpos($resultado['fonte'], 'cache_') === 0
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Coordenadas não encontradas para a cidade: ' . $cidade_original,
            'cidade' => $cidade_original,
            'cidade_normalizada' => $cidade_normalizada,
            'tentativas' => [
                'ibge' => 'tentativa realizada',
                'nominatim' => 'tentativa realizada',
                'backup' => 'tentativa realizada'
            ]
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno do servidor',
        'error' => $e->getMessage()
    ]);
}
?>