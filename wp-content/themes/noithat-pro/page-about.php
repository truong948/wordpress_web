<?php
/**
 * Template Name: Trang Giới Thiệu
 * 
 * Template cho trang Giới thiệu (About Us)
 * 
 * @package NoiThat_Pro
 */

get_header();
?>

<!-- Page Header -->
<section class="np-page-header">
    <div class="np-container">
        <span class="np-section-subtitle">Về Chúng Tôi</span>
        <h1>Câu Chuyện NoiThat Pro</h1>
        <p>Hơn 15 năm đồng hành cùng hàng ngàn gia đình Việt kiến tạo không gian sống hoàn hảo</p>
    </div>
</section>

<!-- About Content -->
<section class="np-section">
    <div class="np-container">
        <div class="np-about-grid">
            <!-- Bên trái: Nội dung -->
            <div class="np-animate">
                <span class="np-section-subtitle">Sứ Mệnh</span>
                <h2 style="font-family:var(--np-font-heading);font-size:2rem;margin-bottom:1.5rem;">
                    Mang Đến Giải Pháp Nội Thất <span style="color:var(--np-primary);">Toàn Diện</span>
                </h2>
                <p style="color:var(--np-text-secondary);line-height:1.8;margin-bottom:1.5rem;">
                    NoiThat Pro được thành lập năm 2009 với sứ mệnh mang đến những sản phẩm 
                    nội thất chất lượng cao, thiết kế hiện đại với mức giá phải chăng. 
                    Chúng tôi tin rằng mỗi ngôi nhà đều xứng đáng có được không gian sống 
                    đẹp và tiện nghi.
                </p>
                <p style="color:var(--np-text-secondary);line-height:1.8;margin-bottom:2rem;">
                    Với đội ngũ thiết kế giàu kinh nghiệm và xưởng sản xuất hiện đại, 
                    chúng tôi tự hào là đối tác tin cậy của hơn 10.000 gia đình và 
                    500+ dự án nội thất trên toàn quốc.
                </p>
                
                <div class="np-about-stats">
                    <div>
                        <div class="np-about-stat-number">15+</div>
                        <div class="np-about-stat-label">Năm Kinh Nghiệm</div>
                    </div>
                    <div>
                        <div class="np-about-stat-number">10K+</div>
                        <div class="np-about-stat-label">Khách Hàng</div>
                    </div>
                    <div>
                        <div class="np-about-stat-number">500+</div>
                        <div class="np-about-stat-label">Sản Phẩm</div>
                    </div>
                </div>
            </div>
            
            <!-- Bên phải: Hình ảnh -->
            <div class="np-animate np-about-image-wrap">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/about-showroom.jpg" 
                     alt="Showroom NoiThat Pro">
            </div>
        </div>
    </div>
</section>

<!-- Tầm nhìn & Sứ mệnh -->
<section class="np-section" style="background:var(--np-bg-section);">
    <div class="np-container">
        <div class="np-section-header">
            <span class="np-section-subtitle">Triết Lý Kinh Doanh</span>
            <h2 class="np-section-title">Tầm Nhìn & Sứ Mệnh</h2>
        </div>
        <div class="np-about-grid">
            <div class="np-animate" style="background:white;padding:2.5rem;border-radius:var(--np-radius-md);border:1px solid var(--np-border-light);">
                <div style="width:56px;height:56px;background:linear-gradient(135deg,var(--np-primary),var(--np-accent));border-radius:var(--np-radius-sm);display:flex;align-items:center;justify-content:center;color:white;font-size:1.4rem;margin-bottom:1.5rem;">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 style="font-family:var(--np-font-heading);font-size:1.3rem;margin-bottom:1rem;">Tầm Nhìn</h3>
                <p style="color:var(--np-text-secondary);line-height:1.8;">
                    Trở thành thương hiệu nội thất hàng đầu Việt Nam, được tin yêu bởi 
                    hàng triệu gia đình. Mang đến giải pháp nội thất toàn diện, từ thiết kế 
                    đến thi công, đáp ứng mọi nhu cầu và phong cách sống.
                </p>
            </div>
            <div class="np-animate" style="background:white;padding:2.5rem;border-radius:var(--np-radius-md);border:1px solid var(--np-border-light);">
                <div style="width:56px;height:56px;background:linear-gradient(135deg,var(--np-primary),var(--np-accent));border-radius:var(--np-radius-sm);display:flex;align-items:center;justify-content:center;color:white;font-size:1.4rem;margin-bottom:1.5rem;">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 style="font-family:var(--np-font-heading);font-size:1.3rem;margin-bottom:1rem;">Sứ Mệnh</h3>
                <p style="color:var(--np-text-secondary);line-height:1.8;">
                    Kiến tạo không gian sống hoàn hảo cho mọi gia đình Việt. Cam kết mang lại 
                    sản phẩm chất lượng cao, thiết kế sáng tạo với chi phí hợp lý, cùng dịch vụ 
                    chăm sóc khách hàng tận tâm.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Giá trị cốt lõi -->
<section class="np-section np-features">
    <div class="np-container">
        <div class="np-section-header">
            <span class="np-section-subtitle" style="color:var(--np-accent);">Giá Trị Cốt Lõi</span>
            <h2 class="np-section-title">Cam Kết Của Chúng Tôi</h2>
        </div>
        <div class="np-features-grid">
            <div class="np-feature-card np-animate">
                <div class="np-feature-icon"><i class="fas fa-award"></i></div>
                <h3 class="np-feature-title">Chất Lượng Hàng Đầu</h3>
                <p class="np-feature-desc">Nguyên liệu nhập khẩu, quy trình sản xuất nghiêm ngặt theo tiêu chuẩn quốc tế.</p>
            </div>
            <div class="np-feature-card np-animate">
                <div class="np-feature-icon"><i class="fas fa-palette"></i></div>
                <h3 class="np-feature-title">Thiết Kế Sáng Tạo</h3>
                <p class="np-feature-desc">Đội ngũ thiết kế giàu kinh nghiệm, luôn cập nhật xu hướng nội thất mới nhất.</p>
            </div>
            <div class="np-feature-card np-animate">
                <div class="np-feature-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <h3 class="np-feature-title">Phục Vụ Tận Tâm</h3>
                <p class="np-feature-desc">Tư vấn miễn phí, giao hàng nhanh chóng, lắp đặt chuyên nghiệp tận nhà.</p>
            </div>
            <div class="np-feature-card np-animate">
                <div class="np-feature-icon"><i class="fas fa-recycle"></i></div>
                <h3 class="np-feature-title">Thân Thiện Môi Trường</h3>
                <p class="np-feature-desc">Sử dụng nguyên liệu bền vững, giảm thiểu tác động đến môi trường.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<?php echo do_shortcode('[banner_khuyen_mai tieu_de="Bạn Cần Tư Vấn?" mo_ta="Hãy liên hệ ngay để được đội ngũ chuyên gia tư vấn giải pháp nội thất phù hợp nhất!" nut="Liên Hệ Ngay"]'); ?>

<?php get_footer(); ?>
