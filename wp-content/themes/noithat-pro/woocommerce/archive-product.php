<?php
/**
 * Archive Product Template - NoiThat Pro
 * 
 * Template tùy chỉnh cho trang danh sách sản phẩm WooCommerce (Shop, Category).
 * 
 * @package NoiThat_Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<!-- Page Header -->
<section class="np-page-header">
    <div class="np-container">
        <span class="np-section-subtitle">Cửa Hàng</span>
        <?php if (is_product_category()) : ?>
            <h1><?php single_cat_title(); ?></h1>
            <?php 
            $term_description = term_description();
            if ($term_description) : ?>
                <p><?php echo wp_strip_all_tags($term_description); ?></p>
            <?php endif; ?>
        <?php elseif (is_product_tag()) : ?>
            <h1>Tag: <?php single_tag_title(); ?></h1>
        <?php else : ?>
            <h1>Tất Cả Sản Phẩm</h1>
            <p>Khám phá bộ sưu tập nội thất cao cấp của chúng tôi</p>
        <?php endif; ?>
    </div>
</section>

<!-- Breadcrumb -->
<?php if (function_exists('woocommerce_breadcrumb')) : ?>
<div class="np-container" style="padding-top:1rem;">
    <?php woocommerce_breadcrumb(); ?>
</div>
<?php endif; ?>

<!-- Products -->
<section class="np-section">
    <div class="np-container">
        
        <?php if (woocommerce_product_loop()) : ?>
            
            <!-- Toolbar: Sort & Count -->
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
                <p class="woocommerce-result-count" style="margin:0;color:var(--np-text-secondary);font-size:0.95rem;">
                    <?php woocommerce_result_count(); ?>
                </p>
                <div>
                    <?php woocommerce_catalog_ordering(); ?>
                </div>
            </div>
            
            <?php woocommerce_product_loop_start(); ?>
            
            <?php if (wc_get_loop_prop('total')) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    /**
                     * Hook: woocommerce_shop_loop
                     */
                    do_action('woocommerce_shop_loop');
                    wc_get_template_part('content', 'product');
                    ?>
                <?php endwhile; ?>
            <?php endif; ?>
            
            <?php woocommerce_product_loop_end(); ?>
            
            <!-- Pagination -->
            <div style="margin-top:3rem;">
                <?php woocommerce_pagination(); ?>
            </div>
            
        <?php else : ?>
            <!-- No Products -->
            <div style="text-align:center;padding:4rem 0;">
                <div style="font-size:4rem;color:var(--np-text-light);margin-bottom:1.5rem;">
                    <i class="fas fa-box-open"></i>
                </div>
                <h2 style="font-family:var(--np-font-heading);font-size:1.5rem;margin-bottom:1rem;">
                    Chưa Có Sản Phẩm
                </h2>
                <p style="color:var(--np-text-secondary);margin-bottom:2rem;">
                    Danh mục này hiện chưa có sản phẩm nào. Vui lòng quay lại sau.
                </p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="np-btn np-btn-primary">
                    <i class="fas fa-home"></i> Về Trang Chủ
                </a>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<?php get_footer(); ?>
