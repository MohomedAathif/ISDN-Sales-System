# ISDN – IslandLink Sales Distribution Network System

## Overview
The IslandLink Sales Distribution Network (ISDN) system is a centralized web-based platform designed to manage product distribution across multiple Regional Distribution Centers (RDCs). The system enables customers to browse products, place orders, and make payments while allowing administrators and logistics staff to manage inventory, deliveries, and sales analytics.

The platform improves efficiency in order processing, inventory management, and delivery coordination across the island.

---

## Features

### Centralised Order Management
Customers can browse products, add items to the cart, and place orders through a web-based interface. The system provides instant order confirmation and estimated delivery dates.

### Real-Time Inventory Management
Inventory is automatically updated whenever orders are placed or stock is transferred between RDCs. This ensures accurate stock levels across all distribution centers.

### Inter-Branch Stock Transfers
RDC staff can transfer stock between distribution centers to balance inventory across different locations.

### Promotions System
Administrators can create promotional discounts that are automatically applied to product prices for customers.

### Cart and Checkout System
Customers can add products to a cart, review selected items, and proceed to checkout with payment options.

### Automated Invoice Generation
Invoices are generated automatically for each order and can be viewed or printed by customers.

### Delivery Scheduling
Logistics staff can assign drivers and delivery dates to orders.

### Reporting System
Sales reports can be generated and exported as PDF files.

### Role-Based Access Control
The system supports multiple user roles:
- Admin
- Manager
- Customer
- RDC Staff
- Logistics Staff

Each role has specific permissions and access levels.

---

## Technology Stack

### Frontend
- HTML5
- Tailwind CSS
- JavaScript

### Backend
- PHP

### Database
- MySQL

### Development Environment
- XAMPP (Apache + MySQL + PHP)
- phpMyAdmin

---

## Security Features

The system implements several security mechanisms:

- Password hashing using `password_hash()`
- Password verification using `password_verify()`
- Session-based authentication
- Role-based authorization
- Prepared SQL statements (PDO) to prevent SQL injection
- Input sanitization using `htmlspecialchars()`

---

## Installation Guide

### 1. Clone the Repository

### 2. Move the Project

Place the project folder inside the XAMPP `htdocs` directory.

### 3. Start XAMPP

Start the following services:

- Apache
- MySQL

---

### 4. Import Database

Open **phpMyAdmin** and import the provided SQL file.

Database tables include:

---

### 5. Access the System

Open your browser and navigate to:

---

## Future Improvements

Possible future enhancements include:

- GPS tracking for delivery vehicles
- Integration with real payment gateways
- Email notifications for invoices
- Advanced analytics dashboards
- Mobile application support
### Architecture
The system follows a simplified MVC (Model-View-Controller) structure.
