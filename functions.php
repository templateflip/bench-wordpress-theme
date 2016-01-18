<?php

// Include Beans
require_once( get_template_directory() . '/lib/init.php' );


// Remove Beans default styling
remove_theme_support( 'beans-default-styling' );


// Enqueue uikit assets
beans_add_smart_action( 'beans_uikit_enqueue_scripts', 'bench_enqueue_uikit_assets', 5 );

function bench_enqueue_uikit_assets() {

	// Enqueue uikit overwrite theme folder
	beans_uikit_enqueue_theme( 'bench', get_stylesheet_directory_uri() . '/assets/less/uikit' );

	// Add the theme style as a uikit fragment to have access to all the variables
	beans_compiler_add_fragment( 'uikit', get_stylesheet_directory_uri() . '/assets/less/style.less', 'less' );

	// Add the theme js as a uikit fragment
	beans_compiler_add_fragment( 'uikit', get_stylesheet_directory_uri() . '/assets/js/bench.js', 'js' );

	// Include the uikit components needed
	beans_uikit_enqueue_components( array( 'contrast' ) );

}

// Register sub-footer widget area
beans_add_smart_action( 'widgets_init', 'bench_register_sub_footer_widget_area' );

function bench_register_sub_footer_widget_area() {

	beans_register_widget_area( array(
		'name' => 'Sub-Footer',
		'id' => 'sub-footer',
		'description' => 'Widgets in this area will be shown above the footer.',
		'beans_type' => 'stack'
	) );

}

// Register bottom widget area
beans_add_smart_action( 'widgets_init', 'bench_register_bottom_widget_area' );

function bench_register_bottom_widget_area() {

	beans_register_widget_area( array(
		'name' => 'Bottom Footer',
		'id' => 'bottom',
		'description' => 'Widgets in this area will be shown in the bottom section as a grid.',
		'beans_type' => 'grid'
	) );

}


// Add admin layout option (filter)
beans_add_smart_action( 'beans_layouts', 'bench_layouts' );

function bench_layouts( $layouts ) {

	$layouts['bench_c'] = get_stylesheet_directory_uri() . '/assets/images/c.png';

	return $layouts;

}


// Remove page post type comment support
beans_add_smart_action( 'init', 'bench_post_type_support' );

function bench_post_type_support() {

	remove_post_type_support( 'page', 'comments' );

}


// Setup document fragements, markups and attributes
add_action( 'wp', 'bench_setup_document' );

function bench_setup_document() {

	// Frontpage posts
	if ( is_home() ) {
		beans_add_smart_action('beans_header_after_markup', 'bench_site_title_tag');
	}

	// Site Logo
	beans_remove_action( 'beans_site_title_tag' );
	//Add back site title after logo image
	beans_add_smart_action('beans_logo_image_after_markup', 'bench_site_title');

	// Layout
	if(beans_get_layout( ) != 'c' && beans_get_layout( ) != 'bench_c') {
		beans_remove_attribute( 'beans_primary', 'class', 'uk-width-medium-7-10' );
		beans_add_attribute( 'beans_primary', 'class', 'uk-width-large-7-10' );
		beans_remove_attribute( 'beans_sidebar_primary', 'class', 'uk-width-medium-3-10' );
		beans_add_attribute( 'beans_sidebar_primary', 'class', 'uk-width-large-3-10 uk-visible-large' );
 }

	// Post meta
	beans_add_attribute( 'beans_post_meta_date', 'class', 'uk-text-muted' );

	// Search form
	beans_replace_attribute( 'beans_search_form', 'class', 'uk-form-icon uk-form-icon-flip', 'uk-display-inline-block' );
	beans_remove_markup( 'beans_search_form_input_icon' );

	// Add grid min width for Bench slim content
	if ( beans_get_layout() == 'bench_c' )
		beans_add_attribute( 'beans_content', 'class', 'tm-centered-content' );

	if ( is_user_logged_in() ) {
		//Add edit post link when user is logged in
		if( is_singular() )
			beans_add_smart_action('beans_post_header_before_markup', 'bench_edit_link');
	}

	// Only applies to singular and not pages
	if ( is_singular() && !is_page() ) {
		//remove breadcrumb
		beans_remove_action( 'beans_breadcrumb' );

		//remove featured image
		beans_remove_action( 'beans_post_image' );

		// Post navigation
		beans_add_attribute( 'beans_post_navigation', 'class', 'uk-grid-margin' );

		// Post author profile
		add_action( 'beans_comments_before_markup', 'bench_author_profile' );

		// Post comments
		beans_add_attribute( 'beans_comments', 'class', 'uk-margin-bottom-remove' );
		beans_add_attribute( 'beans_comment_form_wrap', 'class', 'uk-contrast' );
		beans_add_attribute( 'beans_no_comment', 'class', 'tm-no-comments uk-text-center uk-text-large uk-block' );
		beans_remove_action( 'beans_comment_form_divider' );

	}
}

