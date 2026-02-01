# Step-by-Step Build Checklist
## Jewelry Inventory Management System

**Total Estimated Time:** 4-6 weeks (100-140 hours) 
*(Increased due to custom UI implementation)*

---

## PHASE 0: Project Setup (Day 1)
**Time:** 4-6 hours

### Step 1: Install Laravel & Livewire
```bash
# 1.1 Create Laravel project
□ composer create-project laravel/laravel jewelry-manager
□ cd jewelry-manager

# 1.2 Install Livewire
□ composer require livewire/livewire

# 1.3 Install Tailwind CSS & DaisyUI
□ npm install -D tailwindcss postcss autoprefixer
□ npx tailwindcss init -p
□ npm install -D daisyui@latest

# 1.4 Configure Tailwind (tailwind.config.js)
□ Add DaisyUI plugin
□ Configure paths for Livewire views: './resources/views/**/*.blade.php'

# 1.5 Configure database
□ Create database: jewelry_manager
□ Update .env file with database credentials
□ Set APP_NAME="Jewelry Manager"
```

### Step 2: Setup Layouts & Navigation
```bash
# 2.1 Create App Layout
□ Create resources/views/components/layouts/app.blade.php
□ Add HTML structure with Tailwind/DaisyUI CDN or @vite
□ Add Navigation Sidebar (DaisyUI Drawer or Sidebar)
□ Add Top Navbar
□ Add Flash Message container

# 2.2 Test login/layout
□ php artisan serve
□ Ensure layout renders correctly with DaisyUI styles
```

### Step 3: Setup Version Control
```bash
# 3.1 Initialize git
□ git init
□ git add .
□ git commit -m "Initial Laravel + Livewire + DaisyUI setup"
```

**✅ Checkpoint:** Can you see a styled page with DaisyUI components?

---

## PHASE 1: Database Foundation (Days 2-3)
**Time:** 8-10 hours

### Step 5: Create All Migrations
```bash
# 5.1 Create migration files
□ php artisan make:migration create_products_table
□ php artisan make:migration create_customers_table
□ php artisan make:migration create_sales_table
□ php artisan make:migration create_sale_items_table
□ php artisan make:migration create_payments_table
□ php artisan make:migration create_expenses_table
□ php artisan make:migration create_stock_movements_table
```

### Step 6: Write Migration Code
Reference: `DATABASE_STRUCTURE.md`
*(Same standard migration steps as before - no changes needed for UI framework)*
```bash
# 6.1 - 6.7 Complete all migrations with proper types and FKs
□ Products (decimal 10,2 for money)
□ Customers
□ Sales
□ Sale Items
□ Payments
□ Expenses
□ Stock Movements
```

### Step 7: Run Migrations
```bash
□ php artisan migrate
```

**✅ Checkpoint:** All tables created successfully?

---

## PHASE 2: Models & Relationships (Days 3-4)
**Time:** 6-8 hours

### Step 8-15: Create & Configure Models
*(Same as before - Models are framework agnostic)*
```bash
□ Product (SoftDeletes, casts, accessors, relationships)
□ Customer (SoftDeletes, casts, relationships)
□ Sale (SoftDeletes, casts, relationships)
□ SaleItem (Casts, relationships)
□ Payment (Casts, relationships)
□ Expense (SoftDeletes, casts)
□ StockMovement (Relationships)
```

**✅ Checkpoint:** Run `php artisan tinker` and test model relationships.

---

## PHASE 3: Product Management (Days 5-8)
**Time:** 16-20 hours (Custom UI)

### Step 16: Create Product Components
```bash
# 16.1 Create Livewire components
□ php artisan make:livewire Products/ListProducts
□ php artisan make:livewire Products/CreateProduct
□ php artisan make:livewire Products/EditProduct
□ php artisan make:livewire Products/ShowProduct
```

### Step 17: Build "List Products" UI
```bash
# resources/views/livewire/products/list-products.blade.php

□ Add "Create Product" button (link to Create route)
□ Add Search Input (wire:model.live="search")
□ Add DaisyUI Table (table table-zebra)
  - Columns: Photo, Name, Type, Stock (Badge), Cost, Price, Actions
□ Add Pagination links ($products->links())
```

### Step 18: Implement List Logic
```bash
# app/Livewire/Products/ListProducts.php

□ Add properties: $search
□ Add render(): return view with Product::search($this->search)->paginate(10)
□ Add visual indicators for Low Stock
```

### Step 19: Build "Create/Edit Product" Form
```bash
# resources/views/livewire/products/create-product.blade.php

□ Create Form Layout (DaisyUI Card)
□ Add Inputs (input input-bordered):
  - Name, SKU
  - Type, Material (Select)
  - Cost Price, Selling Price (Input group with currency symbol)
  - Quantity
  - Description (Textarea)
  - Photo (FilePond or standard file input)
□ Add Livewire Validation errors (@error)
□ Add Save Button (btn btn-primary)
```

### Step 20: Implement Create/Edit Logic
```bash
# app/Livewire/Products/CreateProduct.php

□ Add validation rules (Rules array)
□ Add save() method
  - Validate
  - Handle File Upload
  - Create Product
  - Flash success message
  - Redirect to List
```

**✅ Checkpoint:** Can you create, list, edit, and delete products with the new UI?

---

## PHASE 4: Customer Management (Days 9-11)
**Time:** 12-16 hours

### Step 21: Create Customer Components
```bash
□ php artisan make:livewire Customers/ListCustomers
□ php artisan make:livewire Customers/CreateCustomer
□ php artisan make:livewire Customers/EditCustomer
□ php artisan make:livewire Customers/ShowCustomer
```

