<?php 
get_header(); 
$dirbase = get_template_directory();
// require_once $dirbase . '/template-parts/posts-meta-home.php';
  $imagem_excelencia = get_post_meta( 92, 'imagem_excelencia', true);
    $titulo_excelencia = get_post_meta( 92, 'titulo_excelencia', true);
    $texto_excelencia = get_post_meta( 92, 'texto_excelencia', true);
    $logo = get_post_meta( 92, 'logo_brz', true);
    $grupo_metragens = get_post_meta( 92, 'grupo_brz_numero', true);
    $titulos_sonhos = get_post_meta( 92, 'titulo_sonhos', true);
    $texto_sonhos = get_post_meta( 92, 'texto_sonhos', true);
    $titulos_praticas = get_post_meta( 92, 'titulo_praticas', true);
    $texto_praticas = get_post_meta( 92, 'texto_praticas', true);

    $imagem_trajetoria = get_post_meta( 92, 'imagem_trajetoria', true);
    $titulo_trajetoria = get_post_meta( 92, 'titulo_trajetoria', true);
    $texto_trajetoria = get_post_meta( 92, 'texto_trajetoria', true);

    
?>

    <!-- Topo do painel da página principal -->
    <article class="painel-home container">
        <section class="top">
            <div class="box-title">
                <h3 class="white-text center-align m-0">
                    <strong>Mapa</strong>
                </h4>
            </div>
            <div class="box-logo">
                <img class="logo" src="<?php bloginfo('template_url'); ?>/img/logo.png" alt="Logo da BRZ Empreendimentos">
            </div>
        </section>
        <section class="box-mapa white">
            <!-- Overlay de loading -->
            <div id="loading-overlay" class="loading-overlay">
                <div class="loading-content">
                    <div class="loading-spinner"></div>
                    <h3 id="loading-title">Carregando Mapa do Brasil</h3>
                    <p id="loading-message">Preparando dados geográficos...</p>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div id="progress-fill" class="progress-fill"></div>
                        </div>
                        <span id="progress-text">0%</span>
                    </div>
                    <div id="loading-details" class="loading-details">
                        <div class="loading-step">
                            <span id="step-1" class="step-indicator pending">1</span>
                            <span class="step-label">Carregando dados do mapa</span>
                        </div>
                        <div class="loading-step">
                            <span id="step-2" class="step-indicator pending">2</span>
                            <span class="step-label">Buscando coordenadas das cidades</span>
                        </div>
                        <div class="loading-step">
                            <span id="step-3" class="step-indicator pending">3</span>
                            <span class="step-label">Renderizando mapa</span>
                        </div>
                    </div>
                </div>
            </div>
            <svg></svg>
        </section>
        <section class="footer">

        </section>
        <!-- Botão para abrir a página institucional -->
        <div class="flex-buttons-floating">
            <!-- <a class="grey darken-1 custom-btn modal-trigger" href="#institucional"><span class="yellow"></span></a> -->
            <button class="btn waves-effect grey darken-1 custom-btn waves-light" popovertarget="institucional"><span class="yellow"></span>Institucional</button>
        </div>
    </article>
<!-- Modal da página institucional -->
    <div id="institucional" class="modal no-radius single-window" popover>
    <!-- Botões de voltar e avançar -->
        <div class="floating-next-prev">
            <button onclick="moveSlides('institucional-carousel', 'next')" id="next"><i class="fa-solid fa-chevron-right"></i></button>
            <button onclick="moveSlides('institucional-carousel', 'prev')" id="prev"><i class="fa-solid fa-chevron-left"></i></button>
        </div>
        <div class="modal-content full-height">
            <!-- <div id="institucional-carousel" class="owl-carousel owl-theme full-height"> Caso queira com Owl Carousel --> 
            <div id="institucional-carousel" class="full-height carousel carousel-slider full-height">
                <!-- Excelência -->
                <div class="carousel-item item white full-height scroll-y">
                    <?php
                        $dirbase = get_template_directory();
                        require_once $dirbase . '/template-parts/excelencia.php';
                    ?>
                </div>
                <!-- Números -->
                <div class="carousel-item">
                    <?php
                        $dirbase = get_template_directory();
                        require_once $dirbase . '/template-parts/numeros.php';
                    ?>
                </div>
                <!-- Iniciativas -->
                <div class="carousel-item grey darken-3">
                    <?php
                        $dirbase = get_template_directory();
                        require_once $dirbase . '/template-parts/iniciativas.php';
                    ?>
                </div>



            </div>
        </div>
        <button tabindex="0" class="waves-effect btn-flat close-modal" popovertarget="institucional"><i class="fas fa-undo-alt"></i></button>
    </div>



