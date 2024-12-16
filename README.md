# simple-ecommerce-shop

โปรแกรมที่จำเป็น
XAMPP (หรือโปรแกรมเว็บเซิร์ฟเวอร์ที่รองรับ PHP และ MySQL)
PHP 7.4 ขึ้นไป
เว็บเบราว์เซอร์ (Chrome, Firefox, หรือ Edge)
PHPMyAdmin สำหรับจัดการฐานข้อมูล

การติดตั้งโปรเจกต์

ดาวน์โหลดและติดตั้ง XAMPP:

ดาวน์โหลดโปรแกรม XAMPP จากเว็บไซต์ https://www.apachefriends.org
ติดตั้งโปรแกรมและเริ่ม Apache และ MySQL ผ่าน XAMPP Control Panel
คัดลอกไฟล์โปรเจ็กต์:

คัดลอกโฟลเดอร์ project_orchid ไปไว้ที่ htdocs ของ XAMPP
ตัวอย่าง: C:\xampp\htdocs\project_orchid

ตั้งค่าการเชื่อมต่อฐานข้อมูล:

เปิดไฟล์ /source/project_orchid/db.php และ /source/project_orchid/db_pdo.php
แก้ไขค่าการเชื่อมต่อฐานข้อมูลให้ตรงกับเครื่องของคุณ:
$host = "localhost"; // เปลี่ยน server host ตาม MySQL ที่ใช้
$user = "root"; // เปลี่ยน user ตาม MySQL ที่ใช้
$password = ""; // เปลี่ยน password ตาม MySQL ที่ใช้
$database = "ecommerce"; // ชื่อฐานข้อมูล

การติดตั้งฐานข้อมูล

เข้าสู่ PHPMyAdmin:
เปิดเบราว์เซอร์และเข้า URL: http://localhost/phpmyadmin
สร้างฐานข้อมูล:
คลิก "New" และสร้างฐานข้อมูลชื่อ ecommerce
นำเข้าฐานข้อมูล:
ไปที่แท็บ "Import" และเลือกไฟล์ /sql/ecommerce.sql
คลิก "Go" เพื่อสร้างตารางและข้อมูลเริ่มต้น

ตั้งค่าบัญชีผู้ดูแลระบบ (Admin):
แก้ไขโดยตรงที่ฐานข้อมูลในตาราง users และตั้งค่าคอลัมน์ role เป็น admin

รันโปรเจกต์:

เปิดเว็บเบราว์เซอร์
เข้าสู่ URL: http://localhost/project_orchid/login.php

รหัสผ่านเข้าเว็ปไซต์ role admin
username: admin
password: admin123

รหัสผ่านเข้าเว็ปไซต์ role user
username: user
password: user123
