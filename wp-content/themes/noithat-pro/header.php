<?php
/**
 * Header Template - NoiThat Pro
 * 
 * Template hiển thị phần header của website
 * Bao gồm: Logo, Menu điều hướng, Giỏ hàng, Tìm kiếm
 * 
 * @package NoiThat_Pro
 */

$np_shop_url = function_exists('noithat_pro_get_shop_url')
    ? noithat_pro_get_shop_url()
    : home_url('/shop/');

$np_account_url = function_exists('noithat_pro_get_account_page_url')
    ? noithat_pro_get_account_page_url()
    : wp_login_url();

$np_logout_url = wp_logout_url(home_url('/'));
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NoiThat Pro - Cửa hàng nội thất cao cấp, hiện đại. Sofa, bàn, ghế, tủ với thiết kế tinh tế, chất lượng vượt trội.">
    <meta name="keywords" content="nội thất, sofa, bàn ghế, tủ kệ, nội thất cao cấp, furniture">
    <meta name="author" content="NoiThat Pro">
    
    <!-- Open Graph cho mạng xã hội -->
    <meta property="og:title" content="<?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?>">
    <meta property="og:description" content="Cửa hàng nội thất cao cấp, hiện đại - NoiThat Pro">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/favicon.ico">
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ===== HEADER START ===== -->
<header class="np-header" id="np-header">
    <div class="np-header-inner">
        
        <!-- Logo -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="np-logo" id="np-logo">
            <div class="np-logo-icon">
                <i class="fas fa-couch"></i>
            </div>
            <div class="np-logo-text">
                Nội Thất <span>Pro</span>
            </div>
        </a>
        
        <!-- Menu điều hướng chính -->
        <nav class="np-nav" id="np-main-nav">
            <ul>
                <li class="<?php echo is_front_page() ? 'current-menu-item' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Trang Chủ</a>
                </li>
                <li class="<?php echo (function_exists('is_shop') && (is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag())) ? 'current-menu-item' : ''; ?>">
                    <a href="<?php echo esc_url($np_shop_url); ?>">Cửa Hàng</a>
                </li>
                <li class="<?php echo is_page('gioi-thieu') ? 'current-menu-item' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/gioi-thieu')); ?>">Giới Thiệu</a>
                </li>
                <li class="<?php echo is_page('lien-he') ? 'current-menu-item' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/lien-he')); ?>">Liên Hệ</a>
                </li>
            </ul>
        </nav>
        
        <!-- Header Actions: Tìm kiếm + Giỏ hàng + Menu mobile -->
        <div class="np-header-actions">
            <!-- Nút tìm kiếm -->
            <button class="np-header-btn np-search-toggle" id="np-search-btn" aria-label="Tìm kiếm">
                <i class="fas fa-search"></i>
            </button>

            <?php if (is_user_logged_in()) : ?>
                <?php if (current_user_can('manage_options')) : ?>
                <a href="<?php echo esc_url(admin_url()); ?>" class="np-header-btn" aria-label="Trang quản trị" title="Trang quản trị">
                    <i class="fas fa-user-shield"></i>
                </a>
                <?php else : ?>
                <a href="<?php echo esc_url($np_account_url); ?>" class="np-header-btn" aria-label="Tài khoản" title="Tài khoản">
                    <i class="fas fa-user"></i>
                </a>
                <?php endif; ?>
                <a href="<?php echo esc_url($np_logout_url); ?>" class="np-header-btn" aria-label="Đăng xuất" title="Đăng xuất">
                    <i class="fas fa-right-from-bracket"></i>
                </a>
            <?php else : ?>
            <a href="<?php echo esc_url($np_account_url); ?>" class="np-header-btn" aria-label="Đăng nhập" title="Đăng nhập">
                <i class="fas fa-right-to-bracket"></i>
            </a>
            <?php endif; ?>
            
            <!-- Nút giỏ hàng -->
            <?php if (function_exists('WC')) : ?>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="np-header-btn" id="np-cart-btn" aria-label="Giỏ hàng">
                <i class="fas fa-shopping-bag"></i>
                <span class="np-cart-count">
                    <?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : '0'; ?>
                </span>
            </a>
            <?php endif; ?>
            
            <!-- Hamburger menu (mobile) -->
            <div class="np-menu-toggle" id="np-menu-toggle" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        
    </div>
    
    <!-- Search Form (ẩn, hiện khi click) -->
    <div class="np-search-form" id="np-search-form" style="display:none;padding:1rem 2.5rem;background:var(--np-bg-section);border-top:1px solid var(--np-border-light);">
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" style="max-width:600px;margin:0 auto;display:flex;gap:8px;">
            <input type="search" name="s" placeholder="Tìm kiếm sản phẩm..." 
                   value="<?php echo get_search_query(); ?>"
                   style="flex:1;padding:12px 18px;border:1px solid var(--np-border);border-radius:var(--np-radius-sm);font-size:0.95rem;">
            <?php if (function_exists('WC')) : ?>
            <input type="hidden" name="post_type" value="product">
            <?php endif; ?>
            <button type="submit" class="np-btn np-btn-primary" style="padding:12px 24px;">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</header>
<!-- ===== HEADER END ===== -->

<main id="np-main-content">
