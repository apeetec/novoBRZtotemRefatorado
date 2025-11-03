<?php
// REDIRECT 404
add_action('template_redirect','redirect_404');
function redirect_404() {
    if(is_404()) {
        wp_redirect(home_url());
    }
}
// FIM REDIRECT 404

add_action( 'template_redirect', 'attachment_page_redirect', 10 );
function attachment_page_redirect() {
    if( is_attachment() ) {
        $url = wp_get_attachment_url( get_queried_object_id() );
        wp_redirect( home_url(), 301 );
    }
    return;
}

// Disable use XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );
// Disable X-Pingback to header
add_filter( 'wp_headers', 'disable_x_pingback' );
function disable_x_pingback( $headers ) {
    unset( $headers['X-Pingback'] );
return $headers;
}

// Disable comments
function disable_comments() {
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if(post_type_supports($post_type,'comments')) {
            remove_post_type_support($post_type,'comments');
            remove_post_type_support($post_type,'trackbacks');
        }
    }
}
add_action('admin_init','disable_comments');

function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'remove_admin_login_header');
add_theme_support( 'post-thumbnails' );
show_admin_bar(false);

add_action( 'send_headers', 'add_header_xframeoptions' );
function add_header_xframeoptions() {
header( 'X-Frame-Options: SAMEORIGIN' );
}

// Prevent Multi Submit on WPCF7 forms
add_action( 'wp_footer', 'mycustom_wp_footer' );

function mycustom_wp_footer() {
?>

<script type="text/javascript">
var disableSubmit = false;
jQuery('input.wpcf7-submit[type="submit"]').click(function() {
    jQuery(':input[type="submit"]').attr('value',"Enviando...")
    if (disableSubmit == true) {
        return false;
    }
    disableSubmit = true;
    return true;
})
  
var wpcf7Elm = document.querySelector( '.wpcf7' );
if(wpcf7Elm){
wpcf7Elm.addEventListener( 'wpcf7submit', function( event ) {
    jQuery(':input[type="submit"]').attr('value',"Enviar")
    disableSubmit = false;
}, false );
}
</script>
<?php
}

// ##################### SCRIPTS E STYLES ##################### //
function custom_script() {

    // De-register the built in jQuery
    wp_deregister_script('jquery');
    // Register the CDN version
    wp_register_script('jquery', 'https://code.jquery.com/jquery-3.6.3.js', array(), null, false); 
    // Load new jquery
    wp_enqueue_script( 'jquery' );
    // Máscaras
    // wp_enqueue_script('mask-scripts', get_template_directory_uri() .'/js/jquery.mask.js', array('jquery'), null, true); 
    // Extra scripts
    // wp_enqueue_script('extra-scripts', get_template_directory_uri() .'/js/extra-scripts.js', array('jquery'), null, true);

    // CSS do mapa responsivo
    wp_enqueue_style('mapa-responsivo', get_template_directory_uri() . '/css/mapa-responsivo.css', array(), '1.0.0');

}

add_action( 'wp_enqueue_scripts', 'custom_script' );
// ##################### END SCRIPTS E STYLES ##################### //

////////// Disable some endpoints for unauthenticated users
add_filter( 'rest_endpoints', 'disable_default_endpoints' );
function disable_default_endpoints( $endpoints ) {
    $endpoints_to_remove = array(
        '/oembed/1.0',
        '/wp/v2',
        '/wp/v2/media',
        '/wp/v2/types',
        '/wp/v2/statuses',
        '/wp/v2/taxonomies',
        '/wp/v2/tags',
        '/wp/v2/users',
        '/wp/v2/comments',
        '/wp/v2/settings',
        '/wp/v2/themes',
        '/wp/v2/blocks',
        '/wp/v2/oembed',
        '/wp/v2/posts',
        '/wp/v2/pages',
        '/wp/v2/block-renderer',
        '/wp/v2/search',
        '/wp/v2/categories'
    );

    if ( ! is_user_logged_in() ) {
        foreach ( $endpoints_to_remove as $rem_endpoint ) {
            // $base_endpoint = "/wp/v2/{$rem_endpoint}";
            foreach ( $endpoints as $maybe_endpoint => $object ) {
                if ( stripos( $maybe_endpoint, $rem_endpoint ) !== false ) {
                    unset( $endpoints[ $maybe_endpoint ] );
                }
            }
        }
    }
    return $endpoints;
}

