<?php
/**
 * Front Page Template - NoiThat Pro
 * 
 * Template cho trang chủ website
 * Bao gồm: Hero Banner, Danh mục, Sản phẩm nổi bật, Features, Đánh giá, CTA
 * 
 * @package NoiThat_Pro
 */

get_header();
?>

<!-- ===== HERO BANNER ===== -->
<section class="np-hero" id="np-hero">
    <div class="np-hero-bg" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hero-bg.jpg');"></div>
    <div class="np-hero-overlay"></div>
    
    <div class="np-hero-content">
        <div class="np-hero-text">
            <div class="np-hero-badge">
                <i class="fas fa-star"></i> Bộ Sưu Tập Mới 2024
            </div>
            
            <h1>Nâng Tầm <span>Không Gian Sống</span> Của Bạn</h1>
            
            <p class="np-hero-desc">
                Khám phá bộ sưu tập nội thất cao cấp được thiết kế tinh tế, 
                kết hợp hoàn hảo giữa thẩm mỹ và công năng cho ngôi nhà hiện đại.
            </p>
            
            <div class="np-hero-buttons">
                <a href="<?php echo esc_url(home_url('/shop')); ?>" class="np-btn np-btn-primary">
                    Khám Phá Ngay <i class="fas fa-arrow-right"></i>
                </a>
                <a href="<?php echo esc_url(home_url('/gioi-thieu')); ?>" class="np-btn np-btn-outline">
                    <i class="fas fa-play-circle"></i> Về Chúng Tôi
                </a>
            </div>
            
            <div class="np-hero-stats">
                <div class="np-hero-stat">
                    <div class="np-hero-stat-number" data-count="500">0</div>
                    <div class="np-hero-stat-label">Sản Phẩm</div>
                </div>
                <div class="np-hero-stat">
                    <div class="np-hero-stat-number" data-count="10000">0</div>
                    <div class="np-hero-stat-label">Khách Hàng</div>
                </div>
                <div class="np-hero-stat">
                    <div class="np-hero-stat-number" data-count="15">0</div>
                    <div class="np-hero-stat-label">Năm Kinh Nghiệm</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== DANH MỤC SẢN PHẨM ===== -->
<section class="np-section np-categories" id="np-categories">
    <div class="np-container">
        <div class="np-section-header">
            <span class="np-section-subtitle">Bộ Sưu Tập</span>
            <h2 class="np-section-title">Danh Mục Nội Thất</h2>
            <p class="np-section-desc">
                Khám phá đa dạng danh mục sản phẩm nội thất cho mọi không gian
            </p>
        </div>
        
        <?php
        // Hiển thị danh mục sản phẩm bằng shortcode
        echo do_shortcode('[danh_muc_san_pham so_luong="4"]');
        ?>
    </div>
</section>

<!-- ===== SẢN PHẨM NỔI BẬT ===== -->
<section class="np-section" id="np-featured-products">
    <div class="np-container">
        <div class="np-section-header">
            <span class="np-section-subtitle">Bán Chạy Nhất</span>
            <h2 class="np-section-title">Sản Phẩm Nổi Bật</h2>
            <p class="np-section-desc">
                Những sản phẩm được yêu thích nhất bởi khách hàng của chúng tôi
            </p>
        </div>
        
        <?php echo do_shortcode('[san_pham_noi_bat so_luong="4" cot="4"]'); ?>
        
        <div style="text-align:center;margin-top:3rem;">
            <a href="<?php echo esc_url(home_url('/shop')); ?>" class="np-btn np-btn-dark">
                Xem Tất Cả Sản Phẩm <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- ===== TẠI SAO CHỌN CHÚNG TÔI ===== -->
<section class="np-section np-features" id="np-features">
    <div class="np-container">
        <div class="np-section-header">
            <span class="np-section-subtitle" style="color:var(--np-accent);">Cam Kết</span>
            <h2 class="np-section-title">Tại Sao Chọn NoiThat Pro?</h2>
            <p class="np-section-desc">
                Chúng tôi mang đến trải nghiệm mua sắm nội thất tốt nhất
            </p>
        </div>
        
        <div class="np-features-grid">
            <div class="np-feature-card np-animate">
                <div class="np-feature-icon">
                    <i class="fas fa-gem"></i>
                </div>
                <h3 class="np-feature-title">Chất Lượng Premium</h3>
                <p class="np-feature-desc">
                    Sản phẩm được chọn lọc từ các thương hiệu uy tín, 
                    đảm bảo chất lượng và độ bền cao.
                </p>
            </div>
            
            <div class="np-feature-card np-animate">
                <div class="np-feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3 class="np-feature-title">Giao Hàng Miễn Phí</h3>
                <p class="np-feature-desc">
                    Miễn phí giao hàng và lắp đặt tận nhà 
                    cho tất cả đơn hàng trên 5 triệu đồng.
                </p>
            </div>
            
            <div class="np-feature-card np-animate">
                <div class="np-feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="np-feature-title">Bảo Hành 12 Tháng</h3>
                <p class="np-feature-desc">
                    Cam kết bảo hành chính hãng 12 tháng 
                    và hỗ trợ đổi trả trong 30 ngày đầu.
                </p>
            </div>
            
            <div class="np-feature-card np-animate">
                <div class="np-feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="np-feature-title">Tư Vấn 24/7</h3>
                <p class="np-feature-desc">
                    Đội ngũ tư vấn chuyên nghiệp sẵn sàng 
                    hỗ trợ bạn mọi lúc, mọi nơi.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ===== SẢN PHẨM MỚI ===== -->
