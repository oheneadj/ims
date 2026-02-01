# Project Implementation Todo List
> Based on `docs/build_checklist.md`, `requirements.md`, and `code_rules.md`.

## Phase 0: Logic & Config Setup
- [ ] Verify `config/database.php` and `.env` setup
- [ ] Remove `fluxUI
- [ ] Verify `app.css` has DaisyUI plugin
- [ ] Verify `app.blade.php` layout has DaisyUI structure (Drawer/Navbar)
- [ ] Create `enums.php` or `app/Enums/` for:
    - `ProductType` (Necklace, Ring, etc.)
    - `ProductMaterial` (Gold, Silver, etc.)
    - `PaymentStatus` (Paid, Partial, Credit)
    - `PaymentMethod` (Cash, Transfer, etc.)
    - `ExpenseCategory`

## Phase 1: Database Foundation (Migrations)
- [ ] Create & Run `create_products_table` (soft deletes, decimal 10,2)
- [ ] Create & Run `create_customers_table` (soft deletes, credit limit)
- [ ] Create & Run `create_sales_table` (dates, totals, status)
- [ ] Create & Run `create_sale_items_table` (FKs, snapshots of price/cost)
- [ ] Create & Run `create_payments_table` (polymorphic or direct booking?)
- [ ] Create & Run `create_expenses_table`
- [ ] Create & Run `create_stock_movements_table`

## Phase 2: Models & Relationships
- [ ] `Product.php`: Fillable, Casts, Relations (`saleItems`, `stockMovements`), Accessors (`profit`)
- [ ] `Customer.php`: Fillable, Casts, Relations (`sales`, `payments`), Accessors (`available_credit`)
- [ ] `Sale.php`: Fillable, Casts, Relations (`customer`, `items`, `payments`)
- [ ] `SaleItem.php`: Fillable, Casts, Relations (`sale`, `product`)
- [ ] `Payment.php`: Fillable, Casts, Relations (`customer`, `sale`)
- [ ] `Expense.php`: Fillable, Casts
- [ ] `StockMovement.php`: Fillable, Relations (`product`)

## Phase 3: Product Management (Livewire + DaisyUI)
- [ ] Component: `App\Livewire\Products\ListProducts`
    - [ ] View: Table with photo, name, price, stock badges
    - [ ] Logic: Search, Pagination
- [ ] Component: `App\Livewire\Products\CreateProduct`
    - [ ] View: Form with FileUpload, Selects
    - [ ] Logic: Validation, Image Processing
- [ ] Component: `App\Livewire\Products\EditProduct`
- [ ] Component: `App\Livewire\Products\ShowProduct` (Optional for MVP?)

## Phase 4: Customer Management
- [ ] Component: `App\Livewire\Customers\ListCustomers`
    - [ ] View: List with "Credit Allowed" badge
- [ ] Component: `App\Livewire\Customers\CreateCustomer`
    - [ ] View: Toggle for "Is Credit Customer", Credit Limit input
- [ ] Component: `App\Livewire\Customers\EditCustomer`
- [ ] Component: `App\Livewire\Customers\ShowCustomer`
    - [ ] View: Tabs for "Purchase History" and "Payments"

## Phase 5: Sales System (POS) - **CRITICAL**
- [ ] Component: `App\Livewire\Sales\CreateSale` (POS Interface)
    - [ ] Layout: Split screen (Product Grid vs Cart/Checkout)
    - [ ] Logic: Cart state management (Alpine.js or Livewire arrays)
    - [ ] Logic: Real-time Stock validation
    - [ ] Logic: Credit Limit validation
    - [ ] Logic: Transactional saving (Sale + Items + Stock + Payment)
- [ ] Component: `App\Livewire\Sales\ListSales`
- [ ] Observer: `SaleObserver` (Handle stock reduction, Customer balance updates)

## Phase 6: Payment Management
- [ ] Component: `App\Livewire\Payments\ListPayments`
- [ ] Component: `App\Livewire\Payments\CreatePayment`
    - [ ] Logic: Update Customer balance, auto-close partial sales
- [ ] Observer: `PaymentObserver` (Handle balance updates)

## Phase 7: Expense Management
- [ ] Component: `App\Livewire\Expenses\ListExpenses`
- [ ] Component: `App\Livewire\Expenses\CreateExpense`

## Phase 8: Dashboard & Reporting
- [ ] Component: `App\Livewire\Dashboard`
    - [ ] View use https://nexus.daisyui.com/dashboards/ecommerce as a guide  for designing dashbaord
    - [ ] View: Stats Cards (Revenue, Profit, Cash, Credit)
    - [ ] View: Charts (Sales trend)
    - [ ] View: Top Products / Top Customers tables
    - [ ] Logic: Date range filters

## Phase 9: Polish & Tests
- [ ] Add Toaster Magic for notifications
- [ ] Apply "Premium Design" (Glassmorphism, Animations)
- [ ] Feature Tests for Financial Accuracy
