<?php /* Customization for Jetpack features */
/**
* Add theme support for Responsive Videos.
*/
function jetpackme_responsive_videos_setup() {
    add_theme_support( 'jetpack-responsive-videos' );
}
add_action( 'after_setup_theme', 'jetpackme_responsive_videos_setup' );
