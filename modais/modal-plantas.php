<div id="plantas" class="modal white no-radius single-window" popover>
  <div class="modal-content no-padding full-height">
    <div class="row full-height no-gap mobile-row">
        <div class="col s12 m4 l2 side-infos">
            <div class="side-box">
                <div class="logo-box" style="background-color:<?php echo $cor; ?>;">
                    <img class="logo" src="<?php echo $logo;?>" alt="Logo do empreendimento <?php echo $titulo;?>">
                </div>
                <div class="window-close">
                    <button tabindex="0" class="waves-effect btn-flat center-align" popovertarget="plantas" style="background-color:<?= htmlspecialchars($cor); ?>;">
                        <i class="fa-solid fa-bars white-text"></i><span class="white-text">&nbsp;Menu principal</span>
                    </button>
                </div>
                <div class="content mt-6">
                    <h5 class="center-align">
                        Plantas
                    </h5>
                    <ul id="tabs-swipe-demo" class="tabs tabs-container">
                        <li class="tab">
                            <a class="btn active" href="#empreendimento">
                               <span class="marker" style="background-color:<?php echo $cor; ?>;"></span>O empreendimento</button>
                            </a>
                        </li>
                        <?php
                                $c = 0;
                                foreach($grupo_plantas as $entrada_planta){
                                $c++;
                                if(isset($entrada_planta['campo_texto_botao_plantas'])){
                                $texto_botao = $entrada_planta['campo_texto_botao_plantas'];	
                                }

                            ?>
                        <li class="tab">
                            <a class="btn" href="#planta_swipe_<?php echo $c; ?>">
                                <span class="custom-marker" style="background-color:<?php echo $cor; ?>;"></span><?php echo $texto_botao;?>
                            </a>
                        </li>
                        <?php
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Aba de conteudo, informações, imagens e etc -->
         <div class="col s12 m8 l10 full-height-carousel-tabs-content">

            <div id="empreendimento" class="col s12">
                <div class="custom-content">
                    <div class="left-align">
                        <h4 class="grey-text">
                            Implantação
                        </h4>
                    </div>
                </div>
                <div class="">
                    <img src="<?php echo $implantacao;?>" data-magnify-src="<?php echo $implantacao;?>" alt="Imagem da implantação do empreendimento" class="responsive-img zoom">
                </div>                    
            </div>

            <?php
                $cont2 = 0;
                foreach($grupo_plantas as $planta){
                    $chamada = '';
                    $metragem = '';
                    $imagem_planta = '';
                    if(!empty($planta['campo_chamada'])){
                    $chamada = $planta['campo_chamada'];	
                    }
                    if(!empty($planta['campo_metragem'])){
                    $metragem = $planta['campo_metragem'];	
                    }
                    if(!empty($planta['campo_imagem_planta'])){
                    $imagem_planta = $planta['campo_imagem_planta'];	
                    }
                    $cont2++;
            ?>
            <div id="planta_swipe_<?php echo $cont2; ?>" class="col s12">
                <div class="row custom-content">
                    <div class="col s12 m12 l12">
                        <img class="zoom responsive-img" src="<?php echo $imagem_planta;?>" data-magnify-src="<?php echo $imagem_planta;?>" alt="Imagem da planta do empreendimento">
                    </div>
                    <div class="col s12 m12 l12 custom-padding">
                        <h5 class="grey-text darkeen-text-1">
                            <strong>
                                <?php echo apply_filters('the_content', $chamada);?> 
                            </strong>                                        
                        </h5>
                        <h3 class="">
                            <strong class="yellow-text">
                                <?php echo apply_filters('the_content', $metragem);?>
                            </strong>                    
                        </h3>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>

         </div>
    </div>
  </div>
</div>