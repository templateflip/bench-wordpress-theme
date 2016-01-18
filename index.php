<?php

// Set the default layout
add_filter( 'beans_default_layout', 'bench_index_default_layout' );

function bench_index_default_layout() {

	return 'c';

}


// Setup Bench
beans_add_smart_action( 'beans_before_load_document', 'bench_index_setup_document' );

function bench_index_setup_document() {

	//Layout
	if(beans_get_layout( ) == 'c') {
		beans_remove_attribute( 'beans_primary', 'class', 'uk-width-large-7-10' );
	}

	// Posts grid
	beans_add_attribute( 'beans_content', 'class', 'tm-posts-grid uk-grid uk-grid-width-small-1-2 uk-grid-width-medium-1-3' );
	beans_add_attribute( 'beans_content', 'data-uk-grid-margin', '' );
	beans_add_attribute( 'beans_content', 'data-uk-grid-match', "{target:'.uk-panel'}" );
	beans_wrap_inner_markup( 'beans_post', 'bench_post_panel', 'div', array(
	  'class' => 'uk-panel uk-panel-box'
	) );

	// Post content
	beans_remove_attribute( 'beans_content', 'class', 'tm-centered-content' );

	// Post article
	beans_remove_attribute( 'beans_post', 'class', 'uk-article' );

	// Post meta
	beans_remove_action( 'beans_post_meta' );
	beans_remove_action( 'beans_post_meta_tags' );
	beans_modify_action( 'beans_post_meta_categories', 'beans_post_header', null, 7 );
	beans_remove_output( 'beans_post_meta_categories_prefix' );
	beans_add_attribute( 'beans_post_meta_categories', 'class', 'tm-post-category');

	// Post image
	beans_modify_action( 'beans_post_image', 'beans_post_header_before_markup', 'beans_post_image' );

	// Post title
	beans_add_attribute( 'beans_post_title', 'class', 'uk-margin-small-top uk-h3' );

	// Post more link
	//beans_add_attribute( 'beans_post_more_link', 'class', 'uk-button uk-button-small' );

	// Posts pagination
	beans_modify_action_hook( 'beans_posts_pagination', 'beans_content_after_markup' );

}


/* Helpers and utility functions */
require_once 'include/helpers.php';

// Auto generate summary of Post content and read more button
beans_add_smart_action( 'the_content', 'bench_post_content' );

function bench_post_content( $content ) {

    $output = beans_open_markup( 'bench_post_content', 'p' );

    	$output .= beans_output( 'bench_post_content_summary', bench_get_excerpt( $content ) );

   	$output .= beans_close_markup( 'bench_post_content', 'p' );

		$output .= '<p>'.beans_post_more_link().'</p>';

   	return $output;

}


// Resize post image (filter)
beans_add_smart_action( 'beans_edit_post_image_args', 'bench_index_post_image_args' );

function bench_index_post_image_args( $args ) {

	$args['resize'] = array( 430, 250, array( 'center', 'top' )  ); //430, 250

	return $args;

}


// Load beans document
beans_load_document();