<section class="np-section" id="np-new-products">
    <div class="np-container">
        <div class="np-section-header">
            <span class="np-section-subtitle">Hàng Mới Về</span>
            <h2 class="np-section-title">Sản Phẩm Mới Nhất</h2>
            <p class="np-section-desc">
                Cập nhật xu hướng nội thất mới nhất cho không gian sống của bạn
            </p>
        </div>
        
        <?php echo do_shortcode('[san_pham_moi so_luong="8" cot="4"]'); ?>
    </div>
</section>

<!-- ===== BANNER KHUYẾN MÃI ===== -->
<?php echo do_shortcode('[banner_khuyen_mai tieu_de="Ưu Đãi Lên Đến 30%" mo_ta="Áp dụng cho tất cả sản phẩm nội thất phòng khách. Khuyến mãi có hạn!" nut="Mua Ngay"]'); ?>

<!-- ===== ĐÁNH GIÁ KHÁCH HÀNG ===== -->
<section class="np-section np-testimonials" id="np-testimonials">
    <div class="np-container">
        <div class="np-section-header">
            <span class="np-section-subtitle">Khách Hàng Nói Gì</span>
            <h2 class="np-section-title">Đánh Giá Từ Khách Hàng</h2>
            <p class="np-section-desc">
                Hơn 10.000 khách hàng đã tin tưởng và lựa chọn NoiThat Pro
            </p>
        </div>
        
        <div class="np-testimonial-grid">
            <!-- Đánh giá 1 -->
            <div class="np-testimonial-card np-animate">
                <div class="np-testimonial-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="np-testimonial-text">
                    "Bộ sofa mua ở NoiThat Pro thực sự rất đẹp và chất lượng. 
                    Giao hàng nhanh, nhân viên lắp đặt rất chuyên nghiệp. 
                    Cả gia đình tôi đều rất hài lòng!"
                </p>
                <div class="np-testimonial-author">
                    <div class="np-testimonial-avatar" style="background:linear-gradient(135deg,#8B6914,#D4A843);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;">NT</div>
                    <div>
                        <div class="np-testimonial-name">Nguyễn Thanh Tùng</div>
                        <div class="np-testimonial-role">Khách hàng tại TP.HCM</div>
                    </div>
                </div>
            </div>
            
            <!-- Đánh giá 2 -->
            <div class="np-testimonial-card np-animate">
                <div class="np-testimonial-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="np-testimonial-text">
                    "Tôi đã mua bàn ăn và bộ ghế cho căn hộ mới. 
                    Thiết kế hiện đại, gỗ thật, và giá cả rất hợp lý. 
                    Chắc chắn sẽ quay lại mua thêm!"
                </p>
                <div class="np-testimonial-author">
                    <div class="np-testimonial-avatar" style="background:linear-gradient(135deg,#6B5010,#C4A235);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;">TL</div>
                    <div>
                        <div class="np-testimonial-name">Trần Thị Lan</div>
                        <div class="np-testimonial-role">Khách hàng tại Hà Nội</div>
                    </div>
                </div>
            </div>
            
            <!-- Đánh giá 3 -->
            <div class="np-testimonial-card np-animate">
                <div class="np-testimonial-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <p class="np-testimonial-text">
                    "Dịch vụ tư vấn rất tận tâm. Nhân viên giúp tôi chọn 
                    được bộ nội thất phù hợp hoàn hảo với không gian. 
                    Rất đáng tin cậy!"
                </p>
                <div class="np-testimonial-author">
                    <div class="np-testimonial-avatar" style="background:linear-gradient(135deg,#2C2C2C,#4A4A4A);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;">PM</div>
                    <div>
                        <div class="np-testimonial-name">Phạm Minh Đức</div>
                        <div class="np-testimonial-role">Khách hàng tại Đà Nẵng</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
