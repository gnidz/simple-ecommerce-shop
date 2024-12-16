# Simple E-Commerce Shop

Simple E-Commerce Shop is a beginner-friendly project designed to implement a basic e-commerce platform with essential features. This project showcases core functionalities of an online shop, including product management, user account handling, shopping cart functionality, and order processing. Built using **PHP**, **MySQL**, and **Bootstrap**, it offers a dynamic and responsive experience.

---

## Features

### **User Features**
- **Product Browsing**: Explore available products with detailed descriptions and images.
- **Shopping Cart**: Add, update, and remove items from the cart.
- **User Account Management**:
  - User registration and login system.
  - Role-based access control (Admin/User).
- **Order Management**:
  - Place orders and view summaries.
  - Track order status updates.

### **Admin Features**
- **Dashboard**: Access key statistics like:
  - Total revenue
  - Total products
  - Total users
  - Pending orders
- **Product Management**:
  - Add, edit, and delete products.
  - Upload product images.
- **User Management**:
  - View, edit, and delete user accounts.
- **Order Management**:
  - Update order status (e.g., pending, shipped, completed).

---

## Installation

### **Prerequisites**
1. **Web Server**: Install a local server like [XAMPP](https://www.apachefriends.org/) or [WAMP](https://www.wampserver.com/).
2. **Database**: MySQL or equivalent database.
3. **PHP**: PHP 7.4+ is recommended.
   
### **Move Files**
- Navigate to the `/source/` folder within the project.
- Move all files to your local server’s root directory (e.g., `htdocs` for XAMPP).

### **Set Up Database**
1. Open your browser and go to `http://localhost/phpmyadmin`.
2. Create a new database named `ecommerce`.
3. Import the SQL file located in `/sql/ecommerce.sql` to set up the database schema and sample data.

### **Configure Database Connection**
1. Open the `db.php` and `db_pdo.php` files.
2. Update the database connection settings as per your local environment:
   ```php
   $host = 'localhost';
   $user = 'root';
   $password = '';
   $dbname = 'ecommerce';

Navigate to http://localhost/simple-ecommerce-shop/ in your web browser.

---

### **User Roles and Credentials**

The application includes predefined accounts to help you test role-based access control:

1. **User Role**:
   - **Username**: `user`
   - **Password**: `user123`

2. **Admin Role**:
   - **Username**: `admin`
   - **Password**: `admin123`

Use these credentials to log in and explore the respective functionalities for users and administrators.

---

## Usage

### **Homepage**
- Explore featured products, best sellers, and promotional banners.
- Navigate to the shop or individual product pages.

### **Admin Dashboard**
- View a summary of statistics including:
  - **Total Products**: Number of products available in the shop.
  - **Total Users**: Total registered users.
  - **Total Orders**: Orders placed by all users.
  - **Total Revenue**: Sum of all orders' values.
  - **Average Order Value**: Revenue per order.
  - **New Users**: Users registered in the past 30 days.
  - **Pending Orders**: Orders yet to be processed.
  - **Out-of-Stock Products**: Items with no stock remaining.
- Manage products, users, and orders through intuitive forms and tables.

### **Shop & Cart**
- Browse products, view details, and add items to the cart.
- Adjust quantities or remove items from the cart before placing an order.

---

### **Contribution**
Contributions are welcome! Feel free to fork this repository and submit pull requests with new features or improvements.

---
