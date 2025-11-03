<div class="row">
    <div class="col s12 m12 l6">               
        <div class="custom-content">
            <div class="box-title">
                <img class="logo-praticas" src="<?php echo $logo;?>" alt="Logo da BRZ" loading="lazy">
                <div class="">
                    <?php
                        echo apply_filters('the_content', $titulos_praticas);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12 m12 l6">
        <div class="custom-content">
            <div class="white-text">
                <?php
                    echo apply_filters('the_content', $texto_praticas);
                ?>
            </div>
        </div>
    </div>
</div>