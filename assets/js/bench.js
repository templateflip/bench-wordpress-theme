var toolbar_shown = false;

!(function( $ ) {

    // Search Toggle
    $( document ).on( 'click', '.tm-primary-menu .tm-search-toggle', function() {

    	var searchSelector = '.tm-primary-menu .tm-search';

    	$( searchSelector ).toggle();
    	$( '.tm-primary-menu .uk-navbar-nav' ).toggle();

    	if ( $( searchSelector ).is( ':visible' ) ) {

            $( searchSelector ).find( 'input' ).focus();
            $( this ).find( 'i').removeClass( 'uk-icon-search' ).addClass( 'uk-icon-close' );

        } else {

            $( this ).find( 'i').removeClass( 'uk-icon-close' ).addClass( 'uk-icon-search' );
        }

    } );

    // Toolbar toggle
    $(window).scroll(function() {
      var height = $(window).scrollTop();

      if(height  > 300) {
  			if(!toolbar_shown) {
  					$('#tm-toolbar').slideToggle('fast');
  			}
  			toolbar_shown = true;
      }
  		else if(height <= 300) {
  			if(toolbar_shown) {
  					$('#tm-toolbar').slideToggle('fast');
  			}
  			toolbar_shown = false;
  		}
  	});

} )( window.jQuery );
