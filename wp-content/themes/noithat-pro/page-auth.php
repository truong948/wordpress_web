<?php
/**
 * Template Name: Trang Tai Khoan
 *
 * Template frontend cho login/logout va hien thi quyen user/admin.
 *
 * @package NoiThat_Pro
 */

get_header();

$current_user = wp_get_current_user();
$is_logged_in = is_user_logged_in();
$is_admin_user = $is_logged_in && current_user_can('manage_options');
?>

<section class="np-page-header">
    <div class="np-container">
        <span class="np-section-subtitle">Tai Khoan</span>
        <h1><?php echo $is_logged_in ? 'Thong Tin Tai Khoan' : 'Dang Nhap He Thong'; ?></h1>
        <p>
            <?php if ($is_logged_in) : ?>
                Quan ly phien dang nhap va quyen truy cap tai khoan cua ban.
            <?php else : ?>
                Dang nhap de mua hang, theo doi don hang va su dung day du tinh nang website.
            <?php endif; ?>
        </p>
    </div>
</section>

<section class="np-section">
    <div class="np-container" style="max-width:900px;">
        <div class="np-contact-form-wrapper">
            <?php if ($is_logged_in) : ?>
                <h2 style="margin-bottom:1rem;"><i class="fas fa-user-check" style="color:var(--np-primary);margin-right:8px;"></i> Xin chao, <?php echo esc_html($current_user->display_name); ?></h2>
                <p style="margin-bottom:1rem;color:var(--np-text-secondary);">
                    Vai tro hien tai:
                    <strong><?php echo $is_admin_user ? 'Administrator' : esc_html(implode(', ', (array) $current_user->roles)); ?></strong>
                </p>

                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <?php if ($is_admin_user) : ?>
                        <a class="np-btn np-btn-dark" href="<?php echo esc_url(admin_url()); ?>">
                            <i class="fas fa-gauge"></i> Vao Trang Quan Tri
                        </a>
                    <?php else : ?>
                        <a class="np-btn np-btn-dark" href="<?php echo esc_url(home_url('/')); ?>">
                            <i class="fas fa-house"></i> Ve Trang Chu
                        </a>
                    <?php endif; ?>

                    <a class="np-btn np-btn-primary" href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>">
                        <i class="fas fa-right-from-bracket"></i> Dang Xuat
                    </a>
                </div>
            <?php else : ?>
                <h2 style="margin-bottom:1rem;"><i class="fas fa-right-to-bracket" style="color:var(--np-primary);margin-right:8px;"></i> Dang Nhap</h2>

                <?php
                wp_login_form(array(
                    'remember'       => true,
                    'redirect'       => esc_url(home_url('/')),
                    'form_id'        => 'np-loginform',
                    'label_username' => 'Ten dang nhap hoac email',
                    'label_password' => 'Mat khau',
                    'label_remember' => 'Ghi nho dang nhap',
                    'label_log_in'   => 'Dang Nhap',
                ));
                ?>

                <div style="margin-top:1rem;color:var(--np-text-secondary);">
                    <p style="margin:0;">Ban chua co tai khoan?</p>
                    <?php if (get_option('users_can_register')) : ?>
                        <a href="<?php echo esc_url(wp_registration_url()); ?>" style="font-weight:600;">Dang ky tai khoan moi</a>
                    <?php else : ?>
                        <p style="margin:6px 0 0;">Tinh nang dang ky hien dang tat. Vui long lien he quan tri vien.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
