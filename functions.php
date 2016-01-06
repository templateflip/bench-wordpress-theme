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


// Register bottom widget area
beans_add_smart_action( 'widgets_init', 'bench_register_bottom_widget_area' );

function bench_register_bottom_widget_area() {

	beans_register_widget_area( array(
		'name' => 'Bottom',
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
	if ( is_home() )
		beans_remove_attribute( 'beans_post', 'class', 'uk-panel-box' );

	// Site Logo
	beans_remove_attribute( 'beans_site_title_tag', 'class', 'uk-text-muted' );

	// Breadcrumb
	//beans_remove_action( 'beans_breadcrumb' );

	// Post meta
	beans_add_attribute( 'beans_post_meta_date', 'class', 'uk-text-muted' );

	// Search form
	beans_replace_attribute( 'beans_search_form', 'class', 'uk-form-icon uk-form-icon-flip', 'uk-display-inline-block' );
	beans_remove_markup( 'beans_search_form_input_icon' );

	// Add grid min width for Bench slim content
	if ( beans_get_layout() == 'bench_c' )
		beans_add_attribute( 'beans_content', 'class', 'tm-centered-content' );

	// Only applies to singular and not pages
	if ( is_singular() && !is_page() ) {

		//remove featured image
		beans_remove_action( 'beans_post_image' );

		// Post title
		beans_add_attribute( 'beans_post_title', 'class', 'uk-margin-small-bottom' );

		// Post navigation
		beans_add_attribute( 'beans_post_navigation', 'class', 'uk-grid-margin' );

		// Post author profile
		add_action( 'beans_comments_before_markup', 'bench_author_profile' );

		// Post comments
		beans_add_attribute( 'beans_comments', 'class', 'uk-margin-bottom-remove' );
		beans_add_attribute( 'beans_comment_form_wrap', 'class', 'uk-contrast' );
		beans_add_attribute( 'beans_comment_form_submit', 'class', 'uk-button-large' );
		beans_add_attribute( 'beans_no_comment', 'class', 'tm-no-comments uk-text-center uk-text-large uk-block' );

	}
	if ( get_bloginfo( 'description' ) )
		beans_add_attribute( 'beans_primary_menu', 'class', 'uk-margin-small-top' );

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


// Remove comment after note (filter)
beans_add_smart_action( 'comment_form_defaults', 'bench_comment_form_defaults' );

function bench_comment_form_defaults( $args ) {

	$args['comment_notes_after'] = '';

	return $args;

}


// Add the bottom widget area
beans_add_smart_action( 'beans_footer_before_markup', 'bench_bottom_widget_area' );

function bench_bottom_widget_area() {

	// Stop here if no widget
	if( !beans_is_active_widget_area( 'bottom' ) )
		return;

	echo beans_open_markup( 'bench_bottom', 'section', array( 'class' => 'tm-bottom uk-block uk-padding-bottom-remove' ) );

		echo beans_open_markup( 'beans_fixed_wrap[_bottom]', 'div', 'class=uk-container uk-container-center' );

			echo beans_widget_area( 'bottom' );

		echo beans_close_markup( 'beans_fixed_wrap[_bottom]', 'div' );

	echo beans_close_markup( 'bench_bottom', 'section' );

}

// Add footer content (filter)
beans_add_smart_action( 'beans_footer_credit_right_text_output', 'bench_footer' );

function bench_footer() { ?>

  <a href="http://themes.kanishkkunal.in/bench/" target="_blank" title="Bench theme for WordPress">Bench</a> theme for <a href="http://wordpress.org" target="_blank">WordPress</a>. Built-with <a href="http://www.getbeans.io/" title="Beans Framework for WordPress" target="_blank">Beans</a>.

<?php }
