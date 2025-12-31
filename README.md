# 🍽️ Website Quản Lý Nhà Hàng (Restaurant Booking System)

Đây là dự án website nhà hàng với đầy đủ các tính năng giới thiệu món ăn, bộ sưu tập ảnh và hệ thống **Đặt bàn & Quản lý trạng thái bàn** theo thời gian thực.

## ✨ Chức năng chính

### 1. Giao diện khách hàng
* **Trang chủ (Home):** Giới thiệu không gian nhà hàng.
* **Thực đơn (Our Menu):** Hiển thị danh sách món ăn.
* **Bộ sưu tập (Gallery):** Hình ảnh thực tế.
* **Địa chỉ (Location):** Bản đồ và liên hệ.

### 2. Hệ thống Đặt bàn (Booking)
* Khách hàng điền thông tin: Tên, SĐT, Ngày/Giờ.
* **Cơ chế chống trùng:** Hệ thống tự động ngăn chặn việc đặt trùng một bàn vào cùng một ngày.

### 3. Hủy bàn (Admin Only)
* Để hủy một bàn đã đặt, cần nhấn vào bàn đó và nhập mật khẩu **1234**.
* Giúp bảo mật, tránh việc khách hàng tự ý hủy bàn của người khác.

---

## 🛠️ Công nghệ sử dụng
* **Frontend:** HTML, CSS, JavaScript.
* **Backend:** PHP.
* **Database:** MySQL.
* **Server:** XAMPP.

---

## 🚀 Hướng dẫn cài đặt và Chạy dự án

### Bước 1: Cài đặt môi trường
1.  Tải và cài đặt **XAMPP**.
2.  Bật module **Apache** và **MySQL**.
3.  Clone dự án vào thư mục `C:/xampp/htdocs/`:
    ```bash
    git clone [https://github.com/hngnam14/Restaurant-](https://github.com/hngnam14/Restaurant-)
    ```

### Bước 2: Cấu hình Database (Quan trọng)
Bạn không cần import file, hãy làm theo các bước sau để khởi tạo dữ liệu:

1.  Truy cập: `http://localhost/phpmyadmin`
2.  Nhấn **New** (cột bên trái).
3.  Tạo Database mới:
    * **Database name:** `restaurant`
    * **Collation:** `utf8mb4_unicode_ci` (để hỗ trợ tiếng Việt)
    * Nhấn **Create**.
4.  Chọn database `restaurant` vừa tạo, bấm sang tab **SQL** ở thanh menu trên cùng.
5.  Copy đoạn code sau dán vào và nhấn **Go** để chạy :

```sql
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_id VARCHAR(10) NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_table_date (table_id, date)
);
