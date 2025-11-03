/**
 * Mapa Interativo do Brasil - Versão Responsiva e Otimizada
 * 
 * Funcionalidades:
 * - Totalmente responsivo
 * - Cache de coordenadas
 * - Debounce no redimensionamento
 * - Tratamento de erros robusto
 * - Performance otimizada
 */

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
        // Caminho local ao arquivo GeoJSON dentro do tema (melhor para ambiente local e hospedagem própria)
        // Crie o arquivo em: /wp-content/themes/gabriel_theme2/inc/brazil-states.geojson
        LOCAL_GEOJSON_PATH: '/wp-content/themes/gabriel_theme2/inc/brazil-states.geojson',
        NOMINATIM_URL: 'https://nominatim.openstreetmap.org/search',
        MIN_WIDTH: 300,
        MIN_HEIGHT: 400
    };

    // Cache para coordenadas das cidades (persiste durante a sessão)
    const coordenadasCache = new Map();
    
    // ========================================
    // UTILITÁRIOS
    // ========================================
    
    /**
     * Função debounce para otimizar eventos de redimensionamento
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Verifica se um elemento está visível na viewport
     */
    function isElementVisible(element) {
        const rect = element.getBoundingClientRect();
        return rect.width > 0 && rect.height > 0;
    }

    // ========================================
    // CLASSE PRINCIPAL DO MAPA
    // ========================================
    
    class MapaBrasil {
        constructor(containerSelector = '.box-mapa', dados = {}) {
            this.containerSelector = containerSelector;
            this.container = document.querySelector(containerSelector);
            this.svg = null;
            this.projection = d3.geoMercator();
            this.path = d3.geoPath().projection(this.projection);
            
            // Dados externos
            this.estados = dados.estados || [];
            this.cidades = dados.cidades || [];
            
            // Estado interno
            this.geojsonData = null;
            this.estadosSelecionados = [];
            this.isInitialized = false;
            this.resizeObserver = null;

            this.init();
        }

        async init() {
            try {
                if (!this.container) {
                    throw new Error(`Container não encontrado: ${this.containerSelector}`);
                }

                this.adicionarIndicadorCarregamento();
                this.svg = d3.select(`${this.containerSelector} svg`);
                
                await this.carregarDados();
                this.configurarResponsividade();
                await this.renderizar();
                
                this.isInitialized = true;
                this.removerIndicadorCarregamento();
                
            } catch (error) {
                console.error('Erro ao inicializar mapa:', error);
                this.mostrarErro('Erro ao carregar o mapa. Tente recarregar a página.');
            }
        }

        // ========================================
        // INDICADORES VISUAIS
        // ========================================
        
        adicionarIndicadorCarregamento() {
            this.container.classList.add('loading');
        }

        removerIndicadorCarregamento() {
            this.container.classList.remove('loading');
        }

        mostrarErro(mensagem) {
            this.container.innerHTML = `
                <div style="
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100%;
                    min-height: 200px;
                    color: #666;
                    text-align: center;
                    font-size: 14px;
                ">
                    <div>
                        <i class="fas fa-exclamation-triangle" style="font-size: 24px; margin-bottom: 10px; color: #ff9800;"></i>
                        <br>
                        ${mensagem}
                    </div>
                </div>
            `;
        }

        // ========================================
        // CONFIGURAÇÃO E DIMENSIONAMENTO
        // ========================================
        
        obterDimensoes() {
            if (!isElementVisible(this.container)) {
                return { width: CONFIG.MIN_WIDTH, height: CONFIG.MIN_HEIGHT };
            }

            const containerRect = this.container.getBoundingClientRect();
            return {
                width: Math.max(containerRect.width - CONFIG.MARGIN, CONFIG.MIN_WIDTH),
                height: Math.max(containerRect.height, CONFIG.MIN_HEIGHT)
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
            // Usando ResizeObserver para melhor performance
            if (window.ResizeObserver) {
                this.resizeObserver = new ResizeObserver(
                    debounce(() => {
                        if (this.isInitialized) {
                            this.renderizar();
                        }
                    }, CONFIG.DEBOUNCE_DELAY)
                );
                this.resizeObserver.observe(this.container);
            } else {
                // Fallback para navegadores antigos
                const handleResize = debounce(() => {
                    if (this.isInitialized) {
                        this.renderizar();
                    }
                }, CONFIG.DEBOUNCE_DELAY);

                window.addEventListener('resize', handleResize);
                window.addEventListener('orientationchange', handleResize);
            }
        }

        // ========================================
        // CARREGAMENTO DE DADOS
        // ========================================
        
        async carregarDados() {
            if (!this.estados.length) {
                console.warn('Nenhum estado fornecido para o mapa');
                return;
            }

            try {
                // Tenta primeiro carregar um arquivo GeoJSON local (reduz problemas de limite de requisições)
                const localUrl = (typeof window !== 'undefined') ? (window.location.origin + CONFIG.LOCAL_GEOJSON_PATH) : CONFIG.GEOJSON_URL;
                const urlsToTry = [localUrl, CONFIG.GEOJSON_URL];

                let lastError = null;
                for (const url of urlsToTry) {
                    try {
                        this.geojsonData = await this.fetchComTimeout(url, 10000);
                        if (this.geojsonData && this.geojsonData.features) {
                            break; // carregou com sucesso
                        }
                    } catch (err) {
                        lastError = err;
                        console.warn(`Falha ao carregar GeoJSON de ${url}:`, err);
                        // continua para próximo fallback
                    }
                }

                if (!this.geojsonData) {
                    throw lastError || new Error('GeoJSON não encontrado');
                }

                this.estadosSelecionados = this.geojsonData.features.filter(feature =>
                    this.estados.some(estado => estado.sigla === feature.properties.sigla)
                );

                if (!this.estadosSelecionados.length) {
                    throw new Error('Nenhum estado válido encontrado nos dados');
                }
            } catch (error) {
                throw new Error(`Erro ao carregar dados do mapa: ${error.message}`);
            }
        }

        async fetchComTimeout(url, timeout = 5000) {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), timeout);

            try {
                const response = await fetch(url, { signal: controller.signal });
                clearTimeout(timeoutId);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                return await response.json();
            } catch (error) {
                clearTimeout(timeoutId);
                if (error.name === 'AbortError') {
                    throw new Error('Timeout ao carregar dados');
                }
                throw error;
            }
        }

        async obterCoordenadas(nomeCidade) {
            // Verifica cache primeiro
            if (coordenadasCache.has(nomeCidade)) {
                return coordenadasCache.get(nomeCidade);
            }

            try {
                const query = encodeURIComponent(`${nomeCidade}, Brazil`);
                const url = `${CONFIG.NOMINATIM_URL}?q=${query}&format=json&addressdetails=1&limit=1`;
                
                const data = await this.fetchComTimeout(url, 5000);
                
                if (data && data[0]) {
                    const coordenadas = [parseFloat(data[0].lon), parseFloat(data[0].lat)];
                    
                    // Valida coordenadas
                    if (isNaN(coordenadas[0]) || isNaN(coordenadas[1])) {
                        throw new Error('Coordenadas inválidas retornadas');
                    }
                    
                    coordenadasCache.set(nomeCidade, coordenadas);
                    return coordenadas;
                }
                
                console.warn(`Coordenadas não encontradas para: ${nomeCidade}`);
                return null;
                
            } catch (error) {
                console.error(`Erro ao obter coordenadas para ${nomeCidade}:`, error);
                return null;
            }
        }

        // ========================================
        // RENDERIZAÇÃO
        // ========================================
        
        async renderizar() {
            if (!this.geojsonData || !this.estadosSelecionados.length) {
                console.warn('Dados não carregados ainda');
                return;
            }

            try {
                // Limpa SVG
                this.svg.selectAll('*').remove();

                // Configura dimensões
                const { width, height } = this.configurarSVG();

                // Configura projeção
                this.configurarProjecao(width, height);

                // Renderiza elementos
                this.renderizarEstados();
                this.renderizarSiglasEstados();
                
                if (this.cidades.length) {
                    await this.renderizarCidades();
                }

            } catch (error) {
                console.error('Erro durante renderização:', error);
                throw error;
            }
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
                .attr('tabindex', '0') // Acessibilidade
                .on('mouseover', function() {
                    d3.select(this).classed('highlight', true);
                })
                .on('mouseout', function() {
                    d3.select(this).classed('highlight', false);
                })
                .on('focus', function() {
                    d3.select(this).classed('highlight', true);
                })
                .on('blur', function() {
                    d3.select(this).classed('highlight', false);
                });
        }

        renderizarSiglasEstados() {
            this.estadosSelecionados.forEach(estado => {
                const centroide = this.path.centroid(estado);
                
                // Verifica se o centroide é válido
                if (isNaN(centroide[0]) || isNaN(centroide[1])) {
                    console.warn(`Centroide inválido para estado: ${estado.properties.sigla}`);
                    return;
                }
                
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
            // Processa cidades em lotes para melhor performance
            const batchSize = 5;
            const lotes = [];
            
            for (let i = 0; i < this.cidades.length; i += batchSize) {
                lotes.push(this.cidades.slice(i, i + batchSize));
            }

            for (const lote of lotes) {
                const promises = lote.map(cidade => 
                    this.obterCoordenadas(cidade.name)
                        .then(coords => ({ cidade, coords }))
                        .catch(error => {
                            console.warn(`Erro ao processar cidade ${cidade.name}:`, error);
                            return { cidade, coords: null };
                        })
                );

                try {
                    const resultados = await Promise.all(promises);
                    
                    resultados.forEach(({ cidade, coords }) => {
                        if (coords) {
                            this.adicionarMarcadorCidade(cidade, coords);
                        }
                    });
                    
                    // Pequena pausa entre lotes para não sobrecarregar
                    await new Promise(resolve => setTimeout(resolve, 100));
                    
                } catch (error) {
                    console.error('Erro ao processar lote de cidades:', error);
                }
            }
        }

        adicionarMarcadorCidade(cidade, coordenadas) {
            const projecao = this.projection(coordenadas);
            
            if (!projecao || isNaN(projecao[0]) || isNaN(projecao[1])) {
                console.warn(`Projeção inválida para cidade: ${cidade.name}`);
                return;
            }
            
            const [x, y] = projecao;

            // Grupo para cidade
            const cidadeGroup = this.svg.append('g')
                .attr('class', 'cidade-group')
                .style('cursor', 'pointer');

            // Link clicável
            const link = cidadeGroup.append('a')
                .attr('href', cidade.link)
                .attr('target', '_blank')
                .attr('rel', 'noopener noreferrer'); // Segurança

            // Marcador
            link.append('circle')
                .attr('class', 'marker')
                .attr('cx', x)
                .attr('cy', y)
                .attr('r', CONFIG.MARKER_RADIUS)
                .attr('tabindex', '0'); // Acessibilidade

            // Label da cidade (responsivo)
            const label = cidadeGroup.append('text')
                .attr('class', 'city-label')
                .attr('x', x + 10)
                .attr('y', y + 5)
                .attr('dominant-baseline', 'middle')
                .text(cidade.name);

            // Eventos de hover
            cidadeGroup
                .on('mouseover', (event) => this.mostrarTooltip(event, cidade.name))
                .on('mouseout', () => this.esconderTooltip())
                .on('focus', (event) => this.mostrarTooltip(event, cidade.name))
                .on('blur', () => this.esconderTooltip());
        }

        // ========================================
        // TOOLTIP
        // ========================================
        
        mostrarTooltip(event, texto) {
            this.esconderTooltip(); // Remove tooltip anterior
            
            const tooltip = d3.select('body')
                .append('div')
                .attr('class', 'tooltip')
                .style('opacity', 0)
                .text(texto);

            // Posicionamento inteligente do tooltip
            const tooltipNode = tooltip.node();
            const tooltipRect = tooltipNode.getBoundingClientRect();
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;

            let left = event.pageX + 10;
            let top = event.pageY - 10;

            // Ajusta se sair da viewport
            if (left + tooltipRect.width > viewportWidth) {
                left = event.pageX - tooltipRect.width - 10;
            }
            
            if (top - tooltipRect.height < 0) {
                top = event.pageY + 20;
            }

            tooltip
                .style('left', left + 'px')
                .style('top', top + 'px')
                .transition()
                .duration(200)
                .style('opacity', 1);
        }

        esconderTooltip() {
            d3.selectAll('.tooltip')
                .transition()
                .duration(200)
                .style('opacity', 0)
                .remove();
        }

        // ========================================
        // CLEANUP
        // ========================================
        
        destroy() {
            if (this.resizeObserver) {
                this.resizeObserver.disconnect();
            }
            
            this.esconderTooltip();
            
            if (this.svg) {
                this.svg.selectAll('*').remove();
            }
            
            this.isInitialized = false;
        }
    }

    // ========================================
    // EXPOSIÇÃO GLOBAL
    // ========================================
    
    // Torna a classe disponível globalmente para uso em outros scripts
    window.MapaBrasil = MapaBrasil;

    // ========================================
    // AUTO-INICIALIZAÇÃO (OPCIONAL)
    // ========================================
    
    // Se houver dados do WordPress disponíveis, inicializa automaticamente
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.dadosMapaWP !== 'undefined') {
            new MapaBrasil('.box-mapa', window.dadosMapaWP);
        }
    });

})();