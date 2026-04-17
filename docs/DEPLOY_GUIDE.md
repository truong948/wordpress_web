# NoiThat Pro - Hướng Dẫn Deploy Lên InfinityFree

## Bước 1: Chuẩn Bị

### 1.1 Đăng ký hosting
- Vào https://www.infinityfree.com → Sign Up
- Tạo hosting account mới
- Chọn subdomain: `noithatpro.epizy.com`

### 1.2 Cài WordPress
- Trong Control Panel → Softaculous → WordPress → Install
- Điền thông tin site

## Bước 2: Upload Theme

### Cách 1: Qua WordPress Admin
1. Nén thư mục `wp-content/themes/noithat-pro/` thành `noithat-pro.zip`
2. WordPress Admin → Appearance → Themes → Add New → Upload Theme
3. Chọn file zip → Install → Activate

### Cách 2: Qua FTP (FileZilla)
1. Tải FileZilla: https://filezilla-project.org
2. Kết nối FTP:
   - Host: `ftpupload.net`
   - User: (từ hosting control panel)
   - Password: (từ hosting control panel)
   - Port: 21
3. Upload thư mục `noithat-pro/` vào `/htdocs/wp-content/themes/`

## Bước 3: Upload Plugin
1. Nén `wp-content/plugins/noithat-features/` thành `noithat-features.zip`
2. WordPress Admin → Plugins → Add New → Upload Plugin
3. Install → Activate

## Bước 4: Cài Plugins Bắt Buộc
1. WooCommerce → Install → Activate → Chạy Setup Wizard
2. Yoast SEO → Install → Activate
3. Wordfence Security → Install → Activate
4. Contact Form 7 → Install → Activate

## Bước 5: Import Sản Phẩm
1. WordPress Admin → Products → All Products → Import
2. Chọn file `database/products-import.csv`
3. Map columns → Run Importer
4. Thêm ảnh cho từng sản phẩm (lấy từ Unsplash/Pexels)

## Bước 6: Tạo Các Trang
1. Pages → Add New:
   - "Trang Chủ" (để trống nội dung, theme tự render)
   - "Giới Thiệu" → Template: "Trang Giới Thiệu"
   - "Liên Hệ" → Template: "Trang Liên Hệ"
2. Settings → Reading → Static page → Homepage: "Trang Chủ"

## Bước 7: Tạo Menu
1. Appearance → Menus
2. Tạo menu "Menu Chính"
3. Thêm: Trang Chủ, Cửa Hàng, Sofa, Bàn, Ghế, Giới Thiệu, Liên Hệ
4. Location: "Menu Chính" → Save

## Bước 8: Cấu Hình
1. Menu NoiThat Pro → nhập SĐT, Email, Địa chỉ → Lưu
2. WooCommerce → Settings → Currency: VNĐ

## Lưu Ý Quan Trọng
- Hosting miễn phí có giới hạn bandwidth, phù hợp cho demo/bài tập
- Upload ảnh nên resize trước (800x800px) để trang tải nhanh
- Nhớ cài SSL (Let's Encrypt) nếu hosting hỗ trợ
- Backup website định kỳ
