<?php
/**
 * 404 Template - NoiThat Pro
 * 
 * Template hiển thị khi trang không tìm thấy (Error 404)
 * 
 * @package NoiThat_Pro
 */

get_header();
?>

<section class="np-404-section">
    <div class="np-container">
        <div class="np-404-number">404</div>
        <h1 class="np-404-title">Trang Không Tìm Thấy</h1>
        <p class="np-404-desc">
            Xin lỗi, trang bạn đang tìm kiếm không tồn tại hoặc đã được di chuyển. 
            Hãy thử tìm kiếm hoặc quay về trang chủ.
        </p>
        
        <!-- Search Form -->
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" 
              style="max-width:500px;margin:0 auto 2rem;display:flex;gap:8px;">
            <input type="search" name="s" placeholder="Tìm kiếm sản phẩm..." 
                   style="flex:1;padding:14px 20px;border:1px solid var(--np-border);border-radius:var(--np-radius-sm);font-size:0.95rem;font-family:var(--np-font-body);"
                   id="np-404-search">
            <?php if (function_exists('WC')) : ?>
            <input type="hidden" name="post_type" value="product">
            <?php endif; ?>
            <button type="submit" class="np-btn np-btn-primary" style="padding:14px 24px;">
                <i class="fas fa-search"></i>
            </button>
        </form>
        
        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="np-btn np-btn-primary">
                <i class="fas fa-home"></i> Trang Chủ
            </a>
            <?php if (function_exists('wc_get_page_permalink')) : ?>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="np-btn np-btn-dark">
                <i class="fas fa-shopping-bag"></i> Cửa Hàng
            </a>
            <?php endif; ?>
            <a href="<?php echo esc_url(home_url('/lien-he')); ?>" class="np-btn np-btn-outline" 
               style="color:var(--np-text-primary);border-color:var(--np-border);">
                <i class="fas fa-envelope"></i> Liên Hệ
            </a>
        </div>
    </div>
</section>

<!-- Sản phẩm gợi ý -->
<section class="np-section" style="background:var(--np-bg-section);">
    <div class="np-container">
        <div class="np-section-header">
            <span class="np-section-subtitle">Gợi Ý Cho Bạn</span>
            <h2 class="np-section-title">Sản Phẩm Nổi Bật</h2>
        </div>
        <?php echo do_shortcode('[san_pham_noi_bat so_luong="4" cot="4"]'); ?>
    </div>
</section>

<?php get_footer(); ?>
