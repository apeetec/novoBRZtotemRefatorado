<?php
add_action( 'cmb2_admin_init', 'yourprefix_register_about_page_metabox' );
/**
 * Hook in and add a metabox that only appears on the 'About' page
 */
function yourprefix_register_about_page_metabox() {
	$prefix = 'yourprefix_about_';

	/**
	 * Metabox to be displayed on a single page ID
	 */
	$cmb_about_page = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => esc_html__( 'About Page Metabox', 'cmb2' ),
		'object_types' => array( 'page' ), // Post type
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, // Show field names on the left
		'show_on'      => array(
			'id' => array( 92 ),
		), // Specific post IDs to display this metabox
	) );

    $cmb_about_page->add_field( array(
        'name'    => 'Imagem lateral de excelência',
        'desc'    => '',
        'id'      => 'imagem_excelencia',
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
    $cmb_about_page->add_field( array(
        'name'    => 'Titulo de excelência',
        'desc'    => 'insira o título de excelência',
        'id'      => 'titulo_excelencia',
        'type'    => 'wysiwyg',
        'options' => array(),
    ) );
    $cmb_about_page->add_field( array(
        'name'    => 'Texto excelência',
        'desc'    => '',
        'id'      => 'texto_excelencia',
        'type'    => 'wysiwyg',
        'options' => array(),
    ) );
    $cmb_about_page->add_field( array(
        'name'    => 'Logo BRZ',
        'desc'    => '',
        'id'      => 'logo_brz',
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

    $group_field_id = $cmb_about_page->add_field( array(
        'id'          => 'grupo_brz_numero',
        'type'        => 'group',
        'description' => __( '', 'cmb2' ),
        // 'repeatable'  => false, // use false if you want non-repeatable group
        'options'     => array(
            'group_title'       => __( 'Entrada {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'        => __( 'Adicionar outra entrada', 'cmb2' ),
            'remove_button'     => __( 'Remover entrada', 'cmb2' ),
            'sortable'          => true,
            // 'closed'         => true, // true to have the groups closed by default
            // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
        ),
    ) );
    $cmb_about_page->add_group_field( $group_field_id, array(
        'name'    => 'Chamadas com metragens',
        'desc'    => 'Insira as chamadas com metragem',
        'id'      => 'chamda_metragens_brz',
        'type'    => 'wysiwyg',
        'options' => array(),
    ) );

    $cmb_about_page->add_field( array(
        'name'    => 'Titulo de construir sonhos',
        'desc'    => 'insira o título de práticas',
        'id'      => 'titulo_sonhos',
        'type'    => 'wysiwyg',
        'options' => array(),
    ) );
    $cmb_about_page->add_field( array(
        'name'    => 'Texto de sonhos',
        'desc'    => '',
        'id'      => 'texto_sonhos',
        'type'    => 'wysiwyg',
        'options' => array(),
    ) );

    $cmb_about_page->add_field( array(
        'name'    => 'Titulo de práticas',
        'desc'    => 'insira o título de práticas',
        'id'      => 'titulo_praticas',
        'type'    => 'wysiwyg',
        'options' => array(),
    ) );
    $cmb_about_page->add_field( array(
        'name'    => 'Texto de práticas',
        'desc'    => '',
        'id'      => 'texto_praticas',
        'type'    => 'wysiwyg',
        'options' => array(),
    ) );
    $cmb_about_page->add_field( array(
        'name'    => 'Imagem de trajetória',
        'desc'    => '',
        'id'      => 'imagem_trajetoria',
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
    $cmb_about_page->add_field( array(
        'name'    => 'Titulo de trajetórias',
        'desc'    => 'insira o título de trajetórias',
        'id'      => 'titulo_trajetoria',
        'type'    => 'wysiwyg',
        'options' => array(),
    ) );
    $cmb_about_page->add_field( array(
        'name'    => 'Texto de trajetórias',
        'desc'    => '',
        'id'      => 'texto_trajetoria',
        'type'    => 'wysiwyg',
        'options' => array(),
    ) );
}

?>