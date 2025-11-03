<div id="localizacao" class="modal white no-radius single-window" popover>
  <div class="modal-content no-padding full-height">
    <div class="row full-height no-gap mobile-row">
        <div class="col s12 m4 l2 side-infos">
            <div class="side-box">
                <div class="logo-box" style="background-color:<?php echo $cor; ?>;">
                    <img class="logo" src="<?php echo $logo;?>" alt="Logo do empreendimento <?php echo $titulo;?>">
                </div>
                <div class="window-close">
                    <button tabindex="0" class="waves-effect btn-flat center-align" popovertarget="localizacao" style="background-color:<?= htmlspecialchars($cor); ?>;">
                        <i class="fa-solid fa-bars white-text"></i><span class="white-text">&nbsp;Menu principal</span>
                    </button>
                </div>
                <div class="content mt-6">
                    <h5 class="center-align">
                        Localização
                    </h5>
                    <!-- <div class="tabs-container">
                        <?php 
                            if(!empty($mapa_aerea)){
                        ?>
                        <div class="tab active" data-target="#mapa" data-slide="0"><button class="btn transparent"><span class="custom-marker" style="background-color:<?php echo $cor; ?>;"></span>Foto aérea</button></div>
                        <?php 
                            }
                        ?>
                        
                        <?php 
                            if(!empty($mapa_google)){
                        ?>
                            <div class="tab" data-target="#mapa" data-slide="1"><button class="btn transparent"><span class="custom-marker" style="background-color:<?php echo $cor; ?>;"></span>Mapa satélite</button></div>
                        <?php 
                            }
                        ?>
                    </div> -->
                    <ul id="tabs-swipe-demo" class="tabs tabs-container">
                        <li class="tab col s3"><a class="active btn" href="#foto-aerea"><span class="custom-marker" style="background-color:<?php echo $cor; ?>;"></span>Foto aérea</a></li>
                        <?php 
                            if(!empty($mapa_google)){
                        ?>
                            <li class="tab col s3"><a class="btn" href="#mapa_satelite"><span class="custom-marker" style="background-color:<?php echo $cor; ?>;"></span>Mapa satélite</a></li>  
                        <?php 
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Aba de conteudo, informações, imagens e etc -->
         <div class="col s12 m8 l10 full-height-carousel-tabs-content">
                <div id="foto-aerea" class="col s12 blue full-height">
                    <?php 
                        if(!empty($mapa_aerea)){
                    ?>
                        <div class="item">
                            <img class="mapa" src="<?php echo $mapa_aerea;?>" alt="Vista áerea do mapa">
                        </div>
                        <?php 
                        }
                    ?>
                </div>
                
                    <?php 
                        if(!empty($mapa_google)){
                    ?>
                        <div id="mapa_satelite" class="col s12 red full-height">
                            <div class="item">
                                <?php echo $mapa_google;?>
                            </div>
                        </div>
                    <?php 
                        }
                    ?>             
         </div>
    </div>
  </div>
</div>