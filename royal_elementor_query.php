<?php
/*
Plugin Name: Royal Elementor Custom Query
Description: Adiciona query condicional para exibir apenas documentos do tipo IPTU conforme usuário logado no Elementor Loop Grid.
Version: 1.1
Author: Sistema Royal
*/

// Segurança: impede acesso direto ao arquivo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Função para customizar a query do Elementor
add_action( 'elementor/query/cliente_documentos', function( $query ) {

    if ( ! is_user_logged_in() ) {
        $query->set( 'post__in', array( 0 ) );
        return;
    }

    $user_id = get_current_user_id();
    $user = wp_get_current_user();

    if ( in_array( 'cliente', (array) $user->roles ) ) {
        $meta_query = array(
            array(
                'key' => 'cliente', // Nome do campo ACF correto (tipo: usuário)
                'value' => $user_id,
                'compare' => '='
            )
        );
        $query->set( 'meta_query', $meta_query );
        $query->set( 'post_type', 'iptu' ); // CPT correto: iptu
        $query->set( 'post_status', 'publish' );
    } elseif ( in_array( 'administrator', (array) $user->roles ) ) {
        $query->set( 'post_type', 'iptu' );
        $query->set( 'post_status', 'publish' );
    } else {
        $query->set( 'post__in', array( 0 ) );
    }
});
?>
