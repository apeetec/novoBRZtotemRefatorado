<div class="row line no-gap full-height">
    <div class="col s12 m12 l6 grey darken-3">
        <div class="row">
            <div class="col s9 m9 l9">
                <div class="space-top">
                    <div class="box-title-custom">
                        <h4 class="white-text">
                            <strong>A BRZ em números</strong>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row custom-content">
            <?php         
                foreach($grupo_metragens as $entradas){           
                    $metragem = $entradas['chamda_metragens_brz'];            
                ?>
                <div class="col s12 m12 l6">
                    <?php
                        echo $metragem;
                    ?>
                </div>
            <?php
            }
            ?>
        </div>



    </div> 
    <div class="col white s12 m12 l6 custom-content relative">
        <div class="bar-top yellow"></div>
        <img class="logo-sonhos" src="<?php echo $logo;?>" alt="">
        <div class="custom-container">
            <div class="box-title">
                <?php echo $titulos_sonhos;?>
            </div>
            <div class="mt-6">
                <?php echo $texto_sonhos;?>
            </div>
        </div>
    </div>
</div>