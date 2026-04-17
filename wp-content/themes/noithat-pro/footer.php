<?php
/**
 * Footer Template - NoiThat Pro
 * 
 * Template hiển thị phần footer của website
 * Bao gồm: Thông tin công ty, liên kết, liên hệ, mạng xã hội
 * 
 * @package NoiThat_Pro
 */

// Lấy thông tin từ cài đặt Admin
$phone   = get_option('np_phone', '0987 654 321');
$email   = get_option('np_email', 'info@noithatpro.vn');
$address = get_option('np_address', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh');
$facebook = get_option('np_facebook', '#');
?>

</main><!-- #np-main-content -->

<!-- ===== FOOTER START ===== -->
<footer class="np-footer" id="np-footer">
    <div class="np-container">
        <div class="np-footer-grid">
            
            <!-- Cột 1: Thông tin thương hiệu -->
            <div class="np-footer-col">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="np-logo" style="margin-bottom:1.5rem;display:inline-flex;">
                    <div class="np-logo-icon">
                        <i class="fas fa-couch"></i>
                    </div>
                    <div class="np-logo-text">
                        Nội Thất <span>Pro</span>
                    </div>
                </a>
                <p class="np-footer-brand-desc">
                    Chuyên cung cấp nội thất cao cấp, hiện đại cho mọi không gian sống. 
                    Với hơn 10 năm kinh nghiệm, chúng tôi cam kết mang đến sản phẩm 
                    chất lượng và dịch vụ tốt nhất.
                </p>
                <div class="np-footer-social">
                    <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
            
            <!-- Cột 2: Danh mục sản phẩm -->
            <div class="np-footer-col">
                <h4 class="np-footer-title">Danh Mục</h4>
                <ul class="np-footer-links">
                    <li><a href="<?php echo esc_url(home_url('/product-category/sofa')); ?>">Sofa & Ghế Sofa</a></li>
                    <li><a href="<?php echo esc_url(home_url('/product-category/ban')); ?>">Bàn Ăn & Bàn Làm Việc</a></li>
                    <li><a href="<?php echo esc_url(home_url('/product-category/ghe')); ?>">Ghế Ăn & Ghế Văn Phòng</a></li>
                    <li><a href="<?php echo esc_url(home_url('/product-category/tu-ke')); ?>">Tủ & Kệ Trang Trí</a></li>
                    <li><a href="<?php echo esc_url(home_url('/product-category/giuong')); ?>">Giường & Nệm</a></li>
                </ul>
            </div>
            
            <!-- Cột 3: Hỗ trợ khách hàng -->
            <div class="np-footer-col">
                <h4 class="np-footer-title">Hỗ Trợ</h4>
                <ul class="np-footer-links">
                    <li><a href="<?php echo esc_url(home_url('/gioi-thieu')); ?>">Về chúng tôi</a></li>
                    <li><a href="<?php echo esc_url(home_url('/lien-he')); ?>">Liên hệ</a></li>
                    <li><a href="#">Chính sách giao hàng</a></li>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Hướng dẫn mua hàng</a></li>
                    <li><a href="#">Câu hỏi thường gặp</a></li>
                </ul>
            </div>
            
            <!-- Cột 4: Thông tin liên hệ -->
            <div class="np-footer-col">
                <h4 class="np-footer-title">Liên Hệ</h4>
                <div class="np-footer-contact-item">
                    <span class="np-footer-contact-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <span><?php echo esc_html($address); ?></span>
                </div>
                <div class="np-footer-contact-item">
                    <span class="np-footer-contact-icon"><i class="fas fa-phone-alt"></i></span>
                    <span><a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" style="color:inherit;"><?php echo esc_html($phone); ?></a></span>
                </div>
                <div class="np-footer-contact-item">
                    <span class="np-footer-contact-icon"><i class="fas fa-envelope"></i></span>
                    <span><a href="mailto:<?php echo esc_attr($email); ?>" style="color:inherit;"><?php echo esc_html($email); ?></a></span>
                </div>
                <div class="np-footer-contact-item">
                    <span class="np-footer-contact-icon"><i class="fas fa-clock"></i></span>
                    <span>T2 - CN: 8:00 - 21:00</span>
                </div>
            </div>
            
        </div>
        
        <!-- Footer Bottom -->
        <div class="np-footer-bottom">
            <p class="np-footer-copyright">
                &copy; <?php echo date('Y'); ?> <strong>NoiThat Pro</strong>. 
                Bài tập môn học - SV CNTT. All rights reserved.
            </p>
            <div style="display:flex;gap:20px;font-size:0.85rem;">
                <a href="#" style="color:rgba(255,255,255,0.5);">Điều khoản sử dụng</a>
                <a href="#" style="color:rgba(255,255,255,0.5);">Chính sách bảo mật</a>
            </div>
        </div>
    </div>
</footer>
<!-- ===== FOOTER END ===== -->

<!-- Back to Top Button -->
<button class="np-back-to-top" id="np-back-to-top" aria-label="Lên đầu trang">
    <i class="fas fa-chevron-up"></i>
</button>

<?php wp_footer(); ?>
</body>
</html>
