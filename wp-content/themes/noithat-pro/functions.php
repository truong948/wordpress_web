<?php
/**
 * NoiThat Pro - Child Theme Functions
 * 
 * File này chứa toàn bộ tùy chỉnh PHP cho child theme NoiThat Pro.
 * Bao gồm: enqueue styles/scripts, custom shortcodes, widget areas,
 * WooCommerce customization, và các tính năng bổ sung.
 * 
 * @package NoiThat_Pro
 * @version 1.0.0
 * @author SV CNTT
 */

// Ngăn truy cập trực tiếp vào file PHP
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// 1. ENQUEUE STYLES & SCRIPTS
// Nạp CSS và JavaScript cho theme
// ============================================================================

/**
 * Nạp Google Fonts, CSS tùy chỉnh và JavaScript
 * 
 * Hook: wp_enqueue_scripts
 * Priority: 20 (sau parent theme)
 */
function noithat_pro_enqueue_assets() {
    // Nạp parent theme (Astra) styles
    wp_enqueue_style(
        'astra-parent-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme('astra')->get('Version')
    );
    
    // Nạp child theme style.css
    wp_enqueue_style(
        'noithat-pro-style',
        get_stylesheet_uri(),
        array('astra-parent-style'),
        '1.0.0'
    );
    
    // Google Fonts: Playfair Display + Inter
    wp_enqueue_style(
        'noithat-pro-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap',
        array(),
        null
    );
    
    // Font Awesome Icons
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        array(),
        '6.5.1'
    );
    
    // CSS tùy chỉnh components
    wp_enqueue_style(
        'noithat-pro-custom',
        get_stylesheet_directory_uri() . '/assets/css/custom.css',
        array('noithat-pro-style'),
        '1.0.0'
    );
    
    // JavaScript chính
    wp_enqueue_script(
        'noithat-pro-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        array('jquery'),
        '1.0.0',
        true // Load ở footer
    );
    
    // Truyền dữ liệu PHP sang JavaScript (AJAX URL, etc.)
    wp_localize_script('noithat-pro-main', 'noithatProData', array(
        'ajaxUrl'  => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('noithat_pro_nonce'),
        'siteUrl'  => home_url('/'),
        'shopUrl'  => function_exists('wc_get_page_permalink') 
                      ? wc_get_page_permalink('shop') : '',
        'placeholderImage' => noithat_pro_get_product_placeholder_url(),
    ));
}
add_action('wp_enqueue_scripts', 'noithat_pro_enqueue_assets', 20);


// ============================================================================
// 2. THEME SETUP
// Cấu hình cơ bản cho theme
// ============================================================================

/**
 * Thiết lập các tính năng theme
 */
