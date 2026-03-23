<?php
/**
 * FAFO Theme Template Tags
 */

if ( ! function_exists( 'fafo_posted_on' ) ) :
function fafo_posted_on() {
    $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
    $time_string = sprintf( $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() )
    );
    echo '<span class="posted-on">' . $time_string . '</span>';
}
endif;

if ( ! function_exists( 'fafo_posted_by' ) ) :
function fafo_posted_by() {
    $byline = sprintf(
        '<span class="author vcard"><a href="%1$s">%2$s</a></span>',
        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
        esc_html( get_the_author() )
    );
    echo '<span class="byline">' . $byline . '</span>';
}
endif;

if ( ! function_exists( 'fafo_entry_footer' ) ) :
function fafo_entry_footer() {
    if ( 'post' === get_post_type() ) {
        $categories = get_the_category_list( ', ' );
        if ( $categories ) {
            printf( '<span class="cat-links">' . __( 'Filed under: %1$s', 'fafo' ) . '</span>', $categories );
        }
    }
    edit_post_link(
        sprintf( '<span class="screen-reader-text">%s</span> Edit', esc_html( get_the_title() ) ),
        '<span class="edit-link">',
        '</span>'
    );
}
endif;
