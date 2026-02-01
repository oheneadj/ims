# User Stories
## Jewelry Inventory Management System

---

## Epic 1: Inventory Management

### US-1.1: Add New Jewelry Item
**As a** jewelry business owner  
**I want to** add new jewelry items to my inventory  
**So that** I can track what products I have available for sale

**Acceptance Criteria:**
- I can enter item name, type, and material
- I can set cost price and selling price
- System automatically calculates profit margin
- I can add a quantity
- I can optionally upload a photo
- I can optionally add SKU/item code
- System validates all required fields
- Item appears in product list immediately after saving

**Priority:** HIGH  
**Story Points:** 3

---

### US-1.2: View Inventory List
**As a** jewelry business owner  
**I want to** see all my jewelry items in a list  
**So that** I can quickly check what's in stock

**Acceptance Criteria:**
- I can see all products with name, type, stock quantity, prices
- I can see profit margin for each item
- Low stock items (≤5) are highlighted in red
- I can search by name or SKU
- I can filter by type or material
- List shows current stock quantity for each item
- I can click on item to see full details

**Priority:** HIGH  
**Story Points:** 3

---

### US-1.3: Update Stock Levels
**As a** jewelry business owner  
**I want to** add stock when I purchase new inventory  
**So that** my stock levels stay accurate

**Acceptance Criteria:**
- I can increase stock quantity for any product
- I can enter the cost of the new stock
- System logs the stock increase with date and time
- Updated quantity shows immediately
- I can add notes about the purchase

**Priority:** HIGH  
**Story Points:** 2

---

### US-1.4: Get Low Stock Alerts
**As a** jewelry business owner  
**I want to** be notified when items are running low  
**So that** I can restock before running out

**Acceptance Criteria:**
- Dashboard shows count of low stock items
- Low stock threshold is 5 items or fewer
- I can click alert to see which items need restocking
- Low stock items are visually distinct in product list

**Priority:** MEDIUM  
**Story Points:** 2

---

## Epic 2: Customer Management

### US-2.1: Add New Customer
**As a** jewelry business owner  
**I want to** add customer information to the system  
**So that** I can track their purchases and credit status

**Acceptance Criteria:**
- I can enter customer name (required)
- I can enter phone, email, address (optional)
- I can mark customer as "credit customer"
- I can set a credit limit for credit customers
- System initializes balance at ₦0
- Customer appears in customer list immediately

**Priority:** HIGH  
**Story Points:** 2

---

### US-2.2: Set Credit Limits
**As a** jewelry business owner  
**I want to** set credit limits for customers  
**So that** I control how much they can owe me

**Acceptance Criteria:**
- I can set credit limit when creating customer
- I can update credit limit anytime
- System prevents sales that exceed available credit
- I can see "available credit" for each customer (limit - current balance)

**Priority:** HIGH  
**Story Points:** 2

---

### US-2.3: View Customer Details
**As a** jewelry business owner  
**I want to** see detailed customer information  
**So that** I can review their purchase and payment history

**Acceptance Criteria:**
- I can see customer contact information
- I can see current outstanding balance
- I can see credit limit and available credit
- I can see list of all purchases with dates and amounts
- I can see list of all payments with dates and amounts
- I can see total amount purchased (lifetime)

**Priority:** MEDIUM  
**Story Points:** 3

---

### US-2.4: Identify Overdue Customers
**As a** jewelry business owner  
**I want to** see which customers have overdue payments  
**So that** I can follow up for payment

**Acceptance Criteria:**
- Dashboard shows count of customers with overdue balances
- I can view list of customers with outstanding balances
- List shows customer name, amount owed, and days overdue
- I can sort by amount owed or days overdue

**Priority:** MEDIUM  
**Story Points:** 3

---

## Epic 3: Sales Management

### US-3.1: Record Cash Sale
**As a** jewelry business owner  
**I want to** record a cash sale quickly  
**So that** I can track revenue and reduce inventory

**Acceptance Criteria:**
- I can select products to sell
- I can specify quantity for each product
- System shows total amount and profit
- System validates sufficient stock
- I can complete sale as "paid in full"
- Stock quantities reduce automatically
- Sale appears in sales history immediately
- System doesn't require customer selection for cash sales

**Priority:** HIGH  
**Story Points:** 5

---

### US-3.2: Record Credit Sale
**As a** jewelry business owner  
**I want to** record sales on credit  
**So that** I can let trusted customers pay later

