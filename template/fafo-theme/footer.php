        </div><!-- .main-content (closed from index) -->
    </div><!-- .content-area -->
</div><!-- .container -->
</main><!-- .site-content -->

<!-- PATRIOT QUOTE BANNER -->
<section class="opinion-banner" aria-label="Patriot Quote">
    <div class="container">
        <h3>★ FAFO NEWS ★</h3>
        <p>"The tree of liberty must be refreshed from time to time with the truth." — America First</p>
    </div>
</section>

<!-- SITE FOOTER -->
<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-grid">

            <!-- Brand Column -->
            <div class="footer-brand">
                <?php
                $footer_logo_id = get_option('fafo_footer_logo');
                $footer_logo_url = $footer_logo_id ? wp_get_attachment_image_url( $footer_logo_id, 'medium' ) : '';
                if ( $footer_logo_url ) : ?>
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="footer-logo-img">
                    <img src="<?php echo esc_url($footer_logo_url); ?>" alt="<?php echo esc_attr( get_bloginfo('name') ); ?>" style="max-height:60px;width:auto;display:block;margin-bottom:8px;">
                </a>
                <?php else : ?>
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="footer-logo">
                    F<span>A</span>FO
                </a>
                <?php endif; ?>
                <span class="footer-tagline">For America First Only</span>
                <p><?php echo esc_html( get_option( 'fafo_footer_description', 'FAFO News delivers bold, unapologetic conservative reporting for American patriots. We cover the stories the mainstream media refuses to tell — no spin, no agenda, just the truth for the American people.' ) ); ?></p>
                <div class="footer-social">
                    <?php if ( fafo_social('twitter') ) : ?><a href="<?php echo esc_url( fafo_social('twitter') ); ?>" aria-label="Twitter/X" title="Twitter/X"><i class="fab fa-x-twitter"></i></a><?php endif; ?>
                    <?php if ( fafo_social('facebook') ) : ?><a href="<?php echo esc_url( fafo_social('facebook') ); ?>" aria-label="Facebook" title="Facebook"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                    <?php if ( fafo_social('truth') ) : ?><a href="<?php echo esc_url( fafo_social('truth') ); ?>" aria-label="Truth Social" title="Truth Social"><i class="fas fa-flag"></i></a><?php endif; ?>
                    <?php if ( fafo_social('rumble') ) : ?><a href="<?php echo esc_url( fafo_social('rumble') ); ?>" aria-label="Rumble" title="Rumble"><i class="fas fa-video"></i></a><?php endif; ?>
                    <?php if ( fafo_social('telegram') ) : ?><a href="<?php echo esc_url( fafo_social('telegram') ); ?>" aria-label="Telegram" title="Telegram"><i class="fab fa-telegram-plane"></i></a><?php endif; ?>
                    <?php if ( fafo_social('youtube') ) : ?><a href="<?php echo esc_url( fafo_social('youtube') ); ?>" aria-label="YouTube" title="YouTube"><i class="fab fa-youtube"></i></a><?php endif; ?>
                    <a href="<?php echo esc_url( get_feed_link() ); ?>" aria-label="RSS Feed" title="RSS"><i class="fas fa-rss"></i></a>
                </div>
            </div>

            <!-- Topics -->
            <div class="footer-col">
                <h5>Topics</h5>
                <ul>
                    <li><a href="<?php echo esc_url( fafo_cat_link('politics') ); ?>">Politics</a></li>
                    <li><a href="<?php echo esc_url( fafo_cat_link('economy') ); ?>">Economy &amp; Finance</a></li>
                    <li><a href="<?php echo esc_url( fafo_cat_link('national-security') ); ?>">National Security</a></li>
                    <li><a href="<?php echo esc_url( fafo_cat_link('border-immigration') ); ?>">Border &amp; Immigration</a></li>
                    <li><a href="<?php echo esc_url( fafo_cat_link('2nd-amendment') ); ?>">2nd Amendment</a></li>
                    <li><a href="<?php echo esc_url( fafo_cat_link('faith-culture') ); ?>">Faith &amp; Culture</a></li>
                    <li><a href="<?php echo esc_url( fafo_cat_link('election-integrity') ); ?>">Election Integrity</a></li>
                    <li><a href="<?php echo esc_url( fafo_cat_link('opinion') ); ?>">Opinion</a></li>
                    <li><a href="<?php echo esc_url( get_post_type_archive_link('fafo_video') ); ?>">Video</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div class="footer-col">
                <h5>FAFO Network</h5>
                <ul>
                    <li><a href="<?php echo esc_url( fafo_page_link('about') ); ?>">About FAFO</a></li>
                    <li><a href="<?php echo esc_url( fafo_page_link('team') ); ?>">Our Team</a></li>
                    <li><a href="<?php echo esc_url( fafo_page_link('advertise') ); ?>">Advertise</a></li>
                    <li><a href="<?php echo esc_url( fafo_page_link('press') ); ?>">Press Room</a></li>
                    <li><a href="<?php echo esc_url( fafo_page_link('contact') ); ?>">Contact Us</a></li>
                    <li><a href="<?php echo esc_url( fafo_page_link('careers') ); ?>">Careers</a></li>
                    <li><a href="<?php echo esc_url( fafo_page_link('newsletter') ); ?>">Newsletter</a></li>
                    <li><a href="<?php echo esc_url( fafo_page_link('merch') ); ?>">FAFO Merch</a></li>
                </ul>
            </div>

            <!-- Legal + newsletter mini -->
            <div class="footer-col">
                <h5>Legal</h5>
                <ul>
                    <li><a href="<?php echo esc_url( fafo_page_link('privacy-policy') ); ?>">Privacy Policy</a></li>
                    <li><a href="<?php echo esc_url( fafo_page_link('terms-of-service') ); ?>">Terms of Service</a></li>
                    <li><a href="<?php echo esc_url( fafo_page_link('cookie-policy') ); ?>">Cookie Policy</a></li>
                    <li><a href="<?php echo esc_url( fafo_page_link('corrections') ); ?>">Corrections Policy</a></li>
                    <li><a href="<?php echo esc_url( fafo_page_link('dmca') ); ?>">DMCA / Takedowns</a></li>
                    <li><a href="<?php echo esc_url( fafo_page_link('tip-line') ); ?>">Tip Line</a></li>
                </ul>

                <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                    <?php dynamic_sidebar( 'footer-3' ); ?>
                <?php endif; ?>
            </div>

        </div><!-- .footer-grid -->

        <!-- Footer Bottom Bar -->
        <div class="footer-bottom">
            <span>
                <?php
                $copyright = get_option('fafo_copyright_text', '');
                if ( $copyright ) {
                    echo esc_html( $copyright );
                } else {
                    echo '&copy; ' . date('Y') . ' <a href="' . esc_url( home_url('/') ) . '">FAFO News</a> &mdash; For America First Only. All Rights Reserved.';
                }
                ?>
            </span>
            <span>
                <a href="<?php echo esc_url( fafo_page_link('privacy-policy') ); ?>">Privacy</a> &bull;
                <a href="<?php echo esc_url( fafo_page_link('terms-of-service') ); ?>">Terms</a> &bull;
                <a href="<?php echo esc_url( get_feed_link() ); ?>">RSS</a>
            </span>
        </div>

    </div><!-- .container -->
</footer>

<!-- Bottom patriot stripe -->
<div class="patriot-stripe" aria-hidden="true"></div>

<?php wp_footer(); ?>
</body>
</html>
