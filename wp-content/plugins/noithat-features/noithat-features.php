<?php
/**
 * Plugin Name: NoiThat Pro Features
 * Plugin URI:  https://noithat-pro.example.com
 * Description: Plugin bổ sung tính năng cho website NoiThat Pro: Widget sản phẩm, 
 *              Quick View, và các tiện ích khác.
 * Version:     1.0.0
 * Author:      SV CNTT
 * Text Domain: noithat-features
 * 
 * === GIẢI THÍCH ===
 * Plugin này thêm các tính năng:
 * 1. Widget hiển thị sản phẩm bán chạy ở sidebar
 * 2. Shortcode hiển thị đánh giá khách hàng (testimonials)
 * 3. REST API endpoint để lấy danh sách sản phẩm (cho AJAX)
 * 4. Custom post type cho Testimonials (đánh giá)
 */

if (!defined('ABSPATH')) {
    exit; // Ngăn truy cập trực tiếp
}

// ============================================================================
// 1. WIDGET: SẢN PHẨM BÁN CHẠY
// Hiển thị sản phẩm bán chạy nhất ở sidebar
// ============================================================================

class NoiThat_Bestseller_Widget extends WP_Widget {
    
    /**
     * Constructor - Khởi tạo widget
     */
    public function __construct() {
        parent::__construct(
            'noithat_bestseller',              // Widget ID
            'NP - Sản Phẩm Bán Chạy',         // Widget name
            array(
                'description' => 'Hiển thị sản phẩm bán chạy nhất',
                'classname'   => 'np-bestseller-widget',
            )
        );
    }
    