**Acceptance Criteria:**
- I must select a customer marked as credit customer
- I can select products and quantities
- System validates available credit
- System prevents sale if it exceeds credit limit
- Stock reduces automatically
- Customer balance increases by sale amount
- Sale marked as "credit" with balance due
- I can see warning if customer already has outstanding balance

**Priority:** HIGH  
**Story Points:** 5

---

### US-3.3: Record Partial Payment Sale
**As a** jewelry business owner  
**I want to** allow customers to pay part now and part later  
**So that** I can be flexible with payments

**Acceptance Criteria:**
- I can select customer
- I can select products and quantities
- I can enter amount paid (less than total)
- System calculates balance due
- Customer balance increases by balance due (not total amount)
- Sale marked as "partial payment"
- Stock reduces automatically

**Priority:** MEDIUM  
**Story Points:** 3

---

### US-3.4: View Sales History
**As a** jewelry business owner  
**I want to** see all past sales  
**So that** I can review business activity

**Acceptance Criteria:**
- I can see list of all sales with date, customer, amount, status
- I can filter by payment status (paid/partial/credit)
- I can filter by date range
- I can filter by customer
- I can search by sale ID
- I can click sale to see full details including items sold
- I can see profit for each sale

**Priority:** MEDIUM  
**Story Points:** 3

---

### US-3.5: Apply Discounts
**As a** jewelry business owner  
**I want to** give discounts on items  
**So that** I can offer special prices to customers

**Acceptance Criteria:**
- I can modify selling price when adding item to sale
- System shows original price and discounted price
- Profit calculation uses discounted price
- System prevents selling below cost (warning only, not blocked)

**Priority:** LOW  
**Story Points:** 2

---

## Epic 4: Payment Management

### US-4.1: Collect Credit Payment
**As a** jewelry business owner  
**I want to** record when credit customers make payments  
**So that** their balances stay accurate

**Acceptance Criteria:**
- I can select customer
- I can see current outstanding balance
- I can enter payment amount
- System validates payment ≤ outstanding balance
- Customer balance reduces by payment amount
- Payment appears in customer's payment history
- If sale is fully paid, status updates to "paid"

**Priority:** HIGH  
**Story Points:** 3

---

### US-4.2: View Payment History
**As a** jewelry business owner  
**I want to** see all payments received  
**So that** I can track cash flow

**Acceptance Criteria:**
- I can see list of all payments with date, customer, amount
- I can filter by date range
- I can filter by customer
- I can filter by payment method
- Total payments shown for selected period

**Priority:** MEDIUM  
**Story Points:** 2

---

## Epic 5: Expense Management

### US-5.1: Record Business Expense
**As a** jewelry business owner  
**I want to** record business expenses  
**So that** I can track where money is going

**Acceptance Criteria:**
- I can select expense category
- I can enter amount
- I can enter description
- I can set expense date (defaults to today)
- Expense appears in expense list immediately

**Priority:** HIGH  
**Story Points:** 2

---

### US-5.2: View Expense History
**As a** jewelry business owner  
**I want to** see all expenses  
**So that** I can understand my costs

**Acceptance Criteria:**
- I can see list of all expenses with date, category, amount
- I can filter by category
- I can filter by date range
- Total expenses shown for selected period
- I can see breakdown by category

**Priority:** MEDIUM  
**Story Points:** 2

---

## Epic 6: Dashboard & Reporting

### US-6.1: View Daily Summary
**As a** jewelry business owner  
**I want to** see today's sales summary  
**So that** I know how business is doing today

**Acceptance Criteria:**
- Dashboard defaults to "Daily" view showing today
- I can see: total sales, profit, cash collected, transaction count
- I can see top selling items today
- I can see cash sales vs credit sales breakdown
- I can see alerts for low stock or overdue payments
- All figures update when I record new sales

**Priority:** HIGH  
**Story Points:** 5

---

### US-6.2: View Weekly Summary
**As a** jewelry business owner  
**I want to** see this week's performance  
**So that** I can understand weekly trends

**Acceptance Criteria:**
- I can toggle dashboard to "Weekly" view
- I can see total sales, profit, transactions for the week
- I can see daily breakdown (which day had most sales)
- I can see week-over-week comparison
- I can navigate to previous/next week

**Priority:** HIGH  
**Story Points:** 3

---

### US-6.3: View Monthly Summary
**As a** jewelry business owner  
**I want to** see monthly business performance  
**So that** I can track progress over time