function noithat_pro_setup() {
    // Hỗ trợ WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Hỗ trợ ảnh đại diện bài viết
    add_theme_support('post-thumbnails');
    
    // Kích thước ảnh tùy chỉnh cho sản phẩm
    add_image_size('np-product-thumb', 600, 600, true);  // Ảnh sản phẩm vuông
    add_image_size('np-product-large', 800, 800, true);  // Ảnh SP lớn
    add_image_size('np-category-thumb', 400, 500, true); // Ảnh danh mục
    add_image_size('np-hero-banner', 1920, 800, true);   // Ảnh banner
    
    // Đăng ký menu điều hướng
    register_nav_menus(array(
        'primary-menu'  => __('Menu Chính', 'noithat-pro'),
        'footer-menu'   => __('Menu Footer', 'noithat-pro'),
    ));
    
    // Title tag tự động
    add_theme_support('title-tag');
    
    // HTML5 cho các form
    add_theme_support('html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ));
    
    // Custom logo
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'noithat_pro_setup');

/**
 * URL ảnh fallback cho sản phẩm khi deploy/import không tải được ảnh từ CSV.
 */
function noithat_pro_get_product_placeholder_url() {
    return get_stylesheet_directory_uri() . '/assets/images/product-placeholder.svg';
}

/**
 * Đồng bộ placeholder WooCommerce sang ảnh local của theme để tránh phụ thuộc URL ngoài.
 */
function noithat_pro_woocommerce_placeholder_src($src) {
    return noithat_pro_get_product_placeholder_url();
}
add_filter('woocommerce_placeholder_img_src', 'noithat_pro_woocommerce_placeholder_src');

/**
 * Nạp map ảnh theo SKU từ file JSON được export từ CSV.
 */
function noithat_pro_get_sku_image_map() {
    static $images_by_sku = null;

    if (null !== $images_by_sku) {
        return $images_by_sku;
    }

    $images_by_sku = array();
    $map_file = get_stylesheet_directory() . '/assets/data/sku-image-map.json';

    if (!file_exists($map_file) || !is_readable($map_file)) {
        return $images_by_sku;
    }

    $raw = file_get_contents($map_file);
    if (false === $raw || '' === trim($raw)) {
        return $images_by_sku;
    }

    $payload = json_decode($raw, true);
    if (
        is_array($payload)
        && isset($payload['imagesBySku'])
        && is_array($payload['imagesBySku'])
    ) {
        foreach ($payload['imagesBySku'] as $sku => $url) {
            $normalized_sku = strtoupper(trim((string) $sku));
            $normalized_url = trim((string) $url);
            if ('' === $normalized_sku || !wp_http_validate_url($normalized_url)) {
                continue;
            }

            $images_by_sku[$normalized_sku] = $normalized_url;
        }
    }

    return $images_by_sku;
}

/**
 * Fallback built-in theo SKU để vẫn hiện ảnh thật khi file map JSON bị thiếu.
 */
function noithat_pro_get_builtin_image_url_by_sku($sku) {
    $sku = strtoupper(trim((string) $sku));
    if (!preg_match('/^NTP-([A-Z]{2})(\d{3})$/', $sku, $matches)) {
        return '';
    }

    $group = $matches[1];
    $index = max(0, (intval($matches[2]) - 1) % 6);

    $pool = array(
        'SF' => array(
            'https://images.unsplash.com/photo-1493666438817-866a91353ca9?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1484101403633-562f891dc89a?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1200&q=80',
        ),
        'BA' => array(
            'https://images.unsplash.com/photo-1519710884006-4d4f6494f0d7?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1549187774-b4e9b0445b41?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1565538810643-b5bdb714032a?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1586105251261-72a756497a12?auto=format&fit=crop&w=1200&q=80',
        ),
        'GH' => array(
            'https://images.unsplash.com/photo-1579656592043-a20e25a4aa4b?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1616627451902-6fa33f9c5b79?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1551298370-9d3d53740c72?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1601396974228-7ad4dff65136?auto=format&fit=crop&w=1200&q=80',
        ),
        'TK' => array(
            'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1616594039964-3f5f6c5f67df?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1578898887932-dce23a595ad4?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1597074866923-dc0589150358?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1582582429416-97d2f9f5a93c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1556911220-bff31c812dba?auto=format&fit=crop&w=1200&q=80',
        ),
        'GN' => array(
            'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1540518614846-7eded433c457?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1616596871468-6f4d30fbd8b4?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1616628182509-6f3cb0f5d2fc?auto=format&fit=crop&w=1200&q=80',
        ),
    );

    if (!isset($pool[$group]) || !isset($pool[$group][$index])) {
        return '';
    }

    return $pool[$group][$index];
}

/**
 * Lấy URL ảnh external từ map JSON theo SKU sản phẩm.
 */
function noithat_pro_get_external_image_url_by_product($post_id) {
    $sku = '';
    if (function_exists('wc_get_product')) {
        $product = wc_get_product($post_id);
        if ($product && is_a($product, 'WC_Product')) {
            $sku = $product->get_sku();
        }
    }

    if (empty($sku)) {
        $sku = get_post_meta($post_id, '_sku', true);
    }

    $sku = strtoupper(trim((string) $sku));
    if ('' === $sku) {
        return '';
    }

    $map = noithat_pro_get_sku_image_map();
    if (isset($map[$sku])) {
        $url = trim((string) $map[$sku]);
        if (wp_http_validate_url($url)) {
            return $url;
        }
    }

    $built_in_url = noithat_pro_get_builtin_image_url_by_sku($sku);
    if (wp_http_validate_url($built_in_url)) {
        return $built_in_url;
    }

    return '';
}

/**
 * Render thẻ img an toàn từ URL.
 */
function noithat_pro_build_image_tag($url, $attributes = array()) {
    if (!is_array($attributes)) {
        $attributes = array();
    }

    $attributes = array_merge(
        array(
            'loading' => 'lazy',
            'alt'     => '',
        ),
        $attributes
    );

    if (isset($attributes['class']) && is_array($attributes['class'])) {
        $attributes['class'] = implode(' ', array_filter($attributes['class']));
    }

    $attributes['src'] = $url;

    $parts = array();
    foreach ($attributes as $key => $value) {
        if (null === $value || '' === $value) {
            continue;
        }

        $parts[] = esc_attr($key) . '="' . esc_attr((string) $value) . '"';
    }

    return '<img ' . implode(' ', $parts) . '>';
}

/**
 * Fallback ảnh WooCommerce từ map SKU khi chưa có thumbnail attachment.
 */
function noithat_pro_woocommerce_product_image_fallback($image, $product, $size, $attr, $placeholder) {
    if (!$product || !is_a($product, 'WC_Product')) {
        return $image;
    }

    if ($product->get_image_id()) {
        return $image;
    }

    $external_url = noithat_pro_get_external_image_url_by_product($product->get_id());
    if (empty($external_url)) {
        return $image;
    }

    $attributes = array(
        'alt'     => $product->get_name(),
        'loading' => 'lazy',
    );

    if (is_array($attr)) {
        $attributes = array_merge($attributes, $attr);
    }

    return noithat_pro_build_image_tag($external_url, $attributes);
}
add_filter('woocommerce_product_get_image', 'noithat_pro_woocommerce_product_image_fallback', 10, 5);

/**
 * Trả về HTML ảnh sản phẩm với fallback an toàn khi chưa có thumbnail.
 */
function noithat_pro_get_product_image_html($post_id, $size = 'np-product-thumb', $attributes = array()) {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, $attributes);
    }

    $default_attributes = array(
        'loading' => 'lazy',
        'class'   => 'np-product-fallback-image',
        'alt'     => get_the_title($post_id),
    );
    $attributes = array_merge($default_attributes, $attributes);

    $external_url = noithat_pro_get_external_image_url_by_product($post_id);
    if (!empty($external_url)) {
        return noithat_pro_build_image_tag($external_url, $attributes);
    }

    if (function_exists('wc_placeholder_img')) {
        return wc_placeholder_img($size, $attributes);
    }

    return '<img src="' . esc_url(noithat_pro_get_product_placeholder_url()) . '" alt="' . esc_attr(get_the_title($post_id)) . '" loading="lazy">';
}


