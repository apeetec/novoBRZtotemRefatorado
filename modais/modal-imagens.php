<div id="imagens" class="modal white no-radius single-window" popover>
  <div class="modal-content no-padding full-height">
    <div class="row full-height no-gap mobile-row">
        <div class="col s12 m4 l2side-infos">
            <div class="side-box">
                <div class="logo-box" style="background-color:<?php echo $cor; ?>;">
                    <img class="logo" src="<?php echo $logo;?>" alt="Logo do empreendimento <?php echo $titulo;?>">
                </div>
                <div class="window-close">
                    <button tabindex="0" class="waves-effect btn-flat center-align" popovertarget="imagens" style="background-color:<?= htmlspecialchars($cor); ?>;">
                        <i class="fa-solid fa-bars white-text"></i><span class="white-text">&nbsp;Menu principal</span>
                    </button>
                </div>
                <div class="content mt-6">
                    <h5 class="center-align">
                        Imagens
                    </h5>
                    <ul id="tabs-swipe-demo" class="tabs tabs-container tabs-gallery">
                        <?php
                            $cont = -1; // Inicializa $cont com 0
                            if(!empty($galeria)){
                                $src_imagem = '';
                            foreach ($galeria as $foto) {  
                                $cont++; // Incrementa $cont
                                if(!empty($foto['campo_foto'])){
                                    $src_imagem = $foto['campo_foto'];
                                }
                                
                        ?>
                        <li class="tab">
                            <a href="#test-swipe-<?php echo $cont; ?>">
                                <img src="<?= htmlspecialchars($src_imagem); ?>" alt="">
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
                if(!empty($galeria)){
                    $src_imagem = '';
                foreach ($galeria as $foto) {  
                    $cont++; // Incrementa $cont
                    if(!empty($foto['campo_foto'])){
                        $src_imagem = $foto['campo_foto'];
                    }
            ?>
                <div id="test-swipe-<?php echo $cont; ?>" class="col s12 blue full-height">
                    <div class="item full-height">
                        <a data-fslightbox="gallery" class="gallery zoomBtn" href="<?php echo $src_imagem; ?>"></a>
                        <img class="full-responsive-img" src="<?php echo $src_imagem;?>" alt="Imagem da galeria">
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