**Acceptance Criteria:**
- I can toggle dashboard to "Monthly" view
- I can see total sales, profit, transactions for the month
- I can see top 5 customers
- I can see top 5 products
- I can see total expenses
- I can see net profit (after expenses)
- I can navigate to previous/next month
- I can see month-over-month comparison

**Priority:** HIGH  
**Story Points:** 5

---

### US-6.4: View Custom Date Range
**As a** jewelry business owner  
**I want to** analyze any specific time period  
**So that** I can answer specific business questions

**Acceptance Criteria:**
- I can toggle dashboard to "Custom" view
- I can select start date and end date
- All metrics calculate for selected period
- I can see same metrics as monthly view
- I can export report for the custom period

**Priority:** MEDIUM  
**Story Points:** 3

---

### US-6.5: See Top Customers
**As a** jewelry business owner  
**I want to** know my best customers  
**So that** I can appreciate and retain them

**Acceptance Criteria:**
- Dashboard shows top 5 customers by purchase value
- I can see customer name and total purchased
- Calculation based on current time period (day/week/month)
- I can click customer to see their full details

**Priority:** MEDIUM  
**Story Points:** 2

---

### US-6.6: See Top Products
**As a** jewelry business owner  
**I want to** know what sells best  
**So that** I can stock more of popular items

**Acceptance Criteria:**
- Dashboard shows top 5 products by revenue
- I can see product name, units sold, revenue
- Calculation based on current time period
- I can click product to see details

**Priority:** MEDIUM  
**Story Points:** 2

---

### US-6.7: Understand Profit vs Revenue
**As a** jewelry business owner  
**I want to** see both revenue and profit clearly  
**So that** I understand true business performance

**Acceptance Criteria:**
- Dashboard shows total sales (revenue) separately from profit
- I can see: revenue, cost of goods sold, gross profit
- I can see: gross profit, total expenses, net profit
- Profit margin percentage displayed
- Clear distinction between "money earned" and "money collected"

**Priority:** HIGH  
**Story Points:** 3

---

### US-6.8: Track Cash Flow
**As a** jewelry business owner  
**I want to** distinguish between sales and cash collected  
**So that** I know how much money I actually have

**Acceptance Criteria:**
- Dashboard shows "Cash Collected" separately from "Sales Made"
- Cash collected = cash sales + credit payments received
- I can see outstanding receivables (credit not yet paid)
- I understand: made ₦100k in sales but only collected ₦70k

**Priority:** HIGH  
**Story Points:** 3

---

### US-6.9: Export Reports
**As a** jewelry business owner  
**I want to** export reports to PDF or Excel  
**So that** I can share with accountant or keep records

**Acceptance Criteria:**
- I can export current dashboard view
- PDF includes all metrics and charts
- Excel includes detailed transaction data
- Export includes selected date range
- File downloads to my device

**Priority:** LOW  
**Story Points:** 3

---

## Epic 7: System Administration

### US-7.1: Secure Login
**As a** jewelry business owner  
**I want to** log in securely  
**So that** my business data is protected

**Acceptance Criteria:**
- I must log in with email and password
- Invalid credentials show error message
- Session expires after 2 hours of inactivity
- I can log out manually

**Priority:** HIGH  
**Story Points:** 2

---

### US-7.2: Change Password
**As a** jewelry business owner  
**I want to** change my password  
**So that** I can maintain security

**Acceptance Criteria:**
- I can access password change form
- I must enter current password
- I must enter new password twice
- New password must be at least 8 characters
- Success message shown after change

**Priority:** MEDIUM  
**Story Points:** 1

---

## Story Prioritization Summary

### Must Have (MVP - Phase 1)
- US-1.1, US-1.2, US-1.3 (Inventory basics)
- US-2.1, US-2.2 (Customer basics)
- US-3.1, US-3.2 (Cash & credit sales)
- US-4.1 (Payment collection)
- US-5.1 (Expense recording)
- US-6.1, US-6.2, US-6.3, US-6.7, US-6.8 (Core dashboards)
- US-7.1 (Security)

### Should Have (MVP - Phase 2)
- US-1.4 (Low stock alerts)
- US-2.3, US-2.4 (Customer details)
- US-3.3, US-3.4 (Partial payments, history)
- US-4.2 (Payment history)
- US-5.2 (Expense history)
- US-6.4, US-6.5, US-6.6 (Custom range, top lists)
- US-7.2 (Password change)

### Could Have (Future)
- US-3.5 (Discounts)
- US-6.9 (Export)

---

**Total Story Points (MVP):** ~60 points  
**Estimated Duration:** 3-4 weeks (assuming 15-20 points per week)