// ============================================================================
// 3. WIDGET AREAS
// Đăng ký vùng widget (sidebar)
// ============================================================================

/**
 * Đăng ký các sidebar/widget areas
 */
function noithat_pro_widgets_init() {
    // Sidebar trang shop
    register_sidebar(array(
        'name'          => __('Sidebar Cửa Hàng', 'noithat-pro'),
        'id'            => 'shop-sidebar',
        'description'   => 'Widget hiển thị ở trang cửa hàng',
        'before_widget' => '<div id="%1$s" class="widget np-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title np-widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Footer widget columns
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name'          => sprintf(__('Footer Cột %d', 'noithat-pro'), $i),
            'id'            => 'footer-col-' . $i,
            'before_widget' => '<div id="%1$s" class="widget np-footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="np-footer-title">',
            'after_title'   => '</h4>',
        ));
    }
}
add_action('widgets_init', 'noithat_pro_widgets_init');


// ============================================================================
// 4. CUSTOM SHORTCODES
// Các shortcode tùy chỉnh để sử dụng trong bài viết/trang
// ============================================================================

/**
 * [san_pham_noi_bat] - Hiển thị sản phẩm nổi bật
 * 
 * Sử dụng: [san_pham_noi_bat so_luong="4" cot="4"]
 * 
 * @param array $atts Các tham số shortcode
 * @return string HTML output
 */
function noithat_pro_featured_products_shortcode($atts) {
    // Giá trị mặc định
    $atts = shortcode_atts(array(
        'so_luong' => 4,    // Số sản phẩm hiển thị
        'cot'      => 4,    // Số cột
        'danh_muc' => '',   // Slug danh mục (tùy chọn)
    ), $atts, 'san_pham_noi_bat');
    
    // Query sản phẩm nổi bật từ WooCommerce
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => intval($atts['so_luong']),
        'meta_key'       => '_featured',
        'meta_value'     => 'yes',
        'post_status'    => 'publish',
    );
    
    // Thêm filter theo danh mục nếu có
    if (!empty($atts['danh_muc'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => explode(',', $atts['danh_muc']),
            ),
        );
    }
    
    $products = new WP_Query($args);
    
    // Bắt đầu output buffering
    ob_start();
    
    if ($products->have_posts()) :
    ?>
    <div class="np-products-grid" style="grid-template-columns: repeat(<?php echo esc_attr($atts['cot']); ?>, 1fr);">
        <?php while ($products->have_posts()) : $products->the_post(); 
            global $product;
        ?>
        <div class="np-product-card np-animate">
            <div class="np-product-img-wrap">
                <a href="<?php the_permalink(); ?>">
                    <?php echo noithat_pro_get_product_image_html(get_the_ID(), 'np-product-thumb'); ?>
                </a>
                
                <?php if ($product->is_on_sale()) : ?>
                    <span class="np-product-badge np-badge-sale">Giảm giá</span>
                <?php elseif ($product->is_featured()) : ?>
                    <span class="np-product-badge np-badge-hot">Nổi bật</span>
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
                    <?php if ($product->is_on_sale()) : ?>
                        <span class="np-price-original"><?php echo $product->get_regular_price() ? wc_price($product->get_regular_price()) : ''; ?></span>
                    <?php endif; ?>
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
    <?php
    endif;
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('san_pham_noi_bat', 'noithat_pro_featured_products_shortcode');


