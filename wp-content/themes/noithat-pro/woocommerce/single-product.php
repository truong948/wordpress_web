<?php
/**
 * Single Product Template - NoiThat Pro
 * 
 * Template tùy chỉnh cho trang chi tiết sản phẩm WooCommerce.
 * Override WooCommerce single-product template.
 * 
 * @package NoiThat_Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<?php
// Breadcrumb
if (function_exists('woocommerce_breadcrumb')) {
    echo '<div class="np-container" style="padding-top:1rem;">';
    woocommerce_breadcrumb();
    echo '</div>';
}
?>

<?php while (have_posts()) : the_post(); ?>

<?php wc_get_template_part('content', 'single-product'); ?>

<?php endwhile; ?>

<!-- Sản phẩm liên quan - Custom styling -->
<section class="np-section" style="background:var(--np-bg-section);">
    <div class="np-container">
        <div class="np-section-header">
            <span class="np-section-subtitle">Có Thể Bạn Thích</span>
            <h2 class="np-section-title">Sản Phẩm Liên Quan</h2>
        </div>
    </div>
</section>

<?php get_footer(); ?>