### Step 22: Build Customer List
```bash
□ DaisyUI Table for Customers
□ Badge for "Credit Customer"
□ Display Current Balance (Red if > 0)
□ Search functionality
```

### Step 23: Build Customer Form
```bash
□ Inputs: Name, Phone, Email, Address
□ Toggle: "Allow Credit?"
□ Input: Credit Limit (Visible only if Credit Allowed)
```

### Step 24: Customer Detail View
```bash
# resources/views/livewire/customers/show-customer.blade.php

□ Customer Profile Card
□ Tabs (DaisyUI Tabs):
  - Purchase History (Table of Sales)
  - Payment History (Table of Payments)
```

**✅ Checkpoint:** Customers working? Credit toggle works?

---

## PHASE 5: Sales System (Days 12-18)
**Time:** 30-40 hours (Heavy Custom Logic)

### Step 25: Create POS/Sale Components
```bash
□ php artisan make:livewire Sales/CreateSale
□ php artisan make:livewire Sales/ListSales
□ php artisan make:livewire Sales/ShowSale
```

### Step 26: Build "Create Sale" Interface (POS Style)
```bash
# Layout: Split Screen
# Left: Product Selection
# Right: Cart/Checkout

□ Left Column:
  - Product Search
  - Grid of Product Cards (Photo, Name, Price, Stock)
  - Click to Add to Cart

□ Right Column:
  - Select Customer (Searchable Dropdown)
  - Cart Items List (Repeater style)
    - Qty Input (wire:change)
    - Remove Button
  - Totals Section (Subtotal, Total)
  - Payment Section
    - Payment Method (Cash, Credit, etc.)
    - Amount Tendered
    - Change Due / Balance Due
  - "Complete Sale" Button
```

### Step 27: Implement Cart Logic
```bash
# app/Livewire/Sales/CreateSale.php

□ Properties: $cart = [], $customerId, $subtotal, $total
□ Method: addToCart($productId)
  - Check stock
  - Add or increment quantity
□ Method: updateQuantity($index, $qty)
  - Validate stock
□ Method: removeFromCart($index)
□ Computed: calculateTotals()
```

### Step 28: Implement Checkout Logic
```bash
# app/Livewire/Sales/CreateSale.php -> save()

□ Validate Cart not empty
□ Validate Customer selected (if Credit)
□ Validate Credit Limit (if Credit sale)
□ Database Transaction:
  - Create Sale
  - Create SaleItems
  - Decrement Stock (Observer handles this?)
  - Create Payment (if paid)
  - Update Customer Balance
□ Reset Cart & Flash Success
```

### Step 29: Build Sales History List
```bash
□ Table showing: Date, Customer, Total, Status (Paid/Credit), Balance
□ Filters: Date Range, Status
```

**✅ Checkpoint:** Can you complete a full sale flow? Stock deducted?

---

## PHASE 6: Payment Management (Days 19-20)
**Time:** 8-10 hours

### Step 30: Create Payment Components
```bash
□ php artisan make:livewire Payments/ListPayments
□ php artisan make:livewire Payments/CreatePayment
```

### Step 31: Payment Entry Form
```bash
□ Select Customer (Searchable)
  - Show Current Balance
□ Input Amount
□ Select Specific Sale (Optional - Filtered by Unpaid Sales)
□ Date Picker
□ Save Button
```

### Step 32: Implement Payment Logic
```bash
# app/Livewire/Payments/CreatePayment.php

□ save()
  - Create Payment Record
  - Update Customer Balance (Observer)
  - Update Sale Balance (if linked)
```

---

## PHASE 7: Expense Management (Day 21)
**Time:** 4-6 hours

### Step 33: Expense Components
```bash
□ php artisan make:livewire Expenses/ListExpenses
□ php artisan make:livewire Expenses/CreateExpense
```

### Step 34: Expense Views
```bash
□ Simple Form: Category, Amount, Description, Date, Receipt
□ List View: Filter by Category/Date
```

---

## PHASE 8: Dashboard & Reports (Days 22-25)
**Time:** 16-20 hours

### Step 35: Create Dashboard Component
```bash
□ php artisan make:livewire Dashboard
```

### Step 36: Build Stats Cards
```bash
□ DaisyUI Stats Component (Vertical/Horizontal)
  - Total Sales (Today/Month)
  - Total Profit
  - Cash in Hand
  - Outstanding Credit
```

### Step 37: Add Charts
```bash
□ Install Chart.js or ApexCharts (via npm or CDN)
□ Create Blade Component for Charts
□ Pass data from Livewire to Chart (via Alpine.js x-data)
```

### Step 38: Build "Low Stock" & "Top Products" Tables
```bash
□ Reusable Table Components for Dashboard widgets
```

---

## PHASE 9: Polish & Refinement (Days 26-28)
**Time:** 10-15 hours

### Step 39: UI Polish
```bash
□ Ensure Responsive Design (Mobile/Tablet)
□ Add Loading States (wire:loading) to buttons
□ Add Confirm Modals (DaisyUI Modal) for Deletes
□ Improve Empty States (Illustrations)
```

### Step 40: Manual Testing & Optimization
```bash
□ Test entire flow on Mobile
□ Check N+1 Queries (Debugbar)
□ Optimize Asset Loading
```

---

## PHASE 10: Testing & Deployment (Days 29-30)
**Time:** 8-10 hours

### Step 41: Final Testing
*(Same extensive testing as generic checklist)*
□ Financial Accuracy
□ Inventory sync
□ Credit limits

### Step 42: Deployment
□ Set up Production Env
□ Build Assets (npm run build)
□ Optimize Config/Routes
```