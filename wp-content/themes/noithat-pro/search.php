<?php
/**
 * Search Results Template - NoiThat Pro
 * 
 * Template hiển thị kết quả tìm kiếm
 * 
 * @package NoiThat_Pro
 */

get_header();
?>

<!-- Page Header -->
<section class="np-page-header">
    <div class="np-container">
        <span class="np-section-subtitle">Kết Quả Tìm Kiếm</span>
        <h1>Tìm kiếm: "<?php echo esc_html(get_search_query()); ?>"</h1>
        <p>
            <?php 
            global $wp_query;
            $total = $wp_query->found_posts;
            printf('Tìm thấy %d kết quả', $total);
            ?>
        </p>
    </div>
</section>

<!-- Search Results -->
<section class="np-search-section">
    <div class="np-container">
        
        <!-- Search Form lặp lại để user có thể tìm kiếm lại -->
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" 
              style="max-width:600px;margin:0 auto 3rem;display:flex;gap:8px;">
            <input type="search" name="s" placeholder="Tìm kiếm sản phẩm..." 
                   value="<?php echo get_search_query(); ?>"
                   style="flex:1;padding:14px 20px;border:1px solid var(--np-border);border-radius:var(--np-radius-sm);font-size:0.95rem;font-family:var(--np-font-body);"
                   id="np-search-input">
            <?php if (function_exists('WC')) : ?>
            <input type="hidden" name="post_type" value="product">
            <?php endif; ?>
            <button type="submit" class="np-btn np-btn-primary" style="padding:14px 24px;">
                <i class="fas fa-search"></i> Tìm
            </button>
        </form>
        
        <?php if (have_posts()) : ?>
            
            <?php 
            // Kiểm tra nếu là kết quả WooCommerce products
            if (function_exists('WC') && isset($_GET['post_type']) && $_GET['post_type'] === 'product') : 
            ?>
                <!-- Hiển thị dạng lưới sản phẩm WooCommerce -->
                <div class="np-products-grid">
                    <?php while (have_posts()) : the_post(); 
                        global $product;
                        if (!$product) continue;
                    ?>
                    <div class="np-product-card np-animate">
                        <div class="np-product-img-wrap">
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                if (function_exists('noithat_pro_get_product_image_html')) {
                                    echo noithat_pro_get_product_image_html(get_the_ID(), 'np-product-thumb');
                                } elseif (has_post_thumbnail()) {
                                    the_post_thumbnail('np-product-thumb');
                                } else {
                                    echo function_exists('wc_placeholder_img')
                                        ? wc_placeholder_img('np-product-thumb', array('loading' => 'lazy'))
                                        : '<span class="np-placeholder-img"><i class="fas fa-image"></i></span>';
                                }
                                ?>
                            </a>
                            
                            <?php if ($product->is_on_sale()) : ?>
                                <span class="np-product-badge np-badge-sale">Giảm giá</span>
                            <?php endif; ?>
                        </div>
                        <div class="np-product-info">
                            <div class="np-product-category">
                                <?php 
                                $terms = get_the_terms(get_the_ID(), 'product_cat');
                                if ($terms && !is_wp_error($terms)) {
                                    echo esc_html($terms[0]->name);
                                }
                                ?>
                            </div>
                            <h3 class="np-product-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="np-product-price">
                                <span class="np-price-current"><?php echo $product->get_price_html(); ?></span>
                            </div>
                        </div>
                        <button class="np-add-to-cart" 
                                onclick="location.href='<?php echo esc_url($product->add_to_cart_url()); ?>'">
                            <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                        </button>
                    </div>
                    <?php endwhile; ?>
                </div>
            
            <?php else : ?>
                <!-- Hiển thị dạng danh sách bài viết thường -->
                <div class="np-search-results-list">
                    <?php while (have_posts()) : the_post(); ?>
                    <article class="np-search-result-item np-animate">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="np-search-result-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" 
                           style="color:var(--np-primary);font-weight:600;font-size:0.9rem;display:inline-flex;align-items:center;gap:6px;margin-top:8px;">
                            Xem chi tiết <i class="fas fa-arrow-right" style="font-size:0.8em;"></i>
                        </a>
                    </article>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
            
            <!-- Phân trang -->
            <div style="text-align:center;margin-top:3rem;">
                <?php
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => '<i class="fas fa-chevron-left"></i> Trước',
                    'next_text' => 'Sau <i class="fas fa-chevron-right"></i>',
                ));
                ?>
            </div>
            
        <?php else : ?>
            <!-- Không có kết quả -->
            <div style="text-align:center;padding:4rem 0;">
                <div style="font-size:4rem;color:var(--np-text-light);margin-bottom:1.5rem;">
                    <i class="fas fa-search"></i>
                </div>
                <h2 style="font-family:var(--np-font-heading);font-size:1.5rem;margin-bottom:1rem;">
                    Không Tìm Thấy Kết Quả
                </h2>
                <p style="color:var(--np-text-secondary);margin-bottom:2rem;max-width:500px;margin-left:auto;margin-right:auto;">
                    Không có kết quả nào phù hợp với từ khóa "<strong><?php echo esc_html(get_search_query()); ?></strong>". 
                    Vui lòng thử lại với từ khóa khác.
                </p>
                <a href="<?php echo esc_url(home_url('/shop')); ?>" class="np-btn np-btn-primary">
                    <i class="fas fa-store"></i> Xem Cửa Hàng
                </a>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<?php get_footer(); ?>