function bench_site_title() {
	echo beans_output( 'beans_site_title_text', get_bloginfo( 'name' ) );
}

function bench_site_title_tag() {
	// Stop here if there isn't a description.
	if ( !$description = get_bloginfo( 'description' ) )
		return;

	echo beans_open_markup( 'bench_site_title_tag', 'div', array(
		'class' => 'tm-site-title-tag uk-block',
		'itemprop' => 'description'
	) );

		echo beans_output( 'bench_site_title_tag_text', $description );

	echo beans_close_markup( 'bench_site_title_tag', 'div' );
}

function bench_edit_link() {
		edit_post_link( __( 'Edit', 'bench' ), '<div class="uk-margin-bottom-small uk-text-small uk-align-right"><i class="uk-icon-pencil-square-o"></i> ', '</div>' );
}

// Author profile in posts
function bench_author_profile() {

	echo beans_open_markup( 'bench_author_profile', 'div',  array( 'class' => 'uk-panel-box' ) );

	echo '<h3 class="uk-panel-title">'.__('About the author', 'bench').'</h3>';
	echo '<div class="uk-clearfix">';
	  echo '<div class="uk-align-left">'.get_avatar( get_the_author_meta('ID'), 96 ).'</div>';
   	echo '<div class="uk-text-large uk-text-bold">'.get_the_author_meta('display_name').'</div>';
		echo wpautop(get_the_author_meta('description'));
	echo '</div>';
	echo beans_close_markup( 'bench_author_profile', 'div' );

}

// Modify beans layout (filter)
beans_add_smart_action( 'beans_layout_grid_settings', 'bench_layout_grid_settings' );

function bench_layout_grid_settings( $layouts ) {

	return array_merge( $layouts, array(
		'grid' => 10,
		'sidebar_primary' => 3,
		'sidebar_secondary' => 3,
	) );

}

// Add avatar uikit rounded border class (filter)
beans_add_smart_action( 'get_avatar', 'bench_avatar' );

function bench_avatar( $output ) {

	return str_replace( "class='avatar", "class='avatar uk-border-rounded", $output ) ;

}

// Add primaray menu search field
beans_add_smart_action( 'beans_primary_menu_append_markup', 'bench_primary_menu_search' );

function bench_primary_menu_search() {

	echo beans_open_markup( 'bench_menu_primary_search', 'div', array(
		'class' => 'tm-search uk-visible-large uk-navbar-content',
		'style' => 'display: none;'
	) );

		get_search_form();

	echo beans_close_markup( 'bench_menu_primary_search', 'div' );

	echo beans_open_markup( 'bench_menu_primary_search_toggle', 'div', array(
		'class' => 'tm-search-toggle uk-visible-large uk-navbar-content uk-display-inline-block uk-contrast'
	) );

		echo beans_open_markup( 'bench_menu_primary_search_icon', 'i', array( 'class' => 'uk-icon-search' ) );
		echo beans_close_markup( 'bench_menu_primary_search_icon', 'i' );

	echo beans_close_markup( 'bench_menu_primary_search_toggle', 'div' );

}

// Modify the read more text.
add_filter( 'beans_post_more_link_text_output', 'bench_modify_read_more' );

function bench_modify_read_more() {
   return 'Read more';
}

