<?php
//// Cidades para os Empreendimentos
add_action( 'init', 'custom_taxonomy_cidades', 0 );
function custom_taxonomy_cidades() { 
$labels = array(
    'name' => 'Cidades',
    'singular_name' => 'Datas',
    'search_items' => 'Buscar cidade',
    'all_items' => 'Todas as cidade',
    'edit_item' => 'Editar cidade', 
    'update_item' => 'Atualizar cidade',
    'add_new_item' => 'Adicionar cidade',
    'new_item_name' => 'Nova cidade',
    'menu_name' => 'Cidades',
);    

register_taxonomy('cidades',array('empreendimentos'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array(
        'slug' => 'cidade', // This controls the base slug that will display before each term
        'with_front' => true, // Don't display the category base before "/locations/"
        'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
    ),
));
}
?>