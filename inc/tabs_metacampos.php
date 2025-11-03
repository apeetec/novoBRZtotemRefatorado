<?php

add_action( 'cmb2_admin_init', 'cmb2_sample_metabox' );
function cmb2_sample_metabox() {

	$prefix = 'campo';

	$cmb_demo = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Campos', 'cmb2' ),
		'object_types'  => array( 'empreendimentos', 'post' ), // Post type
		'vertical_tabs' => true, // Set vertical tabs, default false
        'tabs' => array(
            array(
                'id'    => 'geral',
                'icon' => 'dashicons-align-left',
                'title' => 'Geral',
                'fields' => array(
                    $prefix . '_logo',       
                    $prefix . '_escolha_a_cor',         
                    $prefix . '_cidade',  
                    $prefix. '_capa',
                ),
            ),
            array(
                'id'    => 'localizacao',
                'icon' => 'dashicons-align-left',
                'title' => 'Localização',
                'fields' => array(
                    $prefix . '_mapagoogle',                
                    $prefix . '_campo_visao_aerea',            
                ),
            ),
            array(
                'id'    => 'imagens',
                'icon' => 'dashicons-align-left',
                'title' => 'Imagens',
                'fields' => array(
                    $prefix . '_galeria_de_imagens',      
                    $prefix . '_arte', 
                    $prefix . '_imagem_principal',         
                    $prefix . 'textos',               
                ),
            ),
            array(
                'id'    => 'plantas',
                'icon' => 'dashicons-align-left',
                'title' => 'Plantas',
                'fields' => array(
                    $prefix . '_imagem_implantacao',        
                    $prefix . '_especificacoes', 
                   
                    $prefix . '_chamada', 
                    $prefix . '_plantas',   
                    $prefix . '_especificacoes_grupo',   
                    $prefix . '_imagem_planta',     
                    $prefix.'_texto_legal_implantacao',   
                ),
            ),
            array(
                'id'    => 'diferenciais',
                'icon' => 'dashicons-align-left',
                'title' => 'Diferenciais',
                'fields' => array(
                    $prefix . '_diferenciais',   
                    $prefix . '_diferenciais_lazer',     
                    $prefix . '_conforto',     
                    $prefix . '_imagem_lateral_conforto',   
                         
                ),
            ),
            array(
                'id'    => 'videos',
                'icon' => 'dashicons-align-left',
                'title' => 'Videos',
                'fields' => array(
                    $prefix . '_videos',       
                    $prefix . '_captive_video',          
                ),
            ),
            array(
                'id'    => 'ficha',
                'icon' => 'dashicons-align-left',
                'title' => 'Ficha técnica',
                'fields' => array(
                    $prefix . '_lazer',       
                    $prefix . '_imagem_lateral_torres',         
                    $prefix . '_texto_torres_apartamentos',  
                    $prefix . '_imagem_do_topo_estacionamento',         
                    $prefix . '_texto_estacionamento',  
                ),
            ),
        )
        ) );
        // Mapas
        $cmb_demo->add_field( array(
            'name'    => 'Imagem da visão aérea',
            'desc'    => '',
            'id'      => $prefix.'_campo_visao_aerea',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        $cmb_demo->add_field( array(
            'name' => 'Mapa do google',
            'desc' => 'Insira aqui o iframe do google ou outro',
            'default' => '',
            'id' => $prefix.'_mapagoogle',
            'type' => 'textarea_code'
        ) );
        // Imagens
        $cmb_demo->add_field( array(
            'name' => 'Galeria de imagens',
            'desc' => 'Insira as imagens da galeria abaixo da logo',
            'id'   => $prefix.'_galeria_de_imagens',
            'type' => 'file_list',
            // 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
            // 'query_args' => array( 'type' => 'image' ), // Only images attachment
            // Optional, override default text strings
            'text' => array(
                'add_upload_files_text' => 'Adicionar', // default: "Add or Upload Files"
                'remove_image_text' => 'Remover', // default: "Remove Image"
                'file_text' => 'Imagem', // default: "File:"
                'file_download_text' => 'Download', // default: "Download"
                'remove_text' => 'Remover', // default: "Remove"
            ),
        ) );
        $cmb_demo->add_field( array(
            'name'    => 'Arte',
            'desc'    => 'insira a arte que vai ao lado do logo caso tenha, caso não, o texto inserido será adicionado automaticamente',
            'id'      => $prefix.'_arte',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        $cmb_demo->add_field( array(
            'name'    => 'Imagem principal',
            'desc'    => 'imagem principal que vai antes do texto ao lado da logo, só será mostrado caso não tenha arte oficial desse empreendimento',
            'id'      => $prefix.'_imagem_principal',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        $cmb_demo->add_field( array(
            'name'    => 'titulo',
            'desc'    => 'Titulo destacado',
            'default' => '',
            'id'      => $prefix.'_titulo_destacado',
            'type'    => 'text',
        ) );
        $group_field_id = $cmb_demo->add_field( array(
            'id'          => $prefix.'textos',
            'type'        => 'group',
            'description' => __( 'Informe outro texto', 'cmb2' ),
            // 'repeatable'  => false, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'       => __( 'Texto {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'        => __( 'Adicionar outro texto', 'cmb2' ),
                'remove_button'     => __( 'Remover texto', 'cmb2' ),
                'sortable'          => true,
                // 'closed'         => true, // true to have the groups closed by default
                // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
            ),
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Foto',
            'desc'    => 'Foto da galeria',
            'id'      => $prefix.'_foto',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(

                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Texto',
            'desc'    => 'insira o texto',
            'id'      => $prefix.'_texto',
            'type'    => 'wysiwyg',
            'options' => array(),
        ) ); 
        //   Plantas
        $cmb_demo->add_field( array(
            'name'    => 'Imagem da implantação',
            'desc'    => '',
            'id'      => $prefix.'_imagem_implantacao',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        $cmb_demo->add_field( array(
            'name'    => 'Especificacões',
            'desc'    => 'adicione uma especifiação',
            'default' => '',
            'repeatable' => true,
            'id'      => $prefix.'_especificacoes',
            'type'    => 'text_medium'
        ) );
        $group_field_id_grupo_espe = $cmb_demo->add_field( array(
            'id'          => $prefix.'_especificacoes_grupo',
            'type'        => 'group',
            'description' => __( 'Informe outro texto', 'cmb2' ),
            // 'repeatable'  => false, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'       => __( 'Especificação {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'        => __( 'Adicionar outra especificação', 'cmb2' ),
                'remove_button'     => __( 'Remover especificação', 'cmb2' ),
                'sortable'          => true,
                // 'closed'         => true, // true to have the groups closed by default
                // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
            ),
        ) );
        $cmb_demo->add_group_field( $group_field_id_grupo_espe, array(
            'name' => 'Galeria dessa especifiação',
            'desc' => '',
            'id'   => 'galeria_especificacao',
            'type' => 'file_list',
            // 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
            // 'query_args' => array( 'type' => 'image' ), // Only images attachment
            // Optional, override default text strings
            'text' => array(
                'add_upload_files_text' => 'Adicionar', // default: "Add or Upload Files"
                'remove_image_text' => 'Remover', // default: "Remove Image"
                'file_text' => 'Foto', // default: "File:"
                'file_download_text' => 'Download', // default: "Download"
                'remove_text' => 'Remover', // default: "Remove"
            ),
        ) );
        $cmb_demo->add_group_field( $group_field_id_grupo_espe, array(
            'name'    => 'Texto de especificação',
            'desc'    => 'insira o texto de especificações',
            'id'      => $prefix.'_texto_espec',
            'type'    => 'wysiwyg',
            'options' => array(),
        ) ); 

        $cmb_demo->add_field( array(
            'name'    => 'Texto legal',
            'desc'    => 'insira o texto legal',
            'id'      => $prefix.'_texto_legal_implantacao',
            'type'    => 'wysiwyg',
            'options' => array(),
        ) );

        $group_field_id = $cmb_demo->add_field( array(
            'id'          => $prefix.'_plantas',
            'type'        => 'group',
            'description' => __( 'Adicione imagens de plantas e seus texos', 'cmb2' ),
            // 'repeatable'  => false, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'       => __( 'Planta {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'        => __( 'Adicionar nova planta', 'cmb2' ),
                'remove_button'     => __( 'Remover Planta', 'cmb2' ),
                'sortable'          => true,
                // 'closed'         => true, // true to have the groups closed by default
                // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
            ),
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Texto botão',
            'desc'    => 'insira o texto do botao',
            'default' => '',
            'id'      => $prefix.'_texto_botao_plantas',
            'type'    => 'text_medium'
        ) );
        // $cmb_demo->add_group_field( $group_field_id, array(
        //     'name'    => 'Texto chamada',
        //     'desc'    => 'insira o texto de chamada',
        //     'default' => '',
        //     'id'      => $prefix.'_chamada',
        //     'type'    => 'text_medium'
        // ) );

      $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Texto chamada',
            'desc'    => 'insira o texto de chamada',
            'id'      => $prefix.'_chamada',
            'type'    => 'wysiwyg',
            'options' => array(),
        ) );

        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Metragem',
            'desc'    => 'insira a metragem',
            'default' => '',
            'id'      => $prefix.'_metragem',
            'type'    => 'text_medium'
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Imagem da planta',
            'desc'    => 'insira a imagem da planta',
            'id'      => $prefix.'_imagem_planta',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Imagem do mapa chave',
            'desc'    => 'insira a imagem do mapa chave',
            'id'      => $prefix.'_imagem_mapa_chave',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        //   Diferenciais
        $group_field_id = $cmb_demo->add_field( array(
            'id'          => $prefix.'_diferenciais',
            'type'        => 'group',
            'description' => __( 'Diferenciais', 'cmb2' ),
            // 'repeatable'  => false, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'       => __( 'Diferencial empreendimento {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'        => __( 'Adicionar diferencial', 'cmb2' ),
                'remove_button'     => __( 'Remover diferencial', 'cmb2' ),
                'sortable'          => true,
                // 'closed'         => true, // true to have the groups closed by default
                // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
            ),
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Empreendimento',
            'desc'    => 'ícone do diferencial empreendimento',
            'id'      => $prefix.'_icone_diferencial_empreendimento',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Titulo',
            'desc'    => 'insira o titulo do diferencial',
            'default' => '',
            'id'      => $prefix.'_titulo_difercial_empreendimento',
            'type'    => 'text_medium'
        ) );
      $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Texto',
            'desc'    => 'insira o texto do diferencial',
            'id'      => $prefix.'_texto_difercial_empreendimento',
            'type'    => 'wysiwyg',
            'options' => array(),
        ) );
        $group_field_id = $cmb_demo->add_field( array(
            'id'          => $prefix.'_diferenciais_lazer',
            'type'        => 'group',
            'description' => __( 'Lazer', 'cmb2' ),
            // 'repeatable'  => false, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'       => __( 'Lazer empreendimento {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'        => __( 'Adicionar Lazer', 'cmb2' ),
                'remove_button'     => __( 'Remover Lazer', 'cmb2' ),
                'sortable'          => true,
                // 'closed'         => true, // true to have the groups closed by default
                // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
            ),
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Empreendimento',
            'desc'    => 'ícone do Lazer empreendimento',
            'id'      => $prefix.'_icone_Lazer_empreendimento',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                    'image/gif',
                    'image/jpeg',
                    'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Titulo',
            'desc'    => 'insira o titulo do lazer',
            'default' => '',
            'id'      => $prefix.'_titulo_Lazer_empreendimento',
            'type'    => 'text_medium'
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Texto',
            'desc'    => 'insira o texto do lazer',
            'id'      => $prefix.'_texto_Lazer_empreendimento',
            'type'    => 'wysiwyg',
            'options' => array(),
        ) );
        $cmb_demo->add_field( array(
            'name'    => 'Imagem de lateral de conforto',
            'desc'    => 'imagem principal que vai na seção de conforto',
            'id'      => $prefix.'_imagem_lateral_conforto',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        // Conforto
        $group_field_id = $cmb_demo->add_field( array(
            'id'          => $prefix.'_conforto',
            'type'        => 'group',
            'description' => __( 'Conforto', 'cmb2' ),
            // 'repeatable'  => false, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'       => __( 'Conforto empreendimento {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'        => __( 'Adicionar conforto', 'cmb2' ),
                'remove_button'     => __( 'Remover conforto', 'cmb2' ),
                'sortable'          => true,
                // 'closed'         => true, // true to have the groups closed by default
                // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
            ),
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Conforto',
            'desc'    => 'ícone do conforto empreendimento',
            'id'      => $prefix.'_icone_conforto_empreendimento',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        // $cmb_demo->add_group_field( $group_field_id, array(
        //     'name'    => 'Texto',
        //     'desc'    => 'insira o conforto',
        //     'default' => '',
        //     'id'      => $prefix.'_texto_conforto_empreendimento',
        //     'type'    => 'text_medium'
        // ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Texto',
            'desc'    => 'insira o conforto',
            'id'      => $prefix.'_texto_conforto_empreendimento',
            'type'    => 'wysiwyg',
            'options' => array(),
        ) );
        //   Videos
        $group_field_id = $cmb_demo->add_field( array(
            'id'          => $prefix.'_videos',
            'type'        => 'group',
            'description' => __( 'Video', 'cmb2' ),
            // 'repeatable'  => false, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'       => __( 'Vídeo {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'        => __( 'Adicionar vídeo', 'cmb2' ),
                'remove_button'     => __( 'Remover vídeo', 'cmb2' ),
                'sortable'          => true,
                // 'closed'         => true, // true to have the groups closed by default
                // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
            ),
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Captive',
            'desc'    => 'Insira a imagem que será usada para servir como botão de acionamento',
            'id'      => $prefix.'_captive_video',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Texto do botão',
            'desc'    => 'insira o texto do botão caso não tenha imagem para inserir',
            'default' => '',
            'id'      => $prefix.'_botao_video',
            'type'    => 'text',
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Vídeo',
            'desc'    => 'insira o vídeo',
            'id'      => $prefix.'_video',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                // 'type' => array(
                // 	'image/gif',
                // 	'image/jpeg',
                // 	'image/png',
                // ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        // Ficha técnica
        $group_field_id = $cmb_demo->add_field( array(
            'id'          => $prefix.'_lazer',
            'type'        => 'group',
            'description' => __( 'Ficha técnica', 'cmb2' ),
            // 'repeatable'  => false, // use false if you want non-repeatable group
            'options'     => array(
                'group_title'       => __( 'Item de lazer {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
                'add_button'        => __( 'Adicionar item de lazer', 'cmb2' ),
                'remove_button'     => __( 'Remover item de lazer', 'cmb2' ),
                'sortable'          => true,
                // 'closed'         => true, // true to have the groups closed by default
                // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
            ),
        ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Ícone do item de lazer',
            'desc'    => 'insira o ícone do item de lazer',
            'id'      => $prefix.'_icone_item_de_lazer',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        // $cmb_demo->add_group_field( $group_field_id, array(
        //     'name'    => 'Texto',
        //     'desc'    => 'insira o texto do item de lazer',
        //     'default' => '',
        //     'id'      => $prefix.'_texto_item_de_lazer',
        //     'type'    => 'text_medium'
        // ) );
        $cmb_demo->add_group_field( $group_field_id, array(
            'name'    => 'Texto',
            'desc'    => 'insira o texto do item de lazer',
            'default' => '',
            'id'      => $prefix.'_texto_item_de_lazer',
            'type'    => 'wysiwyg',
            'options' => array(),
        ) );
        $cmb_demo->add_field( array(
            'name'    => 'Imagem de torres e apartamentos',
            'desc'    => 'imagem da lateral do texto de torres e apartamentos',
            'id'      => $prefix.'_imagem_lateral_torres',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        $cmb_demo->add_field( array(
            'name'    => 'Texto de torres e apartamentos',
            'desc'    => 'insira o texto de torres e apartamentos',
            'id'      => $prefix.'_texto_torres_apartamentos',
            'type'    => 'wysiwyg',
            'options' => array(),
        ) );

        $cmb_demo->add_field( array(
            'name'    => 'Imagem do topo',
            'desc'    => 'imagem do estacionamento',
            'id'      => $prefix.'_imagem_do_topo_estacionamento',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
            ),
            // query_args are passed to wp.media's library query.
            'query_args' => array(
                // Or only allow gif, jpg, or png images
                'type' => array(
                	'image/gif',
                	'image/jpeg',
                	'image/png',
                ),
            ),
            'preview_size' => 'large', // Image size to use when previewing in the admin.
        ) );
        $cmb_demo->add_field( array(
            'name'    => 'Texto do estacionamento',
            'desc'    => 'insira o texto de estacionamento',
            'id'      => $prefix.'_texto_estacionamento',
            'type'    => 'wysiwyg',
            'options' => array(),
        ) );

    // Geral
    $cmb_demo->add_field( array(
        'name'    => 'Cor',
        'id'      => $prefix.'_escolha_a_cor',
        'type'    => 'colorpicker',
        'default' => '#ffffff',
        // 'options' => array(
        // 	'alpha' => true, // Make this a rgba color picker.
        // ),
    ) );
    $cmb_demo->add_field( array(
        'name'    => 'Cidade',
        'desc'    => 'cidade que vai abaixo da logo',
        'default' => '',
        'id'      => $prefix.'_cidade',
        'type'    => 'text_medium'
    ) );
    $cmb_demo->add_field( array(
        'name'    => 'Logo',
        'desc'    => 'logo do empreendimento',
        'id'      => $prefix.'_logo',
        'type'    => 'file',
        // Optional:
        'options' => array(
            'url' => false, // Hide the text input for the url
        ),
        'text'    => array(
            'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
        ),
        // query_args are passed to wp.media's library query.
        'query_args' => array(
            // Or only allow gif, jpg, or png images
            'type' => array(
            	'image/gif',
            	'image/jpeg',
            	'image/png',
            ),
        ),
        'preview_size' => 'large', // Image size to use when previewing in the admin.
    ) );
    $cmb_demo->add_field( array(
        'name'    => 'Capa',
        'desc'    => 'capa do empreendimento',
        'id'      => $prefix.'_capa',
        'type'    => 'file',
        // Optional:
        'options' => array(
            'url' => false, // Hide the text input for the url
        ),
        'text'    => array(
            'add_upload_file_text' => 'Adicionar' // Change upload button text. Default: "Add or Upload File"
        ),
        // query_args are passed to wp.media's library query.
        'query_args' => array(
            // Or only allow gif, jpg, or png images
            'type' => array(
            	'image/gif',
            	'image/jpeg',
            	'image/png',
            ),
        ),
        'preview_size' => 'large', // Image size to use when previewing in the admin.
    ) );


}
// Fim de tabs


?>