<?php get_footer(); ?>
<script>
(function() {
    'use strict';

    // ========================================
    // CONFIGURAÇÕES E CONSTANTES
    // ========================================
    
    const CONFIG = {
        MARGIN: 50,
        MARKER_RADIUS: 8,
        DEBOUNCE_DELAY: 250,
        GEOJSON_URL: 'https://raw.githubusercontent.com/codeforamerica/click_that_hood/master/public/data/brazil-states.geojson',
        // Caminho local ao arquivo GeoJSON dentro do tema (crie o arquivo em inc/)
        LOCAL_GEOJSON: '<?php echo get_template_directory_uri(); ?>/inc/brazil-states.geojson',
        GEOCODING_PROXY: '<?php echo admin_url('admin-ajax.php'); ?>?action=geocoding_proxy',
        NOMINATIM_URL: 'https://nominatim.openstreetmap.org/search',
        // Configurações para evitar sobreposição
        MIN_DISTANCE: 25,      // Distância mínima entre marcadores (pixels)
        MAX_ATTEMPTS: 50,      // Máximo de tentativas para reposicionar
        COLLISION_OFFSET: 15   // Offset para reposicionamento
    };

    // ========================================
    // SISTEMA DE LOADING
    // ========================================
    
    class LoadingManager {
        constructor() {
            this.overlay = document.getElementById('loading-overlay');
            this.title = document.getElementById('loading-title');
            this.message = document.getElementById('loading-message');
            this.progressFill = document.getElementById('progress-fill');
            this.progressText = document.getElementById('progress-text');
            this.steps = {
                1: document.getElementById('step-1'),
                2: document.getElementById('step-2'),
                3: document.getElementById('step-3')
            };
            this.currentProgress = 0;
            this.currentStep = 1;
        }
        
        show() {
            this.overlay.classList.remove('hidden');
            this.updateProgress(0, 'Inicializando...');
            this.setStepStatus(1, 'loading');
        }
        
        hide() {
            setTimeout(() => {
                this.overlay.classList.add('hidden');
            }, 500);
        }
        
        updateProgress(percentage, message = null) {
            this.currentProgress = Math.min(100, Math.max(0, percentage));
            this.progressFill.style.width = this.currentProgress + '%';
            this.progressText.textContent = Math.round(this.currentProgress) + '%';
            
            if (message) {
                this.message.textContent = message;
            }
        }
        
        setStepStatus(stepNumber, status) {
            if (this.steps[stepNumber]) {
                // Remove classes anteriores
                this.steps[stepNumber].classList.remove('pending', 'loading', 'completed');
                // Adiciona nova classe
                this.steps[stepNumber].classList.add(status);
                
                if (status === 'completed' && stepNumber < 3) {
                    // Auto-inicia próximo step
                    this.setStepStatus(stepNumber + 1, 'loading');
                }
            }
        }
        
        setTitle(title) {
            this.title.textContent = title;
        }
        
        // Métodos específicos para cada etapa
        startMapLoading() {
            this.setTitle('Carregando Mapa do Brasil');
            this.updateProgress(10, 'Baixando dados geográficos...');
            this.setStepStatus(1, 'loading');
        }
        
        completeMapLoading() {
            this.updateProgress(30, 'Dados do mapa carregados com sucesso');
            this.setStepStatus(1, 'completed');
        }
        
        startCitiesLoading(totalCities = 0) {
            this.setTitle('Buscando Coordenadas');
            this.updateProgress(35, `Processando ${totalCities} cidades...`);
            this.setStepStatus(2, 'loading');
            this.totalCities = totalCities;
            this.processedCities = 0;
        }
        
        updateCityProgress(cityName, success = true) {
            this.processedCities++;
            const cityProgress = 35 + (this.processedCities / this.totalCities) * 35; // 35% a 70%
            
            const status = success ? '✅' : '⚠️';
            this.updateProgress(cityProgress, `${status} ${cityName} (${this.processedCities}/${this.totalCities})`);
        }
        
        completeCitiesLoading() {
            this.updateProgress(70, 'Todas as coordenadas foram processadas');
            this.setStepStatus(2, 'completed');
        }
        
        startRendering() {
            this.setTitle('Renderizando Mapa');
            this.updateProgress(75, 'Criando visualização do mapa...');
            this.setStepStatus(3, 'loading');
        }
        
        updateRenderProgress(message) {
            const renderProgress = 75 + Math.random() * 20; // 75% a 95%
            this.updateProgress(renderProgress, message);
        }
        
        complete() {
            this.updateProgress(100, 'Mapa carregado com sucesso!');
            this.setStepStatus(3, 'completed');
            this.setTitle('Pronto!');
            
            // Esconde o loading após um breve delay
            setTimeout(() => {
                this.hide();
            }, 1000);
        }
        
        error(message) {
            this.setTitle('Erro no Carregamento');
            this.message.textContent = message;
            this.progressFill.style.background = '#dc3545';
            
            // Esconde após 3 segundos
            setTimeout(() => {
                this.hide();
            }, 3000);
        }
    }

    // Cache para coordenadas das cidades
    const coordenadasCache = new Map();
    
    // Array para armazenar posições dos marcadores já colocados
    const marcadoresColocados = [];
    
    // Base de coordenadas fixas das principais cidades brasileiras
    const coordenadasCidades = {
        // São Paulo
        'Campinas': [-47.0608, -22.9056],
        'São Paulo': [-46.6333, -23.5505],
        'Santos': [-46.3336, -23.9618],
        'São José dos Campos': [-45.8867, -23.2237],
        'Sorocaba': [-47.4581, -23.5015],
        'Ribeirão Preto': [-47.8103, -21.1767],
        'São Bernardo do Campo': [-46.5653, -23.6914],
        'Guarulhos': [-46.5333, -23.4628],
        'Osasco': [-46.7917, -23.5329],
        'Bauru': [-49.0847, -22.3147],
        'São José do Rio Preto': [-49.3794, -20.8197],
        'Franca': [-47.4006, -20.5386],
        'Limeira': [-47.4017, -22.5647],
        'Suzano': [-46.3111, -23.5425],
        'Taboão da Serra': [-46.7586, -23.6086],
        'Sumaré': [-47.2669, -22.8219],
        'Barueri': [-46.8764, -23.5108],
        'Embu das Artes': [-46.8522, -23.6489],
        'São Caetano do Sul': [-46.5564, -23.6181],
        'Itu': [-47.2989, -23.2642],
        'Americana': [-47.3314, -22.7394],
        'Indaiatuba': [-47.2181, -23.0903],
        'Araraquara': [-48.1758, -21.7947],
        'Cotia': [-46.9192, -23.6039],
        'Hortolândia': [-47.2200, -22.8583],
        
        // Rio de Janeiro
        'Rio de Janeiro': [-43.1729, -22.9068],
        'Niterói': [-43.1036, -22.8833],
        'Nova Iguaçu': [-43.4514, -22.7592],
        'Duque de Caxias': [-43.3117, -22.7856],
        'São Gonçalo': [-43.0539, -22.8267],
        'Campos dos Goytacazes': [-41.3297, -21.7642],
        'Petrópolis': [-43.1831, -22.5058],
        'Volta Redonda': [-44.1039, -22.5233],
        'Magé': [-43.0403, -22.6572],
        'Itaboraí': [-42.8597, -22.7444],
        'Cabo Frio': [-42.0278, -22.8794],
        'São Pedro da Aldeia': [-42.1011, -22.8408],
        'Macaé': [-41.7869, -22.3708],
        'Angra dos Reis': [-44.3181, -23.0067],
        'Resende': [-44.4497, -22.4686],
        
        // Minas Gerais
        'Belo Horizonte': [-43.9378, -19.9208],
        'Uberlândia': [-48.2772, -18.9186],
        'Contagem': [-44.0536, -19.9317],
        'Juiz de Fora': [-43.3508, -21.7642],
        'Betim': [-44.1983, -19.9678],
        'Montes Claros': [-43.8614, -16.7289],
        'Uberaba': [-47.9311, -19.7483],
        'Governador Valadares': [-41.9497, -18.8506],
        'Ipatinga': [-42.5369, -19.4681],
        'Santa Luzia': [-43.8514, -19.7697],
        'Sete Lagoas': [-44.2469, -19.4658],
        'Divinópolis': [-44.8839, -20.1386],
        'Ibirité': [-44.0581, -20.0217],
        'Poços de Caldas': [-46.5617, -21.7878],
        'Patos de Minas': [-46.5181, -18.5789],
        
        // Bahia
        'Salvador': [-38.5014, -12.9714],
        'Feira de Santana': [-38.9667, -12.2667],
        'Vitória da Conquista': [-40.8394, -14.8619],
        'Camaçari': [-38.3236, -12.6997],
        'Juazeiro': [-40.4983, -9.4108],
        'Ilhéus': [-39.0289, -14.7889],
        'Lauro de Freitas': [-38.3275, -12.8944],
        'Itabuna': [-39.2803, -14.7856],
        'Jequié': [-40.0836, -13.8581],
        'Alagoinhas': [-38.4192, -12.1356],
        'Paulo Afonso': [-38.2128, -9.4011],
        'Simões Filho': [-38.4019, -12.7869],
        'Teixeira de Freitas': [-39.7361, -17.5394],
        'Candeias': [-38.5436, -12.6747],
        'Guanambi': [-42.7819, -14.2239],
        
        // Paraná
        'Curitiba': [-49.2731, -25.4284],
        'Londrina': [-51.1694, -23.3103],
        'Maringá': [-51.9389, -23.4205],
        'Ponta Grossa': [-50.1617, -25.0950],
        'Cascavel': [-53.4553, -24.9556],
        'São José dos Pinhais': [-49.2064, -25.5328],
        'Foz do Iguaçu': [-54.5886, -25.5478],
        'Colombo': [-49.2244, -25.2919],
        'Guarapuava': [-51.4581, -25.3928],
        'Paranaguá': [-48.5225, -25.5203],
        'Araucária': [-49.4156, -25.5928],
        'Toledo': [-53.7428, -24.7133],
        'Apucarana': [-51.4611, -23.5511],
        'Pinhais': [-49.1919, -25.4450],
        'Campo Largo': [-49.5267, -25.4597],
        
        // Rio Grande do Sul
        'Porto Alegre': [-51.2197, -30.0346],
        'Caxias do Sul': [-51.1794, -29.1678],
        'Pelotas': [-52.3425, -31.7654],
        'Canoas': [-51.1836, -29.9178],
        'Santa Maria': [-53.8069, -29.6842],
        'Gravataí': [-50.9922, -29.9442],
        'Viamão': [-51.0233, -30.0811],
        'Novo Hamburgo': [-51.1306, -29.6783],
        'São Leopoldo': [-51.1472, -29.7603],
        'Rio Grande': [-52.0986, -32.0350],
        'Alvorada': [-51.0825, -29.9897],
        'Passo Fundo': [-52.4069, -28.2636],
        'Sapucaia do Sul': [-51.1439, -29.8431],
        'Uruguaiana': [-57.0881, -29.7547],
        'Santa Cruz do Sul': [-52.4261, -29.7203],
        
        // Outros estados importantes
        'Brasília': [-47.8825, -15.7942],
        'Goiânia': [-49.2539, -16.6869],
        'Fortaleza': [-38.5267, -3.7319],
        'Recife': [-34.8817, -8.0476],
        'Manaus': [-60.0261, -3.1190],
        'Belém': [-48.5044, -1.4558],
        'Vitória': [-40.3378, -20.3155],
        'Florianópolis': [-48.5482, -27.5954],
        'João Pessoa': [-34.8641, -7.1195],
        'Natal': [-35.2094, -5.7945],
        'Campo Grande': [-54.6464, -20.4697],
        'Cuiabá': [-56.0979, -15.6014],
        'Maceió': [-35.7353, -9.6658],
        'Teresina': [-42.8019, -5.0892],
        'São Luís': [-44.3028, -2.5387],
        'Aracaju': [-37.0731, -10.9472],
        'Palmas': [-48.3558, -10.1753],
        'Porto Velho': [-63.9004, -8.7619],
        'Rio Branco': [-67.8103, -9.9747],
        'Macapá': [-51.0694, 0.0389],
        'Boa Vista': [-60.6753, 2.8235]
    };
    
    // Dados do PHP
    const estados = [
        <?php
        $terms = get_terms(array(
            'taxonomy' => 'estados',
            'hide_empty' => false,
        ));
        foreach ($terms as $term) {
            $id = $term->term_id;
            $estado = $term->name;
            $uf = get_term_meta($id, 'uf', true);
            echo "{ name: '" . esc_js($estado) . "', sigla: '" . esc_js($uf) . "' },\n";
        }
        ?>
    ];

    const cidades = [
        <?php
        $terms = get_terms(array(
            'taxonomy' => 'cidades',
            'hide_empty' => false,
        ));
        foreach ($terms as $term) {
            $id = $term->term_id;
            $cidade = $term->name;
            $link = get_term_link($id);
            echo "{ name: '" . esc_js($cidade) . "', link: '" . esc_url($link) . "' },\n";
        }
        ?>
    ];

    // ========================================
    // FUNÇÕES DE DETECÇÃO E RESOLUÇÃO DE COLISÕES
    // ========================================
    
    // Verifica se dois pontos estão muito próximos
    function verificarColisao(x1, y1, x2, y2) {
        const distancia = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
        return distancia < CONFIG.MIN_DISTANCE;
    }

    // Encontra uma posição livre para o marcador
    function encontrarPosicaoLivre(xOriginal, yOriginal) {
        let tentativas = 0;
        let x = xOriginal;
        let y = yOriginal;
        
        while (tentativas < CONFIG.MAX_ATTEMPTS) {
            let temColisao = false;
            
            // Verifica colisão com todos os marcadores já colocados
            for (const marcador of marcadoresColocados) {
                if (verificarColisao(x, y, marcador.x, marcador.y)) {
                    temColisao = true;
                    break;
                }
            }
            
            if (!temColisao) {
                return { x, y, ajustado: tentativas > 0 };
            }
            
            // Calcula nova posição em espiral
            const angulo = (tentativas * 45) * (Math.PI / 180); // 45 graus por tentativa
            const raio = CONFIG.COLLISION_OFFSET + (tentativas * 3); // Aumenta o raio gradualmente
            
            x = xOriginal + Math.cos(angulo) * raio;
            y = yOriginal + Math.sin(angulo) * raio;
            
            tentativas++;
        }
        
        // Se não conseguiu encontrar posição livre, usa a original
        console.warn(`Não foi possível resolver colisão para posição (${xOriginal}, ${yOriginal})`);
        return { x: xOriginal, y: yOriginal, ajustado: false };
    }

    // ========================================
    // CLASSE PRINCIPAL DO MAPA
    // ========================================
    
    class MapaBrasil {
        constructor() {
            this.container = document.querySelector('.box-mapa');
            this.svg = d3.select('.box-mapa svg');
            this.projection = d3.geoMercator();
            this.path = d3.geoPath().projection(this.projection);
            this.geojsonData = null;
            this.estadosSelecionados = [];
            
            // Inicializa o sistema de loading
            this.loading = new LoadingManager();
            
            this.init();
        }

        async init() {
            try {
                // Mostra o loading
                this.loading.show();
                this.loading.startMapLoading();
                
                await this.carregarDados();
                this.configurarResponsividade();
                this.renderizar();
            } catch (error) {
                console.error('Erro ao inicializar mapa:', error);
            }
        }

        // ========================================
        // CONFIGURAÇÃO E DIMENSIONAMENTO
        // ========================================
        
        obterDimensoes() {
            const containerRect = this.container.getBoundingClientRect();
            return {
                width: Math.max(containerRect.width - CONFIG.MARGIN, 300),
                height: Math.max(containerRect.height, 400)
            };
        }

        configurarSVG() {
            const { width, height } = this.obterDimensoes();
            
            this.svg
                .attr('width', '100%')
                .attr('height', '100%')
                .attr('viewBox', `0 0 ${width} ${height}`)
                .attr('preserveAspectRatio', 'xMidYMid meet')
                .style('max-width', '100%')
                .style('height', 'auto');

            return { width, height };
        }

        configurarResponsividade() {
            let timeoutId;
            
            const handleResize = () => {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    // Limpa o array de marcadores ao redimensionar
                    marcadoresColocados.length = 0;
                    this.renderizar();
                }, CONFIG.DEBOUNCE_DELAY);
            };

            window.addEventListener('resize', handleResize);
            window.addEventListener('orientationchange', handleResize);
        }

        // ========================================
        // CARREGAMENTO DE DADOS
        // ========================================
        
        async carregarDados() {
            // Tenta carregar o GeoJSON local do tema primeiro, depois cai no GitHub se não houver
            try {
                this.loading.updateProgress(15, 'Conectando ao servidor...');
                
                const urls = [CONFIG.LOCAL_GEOJSON, CONFIG.GEOJSON_URL];
                let loaded = false;
                let lastErr = null;

                for (let i = 0; i < urls.length; i++) {
                    try {
                        const url = urls[i];
                        const source = i === 0 ? 'local' : 'GitHub';
                        this.loading.updateProgress(20 + (i * 5), `Tentando carregar de ${source}...`);
                        
                        // Usa fetch com timeout para termos controle de falhas
                        const controller = new AbortController();
                        const timeoutId = setTimeout(() => controller.abort(), 10000);

                        const resp = await fetch(url, { signal: controller.signal });
                        clearTimeout(timeoutId);

                        if (!resp.ok) {
                            throw new Error(`HTTP ${resp.status}: ${resp.statusText}`);
                        }

                        this.loading.updateProgress(25, 'Processando dados geográficos...');
                        this.geojsonData = await resp.json();
                        
                        loaded = true;
                        break;
                    } catch (err) {
                        lastErr = err;
                        console.warn(`Falha ao carregar GeoJSON de ${urls[i]}:`, err);
                        // continuar para o próximo
                    }
                }

                if (!loaded) {
                    throw lastErr || new Error('GeoJSON não encontrado');
                }

                this.loading.updateProgress(30, 'Filtrando estados selecionados...');
                this.estadosSelecionados = this.geojsonData.features.filter(feature =>
                    estados.some(estado => estado.sigla === feature.properties.sigla)
                );
                
                this.loading.completeMapLoading();
                
            } catch (error) {
                this.loading.error(`Erro ao carregar dados do mapa: ${error.message}`);
                throw new Error(`Erro ao carregar dados do mapa: ${error.message}`);
            }
        }

        async obterCoordenadas(nomeCidade) {
            // Verifica cache primeiro
            if (coordenadasCache.has(nomeCidade)) {
                return coordenadasCache.get(nomeCidade);
            }

            // Verifica base de coordenadas fixas primeiro
            const cidadeNormalizada = this.normalizarNomeCidade(nomeCidade);
            if (coordenadasCidades[cidadeNormalizada]) {
                const coordenadas = coordenadasCidades[cidadeNormalizada];
                coordenadasCache.set(nomeCidade, coordenadas);
                console.log(`✅ Coordenadas encontradas localmente para: ${nomeCidade}`);
                return coordenadas;
            }

            // Busca por nome similar se não encontrou exato
            const cidadeSimilar = this.buscarCidadeSimilar(cidadeNormalizada);
            if (cidadeSimilar) {
                const coordenadas = coordenadasCidades[cidadeSimilar];
                coordenadasCache.set(nomeCidade, coordenadas);
                console.log(`✅ Coordenadas encontradas para cidade similar: ${nomeCidade} -> ${cidadeSimilar}`);
                return coordenadas;
            }

            // Como fallback, tenta usar uma API alternativa via proxy PHP
            try {
                const coordenadas = await this.obterCoordenadasViaProxy(nomeCidade);
                if (coordenadas) {
                    coordenadasCache.set(nomeCidade, coordenadas);
                    return coordenadas;
                }
            } catch (error) {
                console.warn(`Erro no fallback para ${nomeCidade}:`, error);
            }

            console.warn(`❌ Coordenadas não encontradas para: ${nomeCidade}`);
            return null;
        }

        // Normaliza o nome da cidade para busca
        normalizarNomeCidade(nome) {
            return nome
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '') // Remove acentos
                .replace(/[^\w\s]/g, '') // Remove caracteres especiais
                .replace(/\s+/g, ' ') // Normaliza espaços
                .trim();
        }

        // Busca cidade com nome similar
        buscarCidadeSimilar(nomeNormalizado) {
            const nomes = Object.keys(coordenadasCidades);
            
            // Busca exata sem acentos
            for (const nome of nomes) {
                if (this.normalizarNomeCidade(nome) === nomeNormalizado) {
                    return nome;
                }
            }
            
            // Busca parcial (contém)
            for (const nome of nomes) {
                const nomeNorm = this.normalizarNomeCidade(nome);
                if (nomeNorm.includes(nomeNormalizado) || nomeNormalizado.includes(nomeNorm)) {
                    return nome;
                }
            }
            
            return null;
        }

        // Sistema de geocodificação robusto com múltiplos fallbacks
        async obterCoordenadasViaProxy(nomeCidade) {
            // Tentar múltiplas URLs em ordem de preferência
            const urls = [
                // 1. WordPress AJAX (produção)
                `${CONFIG.GEOCODING_PROXY}&cidade=${encodeURIComponent(nomeCidade)}`,
                // 2. Arquivo direto (local/desenvolvimento)
                `${window.location.origin}${window.location.pathname.replace(/[^/]*$/, '')}wp-content/themes/gabriel_theme2/geocoding-proxy.php?cidade=${encodeURIComponent(nomeCidade)}`,
                // 3. Caminho absoluto
                `${window.location.origin}/totem/wp-content/themes/gabriel_theme2/geocoding-proxy.php?cidade=${encodeURIComponent(nomeCidade)}`
            ];
            
            for (let i = 0; i < urls.length; i++) {
                try {
                    console.log(`🔄 Tentativa ${i + 1}/3 para ${nomeCidade}: ${urls[i]}`);
                    
                    const response = await fetch(urls[i], {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        
                        if (data.success && data.coordenadas) {
                            console.log(`✅ Coordenadas obtidas via proxy para: ${nomeCidade} (fonte: ${data.fonte}, tentativa: ${i + 1})`);
                            return data.coordenadas; // [lon, lat]
                        }
                    }
                    
                } catch (error) {
                    console.warn(`⚠️ Tentativa ${i + 1} falhou para ${nomeCidade}:`, error.message);
                }
            }
            
            // Se todas as tentativas falharam, usar coordenadas hardcoded como último recurso
            console.warn(`⚠️ Todas as tentativas de proxy falharam para ${nomeCidade}, usando backup hardcoded`);
            return this.obterCoordenadasBackup(nomeCidade);
        }

        // Backup hardcoded de coordenadas para cidades importantes
        obterCoordenadasBackup(nomeCidade) {
            const coordenadasBackup = {
                'são paulo': [-46.6333, -23.5505],
                'rio de janeiro': [-43.1729, -22.9068],
                'brasília': [-47.8825, -15.7942],
                'salvador': [-38.5014, -12.9714],
                'fortaleza': [-38.5267, -3.7319],
                'belo horizonte': [-43.9378, -19.9208],
                'curitiba': [-49.2731, -25.4284],
                'recife': [-34.8817, -8.0476],
                'porto alegre': [-51.2197, -30.0346],
                'goiânia': [-49.2539, -16.6869],
                'belém': [-48.5044, -1.4558],
                'guarulhos': [-46.5333, -23.4628],
                'campinas': [-47.0608, -22.9056],
                'sorocaba': [-47.4581, -23.5015],
                'ribeirão preto': [-47.8103, -21.1775],
                'santos': [-46.3336, -23.9618],
                'são bernardo do campo': [-46.5653, -23.6914],
                'santo andré': [-46.5386, -23.6633],
                'osasco': [-46.7917, -23.5329],
                'bauru': [-49.0747, -22.3147],
                'jundiaí': [-46.8843, -23.1864],
                'piracicaba': [-47.6492, -22.7253],
                'tatuí': [-47.5706, -23.3547],
                'barretos': [-48.5682, -20.5569],
                'santa bárbara d\'oeste': [-47.4139, -22.7514],
                'paulínia': [-47.1542, -22.7611],
                'jaguariúna': [-46.9856, -22.7056],
                'americana': [-47.3308, -22.7394],
                'limeira': [-47.4017, -22.5647],
                'sumaré': [-47.2667, -22.8219],
                'hortolândia': [-47.2200, -22.8583],
                'indaiatuba': [-47.2178, -23.0922]
            };
            
            const cidadeNormalizada = nomeCidade.toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '') // Remove acentos
                .trim();
            
            // Busca exata
            if (coordenadasBackup[cidadeNormalizada]) {
                console.log(`✅ Coordenadas backup encontradas para: ${nomeCidade}`);
                return coordenadasBackup[cidadeNormalizada];
            }
            
            // Busca parcial
            for (const [cidade, coords] of Object.entries(coordenadasBackup)) {
                if (cidade.includes(cidadeNormalizada) || cidadeNormalizada.includes(cidade)) {
                    console.log(`✅ Coordenadas backup similares encontradas para: ${nomeCidade} (match: ${cidade})`);
                    return coords;
                }
            }
            
            console.error(`❌ Nenhuma coordenada encontrada para: ${nomeCidade}`);
            return null;
        }

        // ========================================
        // RENDERIZAÇÃO
        // ========================================
        
        renderizar() {
            if (!this.geojsonData || !this.estadosSelecionados.length) {
                console.warn('Dados não carregados ainda');
                return;
            }

            // Limpa SVG e array de marcadores
            this.svg.selectAll('*').remove();
            marcadoresColocados.length = 0;

            // Configura dimensões
            const { width, height } = this.configurarSVG();

            // Configura projeção
            this.configurarProjecao(width, height);

            // Renderiza elementos
            this.renderizarEstados();
            this.renderizarSiglasEstados();
            this.renderizarCidades();
        }

        configurarProjecao(width, height) {
            const featureCollection = {
                type: "FeatureCollection",
                features: this.estadosSelecionados
            };

            const center = d3.geoCentroid(featureCollection);
            
            this.projection
                .center(center)
                .fitSize([width, height], featureCollection);
        }

        renderizarEstados() {
            this.svg.selectAll('.state')
                .data(this.estadosSelecionados)
                .enter()
                .append('path')
                .attr('class', 'state')
                .attr('d', this.path)
                .on('mouseover', function() {
                    d3.select(this).classed('highlight', true);
                })
                .on('mouseout', function() {
                    d3.select(this).classed('highlight', false);
                });
        }

        renderizarSiglasEstados() {
            this.estadosSelecionados.forEach(estado => {
                const centroide = this.path.centroid(estado);
                
                this.svg.append('text')
                    .attr('class', 'state-label')
                    .attr('x', centroide[0])
                    .attr('y', centroide[1])
                    .attr('text-anchor', 'middle')
                    .attr('dominant-baseline', 'middle')
                    .text(estado.properties.sigla);
            });
        }

        async renderizarCidades() {
            // Inicia o progresso de carregamento das cidades
            this.loading.startCitiesLoading(cidades.length);
            
            const coordenadasPromises = cidades.map(async (cidade) => {
                try {
                    const coords = await this.obterCoordenadas(cidade.name);
                    this.loading.updateCityProgress(cidade.name, !!coords);
                    return { cidade, coords };
                } catch (error) {
                    console.error(`Erro ao obter coordenadas para ${cidade.name}:`, error);
                    this.loading.updateCityProgress(cidade.name, false);
                    return { cidade, coords: null };
                }
            });

            try {
                const resultados = await Promise.all(coordenadasPromises);
                
                // Completa o carregamento das coordenadas
                this.loading.completeCitiesLoading();
                
                // Inicia a renderização
                this.loading.startRendering();
                this.loading.updateRenderProgress('Adicionando marcadores ao mapa...');
                
                resultados.forEach(({ cidade, coords }) => {
                    if (coords) {
                        this.adicionarMarcadorCidade(cidade, coords);
                    }
                });
                
                this.loading.updateRenderProgress('Finalizando renderização...');
                
                // Completa todo o processo
                this.loading.complete();
                
            } catch (error) {
                console.error('Erro ao renderizar cidades:', error);
                this.loading.error('Erro ao renderizar cidades: ' + error.message);
            }
        }

        adicionarMarcadorCidade(cidade, coordenadas) {
            const projecaoOriginal = this.projection(coordenadas);
            if (!projecaoOriginal) return;
            
            const [xOriginal, yOriginal] = projecaoOriginal;

            // Encontra posição livre para evitar colisões
            const { x, y, ajustado } = encontrarPosicaoLivre(xOriginal, yOriginal);
            
            // Registra a posição do marcador
            marcadoresColocados.push({ x, y, nome: cidade.name });
            
            if (ajustado) {
                console.log(`🔄 Posição ajustada para evitar colisão: ${cidade.name}`);
            }

            // Grupo para cidade
            const cidadeGroup = this.svg.append('g')
                .attr('class', 'cidade-group')
                .style('cursor', 'pointer');

            // Link clicável
            const link = cidadeGroup.append('a')
                .attr('href', cidade.link)
                .attr('target', '_blank');

            // Marcador principal
            const marcador = link.append('circle')
                .attr('class', 'marker')
                .attr('cx', x)
                .attr('cy', y)
                .attr('r', CONFIG.MARKER_RADIUS);

            // Se a posição foi ajustada, adiciona uma linha conectora sutil
            if (ajustado) {
                cidadeGroup.insert('line', 'a')
                    .attr('class', 'connector-line')
                    .attr('x1', xOriginal)
                    .attr('y1', yOriginal)
                    .attr('x2', x)
                    .attr('y2', y)
                    .style('stroke', '#999')
                    .style('stroke-width', '1px')
                    .style('stroke-dasharray', '2,2')
                    .style('opacity', '0.5');
            }

            // Label da cidade (posicionada estrategicamente)
            const labelX = x + (x > xOriginal ? 12 : -12);
            const labelY = y + (y > yOriginal ? -8 : 8);
            
            cidadeGroup.append('text')
                .attr('class', 'city-label')
                .attr('x', labelX)
                .attr('y', labelY)
                .attr('text-anchor', x > xOriginal ? 'start' : 'end')
                .attr('dominant-baseline', 'middle')
                .text(cidade.name);

            // Eventos de hover com feedback visual melhorado
            cidadeGroup
                .on('mouseover', (event) => {
                    // Destaca o marcador
                    marcador.transition()
                        .duration(200)
                        .attr('r', CONFIG.MARKER_RADIUS + 3)
                        .style('stroke-width', '3px');
                    
                    this.mostrarTooltip(event, cidade.name);
                })
                .on('mouseout', () => {
                    // Volta ao tamanho normal
                    marcador.transition()
                        .duration(200)
                        .attr('r', CONFIG.MARKER_RADIUS)
                        .style('stroke-width', '2px');
                    
                    this.esconderTooltip();
                });
        }

        // ========================================
        // TOOLTIP
        // ========================================
        
        mostrarTooltip(event, texto) {
            this.esconderTooltip(); // Remove tooltip anterior
            
            d3.select('body')
                .append('div')
                .attr('class', 'tooltip')
                .style('position', 'absolute')
                .style('background', 'rgba(0, 0, 0, 0.8)')
                .style('color', 'white')
                .style('padding', '8px 12px')
                .style('border-radius', '4px')
                .style('font-size', '12px')
                .style('pointer-events', 'none')
                .style('z-index', '1000')
                .style('left', (event.pageX + 10) + 'px')
                .style('top', (event.pageY - 10) + 'px')
                .text(texto);
        }

        esconderTooltip() {
            d3.select('.tooltip').remove();
        }
    }

    // ========================================
    // INICIALIZAÇÃO
    // ========================================
    
    // Aguarda DOM estar pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => new MapaBrasil());
    } else {
        new MapaBrasil();
    }

})();
</script>
