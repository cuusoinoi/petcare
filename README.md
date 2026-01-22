# UIT Petcare - Hệ Thống Quản Lý Phòng Khám Thú Y

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![PHP](https://img.shields.io/badge/PHP-8.x-purple)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.x-orange)
![License](https://img.shields.io/badge/license-MIT-green)

Hệ thống quản lý phòng khám thú y toàn diện được xây dựng bằng **CodeIgniter 4**, hỗ trợ quản lý khách hàng, thú cưng, lịch hẹn, hóa đơn và các dịch vụ chăm sóc thú cưng. Hệ thống bao gồm **giao diện admin** cho nhân viên phòng khám và **giao diện customer** cho khách hàng đặt lịch và tra cứu thông tin.

---

## 📋 Mục Lục

- [Tính Năng](#-tính-năng)
- [Công Nghệ Sử Dụng](#-công-nghệ-sử-dụng)
- [Cài Đặt](#-cài-đặt)
- [Cấu Trúc Dự Án](#-cấu-trúc-dự-án)
- [Giao Diện](#-giao-diện)
- [Hướng Dẫn Sử Dụng](#-hướng-dẫn-sử-dụng)
- [Thông Tin Đăng Nhập](#-thông-tin-đăng-nhập)
- [Liên Hệ](#-liên-hệ)

---

## ✨ Tính Năng

### 🔐 Phân Quyền Người Dùng

- **Admin**: Toàn quyền quản lý hệ thống
- **Staff**: Quyền quản lý nghiệp vụ (không quản lý user)
- **Customer**: Quyền xem thông tin và đặt lịch

### 👨‍💼 Giao Diện Admin

#### 1. **Dashboard**
- Thống kê tổng quan: số khách hàng, thú cưng, lịch hẹn, hóa đơn
- Biểu đồ doanh thu theo tháng
- Biểu đồ lượt khám và check-in/check-out
- Tỷ trọng doanh thu theo loại dịch vụ

#### 2. **Quản Lý Chính**
- **Khách hàng**: CRUD thông tin khách hàng, tìm kiếm, phân trang
- **Thú cưng**: Quản lý thông tin thú cưng (tên, loài, giới tính, ngày sinh, cân nặng, v.v.)
- **Bác sĩ**: Quản lý thông tin bác sĩ

#### 3. **Khám & Điều Trị**
- **Khám bệnh**: Tạo và quản lý phiếu khám, lịch sử khám bệnh
- **Liệu trình điều trị**: 
  - Quản lý liệu trình điều trị
  - Quản lý các buổi điều trị trong liệu trình
  - Chẩn đoán bệnh
  - Kê đơn thuốc
- **Tiêm chủng**: Quản lý lịch sử tiêm chủng cho thú cưng

#### 4. **Lưu Chuồng & Hóa Đơn**
- **Lưu chuồng**: Check-in/Check-out thú cưng, tự động tạo hóa đơn khi checkout
- **Hóa đơn**: Tạo và quản lý hóa đơn, chi tiết dịch vụ
- **Mẫu in**: Xem trước và in hóa đơn, giấy cam kết

#### 5. **Danh Mục**
- **Dịch vụ**: Quản lý các loại dịch vụ và giá
- **Thuốc**: Quản lý danh mục thuốc
- **Vaccine**: Quản lý danh mục vaccine

#### 6. **Quản Trị**
- **Người dùng**: Quản lý tài khoản, đổi mật khẩu
- **Cài đặt**: Thông tin phòng khám (tên, địa chỉ, số điện thoại)

#### 7. **Lịch Hẹn**
- Xem danh sách lịch hẹn từ khách hàng
- Cập nhật trạng thái lịch hẹn (Chờ xác nhận, Đã xác nhận, Hoàn thành, Đã hủy)
- Xem và chỉnh sửa chi tiết lịch hẹn

### 👤 Giao Diện Customer

#### 1. **Trang Chủ**
- Giới thiệu phòng khám
- Dịch vụ nổi bật
- Nút đặt lịch nhanh
- Thông tin về phòng khám

#### 2. **Dịch Vụ**
- Danh sách đầy đủ các dịch vụ
- Bảng giá chi tiết

#### 3. **Liên Hệ**
- Thông tin liên hệ phòng khám
- Địa chỉ, số điện thoại

#### 4. **Đặt Lịch**
- Đặt lịch hẹn khám/spa/tiêm chủng
- Chọn thú cưng, bác sĩ, dịch vụ, thời gian
- Xem lịch hẹn của mình

#### 5. **Dashboard Khách Hàng**
- **Tổng quan**: Thống kê thú cưng, lịch hẹn, hóa đơn
- **Thông tin cá nhân**: Cập nhật thông tin
- **Thú cưng**: Quản lý thú cưng của mình
- **Lịch sử khám bệnh**: Xem chi tiết các lần khám
- **Đơn thuốc**: Xem các đơn thuốc đã kê
- **Lịch tiêm chủng**: Xem lịch sử và lịch tiêm sắp tới
- **Hóa đơn**: Xem và in hóa đơn

#### 6. **Đăng Ký/Đăng Nhập**
- Đăng ký bằng số điện thoại
- Đăng nhập bằng số điện thoại + OTP (mặc định: 123456)

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
2. Tạo database mới tên `petcare`
3. Import file `petcare_database.sql` vào database vừa tạo

```sql
-- Hoặc chạy lệnh MySQL
mysql -u root petcare < petcare_database.sql
```

### Bước 4: Cấu hình Environment

1. Copy file `.env` (nếu chưa có, tạo từ file `env`)
2. Cập nhật thông tin database trong `.env`:

```env
database.default.hostname = localhost
database.default.database = petcare
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```

3. Cập nhật baseURL:

```env
app.baseURL = 'http://localhost/petcare/'
```

### Bước 5: Cấu hình Apache

1. Kiểm tra `mod_rewrite` đã được bật trong `C:\xampp\apache\conf\httpd.conf`:
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```

2. Restart Apache trong XAMPP Control Panel

### Bước 6: Kiểm tra

1. Truy cập: `http://localhost/petcare`
2. Đăng nhập admin: `http://localhost/petcare/admin`
3. Đăng nhập customer: `http://localhost/petcare/customer/login`

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
2. Nhập username và password
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
1. Vào menu **Lưu chuồng & Hóa đơn** → **Hóa đơn** → **Thêm hóa đơn**
2. Chọn khách hàng, thú cưng
3. Thêm các dịch vụ và số lượng
4. Giá sẽ tự động lấy từ bảng dịch vụ
5. Nhập giảm giá, đặt cọc (nếu có)
6. Lưu hóa đơn

#### Check-in/Check-out Chuồng
1. Vào menu **Lưu chuồng & Hóa đơn** → **Lưu chuồng**
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
- **Password**: `admin` (hoặc kiểm tra trong database)

> ⚠️ **Lưu ý**: Nên đổi mật khẩu ngay sau lần đăng nhập đầu tiên!

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
