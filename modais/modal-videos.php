<div id="videos" class="modal white no-radius single-window" popover>
  <div class="modal-content no-padding full-height">
    <div class="row full-height no-gap mobile-row">
        <div class="col s12 m4 l2 side-infos">
            <div class="side-box">
                <div class="logo-box" style="background-color:<?php echo $cor; ?>;">
                    <img class="logo" src="<?php echo $logo;?>" alt="Logo do empreendimento <?php echo $titulo;?>">
                </div>
                <div class="window-close">
                    <button tabindex="0" class="waves-effect btn-flat center-align" popovertarget="videos" style="background-color:<?= htmlspecialchars($cor); ?>;">
                        <i class="fa-solid fa-bars white-text"></i><span class="white-text">&nbsp;Menu principal</span>
                    </button>
                </div>
                <div class="content mt-6">
                    <h5 class="center-align">
                        Vídeos
                    </h5>
                    <ul id="tabs-swipe-demo" class="tabs tabs-container">
                      <?php
                            $cont = -1; // Inicializa $cont com 0
                            if(!empty($vídeos)){
                            foreach($vídeos as $campos_video){	
                                $cont++;
                                if(isset($campos_video['campo_captive_video'])){
                                    $captive_video = $campos_video['campo_captive_video'];	
                                }
                                if(isset($campos_video['campo_botao_video'])){
                                    $botao_video = $campos_video['campo_botao_video'];	
                                }
                        ?>
                        <li class="tab">
                            <a class="btn" href="#midia_swipe_<?php echo $cont; ?>">
                                <span class="custom-marker" style="background-color:<?php echo $cor; ?>;"></span><?php echo $botao_video;?>
                            </a>
                        </li>
                        <?php
                            }
                            }
                        ?>	
                    </ul>
                </div>
            </div>
        </div>
        <!-- Aba de conteudo, informações, imagens e etc -->
         <div class="col s12 m8 l10 full-height-carousel-tabs-content">
            <?php
              $cont = -1; // Inicializa $cont com 0
                if(!empty($vídeos)){
                    foreach ($vídeos as $video) {  
                        $cont++;
                        if(isset($campos_video['campo_captive_video'])){
                                $captive_video = $campos_video['campo_captive_video'];	
                            }
                            if(isset($campos_video['campo_botao_video'])){
                                $botao_video = $campos_video['campo_botao_video'];	
                            }
                            if(isset($campos_video['campo_video'])){
                                $video = $campos_video['campo_video'];	
                            }
            ?>
                <div id="midia_swipe_<?php echo $cont;?>" class="col s12 full-height">
                    <div class="item full-height">
                        <?php 
                            $tag_video =  do_shortcode('[video src="'.$video.'" width="1920" height="1080"]');
                            echo $tag_video;
                        ?>
                    </div>

                </div>
            <?php
                }
                }
            ?>
         </div>
    </div>
  </div>
</div>