    /**
     * Hiển thị widget ở frontend
     * 
     * @param array $args     Widget arguments (before_widget, after_widget, etc.)
     * @param array $instance Widget settings (title, count)
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Bán Chạy Nhất';
        $count = !empty($instance['count']) ? intval($instance['count']) : 5;
        
        // Query sản phẩm sắp xếp theo lượt bán
        $products = new WP_Query(array(
            'post_type'      => 'product',
            'posts_per_page' => $count,
            'meta_key'       => 'total_sales',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ));
        
        echo $args['before_widget'];
        echo $args['before_title'] . esc_html($title) . $args['after_title'];
        
        if ($products->have_posts()) :
            echo '<div class="np-bestseller-list">';
            while ($products->have_posts()) : $products->the_post();
                global $product;
                ?>
                <div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid var(--np-border-light,#f0f0f0);">
                    <a href="<?php the_permalink(); ?>" style="width:60px;height:60px;flex-shrink:0;border-radius:8px;overflow:hidden;">
                        <?php
                        if (function_exists('noithat_pro_get_product_image_html')) {
                            echo noithat_pro_get_product_image_html(
                                get_the_ID(),
                                'thumbnail',
                                array('style' => 'width:100%;height:100%;object-fit:cover;')
                            );
                        } elseif (has_post_thumbnail()) {
                            the_post_thumbnail('thumbnail', array('style' => 'width:100%;height:100%;object-fit:cover;'));
                        } elseif (function_exists('wc_placeholder_img')) {
                            echo wc_placeholder_img('thumbnail', array('style' => 'width:100%;height:100%;object-fit:cover;'));
                        }
                        ?>
                    </a>
                    <div style="flex:1;min-width:0;">
                        <a href="<?php the_permalink(); ?>" style="font-size:0.9rem;font-weight:600;color:inherit;display:block;margin-bottom:4px;">
                            <?php the_title(); ?>
                        </a>
                        <span style="color:#8B6914;font-weight:700;font-size:0.85rem;">
                            <?php echo $product->get_price_html(); ?>
                        </span>
                    </div>
                </div>
                <?php
            endwhile;
            echo '</div>';
        endif;
        wp_reset_postdata();
        
        echo $args['after_widget'];
    }
    
    /**
     * Form cài đặt widget trong Admin
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Bán Chạy Nhất';
        $count = !empty($instance['count']) ? intval($instance['count']) : 5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Tiêu đề:</label>
            <input class="widefat" type="text" 
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>">Số sản phẩm:</label>
            <input class="tiny-text" type="number" min="1" max="20"
                   id="<?php echo $this->get_field_id('count'); ?>"
                   name="<?php echo $this->get_field_name('count'); ?>"
                   value="<?php echo esc_attr($count); ?>">
        </p>
        <?php
    }
    
    /**
     * Lưu cài đặt widget
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['count'] = intval($new_instance['count'] ?? 5);
        return $instance;
    }
}

// Đăng ký widget
function noithat_register_widgets() {
    register_widget('NoiThat_Bestseller_Widget');
}
add_action('widgets_init', 'noithat_register_widgets');


// ============================================================================
// 2. CUSTOM POST TYPE: ĐÁNH GIÁ KHÁCH HÀNG
// Cho phép quản lý đánh giá khách hàng từ Admin
// ============================================================================

function noithat_register_testimonials_cpt() {
    $labels = array(
        'name'               => 'Đánh giá KH',
        'singular_name'      => 'Đánh giá',
        'add_new'            => 'Thêm đánh giá',
        'add_new_item'       => 'Thêm đánh giá mới',
        'edit_item'          => 'Sửa đánh giá',
        'view_item'          => 'Xem đánh giá',
        'all_items'          => 'Tất cả đánh giá',
        'search_items'       => 'Tìm đánh giá',
        'not_found'          => 'Không tìm thấy',
    );
    
    register_post_type('np_testimonial', array(
        'labels'       => $labels,
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-star-filled',
        'supports'     => array('title', 'editor', 'thumbnail'),
        'menu_position' => 25,
    ));
    
    // Meta box cho thông tin bổ sung
    add_action('add_meta_boxes', function() {
        add_meta_box(
            'np_testimonial_meta',
            'Thông tin khách hàng',
            'noithat_testimonial_meta_box',
            'np_testimonial',
            'side'
        );
    });
}
add_action('init', 'noithat_register_testimonials_cpt');

/**
 * Meta box cho testimonial
 */
function noithat_testimonial_meta_box($post) {
    $role = get_post_meta($post->ID, '_np_customer_role', true);
    $rating = get_post_meta($post->ID, '_np_rating', true) ?: 5;
    wp_nonce_field('np_testimonial_meta', 'np_testimonial_nonce');
    ?>
    <p>
        <label>Vai trò/Vị trí:</label><br>
        <input type="text" name="np_customer_role" value="<?php echo esc_attr($role); ?>" 
               style="width:100%;" placeholder="VD: Khách hàng tại TP.HCM">
    </p>
    <p>
        <label>Đánh giá (1-5 sao):</label><br>
        <select name="np_rating" style="width:100%;">
            <?php for ($i = 5; $i >= 1; $i--) : ?>
            <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>><?php echo $i; ?> sao</option>
            <?php endfor; ?>
        </select>
    </p>
    <?php
}

/**
 * Lưu meta data cho testimonial
 */
function noithat_save_testimonial_meta($post_id) {
    if (!isset($_POST['np_testimonial_nonce']) || 
        !wp_verify_nonce($_POST['np_testimonial_nonce'], 'np_testimonial_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    
    update_post_meta($post_id, '_np_customer_role', 
        sanitize_text_field($_POST['np_customer_role'] ?? ''));
    update_post_meta($post_id, '_np_rating', 
        intval($_POST['np_rating'] ?? 5));
}
add_action('save_post_np_testimonial', 'noithat_save_testimonial_meta');


// ============================================================================
// 3. SHORTCODE: HIỂN THỊ ĐÁNH GIÁ TỪ CPT
// [danh_gia_khach_hang so_luong="3"]
// ============================================================================

function noithat_testimonials_shortcode($atts) {
    $atts = shortcode_atts(array('so_luong' => 3), $atts);
    
    $testimonials = new WP_Query(array(
        'post_type'      => 'np_testimonial',
        'posts_per_page' => intval($atts['so_luong']),
        'orderby'        => 'rand',
        'post_status'    => 'publish',
    ));
    
    ob_start();
    if ($testimonials->have_posts()) :
    ?>
    <div class="np-testimonial-grid">
        <?php while ($testimonials->have_posts()) : $testimonials->the_post();
            $role = get_post_meta(get_the_ID(), '_np_customer_role', true);
            $rating = get_post_meta(get_the_ID(), '_np_rating', true) ?: 5;
            $initials = mb_strtoupper(mb_substr(get_the_title(), 0, 1));
        ?>
        <div class="np-testimonial-card np-animate">
            <div class="np-testimonial-stars">
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <i class="fas fa-star<?php echo $i > $rating ? '-o' : ''; ?>" 
                       style="<?php echo $i > $rating ? 'opacity:0.3;' : ''; ?>"></i>
                <?php endfor; ?>
            </div>
            <p class="np-testimonial-text">"<?php echo wp_strip_all_tags(get_the_content()); ?>"</p>
            <div class="np-testimonial-author">
                <div class="np-testimonial-avatar" style="background:linear-gradient(135deg,#8B6914,#D4A843);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.1rem;">
                    <?php echo esc_html($initials); ?>
                </div>
                <div>
                    <div class="np-testimonial-name"><?php the_title(); ?></div>
                    <div class="np-testimonial-role"><?php echo esc_html($role); ?></div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php
    endif;
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('danh_gia_khach_hang', 'noithat_testimonials_shortcode');


// ============================================================================
// 4. WOOCOMMERCE: THÊM NHÃN "MỚI" CHO SẢN PHẨM < 30 NGÀY
// ============================================================================

function noithat_new_product_badge() {
    global $post;
    $post_date = get_the_date('U', $post->ID);
    $thirty_days_ago = strtotime('-30 days');
    
    if ($post_date > $thirty_days_ago) {
        echo '<span class="np-product-badge np-badge-new" style="position:absolute;top:12px;right:12px;z-index:2;">Mới</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'noithat_new_product_badge', 5);
