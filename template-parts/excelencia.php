<div class="row no-gap full-height">
    <div class="col s12 m12 l4">
        <img class="side-img-excelencia" src="<?php echo $imagem_excelencia;?>" alt="imagem lateral excelência" loading="lazy" class="imagem-excelencia">
    </div>
    <div class="col s12 m12 l8 relative">
        <div class="bar yellow bottom"></div>
        <div class="custom-content yellow">
            <div class="box-title">
                <?php
                    echo apply_filters('the_content', $titulo_excelencia);
                ?>
            </div>
        
        </div>
        <div class="custom-content">
            <p><?php echo apply_filters( 'the_content', $texto_excelencia ); ?></p>
            <img src="<?php echo $logo;?>" alt="Logo do BRZ Empreendimentos" class="logo" loading="lazy">
        </div>
    </div>
</div>