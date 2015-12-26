<?php

// Modify the 404 page markup
beans_add_smart_action( 'beans_before_load_document', 'bench_404_setup_document' );

function bench_404_setup_document() {

    // Post
    beans_remove_attribute( 'beans_no_post_article_content', 'class', 'uk-alert uk-alert-warning' );
    beans_modify_markup( 'beans_no_post_article_content', 'h2' );
    beans_add_attribute( 'beans_no_post_article_content', 'class', 'uk-margin-large-bottom' );

}


// Update the 404 page title
beans_add_smart_action( 'beans_no_post_article_title_text_output', 'bench_404_post_title' );

function bench_404_post_title() {

	return beans_output( 'bench_no_post_article_title_text', __( '404', 'bench' ) );

}


// Update the 404 page content
beans_add_smart_action( 'beans_no_post_article_content_text_output', 'bench_404_post_content' );

function bench_404_post_content() {

	return beans_output( 'bench_no_post_article_content_text', __( 'Page not found', 'bench' ) );

}


// Load Beans document
beans_load_document();
