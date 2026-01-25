# UIT Petcare - Hệ Thống Chăm Sóc Thú Cưng

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![PHP](https://img.shields.io/badge/PHP-8.x-purple)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.x-orange)
![License](https://img.shields.io/badge/license-MIT-green)

Hệ thống chăm sóc thú cưng toàn diện được xây dựng bằng **CodeIgniter 4**, hỗ trợ quản lý khách hàng, thú cưng, lịch hẹn, hóa đơn và các dịch vụ chăm sóc thú cưng. Hệ thống bao gồm **giao diện admin** cho nhân viên phòng khám và **giao diện customer** cho khách hàng đặt lịch và tra cứu thông tin.

---

## 📋 Mục Lục

- [Công Nghệ Sử Dụng](#-công-nghệ-sử-dụng)
- [Cài Đặt](#-cài-đặt)
- [Cấu Trúc Dự Án](#-cấu-trúc-dự-án)
- [Giao Diện](#-giao-diện)
- [Hướng Dẫn Sử Dụng](#-hướng-dẫn-sử-dụng)
- [Thông Tin Đăng Nhập](#-thông-tin-đăng-nhập)
- [Liên Hệ](#-liên-hệ)

---

## 💻 Công Nghệ Sử Dụng

### Backend
- **Framework**: CodeIgniter 4
- **Ngôn ngữ**: PHP 8.x
- **Database**: MySQL 5.7+
- **Server**: XAMPP (Apache + MySQL)

### Frontend
- **HTML5, CSS3, JavaScript**
- **Font Awesome 6.5.0** (Icons)
- **Chart.js** (Biểu đồ thống kê)
- **Google Fonts** (Roboto, Noto Sans)
- **Responsive Design**

### Bảo Mật
- **Session Management**
- **Role-based Access Control (RBAC)**
- **Password Hashing**
- **CSRF Protection**

---

## 🚀 Cài Đặt

### Bước 1: Clone hoặc Download dự án

```bash
# Nếu dùng Git
git clone https://github.com/cuusoinoi/petcare.git
cd petcare

# Hoặc giải nén file ZIP vào thư mục htdocs của XAMPP
```

### Bước 2: Cài đặt vào XAMPP

1. Copy toàn bộ thư mục `petcare` vào `C:\xampp\htdocs\`
2. Đảm bảo đường dẫn là: `C:\xampp\htdocs\petcare\`

### Bước 3: Tạo Database

1. Mở **phpMyAdmin** (http://localhost/phpmyadmin)
2. Tạo database mới tên `petcare` (utf8mb4_unicode_ci)
3. Import file `petcare_database.sql` trong thư mục `petcare` vào database vừa tạo

### Bước 4: Kiểm tra

1. Truy cập: `http://localhost/petcare`
2. Đăng nhập admin: `http://localhost/petcare/admin` 
   - Tài khoản: admin, mật khẩu: 123456
3. Đăng nhập customer: `http://localhost/petcare/customer/login`
   - Tài khoản: 0901234567, mật khẩu: 123456
   - Hoặc đăng kí bằng cách truy cập http://localhost/petcare/customer/register, nhập tên, SĐT + OTP 123456

---

## 📁 Cấu Trúc Dự Án

```
petcare/
├── app/
│   ├── Config/
│   │   ├── App.php              # Cấu hình ứng dụng
│   │   ├── Database.php         # Cấu hình database
│   │   ├── Routes.php           # Định tuyến
│   │   └── Filters.php          # Bộ lọc (Auth, CSRF)
│   ├── Controllers/
│   │   ├── AuthController.php           # Đăng nhập admin
│   │   ├── CustomerController.php        # Trang công khai
│   │   ├── CustomerAuthController.php     # Đăng nhập/Đăng ký customer
│   │   ├── BookingController.php         # Đặt lịch
│   │   ├── CustomerDashboardController.php  # Dashboard customer
│   │   └── Admin/
│   │       ├── BaseController.php        # Controller cơ sở admin
│   │       ├── DashboardController.php    # Dashboard admin
│   │       ├── CustomerController.php     # Quản lý khách hàng
│   │       ├── PetController.php         # Quản lý thú cưng
│   │       ├── DoctorController.php      # Quản lý bác sĩ
│   │       ├── UserController.php        # Quản lý user
│   │       ├── MedicalRecordController.php
│   │       ├── InvoiceController.php
│   │       ├── AppointmentController.php # Quản lý lịch hẹn
│   │       └── ...
│   ├── Filters/
│   │   └── AuthFilter.php       # Kiểm tra đăng nhập và quyền
│   ├── Models/
│   │   ├── CustomerModel.php
│   │   ├── PetModel.php
│   │   ├── DoctorModel.php
│   │   ├── UserModel.php
│   │   ├── MedicalRecordModel.php
│   │   ├── InvoiceModel.php
│   │   ├── AppointmentModel.php
│   │   └── ...
│   └── Views/
│       ├── auth/                 # Trang đăng nhập
│       ├── layouts/              # Layout chung
│       │   ├── admin_header.php
│       │   ├── admin_sidebar.php
│       │   ├── admin_footer.php
│       │   ├── customer_header.php
│       │   └── customer_footer.php
│       ├── admin/                # Views admin
│       │   ├── customer/
│       │   ├── pet/
│       │   ├── doctor/
│       │   ├── invoice/
│       │   ├── appointment/
│       │   └── ...
│       └── customer/             # Views customer
│           ├── home.php
│           ├── services.php
│           ├── contact.php
│           ├── auth/
│           ├── booking/
│           └── dashboard/
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   ├── base.css         # CSS cơ bản, biến màu
│   │   │   ├── main.css         # CSS admin
│   │   │   └── customer.css     # CSS customer
│   │   └── js/
│   │       ├── script.js        # JS admin
│   │       └── customer.js     # JS customer
│   ├── admin_assets/
│   │   └── images/
│   │       └── logo.png         # Logo phòng khám
│   ├── index.php                # Entry point
│   └── .htaccess                # URL rewriting
├── writable/
│   └── logs/                    # Log files
├── .env                         # Environment config
├── .htaccess                    # Root rewrite rules
├── petcare_database.sql          # Database schema + sample data
└── README.md                   
```

---

## 🎨 Giao Diện

### Màu Sắc Chủ Đạo
- **Primary**: Màu nâu (#8B4513)
- **Secondary**: Màu vàng/nâu nhạt
- **Font**: Roboto, Noto Sans

### Responsive
- Desktop: Full features
- Tablet: Sidebar có thể collapse
- Mobile: Menu hamburger, responsive tables

---

## 📖 Hướng Dẫn Sử Dụng

### Cho Admin/Staff

#### Đăng nhập
1. Truy cập: `http://localhost/petcare/admin`
2. Nhập username `admin` và password `123456`
3. Click "Đăng nhập"

#### Quản lý Khách hàng
1. Vào menu **Quản lý chính** → **Khách hàng**
2. Click **Thêm khách hàng** để tạo mới
3. Click icon **Sửa** để chỉnh sửa
4. Click icon **Xóa** để xóa (có xác nhận)

#### Quản lý Thú cưng
1. Vào menu **Quản lý chính** → **Thú cưng**
2. Thêm thú cưng mới cho khách hàng
3. Cập nhật thông tin thú cưng

#### Tạo Phiếu Khám
1. Vào menu **Khám & Điều trị** → **Khám bệnh** → **Tạo phiếu khám**
2. Chọn khách hàng, thú cưng, bác sĩ
3. Điền thông tin khám và lưu

#### Quản lý Lịch Hẹn
1. Vào menu **Khám & Điều trị** → **Lịch hẹn**
2. Xem danh sách lịch hẹn từ khách hàng
3. Hover vào nút **✓** để cập nhật trạng thái nhanh
4. Click **👁️** để xem chi tiết và chỉnh sửa

#### Tạo Hóa Đơn
1. Vào menu **Hóa đơn** → **Thêm hóa đơn**
2. Chọn khách hàng, thú cưng
3. Thêm các dịch vụ và số lượng
4. Giá sẽ tự động lấy từ bảng dịch vụ
5. Nhập giảm giá, đặt cọc (nếu có)
6. Lưu hóa đơn

#### Check-in/Check-out Chuồng
1. Vào menu **Lưu chuồng**
2. **Check-in**: Thêm thú cưng vào chuồng
3. **Checkout**: Click nút checkout, hệ thống tự động tính phí và tạo hóa đơn

### Cho Customer

#### Đăng ký Tài khoản
1. Truy cập: `http://localhost/petcare/customer/register`
2. Điền thông tin: Họ tên, Số điện thoại, Email (tùy chọn), Địa chỉ (tùy chọn)
3. Nhập OTP: `123456` (mặc định)
4. Click "Đăng ký"

#### Đăng nhập
1. Truy cập: `http://localhost/petcare/customer/login`
2. Nhập số điện thoại
3. Nhập OTP: `123456`
4. Click "Đăng nhập"

#### Đặt Lịch Hẹn
1. Đăng nhập vào tài khoản
2. Click **Đặt lịch** trong menu
3. Chọn thú cưng, loại dịch vụ (Khám/Spa/Tiêm chủng)
4. Chọn bác sĩ (tùy chọn), dịch vụ (tùy chọn)
5. Chọn ngày và giờ
6. Thêm ghi chú (nếu có)
7. Click "Đặt lịch"

#### Xem Lịch Hẹn
1. Vào **Dashboard** → Click vào card **Lịch hẹn** hoặc menu **Đặt lịch** → **Lịch hẹn của tôi**
2. Xem danh sách lịch hẹn và trạng thái

#### Quản Lý Thú Cưng
1. Vào **Dashboard** → **Thú cưng**
2. Click **Thêm** để thêm thú cưng mới
3. Điền thông tin: Tên, Loài/Giống, Giới tính, Ngày sinh, Cân nặng, v.v.

#### Xem Lịch Sử Khám Bệnh
1. Vào **Dashboard** → **Hồ sơ khám**
2. Chọn thú cưng từ dropdown (hoặc xem tất cả)
3. Xem chi tiết các lần khám: Ngày khám, Bác sĩ, Tóm tắt, Chi tiết

#### Xem Hóa Đơn
1. Vào **Dashboard** → **Hóa đơn** (click vào card hoặc menu)
2. Xem danh sách hóa đơn
3. Click **Xem chi tiết** để xem đầy đủ thông tin
4. Click **In** để in hóa đơn

---

## 🔑 Thông Tin Đăng Nhập

### Admin/Staff

Sau khi import database, sử dụng tài khoản mặc định:

- **Username**: `admin`
- **Password**: `123456`

### Customer

- **Đăng ký mới**: Sử dụng số điện thoại và OTP `123456`
- **Đăng nhập**: Số điện thoại đã đăng ký + OTP `123456`

---

## 📧 Liên Hệ

- **Phòng khám**: UIT Petcare
- **Địa chỉ**: Hàn Thuyên, Khu phố 6 P, Thủ Đức, TP. Hồ Chí Minh
- **Điện thoại**: 028 3725 2002

---

**Chúc bạn có nhiều thú nuôi! 🐾**
