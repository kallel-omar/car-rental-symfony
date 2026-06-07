# 🚗 Car Rental Management System

![Symfony](https://img.shields.io/badge/Symfony-7.4-black)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![MySQL](https://img.shields.io/badge/MySQL-8-orange)
![License](https://img.shields.io/badge/License-MIT-green)
![Status](https://img.shields.io/badge/Status-Active-success)

A modern and comprehensive Car Rental Management System built with **Symfony 7.4 LTS**, designed to simplify vehicle reservations, fleet management, customer management, and administrative operations.

---

## 📋 Overview

This project provides a complete solution for car rental businesses. Customers can browse vehicles, create reservations, upload required documents, manage their bookings, and view their rental history. Administrators can manage vehicles, users, reservations, and business operations through a dedicated dashboard.

---

## ✨ Features

### Customer Features

* User Registration & Authentication
* Email Verification
* Password Reset
* Vehicle Browsing
* Vehicle Reservation System
* Reservation History
* User Profile Management
* CIN Upload
* Driver License Upload

### Administrative Features

* Admin Dashboard
* User Management
* Vehicle Management
* Reservation Management
* Revenue Monitoring
* Reservation Tracking
* Customer Monitoring

### Advanced Features

* Availability Validation
* PDF Invoice Generation
* Stripe Payment Integration
* Role-Based Access Control
* Responsive Design
* Secure Authentication System

---

## 🛠️ Technology Stack

### Backend

* PHP 8.2
* Symfony 7.4 LTS
* Doctrine ORM

### Frontend

* Twig
* Bootstrap
* HTML5
* CSS3
* JavaScript

### Database

* MySQL

### Additional Libraries

* Stripe PHP SDK
* DomPDF
* Symfony Mailer
* Symfony Security Bundle

---

## 📸 Screenshots

### Users Management

![Users Management](docs/screenshots/users-management.png)

*Comprehensive user management interface with role assignment and account status control.*

### Dashboard Analytics

![Dashboard Analytics](docs/screenshots/dashboard.png)

*Admin dashboard displaying total cars, reservations calendar, revenue tracking, and latest reservation activities.*

### User Profile

![User Profile](docs/screenshots/profile.png)

*Personal profile page with reservation history, document verification, and account information management.*

### Reservation System

![Reservation System](docs/screenshots/reservation.png)

*Car reservation form with vehicle selection, date picker, CIN upload, and driver license upload.*

---

## 🎯 Learning Objectives

This project was developed to:

* Learn Symfony 7 best practices
* Master Doctrine ORM
* Build a real-world reservation platform
* Implement secure authentication workflows
* Integrate online payments using Stripe
* Generate PDF invoices
* Gain experience with full-stack web development

---

## 🚧 Project Status

### Completed

* Authentication System
* Email Verification
* Password Reset
* Vehicle Management
* Reservation Management
* Admin Dashboard
* Document Upload System
* PDF Invoice Generation
* Stripe Integration
* Availability Validation
* Security Dependency Updates

### In Progress

* Online Deployment
* Automated Testing
* API Development

---

## 📦 Requirements

* PHP 8.2+
* Composer
* MySQL 8+
* Symfony CLI (optional)

---

## 🚀 Installation

### 1. Clone Repository

```bash
git clone https://github.com/kallel-omar/car-rental-symfony.git
cd car-rental-symfony
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

```bash
cp .env.example .env
```

Update the database credentials and other settings inside `.env`.

### 4. Create Database

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5. Load Fixtures (Optional)

```bash
php bin/console doctrine:fixtures:load
```

### 6. Run Application

Using Symfony CLI:

```bash
symfony server:start
```

Or PHP built-in server:

```bash
php -S localhost:8000 -t public
```

---

## ⚙️ Environment Variables

Create a `.env` file based on `.env.example` and configure the following variables:

```env
APP_ENV=dev
APP_SECRET=change_me

DATABASE_URL="mysql://root:password@127.0.0.1:3306/car_rental"

MAILER_DSN=null://null

STRIPE_SECRET_KEY=your_secret_key
STRIPE_PUBLIC_KEY=your_public_key
```

---

## 📁 Project Structure

```text
car-rental-symfony/
├── config/
├── migrations/
├── public/
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Repository/
│   ├── Security/
│   └── Service/
├── templates/
├── tests/
├── var/
└── vendor/
```

---

## 🌐 Live Demo

🚧 Deployment in Progress

The application is currently being prepared for production deployment.

---

## 🔒 Security

* Password Hashing
* Role-Based Access Control
* CSRF Protection
* Email Verification
* Secure Authentication
* Input Validation
* Composer Security Audit Checked

---

## 📈 Future Improvements

* REST API
* Mobile Application
* Real-Time Notifications
* Advanced Analytics
* Vehicle Maintenance Management
* Multi-Language Support

---

## 🔗 Repository

GitHub Repository:

https://github.com/kallel-omar/car-rental-symfony

---

## 👨‍💻 Author

**Omar Kallel**

GitHub:

https://github.com/kallel-omar

---

## 🤝 Contributing

Contributions are welcome.

Feel free to fork the repository and submit a Pull Request.

---

## 📚 Resources

* Symfony Documentation
* Doctrine ORM
* Twig Documentation
* Stripe Documentation

---

**Last Updated:** June 2026