// Importandos os metaboxes
$dirbase = get_template_directory();
// Post type empreendimentos
require_once $dirbase . '/inc/posttype_empreendimento.php';
// Categoria de estados dos empreendimentos
require_once $dirbase . '/inc/estados_empreendimentos.php';
// Categoria Cidades dos empreendimentos
require_once $dirbase . '/inc/cidades_empreendimentos.php';
// Campos para categorias de Estados
require_once $dirbase . '/inc/campo_uf_da_categoria_estados.php';
// Tabs
require_once $dirbase . '/inc/tabs_metacampos.php';
// Página de institucional
require_once $dirbase . '/inc/metabox-pagina-institucional.php';

// ========================================
// SISTEMA DE GEOCODIFICAÇÃO AJAX
// ========================================

/**
 * Registra endpoint AJAX para geocodificação
 */
add_action('wp_ajax_geocoding_proxy', 'handle_geocoding_ajax');
add_action('wp_ajax_nopriv_geocoding_proxy', 'handle_geocoding_ajax');

function handle_geocoding_ajax() {
    // Verificações de segurança
    if (!isset($_GET['cidade']) || empty($_GET['cidade'])) {
        wp_die(json_encode(['error' => 'Parâmetro cidade é obrigatório']), 400);
    }
    
    $cidade = sanitize_text_field($_GET['cidade']);
    
    // Headers para CORS e JSON
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    
    try {
        $resultado = geocodificar_cidade($cidade);
        
        if ($resultado) {
            echo json_encode([
                'success' => true,
                'cidade' => $cidade,
                'cidade_normalizada' => normalizar_nome_cidade($cidade),
                'latitude' => $resultado['latitude'],
                'longitude' => $resultado['longitude'],
                'coordenadas' => [$resultado['longitude'], $resultado['latitude']],
                'fonte' => $resultado['fonte'],
                'display_name' => $resultado['display_name'] ?? '',
                'timestamp' => time(),
                'cached' => $resultado['cached'] ?? false
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Coordenadas não encontradas para a cidade: ' . $cidade,
                'cidade' => $cidade
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
    
    wp_die(); // Finaliza a requisição AJAX
}

/**
 * Função principal de geocodificação
 */
function geocodificar_cidade($cidade) {
    $cidade_normalizada = normalizar_nome_cidade($cidade);
    
    // 1. Verifica cache primeiro
    $resultado = obter_coordenadas_cache($cidade_normalizada);
    if ($resultado) {
        $resultado['cached'] = true;
        return $resultado;
    }
    
    // 2. Tenta geocodificar usando Nominatim
    $resultado = geocodificar_nominatim_wp($cidade);
    
    // 3. Se falhar, usa backup
    if (!$resultado) {
        $resultado = geocodificar_backup_wp($cidade);
    }
    
    // Salva no cache se encontrou resultado
    if ($resultado) {
        salvar_coordenadas_cache($cidade_normalizada, $resultado);
        $resultado['cached'] = false;
    }
    
    return $resultado;
}

/**
 * Normaliza nome da cidade
 */
function normalizar_nome_cidade($cidade) {
    // Remove acentos
    $cidade = remove_accents($cidade);
    // Converte para lowercase
    $cidade = strtolower(trim($cidade));
    return $cidade;
}

/**
 * Cache usando transients do WordPress
 */
function obter_coordenadas_cache($cidade) {
    $cache_key = 'geocoding_' . md5($cidade);
    return get_transient($cache_key);
}

function salvar_coordenadas_cache($cidade, $dados) {
    $cache_key = 'geocoding_' . md5($cidade);
    set_transient($cache_key, $dados, 24 * HOUR_IN_SECONDS); // 24 horas
}

/**
 * Geocodificação via Nominatim
 */
function geocodificar_nominatim_wp($cidade) {
    $queries = [
        urlencode($cidade . ', Brazil'),
        urlencode($cidade . ', SP, Brazil'),
        urlencode($cidade . ', Brasil'),
        urlencode($cidade)
    ];
    
    foreach ($queries as $query) {
        $url = "https://nominatim.openstreetmap.org/search?q={$query}&format=json&addressdetails=1&limit=3&countrycodes=br";
        
        $response = wp_remote_get($url, [
            'timeout' => 10,
            'headers' => [
                'User-Agent' => 'WordPress Geocoding/' . get_bloginfo('version')
            ]
        ]);
        
        if (is_wp_error($response)) {
            continue;
        }
        
        $data = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($data && count($data) > 0) {
            foreach ($data as $result) {
                if (isset($result['address']['country_code']) && 
                    $result['address']['country_code'] === 'br') {
                    
                    return [
                        'latitude' => floatval($result['lat']),
                        'longitude' => floatval($result['lon']),
                        'fonte' => 'nominatim',
                        'display_name' => $result['display_name'] ?? ''
                    ];
                }
            }
        }
        
        // Rate limiting
        usleep(500000); // 0.5 segundos
    }
    
    return null;
}

/**
 * Sistema de backup inteligente
 */
function geocodificar_backup_wp($cidade) {
    $coordenadas_backup = [
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
        'guarulhos' => [-46.5333, -23.4628],
        'campinas' => [-47.0608, -22.9056],
        'sorocaba' => [-47.4581, -23.5015],
        'ribeirão preto' => [-47.8103, -21.1775],
        'santos' => [-46.3336, -23.9618],
        'são bernardo do campo' => [-46.5653, -23.6914],
        'santo andré' => [-46.5386, -23.6633],
        'osasco' => [-46.7917, -23.5329],
        'bauru' => [-49.0747, -22.3147],
        'jundiaí' => [-46.8843, -23.1864],
        'piracicaba' => [-47.6492, -22.7253],
        'franca' => [-47.4006, -20.5386],
        'são josé dos campos' => [-45.8869, -23.2237],
        'presidente prudente' => [-51.3890, -22.1256],
        'americana' => [-47.3308, -22.7394],
        'taubaté' => [-45.5556, -23.0264],
        'são carlos' => [-47.8947, -22.0175],
        'marília' => [-49.9456, -22.2139],
        'limeira' => [-47.4017, -22.5647],
        'suzano' => [-46.3111, -23.5425],
        'mogi das cruzes' => [-46.1881, -23.5225],
        'indaiatuba' => [-47.2178, -23.0922],
        'jacareí' => [-45.9661, -23.3053],
        // Cidades específicas que estavam com erro
        'tatuí' => [-47.5706, -23.3547],
        'barretos' => [-48.5682, -20.5569],
        'santa bárbara d\'oeste' => [-47.4139, -22.7514],
        'santa barbara d\'oeste' => [-47.4139, -22.7514],
        'santa barbara doeste' => [-47.4139, -22.7514],
        'paulínia' => [-47.1542, -22.7611],
        'paulinia' => [-47.1542, -22.7611],
        'jaguariúna' => [-46.9856, -22.7056],
        'jaguariunna' => [-46.9856, -22.7056],
        'jaguariuna' => [-46.9856, -22.7056]
    ];
    
    $cidade_normalizada = normalizar_nome_cidade($cidade);
    
    // Busca exata
    if (isset($coordenadas_backup[$cidade_normalizada])) {
        $coords = $coordenadas_backup[$cidade_normalizada];
        return [
            'latitude' => $coords[1],
            'longitude' => $coords[0],
            'fonte' => 'backup'
        ];
    }
    
    // Busca parcial inteligente
    foreach ($coordenadas_backup as $nome => $coords) {
        if (strpos($nome, $cidade_normalizada) !== false || 
            strpos($cidade_normalizada, $nome) !== false) {
            return [
                'latitude' => $coords[1],
                'longitude' => $coords[0],
                'fonte' => 'backup_similar'
            ];
        }
    }
    
    return null;
}


?>