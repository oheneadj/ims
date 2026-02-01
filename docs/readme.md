# Jewelry Inventory Management System

A comprehensive web-based inventory and sales management system built specifically for jewelry businesses using Laravel Livewire and DaisyUI.

## Project Overview

This application helps jewelry business owners track inventory, manage sales (cash and credit), monitor customer purchases, record expenses, and generate profit reports with daily, weekly, and monthly views.

## Tech Stack

- **Framework**: Laravel 12
- **Database**: MySQL/PostgreSQL
- **PHP Version**: 8.4+
- **Frontend**: Livewire 3.x, Alpine.js, Tailwind CSS, DaisyUI

## Features

### Core Modules

1. **Inventory Management**
   - Individual jewelry item tracking
   - Cost price and selling price management
   - Stock level monitoring
   - Low stock alerts
   - Product categorization by type and material
   - Photo uploads

2. **Sales Management**
   - Cash sales (immediate payment)
   - Credit sales (payment later)
   - Partial payments
   - Automatic inventory reduction
   - Profit calculation per sale
   - Invoice generation

3. **Customer Management**
   - Customer database with contact info
   - Credit customer designation
   - Credit limit setting
   - Outstanding balance tracking
   - Purchase history
   - Payment history

4. **Payment Tracking**
   - Record credit payments
   - Update customer balances
   - Payment method tracking
   - Payment history

5. **Expense Management**
   - Categorized expense tracking
   - Equipment, supplies, utilities, etc.
   - Date-based expense records
   - Expense summaries

6. **Reporting & Analytics**
   - Dashboard with toggleable views (Day/Week/Month/Custom)
   - Sales performance metrics
   - Profit analysis
   - Top-selling products
   - Top customers
   - Inventory valuation
   - Outstanding credit summary
   - Export capabilities

## Installation

### Prerequisites

- PHP 8.4 or higher
- Composer 2.7+
- MySQL 8.0+ or PostgreSQL 16+
- Node.js 20.x LTS or 22.x and NPM 10+

### Setup Steps

```bash
# Clone the repository
git clone [repository-url]
cd jewelry-manager

# Install PHP dependencies
composer install

# Install NPM dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env file
# DB_DATABASE=jewelry_manager
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Create admin user
php artisan make:filament-user

# Build assets
npm run build

# Start development server
php artisan serve
```


## Database Structure

### Tables

- **products** - Jewelry inventory items
- **customers** - Customer information
- **sales** - Sales transactions
- **sale_items** - Individual items in each sale
- **payments** - Credit payment records
- **expenses** - Business expense tracking
- **stock_movements** - Inventory change log

## Project Structure

```
app/
├── Filament/
│   ├── Resources/
│   │   ├── ProductResource.php
│   │   ├── CustomerResource.php
│   │   ├── SaleResource.php
│   │   ├── PaymentResource.php
│   │   └── ExpenseResource.php
│   ├── Widgets/
│   │   ├── SalesOverview.php
│   │   ├── ProfitChart.php
│   │   ├── TopProducts.php
│   │   └── TopCustomers.php
│   └── Pages/
│       └── Dashboard.php
├── Models/
│   ├── Product.php
│   ├── Customer.php
│   ├── Sale.php
│   ├── SaleItem.php
│   ├── Payment.php
│   ├── Expense.php
│   └── StockMovement.php
└── Observers/
    ├── SaleObserver.php
    └── SaleItemObserver.php
```

## Key Functionalities

### Automatic Calculations

- **Profit per item**: `selling_price - cost_price`
- **Profit margin**: `(profit / selling_price) × 100`
- **Sale total profit**: Sum of all item profits in sale
- **Customer balance**: Automatically updated on sales and payments
- **Inventory**: Auto-reduced on sales, increased on restocking

### Business Rules

1. **Credit Sales**:
   - Can only assign to customers marked as credit customers
   - Cannot exceed customer's credit limit
   - Outstanding balance tracked automatically

2. **Inventory**:
   - Stock cannot go negative
   - Low stock threshold: 5 items or less
   - Stock movements logged for audit

3. **Payments**:
   - Payment amount cannot exceed outstanding balance
   - Customer balance updated immediately

## Development Guidelines

- Follow Laravel best practices
- Use Filament conventions for resources
- Database operations use Eloquent ORM
- Use observers for side effects (stock updates, balance changes)
- Validation on all forms
- Soft deletes for data integrity

## Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure production database
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set up SSL certificate
- [ ] Configure backup system
- [ ] Set up monitoring

### Recommended Hosting

- DigitalOcean (App Platform or Droplet)
- Vultr
- Linode
- Any VPS with PHP 8.2+ support

## Support

For issues, feature requests, or questions, please contact the development team.

## License

Proprietary - All rights reserved

## Version

Current Version: 1.0.0 (MVP)