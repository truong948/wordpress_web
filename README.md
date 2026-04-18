# 🏠 NoiThat Pro - Website Bán Đồ Nội Thất

[![WordPress](https://img.shields.io/badge/WordPress-6.5+-blue?logo=wordpress)](https://wordpress.org)
[![WooCommerce](https://img.shields.io/badge/WooCommerce-8.x-purple?logo=woocommerce)](https://woocommerce.com)
[![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php)](https://php.net)
[![License: GPL v2](https://img.shields.io/badge/License-GPLv2-blue.svg)](https://www.gnu.org/licenses/gpl-2.0)

> Website bán đồ nội thất cao cấp xây dựng bằng WordPress + WooCommerce.
> Bài tập cá nhân - Sinh viên CNTT.

## ✨ Tính Năng

- 🛒 **Thương mại điện tử** đầy đủ (WooCommerce)
- 🎨 **Giao diện đẹp**, hiện đại, responsive
- 📱 **Tương thích mobile** hoàn toàn
- 🔍 **Tối ưu SEO** (Yoast SEO)  
- 🔒 **Bảo mật** (Wordfence + custom security)
- ⚡ **Tối ưu hiệu suất** (lazy loading, cleanup)
- 📧 **Form liên hệ** (Contact Form 7)
- 🧩 **Custom Shortcodes** tiếng Việt
- 🛍️ **30 sản phẩm mẫu** có sẵn (CSV import)
- ⚙️ **Admin Panel** tùy chỉnh

## 🛠️ Tech Stack

| Thành phần | Công nghệ |
|-----------|-----------|
| CMS | WordPress 6.5+ |
| E-commerce | WooCommerce 8.x |
| Parent Theme | Astra (Free) |
| Child Theme | NoiThat Pro (Custom) |
| Languages | PHP 8.0, HTML5, CSS3, JS |
| Database | MySQL 8.0 / MariaDB |
| Hosting | InfinityFree (Free) |

## 📁 Cấu Trúc

```
├── wp-content/themes/noithat-pro/    # Child Theme
│   ├── style.css                      # CSS chính
│   ├── functions.php                  # PHP functions
│   ├── header.php / footer.php        # Header & Footer
│   ├── front-page.php                 # Trang chủ
│   ├── page-about.php                 # Giới thiệu
│   ├── page-contact.php               # Liên hệ
│   └── assets/                        # CSS, JS, Images
├── wp-content/plugins/noithat-features/  # Custom Plugin
├── database/products-import.csv       # 30 SP mẫu (có URL ảnh)
├── database/products-import.host-safe.csv  # CSV cho host free (không tải ảnh ngoài)
├── scripts/check-products-csv-images.js    # Kiểm tra link ảnh localhost/private
├── scripts/make-host-safe-products-csv.js  # Tạo CSV host-safe
└── docs/DEPLOY_GUIDE.md              # Hướng dẫn deploy
```

## 🚀 Cài Đặt

1. Cài WordPress trên hosting
2. Cài theme Astra (parent)
3. Upload & activate child theme `noithat-pro`
4. Cài plugins: WooCommerce, Yoast SEO, Wordfence, CF7
5. Upload & activate plugin `noithat-features`
6. Trước khi deploy: chạy `npm run csv:check-images`, `npm run csv:host-safe`, `npm run csv:export-image-map`
7. Import sản phẩm từ `products-import.host-safe.csv` trên host free
8. Theme sẽ tự fallback ảnh theo SKU từ file map nếu host không tải được ảnh vào Media
9. Tạo trang & menu

> Chi tiết xem file `docs/DEPLOY_GUIDE.md`

## 👨‍💻 Tác Giả

- **Sinh viên CNTT** - Bài tập môn học
- Website: NoiThat Pro

## 📄 License

GPL v2.0 - xem file [LICENSE](LICENSE)