// Remove comment after note (filter)
beans_add_smart_action( 'comment_form_defaults', 'bench_comment_form_defaults' );

function bench_comment_form_defaults( $args ) {

	$args['comment_notes_after'] = '';

	return $args;

}

// Add the sub-footer widget area
beans_add_smart_action( 'beans_footer_before_markup', 'bench_sub_footer_widget_area' );

function bench_sub_footer_widget_area() {

	// Stop here if no widget
	if( !beans_is_active_widget_area( 'sub-footer' ) )
		return;

	echo beans_open_markup( 'bench_sub_footer', 'section', array( 'class' => 'tm-sub-footer' ) );

		echo beans_open_markup( 'beans_fixed_wrap[_sub_footer]', 'div', 'class=uk-container uk-container-center' );

			echo beans_widget_area( 'sub-footer' );

		echo beans_close_markup( 'beans_fixed_wrap[_sub_footer]', 'div' );

	echo beans_close_markup( 'bench_sub_footer', 'section' );

}


// The bottom widget area
function bench_bottom_widget_area() {

	// Stop here if no widget
	if( !beans_is_active_widget_area( 'bottom' ) )
		return;

	echo beans_open_markup( 'bench_bottom', 'section', array( 'class' => 'tm-bottom' ) );
			echo beans_widget_area( 'bottom' );
	echo beans_close_markup( 'bench_bottom', 'section' );
}

// Overwrite the footer content.
beans_modify_action_callback( 'beans_footer_content', 'bench_footer_content' );

function bench_footer_content() {

	?>
	<div class="uk-grid uk-text-muted">
		<div class="uk-width-medium-1-2">
	<?php
		echo '<div class="uk-clearfix">';
		if ( $logo = get_theme_mod( 'beans_logo_image', false ) ) {
			echo beans_selfclose_markup( 'bench_footer_logo_image', 'img', array(
				'class' => 'tm-footer-logo uk-align-left uk-margin-right',
				'src' => esc_url( $logo ),
				'alt' => esc_attr( $name ),
				) );
		}
		if ($description = get_bloginfo( 'description' )) {
			echo '<p>'.$description.'</p>';
		}
		echo '</div>';
		echo '<p>';
		echo beans_output( 'beans_footer_credit_text', sprintf(
				__( '&#x000A9; %1$s - Copyright %2$s. All rights reserved.', 'bench' ),
				date( "Y" ),
				get_bloginfo( 'name' )
			) );
		echo '</p>';
	?>
		</div>
	<div class="uk-width-medium-1-2">
	<?php bench_bottom_widget_area(); ?>
	</div>
</div>
<div class="uk-text-muted uk-text-small uk-text-center uk-margin-top">
			<a href="https://kkthemes.com/wordpress/bench/" target="_blank" title="Bench theme for WordPress">Bench</a> theme for <a href="http://wordpress.org" target="_blank">WordPress</a>. Built-with <a href="http://www.getbeans.io/" title="Beans Framework for WordPress" target="_blank">Beans</a>.
</div>
<?php
}

//Setup Widgets
beans_add_smart_action( 'widgets_init', 'fast_monkey_register_widgets');

function fast_monkey_register_widgets() {
			//Include widget classes
	 		require_once('widgets/posts.php');

	 		// Regidter widgets
			register_widget('Bench_Posts_Widget');
}

//Customizer fields

//Additional Header & Footer Codes (for Google Analytics)
add_action( 'init', 'bench_customization_fields' );
function bench_customization_fields() {

	$fields = array(
		array(
			'id' => 'bench_head_code',
			'label' => __( 'Additional Head Code', 'bench' ),
			'type' => 'textarea',
			'default' => ''
		)
	);

	beans_register_wp_customize_options( $fields, 'bench_custom_code', array( 'title' => __( 'Custom Code', 'bench' ), 'priority' => 1100 ) );
}

add_action('beans_head_append_markup', 'bench_custom_head_code');

function bench_custom_head_code() {
	echo get_theme_mod( 'bench_head_code', '' );
}

/* Customize Jetpack */
require 'include/jetpack-custom.php';
