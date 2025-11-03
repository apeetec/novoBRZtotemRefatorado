<!-- <div class="lista-de-empreendimentos"> -->
    <!-- <a href="<?php echo get_site_url(); ?>" class="back-home"><i class="fa-solid fa-xmark"></i></a> -->
    <?php
        $args = [
            'post_type' => 'empreendimentos', // Tipo de post
            'tax_query' => [
                    [
                        'taxonomy' => 'cidades', // Nome da taxonomia
                        'field'    => 'term_id',       // Campo de busca ('slug', 'term_id' ou 'name')
                        'terms'    => $term_id, // Valor a buscar
                        'include_children' => false, //
                    ],
                ],
        ];
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $titulo = get_the_title($post_id);
                $capa = get_post_meta( $post_id, 'campo_capa', true);
                $cidade = get_post_meta( $post_id, 'campo_cidade', true);
              
    ?>
    <div class="swiper-slide">
        <figure class="white">
            <a href="<?php echo get_the_permalink($post_id);?>"></a>
            <img src="<?php echo $capa;?>" alt="Capa do empreendimento <?php echo $titulo;?>">
            <figcaption>
                <span class="title">
                    <?php echo $titulo;?>
                </span>
                <span class="">
                    <?php echo $cidade;?>
                </span>
            </figcaption>
        </figure>
    </div>
    <?php
                
                }
            } 
                wp_reset_postdata();
    ?>
<!-- </div> -->