<?php
add_action( 'cmb2_admin_init', 'yourprefix_register_taxonomy_metabox' );
/**
 * Hook in and add a metabox to add fields to taxonomy terms
 */
function yourprefix_register_taxonomy_metabox() {
	$prefix = 'campo_';

	/**
	 * Metabox to add fields to categories and tags
	 */
	$cmb_term = new_cmb2_box( array(
		'id'               => $prefix . 'edit',
		'title'            => esc_html__( 'Category Metabox', 'cmb2' ), // Doesn't output for term boxes
		'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
		'taxonomies'       => array( 'estados', 'post_tag' ), // Tells CMB2 which taxonomies should have these fields
		// 'new_term_section' => true, // Will display in the "Add New Category" section
	) );

    $cmb_term->add_field( array(
        'name'    => 'UF',
        'desc'    => '',
        'default' => '',
        'id'      => 'uf',
        'type'    => 'text_medium'
    ) );
}



// Adicionar Estado à cidade
add_action( 'cmb2_admin_init', 'estado_e_cidade' );
/**
 * Hook in and add a metabox to add fields to taxonomy terms
 */
function estado_e_cidade() {
	$prefix = 'campo_';

	/**
	 * Metabox to add fields to categories and tags
	 */
	$cmb_term = new_cmb2_box( array(
		'id'               => $prefix . 'estados_cidades',
		'title'            => esc_html__( 'Category Metabox', 'cmb2' ), // Doesn't output for term boxes
		'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
		'taxonomies'       => array( 'cidades', 'post_tag' ), // Tells CMB2 which taxonomies should have these fields
		// 'new_term_section' => true, // Will display in the "Add New Category" section
	) );
	$cmb_term->add_field( array(
		'name'           => 'Estados',
		'desc'           => 'Selecione à qual estado essa cidade pertence',
		'id'             => 'estado_cidade',
		'taxonomy'       => 'estados', //Enter Taxonomy Slug
		'type'           => 'taxonomy_select',
		'remove_default' => 'true', // Removes the default metabox provided by WP core.
		// Optionally override the args sent to the WordPress get_terms function.
		'query_args' => array(
			// 'orderby' => 'slug',
			// 'hide_empty' => true,
		),
	) );
    $cmb_term->add_field( array(
        'name'    => 'UF',
        'desc'    => '',
        'default' => '',
        'id'      => 'uf_estado_cidade',
        'type'    => 'text_medium'
    ) );
}
?>