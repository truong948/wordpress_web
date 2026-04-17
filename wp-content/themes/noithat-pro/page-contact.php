<?php
/**
 * Template Name: Trang Liên Hệ
 * 
 * Template cho trang Liên hệ (Contact)
 * 
 * @package NoiThat_Pro
 */

get_header();

$phone   = get_option('np_phone', '0987 654 321');
$email   = get_option('np_email', 'info@noithatpro.vn');
$address = get_option('np_address', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh');
?>

<!-- Page Header -->
<section class="np-page-header">
    <div class="np-container">
        <span class="np-section-subtitle">Hỗ Trợ</span>
        <h1>Liên Hệ Với Chúng Tôi</h1>
        <p>Hãy liên hệ để được tư vấn miễn phí về giải pháp nội thất phù hợp nhất cho bạn</p>
    </div>
</section>

<!-- Contact Content -->
<section class="np-section">
    <div class="np-container">
        <div class="np-contact-grid">
            
            <!-- Bên trái: Form liên hệ -->
            <div class="np-contact-form-wrapper np-animate">
                <h2><i class="fas fa-paper-plane" style="color:var(--np-primary);margin-right:8px;"></i> Gửi Tin Nhắn</h2>
                
                <?php
                // Nếu có Contact Form 7 thì dùng shortcode
                if (shortcode_exists('contact-form-7')) {
                    // ID form tùy theo cài đặt thực tế
                    echo do_shortcode('[contact-form-7 id="1" title="Form Liên Hệ"]');
                } else {
                    // Form HTML fallback
                ?>
                <form method="post" action="#" id="np-contact-form" class="np-contact-form">
                    <div class="np-contact-form-row">
                        <input type="text" name="name" placeholder="Họ và tên *" required
                               id="np-contact-name">
                        <input type="email" name="email" placeholder="Email *" required
                               id="np-contact-email">
                    </div>
                    <input type="tel" name="phone" placeholder="Số điện thoại"
                           id="np-contact-phone">
                    <input type="text" name="subject" placeholder="Tiêu đề"
                           id="np-contact-subject">
                    <textarea name="message" placeholder="Nội dung tin nhắn *" rows="5" required
                              id="np-contact-message"></textarea>
                    <button type="submit" class="np-btn np-btn-primary" 
                            style="margin-top:0.5rem;width:100%;justify-content:center;"
                            id="np-contact-submit">
                        <i class="fas fa-paper-plane"></i> Gửi Tin Nhắn
                    </button>
                </form>
                <?php } ?>
            </div>
            
            <!-- Bên phải: Thông tin liên hệ -->
            <div class="np-animate">
                <h2 style="font-family:var(--np-font-heading);font-size:1.5rem;margin-bottom:1.5rem;">
                    <i class="fas fa-info-circle" style="color:var(--np-primary);margin-right:8px;"></i>
                    Thông Tin Liên Hệ
                </h2>
                
                <div class="np-contact-info-card">
                    <div class="np-contact-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <strong style="display:block;margin-bottom:4px;">Showroom</strong>
                        <span style="color:var(--np-text-secondary);"><?php echo esc_html($address); ?></span>
                    </div>
                </div>
                
                <div class="np-contact-info-card">
                    <div class="np-contact-info-icon"><i class="fas fa-phone-alt"></i></div>
                    <div>
                        <strong style="display:block;margin-bottom:4px;">Hotline</strong>
                        <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" 
                           style="color:var(--np-text-secondary);">
                            <?php echo esc_html($phone); ?>
                        </a>
                    </div>
                </div>
                
                <div class="np-contact-info-card">
                    <div class="np-contact-info-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <strong style="display:block;margin-bottom:4px;">Email</strong>
                        <a href="mailto:<?php echo esc_attr($email); ?>" 
                           style="color:var(--np-text-secondary);">
                            <?php echo esc_html($email); ?>
                        </a>
                    </div>
                </div>
                
                <div class="np-contact-info-card">
                    <div class="np-contact-info-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <strong style="display:block;margin-bottom:4px;">Giờ Làm Việc</strong>
                        <span style="color:var(--np-text-secondary);">
                            Thứ 2 - Chủ Nhật: 8:00 - 21:00
                        </span>
                    </div>
                </div>
                
                <!-- Google Maps Embed -->
                <div style="margin-top:1.5rem;border-radius:var(--np-radius-md);overflow:hidden;border:1px solid var(--np-border-light);box-shadow:var(--np-shadow-sm);">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.447!2d106.701!3d10.776!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTDCsDQ2JzM0LjAiTiAxMDbCsDQyJzAzLjAiRQ!5e0!3m2!1svi!2svn!4v1234567890"
                        width="100%" height="250" style="border:0;display:block;" 
                        allowfullscreen="" loading="lazy"
                        title="Vị trí NoiThat Pro trên bản đồ">
                    </iframe>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php get_footer(); ?>
