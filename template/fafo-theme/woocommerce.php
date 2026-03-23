<?php
/**
 * WooCommerce Layout Template
 * Wraps all WooCommerce pages (shop, single product, cart, checkout, account)
 * inside the FAFO theme structure.
 */
get_header(); ?>

<!-- SHOP HEADER -->
<div class="category-header">
    <div class="container">
        <?php
        if ( is_shop() ) {
            echo '<h1><i class="fas fa-shopping-bag" style="margin-right:10px;"></i>' . esc_html( get_the_title( wc_get_page_id( 'shop' ) ) ) . '</h1>';
            echo '<p style="color:rgba(255,255,255,.8);margin:8px 0 0;font-size:.95rem;">100% American-printed gear for American patriots.</p>';
        } elseif ( is_product_category() ) {
            echo '<h1>' . single_cat_title( '', false ) . '</h1>';
            $cat_desc = category_description();
            if ( $cat_desc ) { echo '<p style="color:rgba(255,255,255,.8);margin:8px 0 0;">' . esc_html( $cat_desc ) . '</p>'; }
        } elseif ( is_cart() ) {
            echo '<h1><i class="fas fa-shopping-cart" style="margin-right:10px;"></i>Your Cart</h1>';
        } elseif ( is_checkout() ) {
            echo '<h1><i class="fas fa-lock" style="margin-right:10px;"></i>Secure Checkout</h1>';
        } elseif ( is_account_page() ) {
            echo '<h1><i class="fas fa-user" style="margin-right:10px;"></i>My Account</h1>';
        } elseif ( is_product() ) {
            echo '<h1>' . esc_html( get_the_title() ) . '</h1>';
        } else {
            woocommerce_page_title();
        }
        ?>
    </div>
</div>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

    <?php woocommerce_content(); ?>

</div><!-- .main-content -->

<aside class="sidebar" role="complementary">
    <?php if ( is_active_sidebar( 'sidebar-shop' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-shop' ); ?>
    <?php else : ?>
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-shopping-bag"></i> Shop</h3>
        <div class="widget-body">
            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
            <ul style="list-style:none;padding:0;margin:0;font-size:.88rem;">
                <li style="padding:6px 0;border-bottom:1px solid #eee;">
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" style="color:#333;">
                        <i class="fas fa-shopping-cart" style="color:#C8102E;margin-right:8px;"></i>
                        View Cart
                        <?php $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
                        if ( $count > 0 ) echo '<span style="background:#C8102E;color:#fff;font-size:.72rem;padding:1px 6px;border-radius:10px;margin-left:6px;">' . $count . '</span>'; ?>
                    </a>
                </li>
                <li style="padding:6px 0;">
                    <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" style="color:#333;">
                        <i class="fas fa-lock" style="color:#C8102E;margin-right:8px;"></i>Checkout
                    </a>
                </li>
            </ul>
            <?php endif; ?>
        </div>
    </div>
    <?php if ( is_active_sidebar( 'sidebar-main' ) ) dynamic_sidebar( 'sidebar-main' ); ?>
    <?php endif; ?>
</aside>

</div><!-- .content-area -->
</div><!-- .container -->
</main>

<?php get_footer(); ?>
