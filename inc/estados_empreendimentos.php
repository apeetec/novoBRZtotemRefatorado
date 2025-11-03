<?php

// Estados para os Empreendimentos
add_action( 'init', 'custom_taxonomy_estados', 0 );
function custom_taxonomy_estados() { 
$labels = array(
    'name' => 'Estados',
    'singular_name' => 'Estados',
    'search_items' => 'Buscar estados',
    'all_items' => 'Todas as estados',
    'edit_item' => 'Editar estado', 
    'update_item' => 'Atualizar estados',
    'add_new_item' => 'Adicionar estado',
    'new_item_name' => 'Novo estados',
    'menu_name' => 'Estados',
);    

register_taxonomy('estados',array('empreendimentos'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array(
        'slug' => 'estado', // This controls the base slug that will display before each term
        'with_front' => true, // Don't display the category base before "/locations/"
        'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
    ),
));
}
?>