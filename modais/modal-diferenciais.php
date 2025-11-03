<div id="diferenciais" class="modal white no-radius single-window" popover>
  <div class="modal-content no-padding full-height">
    <div class="row full-height no-gap mobile-row">
        <div class="col s12 m4 l2 side-infos">
            <div class="side-box">
                <div class="logo-box" style="background-color:<?php echo $cor; ?>;">
                    <img class="logo" src="<?php echo $logo;?>" alt="Logo do empreendimento <?php echo $titulo;?>">
                </div>
                <div class="window-close">
                    <button tabindex="0" class="waves-effect btn-flat center-align" popovertarget="diferenciais" style="background-color:<?= htmlspecialchars($cor); ?>;">
                        <i class="fa-solid fa-bars white-text"></i><span class="white-text">&nbsp;Menu principal</span>
                    </button>
                </div>
                <div class="content mt-6">
                    <h5 class="center-align">
                        Diferenciais
                    </h5>
                    <ul id="tabs-swipe-demo" class="tabs tabs-container">
                          <?php 
                            if(!empty($diferenciais)){
                        ?>
                        <li class="tab col s3"><a class="active btn" href="#tab-empreendimento"><span class="custom-marker" style="background-color:<?php echo $cor; ?>;"></span>O empreendimento</a></li>
                        <?php
                            }
                        ?>
                        <?php 
                            if(!empty($confortos)){
                        ?>
                            <li class="tab col s3"><a class="btn" href="#tab-conforto"><span class="custom-marker" style="background-color:<?php echo $cor; ?>;"></span>Conforto</a></li>  
                        <?php 
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Aba de conteudo, informações, imagens e etc -->
         <div class="col s12 m8 l10 full-height-carousel-tabs-content grey darken-1">
            <!-- Conteúdo da tab "O empreendimento" -->
            <div id="tab-empreendimento" class="row">
                <div class="box-title custom-content col s12 m12 l12">
                    <h5 class="center-align title yellow-text">
                        Confira tudo o que <br> o empreendimento tem
                    </h5>
                </div>
                <div class="col s12 m12 l12 box-carousel-icons">
                    <div id="carousel-icones" class="carousel">
                        <?php foreach ($diferenciais as $diferencial): ?>
                            <?php 
                                $titulo_diferencial = $diferencial['campo_titulo_diferencial_empreendimento'] ?? '';
                                $texto = $diferencial['campo_texto_difercial_empreendimento'] ?? '';
                                $icone_diferencial = $diferencial['campo_icone_diferencial_empreendimento'] ?? '';
                            ?>
                            <div class="carousel-item">
                                <img class="responsive-img" src="<?= htmlspecialchars($icone_diferencial); ?>" alt="ícone diferencial">
                                <span class="title"><?php echo apply_filters('the_content', $titulo_diferencial); ?></span>
                                <?php echo apply_filters('the_content', $texto); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col s12 m12 l12">
                    <div class="box-diferenciais-lazer">
                        <div class="custom-content">
                            <ul class="row list-icons-diferenciais-lazer">
                                <?php foreach ($diferenciais_lazer as $diferencial_lazer): ?>
                                    <?php 
                                        $titulo_diferencial_lazer = $diferencial_lazer['campo_titulo_Lazer_empreendimento'] ?? '';
                                        $texto_lazer = $diferencial_lazer['campo_texto_Lazer_empreendimento'] ?? '';
                                        $icone_diferencial_lazer = $diferencial_lazer['campo_icone_Lazer_empreendimento'] ?? '';
                                    ?>
                                    <li class="col s12 m4 l2">
                                        <img class="responsive-img" src="<?= htmlspecialchars($icone_diferencial_lazer); ?>" alt="">
                                        <span class="white-text"><?php echo $titulo_diferencial_lazer; ?></span>
                                        <?php echo apply_filters('the_content', $texto_lazer); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Conteúdo da tab "Conforto" -->
            <?php if(!empty($confortos)): ?>
            <div id="tab-conforto" class="full-height" style="display: none;">
                <div class="row full-height">
                    <?php if(!empty($diferenciais_lazer_imagem_lateral)): ?>
                    <div class="col s12 m12 l4">
                        <img class="side-image" src="<?= htmlspecialchars($diferenciais_lazer_imagem_lateral); ?>" alt="Imagem lateral da área de diferencial">
                    </div>
                    <?php endif; ?>
                    <div class="col s12 m12 l8 box-infos">
                        <div class="custom-content">
                            <h5 class="left-align">
                                <span class="yellow-text"><strong>O conforto</strong></span><br>
                                <span class="yellow-text"><strong>e o espaço que</strong></span><br>
                                <span class="white-text"><strong>toda a família</strong> <br><strong>precisa</strong></span>
                            </h5>
                        </div>
                        <div class="custom-content">
                            <ul class="list-confort">
                                <?php 
                                if(!empty($confortos)):
                                foreach ($confortos as $conforto):
                                ?>
                                    <?php 
                                        $texto_conforto = $conforto['campo_texto_conforto_empreendimento'] ?? '';
                                        $icone_conforto = $conforto['campo_icone_conforto_empreendimento'] ?? '';
                                    ?>
                                    <li class="flex">
                                        <?php if(!empty($icone_conforto)): ?>
                                        <img src="<?= htmlspecialchars($icone_conforto); ?>" alt="ícone da área de conforto" loading="lazy">
                                        <?php endif; ?>
                                        <?php if(!empty($texto_conforto)): ?>
                                        <div><?php echo apply_filters('the_content', $texto_conforto); ?></div>
                                        <?php endif; ?>
                                    </li>
                                <?php				
                                endforeach; 
                                endif
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <?php endif; ?>
         </div>
    </div>
  </div>
</div>