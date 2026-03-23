<?php
/**
 * Template Name: FAFO Merch
 */
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:50px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.6rem;font-weight:900;color:#FFD700;margin:0 0 10px;">FAFO MERCH STORE</h1>
    <p style="font-size:1.1rem;color:rgba(255,255,255,0.85);max-width:550px;margin:0 auto;">
        Wear your patriotism. Show the world where you stand. 100% American-printed gear for American patriots.
    </p>
</div>

<!-- NOTICE -->
<div style="background:#FFF3CD;border:1px solid #FFD700;border-radius:6px;padding:16px 20px;margin-bottom:32px;display:flex;align-items:center;gap:12px;">
    <i class="fas fa-shopping-bag" style="color:#856404;font-size:1.4rem;flex-shrink:0;"></i>
    <div>
        <strong style="color:#856404;">Merch Store Coming Soon!</strong>
        <p style="color:#856404;margin:4px 0 0;font-size:0.88rem;">Our store is being set up. Sign up for the newsletter to be notified when products go live — newsletter subscribers get 20% off their first order.</p>
    </div>
</div>

<?php
$products = [
    [
        'name'   => '"FAFO" Classic Tee',
        'price'  => '$29.99',
        'tag'    => 'BESTSELLER',
        'desc'   => 'Bold FAFO logo on heavyweight 100% cotton. Available in Navy, Red, and Black. Unisex sizing S-3XL.',
        'color'  => '#C8102E',
    ],
    [
        'name'   => 'America First Eagle Hoodie',
        'price'  => '$54.99',
        'tag'    => 'NEW',
        'desc'   => 'Premium fleece pullover hoodie featuring the FAFO bald eagle emblem. Heavyweight 8oz fleece. S-3XL.',
        'color'  => '#002868',
    ],
    [
        'name'   => 'Patriot Dad Hat',
        'price'  => '$24.99',
        'tag'    => '',
        'desc'   => 'Structured dad hat with embroidered FAFO eagle logo. Adjustable strap. Available in Navy/Gold.',
        'color'  => '#002868',
    ],
    [
        'name'   => '"No Spin Zone" Mug',
        'price'  => '$19.99',
        'tag'    => '',
        'desc'   => '15oz ceramic mug. Dishwasher safe. Bold FAFO branding. Start every morning the America First way.',
        'color'  => '#666',
    ],
    [
        'name'   => 'FAFO Flag (3x5 ft)',
        'price'  => '$39.99',
        'tag'    => 'HOT',
        'desc'   => 'Fly it proudly. 3x5 foot polyester flag with FAFO eagle design on red/white/blue. Double-stitched.',
        'color'  => '#C8102E',
    ],
    [
        'name'   => '"For America First Only" Long-Sleeve Shirt',
        'price'  => '$34.99',
        'tag'    => '',
        'desc'   => 'Full statement shirt with the complete FAFO motto across the back. Front chest logo. S-3XL.',
        'color'  => '#002868',
    ],
    [
        'name'   => 'FAFO Sticker Pack (10)',
        'price'  => '$9.99',
        'tag'    => 'VALUE',
        'desc'   => '10 premium vinyl stickers — waterproof and UV-resistant. Perfect for laptops, trucks, and gun cases.',
        'color'  => '#555',
    ],
    [
        'name'   => '"Deep State Enemy" Tee',
        'price'  => '$29.99',
        'tag'    => '',
        'desc'   => 'Let them know where you stand. Bold graphic tee with "Proud Deep State Enemy" design. S-3XL.',
        'color'  => '#333',
    ],
];
?>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:24px;margin-bottom:48px;">
    <?php foreach ( $products as $prod ) : ?>
    <div style="background:#fff;border-radius:8px;border:1px solid #E8E8E8;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06);transition:transform .2s,box-shadow .2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.12)';" onmouseout="this.style.transform='';this.style.boxShadow='0 2px 8px rgba(0,0,0,.06)';">
        <!-- Product Image Placeholder -->
        <div style="height:220px;background:linear-gradient(135deg,<?php echo esc_attr($prod['color']); ?>,rgba(0,0,0,.6));display:flex;align-items:center;justify-content:center;position:relative;">
            <div style="text-align:center;color:#fff;">
                <div style="font-family:var(--font-head);font-size:2.5rem;font-weight:900;letter-spacing:0.05em;">F<span style="color:#FFD700;">A</span>FO</div>
                <div style="font-size:0.7rem;letter-spacing:0.15em;text-transform:uppercase;opacity:0.7;margin-top:4px;">FOR AMERICA FIRST ONLY</div>
            </div>
            <?php if ( $prod['tag'] ) : ?>
            <div style="position:absolute;top:12px;right:12px;background:#FFD700;color:#002868;font-family:var(--font-head);font-size:0.68rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;padding:4px 10px;border-radius:20px;">
                <?php echo esc_html($prod['tag']); ?>
            </div>
            <?php endif; ?>
        </div>
        <div style="padding:18px;">
            <h3 style="font-family:var(--font-head);font-size:1rem;font-weight:700;color:#002868;margin:0 0 6px;"><?php echo esc_html($prod['name']); ?></h3>
            <p style="font-size:0.82rem;color:#666;line-height:1.55;margin:0 0 14px;"><?php echo esc_html($prod['desc']); ?></p>
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="font-family:var(--font-head);font-size:1.3rem;font-weight:900;color:#C8102E;"><?php echo esc_html($prod['price']); ?></span>
                <button style="background:#002868;color:#fff;border:none;padding:8px 18px;font-family:var(--font-head);font-size:0.78rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;cursor:pointer;">
                    <i class="fas fa-shopping-cart"></i> Pre-Order
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- NOTIFY ME -->
<section style="background:linear-gradient(135deg,#C8102E,#8b0000);padding:40px;border-radius:8px;text-align:center;color:#fff;margin-bottom:40px;">
    <h3 style="font-family:var(--font-head);font-size:1.6rem;font-weight:900;color:#FFD700;margin:0 0 10px;">BE FIRST IN LINE</h3>
    <p style="opacity:0.9;margin:0 0 20px;">Newsletter subscribers get 20% off when the store launches. Sign up now.</p>
    <a href="<?php echo esc_url( fafo_page_link('newsletter') ); ?>" style="display:inline-block;background:#FFD700;color:#002868;padding:14px 36px;font-family:var(--font-head);font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;border-radius:4px;">
        <i class="fas fa-envelope"></i> Get Notified — Subscribe Free
    </a>
</section>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-tag"></i> Categories</h3>
        <div class="widget-body">
            <ul style="list-style:none;padding:0;margin:0;">
                <?php
                $cats = ['T-Shirts','Hoodies','Hats & Caps','Mugs & Drinkware','Flags & Banners','Stickers & Decals','Accessories'];
                foreach ( $cats as $c ) : ?>
                <li style="padding:6px 0;border-bottom:1px solid #eee;font-size:0.88rem;">
                    <a href="#" style="color:#333;text-decoration:none;"><i class="fas fa-chevron-right" style="font-size:10px;color:#C8102E;margin-right:8px;"></i><?php echo esc_html($c); ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>
<?php get_footer(); ?>
