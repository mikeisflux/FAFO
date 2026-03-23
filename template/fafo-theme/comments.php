<?php
if ( post_password_required() ) return;
?>

<section id="comments" style="margin-top:40px;">

    <?php if ( have_comments() ) : ?>
    <div class="section-header">
        <h2>
            <i class="far fa-comments"></i>
            <?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?>
        </h2>
    </div>

    <ol class="comment-list" style="list-style:none;padding:0;margin-bottom:30px;">
        <?php
        wp_list_comments( [
            'style'      => 'ol',
            'short_ping' => true,
            'avatar_size'=> 48,
            'callback'   => 'fafo_comment',
        ] );
        ?>
    </ol>

    <?php the_comments_pagination( [
        'prev_text' => '<i class="fas fa-chevron-left"></i>',
        'next_text' => '<i class="fas fa-chevron-right"></i>',
    ] ); ?>

    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
    <p style="font-family:var(--font-ui);font-size:0.85rem;color:#999;padding:14px;background:#F5F5F0;border-left:4px solid var(--red);">
        Comments are closed.
    </p>
    <?php endif; ?>

    <?php
    comment_form( [
        'title_reply'          => '<span style="font-family:var(--font-head);letter-spacing:0.08em;">JOIN THE CONVERSATION</span>',
        'title_reply_before'   => '<div class="section-header" style="margin-top:32px;"><h2>',
        'title_reply_after'    => '</h2></div>',
        'comment_notes_before' => '<p style="font-size:0.82rem;color:#999;margin-bottom:16px;">Your email will not be published. Patriots speak freely here.</p>',
        'label_submit'         => 'POST COMMENT',
        'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s btn-primary" value="%4$s">',
        'class_form'           => 'comment-form',
        'class_submit'         => 'submit',
    ] );
    ?>

</section>

<?php
// Custom comment callback
function fafo_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li <?php comment_class( 'fafo-comment' ); ?> id="comment-<?php comment_ID(); ?>"
        style="display:flex;gap:14px;padding:18px 0;border-bottom:1px solid #E8E8E8;">

        <div style="flex-shrink:0;">
            <?php echo get_avatar( $comment, 48, '', '', [ 'style' => 'border-radius:3px;' ] ); ?>
        </div>

        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                <strong style="font-family:var(--font-head);font-size:0.9rem;color:#002868;">
                    <?php comment_author_link(); ?>
                </strong>
                <span style="font-size:0.72rem;color:#999;">
                    <?php comment_date( 'M j, Y \a\t g:i a' ); ?>
                </span>
            </div>

            <?php if ( '0' == $comment->comment_approved ) : ?>
            <p style="font-size:0.8rem;color:#856404;background:#fff3cd;padding:6px 10px;margin-bottom:8px;">
                <i class="fas fa-clock"></i> Your comment is awaiting moderation.
            </p>
            <?php endif; ?>

            <div style="font-size:0.9rem;line-height:1.65;color:#444;">
                <?php comment_text(); ?>
            </div>

            <div style="margin-top:8px;">
                <?php comment_reply_link( array_merge( $args, [
                    'reply_text' => '<i class="fas fa-reply"></i> Reply',
                    'depth'      => $depth,
                    'max_depth'  => $args['max_depth'],
                    'before'     => '<span style="font-size:0.75rem;font-family:var(--font-head);letter-spacing:0.08em;color:#999;">',
                    'after'      => '</span>',
                ] ) ); ?>
            </div>
        </div>

    </li>
    <?php
}
?>