/**
 * [san_pham_moi] - Hiển thị sản phẩm mới nhất
 * 
 * Sử dụng: [san_pham_moi so_luong="8"]
 */
function noithat_pro_new_products_shortcode($atts) {
    $atts = shortcode_atts(array(
        'so_luong' => 8,
        'cot'      => 4,
    ), $atts, 'san_pham_moi');
    
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => intval($atts['so_luong']),
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    );
    
    $products = new WP_Query($args);
    ob_start();
    
    if ($products->have_posts()) :
    ?>
    <div class="np-products-grid" style="grid-template-columns: repeat(<?php echo esc_attr($atts['cot']); ?>, 1fr);">
        <?php while ($products->have_posts()) : $products->the_post();
            global $product;
        ?>
        <div class="np-product-card np-animate">
            <div class="np-product-img-wrap">
                <a href="<?php the_permalink(); ?>">
                    <?php echo noithat_pro_get_product_image_html(get_the_ID(), 'np-product-thumb'); ?>
                </a>
                <span class="np-product-badge np-badge-new">Mới</span>
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
    <?php
    endif;
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('san_pham_moi', 'noithat_pro_new_products_shortcode');


/**
 * [danh_muc_san_pham] - Hiển thị lưới danh mục sản phẩm
 * 
 * Sử dụng: [danh_muc_san_pham so_luong="4"]
 */
function noithat_pro_categories_shortcode($atts) {
    $atts = shortcode_atts(array(
        'so_luong' => 4,
    ), $atts, 'danh_muc_san_pham');
    
    $categories = get_terms(array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'number'     => intval($atts['so_luong']),
        'exclude'    => get_option('default_product_cat'), // Loại bỏ "Uncategorized"
    ));
    
    ob_start();
    
    if (!empty($categories) && !is_wp_error($categories)) :
    ?>
    <div class="np-cat-grid">
        <?php foreach ($categories as $cat) : 
            $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
            $image_url = $thumbnail_id 
                ? wp_get_attachment_url($thumbnail_id) 
                : (function_exists('wc_placeholder_img_src')
                    ? wc_placeholder_img_src('woocommerce_single')
                    : 'https://via.placeholder.com/600x600?text=NoiThat+Pro');
            $cat_link = get_term_link($cat);
        ?>
        <a href="<?php echo esc_url($cat_link); ?>" class="np-cat-card np-animate">
            <img src="<?php echo esc_url($image_url); ?>" 
                 alt="<?php echo esc_attr($cat->name); ?>"
                 loading="lazy">
            <div class="np-cat-card-overlay">
                <h3 class="np-cat-card-title"><?php echo esc_html($cat->name); ?></h3>
                <span class="np-cat-card-count"><?php echo $cat->count; ?> sản phẩm</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php
    endif;
    
    return ob_get_clean();
}
add_shortcode('danh_muc_san_pham', 'noithat_pro_categories_shortcode');


/**
 * [banner_khuyen_mai] - Banner khuyến mãi CTA
 * 
 * Sử dụng: [banner_khuyen_mai tieu_de="Giảm 30%" mo_ta="Cho đơn hàng đầu tiên"]
 */
