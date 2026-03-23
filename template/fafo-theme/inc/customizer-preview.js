/**
 * FAFO Theme Customizer live preview
 */
( function( $ ) {
    wp.customize( 'fafo_header_tagline', function( value ) {
        value.bind( function( newval ) {
            $( '.site-tagline' ).text( newval );
        } );
    } );

    wp.customize( 'blogname', function( value ) {
        value.bind( function( newval ) {
            $( '.site-title' ).text( newval );
        } );
    } );
} )( jQuery );
