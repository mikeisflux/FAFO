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
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="footer-logo">
                    F<span>A</span>FO
                </a>
                <span class="footer-tagline">For America First Only</span>
                <p>
                    FAFO News delivers bold, unapologetic conservative reporting for American patriots.
                    We cover the stories the mainstream media refuses to tell — no spin, no agenda,
                    just the truth for the American people.
                </p>
                <div class="footer-social">
                    <a href="#" aria-label="Twitter/X" title="Twitter/X"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" aria-label="Facebook" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Truth Social" title="Truth Social">T</a>
                    <a href="#" aria-label="Rumble" title="Rumble"><i class="fas fa-video"></i></a>
                    <a href="#" aria-label="Telegram" title="Telegram"><i class="fab fa-telegram-plane"></i></a>
                    <a href="#" aria-label="RSS Feed" title="RSS"><i class="fas fa-rss"></i></a>
                </div>
            </div>

            <!-- Topics -->
            <div class="footer-col">
                <h5>Topics</h5>
                <ul>
                    <li><a href="#">Politics</a></li>
                    <li><a href="#">Economy &amp; Finance</a></li>
                    <li><a href="#">National Security</a></li>
                    <li><a href="#">Border &amp; Immigration</a></li>
                    <li><a href="#">2nd Amendment</a></li>
                    <li><a href="#">Faith &amp; Culture</a></li>
                    <li><a href="#">Election Integrity</a></li>
                    <li><a href="#">Opinion</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div class="footer-col">
                <h5>FAFO Network</h5>
                <ul>
                    <li><a href="#">About FAFO</a></li>
                    <li><a href="#">Our Team</a></li>
                    <li><a href="#">Advertise</a></li>
                    <li><a href="#">Press Room</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">FAFO Merch</a></li>
                </ul>
            </div>

            <!-- Legal + newsletter mini -->
            <div class="footer-col">
                <h5>Legal</h5>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Cookie Policy</a></li>
                    <li><a href="#">Corrections Policy</a></li>
                    <li><a href="#">DMCA / Takedowns</a></li>
                    <li><a href="#">Tip Line</a></li>
                </ul>

                <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                    <?php dynamic_sidebar( 'footer-3' ); ?>
                <?php endif; ?>
            </div>

        </div><!-- .footer-grid -->

        <!-- Footer Bottom Bar -->
        <div class="footer-bottom">
            <span>
                &copy; <?php echo date('Y'); ?>
                <a href="<?php echo esc_url( home_url('/') ); ?>">FAFO News</a>
                &mdash; For America First Only. All Rights Reserved.
            </span>
            <span>
                <a href="#">Privacy</a> &bull;
                <a href="#">Terms</a> &bull;
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