function noithat_pro_promo_banner_shortcode($atts) {
    $atts = shortcode_atts(array(
        'tieu_de' => 'Ưu Đãi Đặc Biệt',
        'mo_ta'   => 'Giảm đến 30% cho tất cả sản phẩm nội thất',
        'nut'     => 'Mua Ngay',
        'link'    => function_exists('wc_get_page_permalink') 
                     ? wc_get_page_permalink('shop') : '#',
    ), $atts, 'banner_khuyen_mai');
    
    ob_start();
    ?>
    <section class="np-cta">
        <div class="np-container">
            <h2 class="np-cta-title"><?php echo esc_html($atts['tieu_de']); ?></h2>
            <p class="np-cta-desc"><?php echo esc_html($atts['mo_ta']); ?></p>
            <a href="<?php echo esc_url($atts['link']); ?>" class="np-btn np-btn-primary" style="position:relative;">
                <?php echo esc_html($atts['nut']); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('banner_khuyen_mai', 'noithat_pro_promo_banner_shortcode');


// ============================================================================
// 5. WOOCOMMERCE CUSTOMIZATIONS
// Tùy chỉnh WooCommerce
// ============================================================================

/**
 * Thay đổi số sản phẩm mỗi trang
 */
function noithat_pro_products_per_page($cols) {
    return 12; // Hiển thị 12 sản phẩm mỗi trang
}
add_filter('loop_shop_per_page', 'noithat_pro_products_per_page', 20);

/**
 * Thay đổi số cột sản phẩm
 */
function noithat_pro_shop_columns($columns) {
    return 4; // 4 cột
}
add_filter('loop_shop_columns', 'noithat_pro_shop_columns');

/**
 * Thêm text "Miễn phí vận chuyển" dưới giá sản phẩm
 */
function noithat_pro_free_shipping_badge() {
    echo '<div class="np-free-shipping" style="color:var(--np-accent);font-size:0.85rem;margin-top:6px;">
            <i class="fas fa-truck"></i> Miễn phí vận chuyển
          </div>';
}
add_action('woocommerce_after_shop_loop_item_title', 'noithat_pro_free_shipping_badge', 15);

/**
 * Thay đổi text nút "Thêm vào giỏ hàng"
 */
function noithat_pro_add_to_cart_text($text) {
    return __('Thêm vào giỏ', 'noithat-pro');
}
add_filter('woocommerce_product_add_to_cart_text', 'noithat_pro_add_to_cart_text');

/**
 * Thêm tab "Chính sách đổi trả" vào trang sản phẩm
 */
function noithat_pro_custom_product_tab($tabs) {
    $tabs['doi_tra'] = array(
        'title'    => __('Chính Sách Đổi Trả', 'noithat-pro'),
        'priority' => 30,
        'callback' => 'noithat_pro_doi_tra_tab_content',
    );
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'noithat_pro_custom_product_tab');

/**
 * Nội dung tab "Chính sách đổi trả"
 */
function noithat_pro_doi_tra_tab_content() {
    ?>
    <div class="np-doi-tra">
        <h3><i class="fas fa-exchange-alt"></i> Chính Sách Đổi Trả</h3>
        <ul style="list-style:none;padding:0;">
            <li style="padding:8px 0;border-bottom:1px solid var(--np-border-light);">
                <i class="fas fa-check-circle" style="color:var(--np-accent);margin-right:8px;"></i>
                Đổi trả miễn phí trong vòng <strong>30 ngày</strong> kể từ ngày nhận hàng
            </li>
            <li style="padding:8px 0;border-bottom:1px solid var(--np-border-light);">
                <i class="fas fa-check-circle" style="color:var(--np-accent);margin-right:8px;"></i>
                Sản phẩm phải còn nguyên tem, nhãn mác, chưa qua sử dụng
            </li>
            <li style="padding:8px 0;border-bottom:1px solid var(--np-border-light);">
                <i class="fas fa-check-circle" style="color:var(--np-accent);margin-right:8px;"></i>
                Hoàn tiền 100% nếu sản phẩm lỗi từ nhà sản xuất
            </li>
            <li style="padding:8px 0;">
                <i class="fas fa-check-circle" style="color:var(--np-accent);margin-right:8px;"></i>
                Bảo hành <strong>12 tháng</strong> cho tất cả sản phẩm
            </li>
        </ul>
    </div>
    <?php
}

/**
 * Thêm breadcrumb tùy chỉnh
 */
function noithat_pro_custom_breadcrumb($defaults) {
    $defaults['delimiter']   = ' <i class="fas fa-chevron-right" style="font-size:0.7em;margin:0 10px;color:var(--np-text-light);"></i> ';
    $defaults['wrap_before'] = '<nav class="np-breadcrumb" style="padding:1rem 0;font-size:0.9rem;color:var(--np-text-secondary);">';
    $defaults['wrap_after']  = '</nav>';
    $defaults['home']        = '<i class="fas fa-home"></i> Trang chủ';
    return $defaults;
}
add_filter('woocommerce_breadcrumb_defaults', 'noithat_pro_custom_breadcrumb');


// ============================================================================
// 6. AJAX - Cập nhật giỏ hàng không reload trang
// ============================================================================

/**
 * Cập nhật số lượng giỏ hàng qua AJAX
 * Cho phép header hiển thị số lượng realtime
 */
function noithat_pro_cart_count_ajax() {
    // Kiểm tra nonce bảo mật
    check_ajax_referer('noithat_pro_nonce', 'nonce');
    
    if (function_exists('WC')) {
        wp_send_json_success(array(
            'count' => WC()->cart->get_cart_contents_count(),
            'total' => WC()->cart->get_cart_total(),
        ));
    }
    
    wp_send_json_error();
}
add_action('wp_ajax_noithat_cart_count', 'noithat_pro_cart_count_ajax');
add_action('wp_ajax_nopriv_noithat_cart_count', 'noithat_pro_cart_count_ajax');


// ============================================================================
// 7. CUSTOM ADMIN PANEL
// Thêm trang cấu hình trong Admin
// ============================================================================

/**
 * Thêm menu cài đặt theme trong Admin Dashboard
 */
function noithat_pro_admin_menu() {
    add_menu_page(
        'NoiThat Pro Settings',           // Page title
        'NoiThat Pro',                     // Menu title
        'manage_options',                  // Capability
        'noithat-pro-settings',            // Menu slug
        'noithat_pro_settings_page',       // Callback function
        'dashicons-admin-home',            // Icon
        60                                 // Position
    );
}
add_action('admin_menu', 'noithat_pro_admin_menu');

/**
 * Render trang cài đặt
 */
function noithat_pro_settings_page() {
    // Lưu settings khi submit form
    if (isset($_POST['np_save_settings']) && check_admin_referer('np_settings_nonce')) {
        update_option('np_phone', sanitize_text_field($_POST['np_phone'] ?? ''));
        update_option('np_email', sanitize_email($_POST['np_email'] ?? ''));
        update_option('np_address', sanitize_textarea_field($_POST['np_address'] ?? ''));
        update_option('np_facebook', esc_url_raw($_POST['np_facebook'] ?? ''));
        echo '<div class="notice notice-success"><p>Đã lưu cài đặt!</p></div>';
    }
    
    $phone   = get_option('np_phone', '0987 654 321');
    $email   = get_option('np_email', 'info@noithatpro.vn');
    $address = get_option('np_address', '123 Nguyễn Huệ, Q.1, TP.HCM');
    $facebook = get_option('np_facebook', '');
    ?>
    <div class="wrap">
        <h1><span class="dashicons dashicons-admin-home"></span> NoiThat Pro - Cài Đặt</h1>
        <form method="post" action="">
            <?php wp_nonce_field('np_settings_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="np_phone">Số điện thoại</label></th>
                    <td><input type="text" id="np_phone" name="np_phone" 
                         value="<?php echo esc_attr($phone); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="np_email">Email liên hệ</label></th>
                    <td><input type="email" id="np_email" name="np_email" 
                         value="<?php echo esc_attr($email); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="np_address">Địa chỉ</label></th>
                    <td><textarea id="np_address" name="np_address" class="large-text" 
                         rows="3"><?php echo esc_textarea($address); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="np_facebook">Facebook URL</label></th>
                    <td><input type="url" id="np_facebook" name="np_facebook" 
                         value="<?php echo esc_url($facebook); ?>" class="regular-text"></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="np_save_settings" class="button-primary" 
                       value="Lưu Cài Đặt">
            </p>
        </form>
    </div>
    <?php
}


// ============================================================================
// 8. PERFORMANCE & SEO
// Tối ưu hiệu suất và SEO
// ============================================================================

/**
 * Thêm lazy loading cho ảnh
 */
function noithat_pro_lazy_load_images($content) {
    if (is_admin()) return $content;
    
    // Thêm loading="lazy" cho tất cả thẻ img chưa có
    $content = preg_replace(
        '/<img((?!.*loading)[^>]*)>/i', 
        '<img$1 loading="lazy">', 
        $content
    );
    
    return $content;
}
add_filter('the_content', 'noithat_pro_lazy_load_images');

/**
 * Thêm meta description tự động cho sản phẩm
 */
function noithat_pro_product_meta_desc() {
    if (is_product()) {
        global $post;
        $excerpt = wp_strip_all_tags($post->post_excerpt);
        if (empty($excerpt)) {
            $excerpt = wp_trim_words(wp_strip_all_tags($post->post_content), 30);
        }
        if (!empty($excerpt)) {
            echo '<meta name="description" content="' . esc_attr($excerpt) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'noithat_pro_product_meta_desc', 1);

/**
 * Xóa bỏ các script/style không cần thiết để tăng tốc
 */
function noithat_pro_cleanup_head() {
    remove_action('wp_head', 'wp_generator');              // Ẩn phiên bản WP
    remove_action('wp_head', 'wlwmanifest_link');           // Windows Live Writer
    remove_action('wp_head', 'rsd_link');                   // Really Simple Discovery
    remove_action('wp_head', 'wp_shortlink_wp_head');       // Shortlink
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
}
add_action('init', 'noithat_pro_cleanup_head');


// ============================================================================
// 9. SECURITY ENHANCEMENTS  
// Tăng cường bảo mật
// ============================================================================

/**
 * Tắt XML-RPC (ngăn brute force)
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Ẩn phiên bản WordPress khỏi source code
 */
function noithat_pro_remove_version() {
    return '';
}
add_filter('the_generator', 'noithat_pro_remove_version');

/**
 * Giới hạn số lần đăng nhập sai
 */
function noithat_pro_login_check($user, $password) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $transient_key = 'login_attempts_' . md5($ip);
    $attempts = get_transient($transient_key);
    
    if ($attempts !== false && $attempts >= 5) {
        return new WP_Error(
            'too_many_attempts',
            __('Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau 15 phút.', 'noithat-pro')
        );
    }
    
    return $user;
}
add_filter('authenticate', 'noithat_pro_login_check', 30, 2);

/**
 * Đếm số lần đăng nhập sai
 */
function noithat_pro_login_failed($username) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $transient_key = 'login_attempts_' . md5($ip);
    $attempts = get_transient($transient_key);
    
    if ($attempts === false) {
        set_transient($transient_key, 1, 15 * MINUTE_IN_SECONDS);
    } else {
        set_transient($transient_key, $attempts + 1, 15 * MINUTE_IN_SECONDS);
    }
}
add_action('wp_login_failed', 'noithat_pro_login_failed');


// ============================================================================
// 10. DEPLOY STABILITY HELPERS
// Cố định danh mục, link category, và rewrite để tránh 404 sau deploy/import
// ============================================================================

/**
 * URL trang cửa hàng, fallback an toàn khi WooCommerce chưa sẵn sàng.
 */
function noithat_pro_get_shop_url() {
    if (function_exists('wc_get_page_permalink')) {
        $shop_url = wc_get_page_permalink('shop');
        if (!empty($shop_url) && '#' !== $shop_url) {
            return $shop_url;
        }
    }

    return home_url('/shop/');
}

/**
 * Trả về URL danh mục sản phẩm theo slug, fallback về trang shop nếu không tồn tại.
 */
function noithat_pro_get_product_category_url($slug) {
    if (!taxonomy_exists('product_cat')) {
        return noithat_pro_get_shop_url();
    }

    $term = get_term_by('slug', sanitize_title($slug), 'product_cat');
    if ($term && !is_wp_error($term)) {
        $link = get_term_link($term);
        if (!is_wp_error($link)) {
            return $link;
        }
    }

    return noithat_pro_get_shop_url();
}

/**
 * Tạo sẵn các slug danh mục thường dùng để menu không bị 404.
 */
function noithat_pro_seed_product_categories() {
    if (!taxonomy_exists('product_cat')) {
        return;
    }

    $seed_version = '1.0.0';
    if (get_option('np_seed_product_categories_version') === $seed_version) {
        return;
    }

    $default_categories = array(
        'sofa'   => 'Sofa',
        'ban'    => 'Ban',
        'ghe'    => 'Ghe',
        'tu-ke'  => 'Tu & Ke',
        'giuong' => 'Giuong & Nem',
    );

    foreach ($default_categories as $slug => $name) {
        if (!get_term_by('slug', $slug, 'product_cat')) {
            wp_insert_term($name, 'product_cat', array('slug' => $slug));
        }
    }

    update_option('np_seed_product_categories_version', $seed_version);
}
add_action('init', 'noithat_pro_seed_product_categories', 25);

/**
 * Redirect tự động các URL category phổ biến khi gặp 404 do lệch slug.
 */
function noithat_pro_redirect_legacy_product_category_404() {
    if (!is_404()) {
        return;
    }

    if (empty($_SERVER['REQUEST_URI'])) {
        return;
    }

    $request_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    if (0 !== strpos($request_path, 'product-category/')) {
        return;
    }

    $broken_slug = trim(substr($request_path, strlen('product-category/')), '/');
    if (empty($broken_slug)) {
        return;
    }

    $slug_map = array(
        'sofa'   => array('sofa', 'sofa-ghe-sofa'),
        'ban'    => array('ban', 'ban-an-ban-lam-viec'),
        'ghe'    => array('ghe', 'ghe-an-ghe-van-phong'),
        'tu-ke'  => array('tu-ke', 'tu-ke-trang-tri'),
        'giuong' => array('giuong', 'giuong-nem'),
    );

    if (!isset($slug_map[$broken_slug])) {
        return;
    }

    foreach ($slug_map[$broken_slug] as $candidate_slug) {
        $term = get_term_by('slug', $candidate_slug, 'product_cat');
        if ($term && !is_wp_error($term)) {
            $target = get_term_link($term);
            if (!is_wp_error($target)) {
                wp_safe_redirect($target, 301);
                exit;
            }
        }
    }
}
add_action('template_redirect', 'noithat_pro_redirect_legacy_product_category_404');


// ============================================================================
// 11. CONTACT FORM RELIABILITY
// Tự động tìm form CF7 hợp lệ và fallback gửi email thật khi không có CF7
// ============================================================================

/**
 * Lấy shortcode Contact Form 7 hợp lệ (form đầu tiên), tránh hard-code ID.
 */
function noithat_pro_get_cf7_shortcode() {
    if (!shortcode_exists('contact-form-7')) {
        return '';
    }

    $forms = get_posts(array(
        'post_type'      => 'wpcf7_contact_form',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'fields'         => 'ids',
    ));

    if (empty($forms)) {
        return '';
    }

    return '[contact-form-7 id="' . intval($forms[0]) . '"]';
}

/**
 * Xử lý form liên hệ fallback (không dùng CF7).
 */
function noithat_pro_handle_contact_submit() {
    if (
        !isset($_POST['np_contact_nonce']) ||
        !wp_verify_nonce($_POST['np_contact_nonce'], 'np_contact_submit')
    ) {
        wp_die(__('Yeu cau khong hop le.', 'noithat-pro'));
    }

    $name    = sanitize_text_field($_POST['name'] ?? '');
    $email   = sanitize_email($_POST['email'] ?? '');
    $phone   = sanitize_text_field($_POST['phone'] ?? '');
    $subject = sanitize_text_field($_POST['subject'] ?? 'Lien he tu website NoiThat Pro');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    $referer = wp_get_referer() ?: home_url('/lien-he/');

    if (empty($name) || empty($email) || empty($message) || !is_email($email)) {
        wp_safe_redirect(add_query_arg('np_contact', 'invalid', $referer));
        exit;
    }

    $to = get_option('admin_email');
    $mail_subject = '[NoiThat Pro] ' . $subject;
    $mail_body = "Ho ten: {$name}\n"
        . "Email: {$email}\n"
        . "Dien thoai: {$phone}\n\n"
        . "Noi dung:\n{$message}";
    $headers = array('Reply-To: ' . $name . ' <' . $email . '>');

    $sent = wp_mail($to, $mail_subject, $mail_body, $headers);

    wp_safe_redirect(add_query_arg('np_contact', $sent ? 'success' : 'error', $referer));
    exit;
}
add_action('admin_post_np_contact_submit', 'noithat_pro_handle_contact_submit');
add_action('admin_post_nopriv_np_contact_submit', 'noithat_pro_handle_contact_submit');


// ============================================================================
// 12. AUTH + ROLE PERMISSIONS
// Phân quyền admin/user, login redirect, logout và hạn chế wp-admin cho user
// ============================================================================

/**
 * URL trang tai khoan: uu tien WooCommerce My Account, fallback wp-login.
 */
function noithat_pro_get_account_page_url() {
    $custom_account_page = get_page_by_path('tai-khoan');
    if ($custom_account_page instanceof WP_Post) {
        return get_permalink($custom_account_page);
    }

    if (function_exists('wc_get_page_permalink')) {
        $account_url = wc_get_page_permalink('myaccount');
        if (!empty($account_url) && '#' !== $account_url) {
            return $account_url;
        }
    }

    return wp_login_url();
}

/**
 * Redirect theo role sau khi login.
 */
function noithat_pro_login_redirect($redirect_to, $requested_redirect_to, $user) {
    if (!$user instanceof WP_User) {
        return $redirect_to;
    }

    if (in_array('administrator', (array) $user->roles, true)) {
        return admin_url();
    }

    return noithat_pro_get_account_page_url();
}
add_filter('login_redirect', 'noithat_pro_login_redirect', 10, 3);

/**
 * Chan user khong phai admin truy cap wp-admin (tru AJAX).
 */
function noithat_pro_restrict_wp_admin_for_non_admin() {
    if (!is_user_logged_in() || current_user_can('manage_options') || wp_doing_ajax()) {
        return;
    }

    $pagenow = $GLOBALS['pagenow'] ?? '';
    if (in_array($pagenow, array('profile.php', 'admin-ajax.php', 'async-upload.php'), true)) {
        return;
    }

    wp_safe_redirect(home_url('/'));
    exit;
}
add_action('admin_init', 'noithat_pro_restrict_wp_admin_for_non_admin');

/**
 * An admin bar voi role user de giao dien gon hon.
 */
function noithat_pro_hide_admin_bar_for_users($show_admin_bar) {
    if (current_user_can('manage_options')) {
        return $show_admin_bar;
    }

    return false;
}
add_filter('show_admin_bar', 'noithat_pro_hide_admin_bar_for_users');
