# 🎓 Hệ Thống Quản Lý Khóa Học (Course Management System)

Dự án Mini Project - Bài kiểm tra thực hành Chương 3. 
Hệ thống cho phép quản lý khóa học, bài học và học viên đăng ký, được xây dựng bằng **Laravel**.

## 🚀 Các chức năng chính
- **Quản lý Khóa học:** Thêm mới, chỉnh sửa, xóa khóa học.
- **Upload Ảnh:** Hỗ trợ tải lên và hiển thị ảnh đại diện cho khóa học.
- **Tìm kiếm & Lọc:** Tìm kiếm theo tên khóa học, lọc theo trạng thái (Bản nháp/Xuất bản) và sắp xếp theo giá.
- **Validation:** Bắt lỗi form nhập liệu chặt chẽ bằng FormRequest.
- **Giao diện:** Tích hợp Bootstrap 5, sử dụng Layout (Master) và Components (Alert, Badge) chuẩn xác.

## 🛠️ Công nghệ sử dụng
- **Framework:** Laravel (PHP)
- **Database:** MySQL
- **Frontend:** HTML, CSS, Bootstrap 5

---

## ⚙️ Hướng dẫn cài đặt và chạy dự án

Do thư mục `vendor` và file `.env` không được đẩy lên Git để bảo mật, vui lòng làm theo các bước sau để chạy dự án trên máy của bạn:

**Bước 1: Cài đặt các thư viện cần thiết**
Mở Terminal tại thư mục dự án và chạy lệnh:
```bash
composer install
Bước 2: Cấu hình môi trường (.env)
Copy file .env.example và đổi tên thành .env:

Bash
cp .env.example .env
Sau đó mở file .env lên, cấu hình thông tin Database của bạn:

Đoạn mã
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_app  # Đổi thành tên database của bạn
DB_USERNAME=root
DB_PASSWORD=
(Lưu ý: Nếu bị lỗi database, hãy chuyển SESSION_DRIVER=database thành SESSION_DRIVER=file trong file .env)

Bước 3: Tạo App Key

Bash
php artisan key:generate
Bước 4: Tạo các bảng trong Database
Hãy chắc chắn bạn đã tạo sẵn một database trống trong MySQL (phpMyAdmin), sau đó chạy lệnh:

Bash
php artisan migrate
Bước 5: Cấp quyền hiển thị ảnh (Storage Link)
Để ảnh upload lên có thể hiển thị được ra ngoài web, chạy lệnh:

Bash
php artisan storage:link
Bước 6: Khởi động Server

Bash
php artisan serve
Mở trình duyệt và truy cập: http://127.0.0.1:8000/courses để xem thành quả!