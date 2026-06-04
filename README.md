# Car Rental Management System

A comprehensive Symfony-based web application for managing vehicle reservations, fleet management, and administrative operations for car rental businesses.

## 📋 Overview

This Car Rental Management System is built with **Symfony** and provides a complete solution for:
- Vehicle fleet management and tracking
- Reservation and booking system
- Customer management
- Administrative dashboard
- Responsive and user-friendly interface

## 🛠️ Technology Stack

- **Backend**: PHP with Symfony Framework
- **Frontend**: HTML, CSS, SCSS, JavaScript
- **Templating**: Twig
- **Database**: MySQL/MariaDB (recommended)


## ✨ Features

- **Fleet Management**: Manage vehicle inventory with detailed specifications
- **Reservation System**: Easy booking and rental management
- **Customer Management**: Track customer information and rental history
- **Admin Dashboard**: Comprehensive administrative interface
- **Responsive Design**: Mobile-friendly interface
- **User Authentication**: Secure login and access control

## 📦 Requirements

- PHP 8.0 or higher
- Composer
- Symfony 5.x or 6.x
- MySQL/MariaDB 5.7 or higher
- Node.js (optional, for asset compilation)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/kallel-omar/car-rental-symfony.git
   cd car-rental-symfony
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   ```
   Update `.env` with your database credentials and other configuration

4. **Create database**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Load fixtures (optional)**
   ```bash
   php bin/console doctrine:fixtures:load
   ```

6. **Start the development server**
   ```bash
   symfony server:start
   ```
   
   Or with PHP built-in server:
   ```bash
   php -S localhost:8000 -t public
   ```

## 📁 Project Structure

```
car-rental-symfony/
├── public/              # Web root directory
├── src/                 # Application source code
│   ├── Controller/     # Symfony controllers
│   ├── Entity/         # Doctrine entities
│   ├── Repository/     # Data repositories
│   └── ...
├── templates/           # Twig templates
├── var/                # Logs and cache
├── config/             # Configuration files
├── migrations/         # Database migrations
└── ...
```

## 🔧 Configuration

### Database Connection
Update your `.env` file:
```
DATABASE_URL="mysql://user:password@localhost:3306/car_rental"
```

### Email Configuration (if applicable)
Configure MAILER_DSN in `.env` for sending notifications

## 🎮 Usage

### For Administrators
- Access the admin panel to manage vehicles, customers, and bookings
- Monitor rental history and generate reports
- Configure system settings

### For Customers
- Register and create an account
- Browse available vehicles
- Make and manage reservations
- View rental history

## 📝 License

This project is open source. Please check the LICENSE file for details.

## 👤 Author

**Kallel Omar**
- GitHub: [@kallel-omar](https://github.com/kallel-omar)

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📧 Support

For support, please open an issue in the repository.

## 📚 Additional Resources

- [Symfony Documentation](https://symfony.com/doc)
- [Doctrine ORM](https://www.doctrine-project.org/)
- [Twig Template Engine](https://twig.symfony.com/)

---

**Last Updated**: June 2026
