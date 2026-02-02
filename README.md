# Jewelry Manager (IMS)

A modern, responsive Inventory Management & Point of Sale (POS) system built with **Laravel Livewire** and **Alpine.js**. Designed for high-speed sales entry, customer management, and inventory tracking.

![Status](https://img.shields.io/badge/status-active-success.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)

## 🚀 Key Features

### 🛒 Point of Sale (POS)
- **Fast Checkout Flow**: Optimized "Invoice Style" layout for quick data entry.
- **Smart Payment Processing**:
  - Automatically detects **Paid**, **Partial**, or **Credit** status based on amount entered.
  - Reactive feedback shows remaining balance instantly.
  - One-click "Full Payment" button.
- **Visual Payment Selection**: Large, touch-friendly grid for Cash, Mobile Money, Card, and Bank Transfer.
- **Product Search**: Instant search by Name or SKU with stock awareness.

### 👥 Customer Management
- **Quick-Add Modal**: Create customers directly from the POS screen without leaving the flow.
- **Duplicate Protection**: Smart detection of existing phone numbers prevents duplicates and offers to load the existing profile.
- **Credit Tracking**: Integrated credit limits and balance tracking.

### 📱 User Interface
- **Mobile First**: Fully responsive design with robust mobile usage (sidebar, touch targets).
- **FlyonUI & Tailwind**: Premium, clean aesthetic with dark mode support basics.
- **Real-time**: Powered by Livewire & Alpine.js for a SPA-like feel without the complexity.

---

## 🛠️ Technology Stack

- **Backend**: Laravel 12.x
- **Frontend**: Blade Components + Livewire 3
- **Interactivity**: Alpine.js v3
- **Styling**: Tailwind CSS + FlyonUI
- **Database**: MySQL / SQLite
- **Testing**: Pest / PHPUnit

---

## ⚙️ Installation

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM

### Setup Steps

1.  **Clone the repository**
    ```bash
    git clone https://github.com/yourusername/ims.git
    cd ims
    ```
`
2.  **Install PHP Dependencies**
    ```bash
    composer install
    ```

3.  **Install & Build Frontend**
    ```bash
    npm install
    npm run build
    ```

4.  **Environment Setup**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5.  **Database Setup**
    - Configure your database credentials in `.env`.
    - Run migrations and seeders:
    ```bash
    php artisan migrate --seed
    ```

6.  **Run the Server**
    ```bash
    php artisan serve
    ```
    Visit `http://localhost:8000` to access the application.

---

## 🧪 Testing

The application includes a comprehensive test suite covering authentication, dashboard, and core features.

To run tests:
```bash
php artisan test
```

---

## 📂 Project Structure

- `app/Livewire/`: Contains all dynamic components (Sales, Dashboard, etc.).
- `resources/views/livewire/`: Blade templates for the components.
- `app/Enums/`: Standardized Enums for Payment Methods and Statuses.
- `tests/Feature/`: Feature tests for core flows.

---

## 🤝 Contributing

1.  Fork the repository.
2.  Create your feature branch (`git checkout -b feature/AmazingFeature`).
3.  Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4.  Push to the branch (`git push origin feature/AmazingFeature`).
5.  Open a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
