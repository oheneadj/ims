# Software Requirements Specification
## Jewelry Inventory Management System

**Version:** 1.0  
**Date:** January 29, 2026  
**Author:** Product Team  
**Project:** Jewelry Inventory & Sales Management System

---

## 1. Introduction

### 1.1 Purpose
This document specifies the functional and non-functional requirements for a web-based jewelry inventory and sales management system built with FilamentPHP and Laravel.

### 1.2 Scope
The system will enable a jewelry business owner to:
- Track individual jewelry items with cost and selling prices
- Manage customer information and credit accounts
- Record sales transactions (cash and credit)
- Track payments and outstanding balances
- Monitor business expenses
- Generate profit reports with multiple time views (daily, weekly, monthly, custom)
- Maintain complete business financial records

### 1.3 Target Users
- **Primary User:** Jewelry business owner
- **Secondary Users:** Future staff members (Phase 2)

### 1.4 Business Context
- **Business Type:** Single location jewelry retail business
- **Products:** Physical jewelry items only (no services)
- **Initial Inventory:** Approximately 20 items
- **Sales Model:** Mix of immediate payment (cash) and deferred payment (credit)
- **Currency:** Ghanaian Cedi (GH₵)
- **Tracking Level:** Individual item tracking (not batch/bulk)
- **Location:** Single warehouse/location

---

## 2. Functional Requirements

### 2.1 Product/Inventory Management

#### 2.1.1 Product Information Storage

**REQ-INV-001: Product Data Fields**  
The system shall store the following information for each jewelry item:

| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| Name | String | Yes | Max 255 chars | Product name (e.g., "Gold Heart Necklace") |
| Type | Enum | Yes | See list below | Category of jewelry |
| Material | Enum | No | See list below | Primary material |
| Description | Text | No | Max 500 chars | Detailed description |
| SKU/Item Code | String | No | Max 50 chars, Unique | Stock Keeping Unit |
| Cost Price | Decimal | Yes | 2 decimal places, ≥ 0 | Purchase/production cost |
| Selling Price | Decimal | Yes | 2 decimal places, ≥ 0 | Retail price |
| Quantity in Stock | Integer | Yes | ≥ 0, Default 0 | Current inventory count |
| Photo | File | No | Image format | Product image |

**Jewelry Types:**
- Necklace
- Earrings
- Bracelet
- Ring
- Anklet
- Pendant
- Chain
- Set (multiple pieces)
- Other

**Materials:**
- Gold
- Gold Plated
- Silver
- Sterling Silver
- Stainless Steel
- Beads
- Crystal
- Costume/Fashion Jewelry
- Other

**REQ-INV-002: Automatic Profit Calculations**  
The system shall automatically calculate and display:
- **Profit per piece** = Selling Price - Cost Price
- **Profit margin** = (Profit per piece / Selling Price) × 100

**REQ-INV-003: Stock Quantity Validation**  
The system shall prevent stock quantity from becoming negative at any time.

**REQ-INV-004: Low Stock Identification**  
The system shall automatically flag items as "Low Stock" when quantity in stock ≤ 5 units.

**REQ-INV-005: Product Filtering**  
The system shall allow filtering products by:
- Type (necklace, earrings, etc.)
- Material (gold, silver, etc.)
- Low stock status (yes/no)

**REQ-INV-006: Product Search**  
The system shall allow searching products by:
- Product name (partial match)
- SKU/Item code (exact or partial match)

**REQ-INV-007: Photo Management**  
The system shall support:
- Uploading product photos (JPEG, PNG formats)
- Displaying product photos in list and detail views
- Removing/replacing product photos

**REQ-INV-008: Inventory Valuation**  
The system shall calculate total inventory value as:
- **Total Inventory Value (Cost Basis)** = Σ(Cost Price × Quantity in Stock) for all products
- **Total Inventory Value (Retail Basis)** = Σ(Selling Price × Quantity in Stock) for all products

#### 2.1.2 Stock Movement Management

**REQ-INV-009: Stock Movement Logging**  
The system shall log all stock movements with:
- Product reference
- Movement type (purchase, sale, adjustment)
- Quantity changed (positive for increases, negative for decreases)
- Date and time
- Reference/notes (e.g., "Sale #123", "Initial stock", "Correction")

**REQ-INV-010: Automatic Stock Reduction**  
The system shall automatically reduce stock quantity when a sale is completed.

**REQ-INV-011: Manual Stock Adjustments**  
The system shall allow manual stock quantity adjustments with:
- Reason/notes required
- Adjustment type (increase or decrease)
- Audit trail in stock movements

---

### 2.2 Customer Management

**REQ-CUST-001: Customer Information Storage**  
The system shall store the following customer information:

| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| Name | String | Yes | Max 255 chars | Customer full name |
| Phone | String | No | Max 20 chars | Contact number |
| Email | String | No | Valid email format | Email address |
| Address | Text | No | - | Physical address |
| Is Credit Customer | Boolean | Yes | Default: false | Credit privileges flag |
| Credit Limit | Decimal | Yes | ≥ 0, Default: 0 | Maximum credit allowed |
| Current Balance | Decimal | Yes | Auto-calculated | Amount currently owed |
| Notes | Text | No | - | Additional information |

**REQ-CUST-002: Customer Balance Auto-Calculation**  
The system shall automatically calculate and maintain customer current balance as:
- **Current Balance** = Total Credit Sales - Total Payments Received

**REQ-CUST-003: Credit Limit Enforcement**  
The system shall prevent credit sales that would cause the customer's balance to exceed their credit limit.

**REQ-CUST-004: Purchase History Display**  
The system shall display complete customer purchase history including:
- Sale date
- Sale ID/reference
- Items purchased
- Total amount
- Payment status
- Amount paid
- Balance remaining

**REQ-CUST-005: Payment History Display**  
The system shall display complete customer payment history including:
- Payment date
- Amount paid
- Payment method
- Associated sale (if applicable)
- Notes

**REQ-CUST-006: Overdue Balance Identification**  
The system shall identify and flag customers with:
- Outstanding balances (current_balance > 0)
- Overdue payments (configurable days threshold)

**REQ-CUST-007: Walk-in Customer Option**  
The system shall allow sales to be recorded without a customer record (walk-in customers) for cash transactions.

---

### 2.3 Sales Management

#### 2.3.1 Sales Transaction Recording

**REQ-SALE-001: Sales Transaction Data**  
The system shall record sales with the following information:

| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| Customer | Reference | Conditional | Required for credit | Link to customer record |
| Sale Date | Date | Yes | Default: today | Transaction date |
| Items | List | Yes | Minimum 1 | Products sold |
| Total Amount | Decimal | Yes | Auto-calculated | Sum of all item subtotals |
| Total Cost | Decimal | Yes | Auto-calculated | Sum of all item costs |
| Total Profit | Decimal | Yes | Auto-calculated | Total Amount - Total Cost |
| Payment Status | Enum | Yes | paid/partial/credit | Payment state |
| Amount Paid | Decimal | Yes | 0 to Total Amount | Payment received |
| Balance Due | Decimal | Yes | Auto-calculated | Total Amount - Amount Paid |
| Notes | Text | No | - | Additional information |

**REQ-SALE-002: Sale Item Details**  
For each item in a sale, the system shall record:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| Product | Reference | Yes | Link to product |
| Quantity | Integer | Yes | Number of units sold |
| Unit Cost Price | Decimal | Yes | Cost at time of sale (from product) |
| Unit Selling Price | Decimal | Yes | Price at time of sale (editable for discounts) |
| Subtotal | Decimal | Yes | Quantity × Unit Selling Price |
| Profit | Decimal | Yes | Quantity × (Unit Selling Price - Unit Cost Price) |

**REQ-SALE-003: Sale Total Calculations**  
The system shall automatically calculate:
- **Total Amount** = Σ(Item Subtotals)
- **Total Cost** = Σ(Item Quantity × Unit Cost Price)
- **Total Profit** = Total Amount - Total Cost
- **Balance Due** = Total Amount - Amount Paid

**REQ-SALE-004: Sale Validation Rules**  
The system shall validate before completing a sale:
- Sufficient stock available for all items (Quantity ≤ Product Stock)
- Payment amount ≤ Total amount
- Credit sales only allowed for customers marked as credit customers
- Credit sale total ≤ Customer's available credit (Credit Limit - Current Balance)
- At least one item in the sale

**REQ-SALE-005: Automatic Side Effects**  
When a sale is completed, the system shall automatically:
- Reduce product stock quantities by quantities sold
- Update customer balance (if credit or partial payment)
- Create stock movement records for audit trail
- Update sale status based on payment

#### 2.3.2 Payment Types

**REQ-SALE-006: Cash Sale (Full Payment)**  
For cash sales, the system shall:
- Set Payment Status = "paid"
- Require Amount Paid = Total Amount
- Allow sale without customer record (walk-in)
- Not affect any customer balance

**REQ-SALE-007: Credit Sale (Pay Later)**  
For credit sales, the system shall:
- Require customer to be marked as credit customer
- Set Payment Status = "credit"
- Set Amount Paid = 0
- Set Balance Due = Total Amount
- Increase customer's Current Balance by Total Amount
- Validate sale doesn't exceed available credit

**REQ-SALE-008: Partial Payment Sale**  
For partial payment sales, the system shall:
- Set Payment Status = "partial"
- Require 0 < Amount Paid < Total Amount
- Set Balance Due = Total Amount - Amount Paid
- Increase customer's Current Balance by Balance Due (not Total Amount)
- Allow customer to pay balance later

---

### 2.4 Payment Management

**REQ-PAY-001: Payment Recording**  
The system shall record payments with:

| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| Customer | Reference | Yes | Must exist | Customer making payment |
| Sale | Reference | No | Optional link | Specific sale being paid |
| Amount | Decimal | Yes | > 0 | Payment amount |
| Payment Date | Date | Yes | Default: today | Date received |
| Payment Method | Enum | No | See list | How paid |
| Notes | Text | No | - | Additional info |

**Payment Methods:**
- Cash
- Bank Transfer
- Card
- Other

**REQ-PAY-002: Payment Validation**  
The system shall validate:
- Payment amount > 0
- Payment amount ≤ Customer's current balance
- Customer exists and has outstanding balance

**REQ-PAY-003: Automatic Payment Processing**  
When a payment is recorded, the system shall automatically:
- Reduce customer's Current Balance by payment amount
- If Sale ID provided:
  - Increase sale's Amount Paid by payment amount
  - Decrease sale's Balance Due by payment amount
  - Update sale's Payment Status to "paid" if Balance Due = 0

**REQ-PAY-004: General Account Payments**  
The system shall allow payments not tied to specific sales (general account payments that reduce overall customer balance).

---

### 2.5 Expense Management

**REQ-EXP-001: Expense Recording**  
The system shall record expenses with:

| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| Category | Enum | Yes | See list | Expense type |
| Amount | Decimal | Yes | > 0 | Expense amount |
| Description | String | Yes | Max 500 chars | What was purchased |
| Expense Date | Date | Yes | Default: today | Date of expense |
| Receipt/Reference | String | No | Max 100 chars | Receipt number or reference |

**Expense Categories:**
- Equipment (mannequins, displays, etc.)
- Supplies (packaging, bags, tags, etc.)
- Inventory Restocking
- Transportation/Delivery
- Rent
- Utilities
- Marketing/Advertising
- Salaries (if applicable)
- Other

**REQ-EXP-002: Expense Filtering**  
The system shall allow filtering expenses by:
- Category
- Date range
- Description (search)

**REQ-EXP-003: Expense Totals**  
The system shall calculate total expenses for any selected period.

---

### 2.6 Dashboard & Reporting

#### 2.6.1 Dashboard Time Period Views

**REQ-DASH-001: Toggleable Time Periods**  
The system shall provide dashboard views for:
- **Daily** - Specific date (default: today)
- **Weekly** - 7-day period (default: current week)
- **Monthly** - Calendar month (default: current month)
- **Custom** - User-defined date range

**REQ-DASH-002: Dashboard Metrics**  
For the selected period, the dashboard shall display:

**Sales Metrics:**
- Total sales amount (revenue)
- Total profit
- Profit margin percentage
- Total cash collected (actual money received)
- Number of transactions
- Number of items sold
- Average sale value

**Outstanding Metrics:**
- Outstanding credit amount (total owed by all customers)
- Number of customers with overdue balances

**Inventory Metrics:**
- Current total inventory value (cost basis)
- Current total inventory value (retail basis)
- Number of low stock items

**Expense Metrics:**
- Total expenses for period

**Top Performers:**
- Top 5 selling products (by revenue)
- Top 5 customers (by purchase value)

**Alerts:**
- Low stock alerts (products with quantity ≤ 5)
- Overdue payment alerts (customers with outstanding balances)

#### 2.6.2 Sales Reports

**REQ-REP-001: Sales Performance Report**  
The system shall generate sales reports showing:
- Total sales revenue
- Total profit
- Profit margin percentage (profit / revenue × 100)
- Transaction count
- Items sold count
- Average sale value
- Sales by payment type breakdown (cash vs credit vs partial)
- Daily/weekly/monthly trend graphs

**REQ-REP-002: Sales Breakdown**  
The system shall display sales breakdown by:
- Individual product (quantity sold, revenue, profit)
- Individual customer (number of purchases, total spent)
- Date (daily sales totals)
- Payment status (paid, partial, credit)

#### 2.6.3 Financial Reports

**REQ-REP-003: Financial Calculations**  
The system shall calculate:
- **Gross Profit** = Total Sales - Cost of Goods Sold
- **Net Profit** = Gross Profit - Total Expenses
- **Profit Margin** = (Net Profit / Total Sales) × 100

**REQ-REP-004: Cash Flow Distinction**  
The system shall clearly distinguish:
- **Revenue Earned** = Total sales (including credit sales)
- **Cash Collected** = Actual money received (cash sales + credit payments)
- **Outstanding Receivables** = Revenue earned but not yet collected

Example: Made ₦100,000 in sales but ₦30,000 is on credit = Only ₦70,000 cash collected

#### 2.6.4 Inventory Reports

**REQ-REP-005: Inventory Reports**  
The system shall provide:
- Current stock levels by product
- Inventory value (cost basis and retail basis)
- Stock movement history with filters
- Low stock items list
- Products with no recent sales (slow movers)

#### 2.6.5 Customer Reports

**REQ-REP-006: Customer Analytics**  
The system shall provide:
- Total number of customers
- Repeat customers vs new customers (for period)
- Customer purchase history
- Outstanding balances by customer (sorted by amount)
- Payment reliability metrics

#### 2.6.6 Export Functionality

**REQ-REP-007: Report Export**  
The system shall allow exporting reports to:
- PDF format (formatted for printing)
- Excel/CSV format (for further analysis)

---

## 3. Non-Functional Requirements

### 3.1 Performance Requirements

**REQ-PERF-001: Page Load Time**  
Dashboard shall load within 2 seconds on standard broadband connection (5 Mbps+).

**REQ-PERF-002: Product Capacity**  
The system shall support at least 1,000 products without performance degradation.

**REQ-PERF-003: Transaction Capacity**  
The system shall support at least 10,000 sales records without performance degradation.

**REQ-PERF-004: Report Generation**  
Reports shall generate within 5 seconds for date ranges up to 1 year.

**REQ-PERF-005: Database Queries**  
Database queries shall execute in under 100ms on average.

### 3.2 Usability Requirements

**REQ-USE-001: Responsive Design**  
The interface shall be fully functional and visually appropriate on:
- Desktop (1920×1080 and above)
- Tablet (768×1024)
- Mobile (375×667 and above)

**REQ-USE-002: Form Validation**  
Forms shall provide clear, specific validation messages indicating:
- Which fields have errors
- What the error is
- How to correct it

**REQ-USE-003: Confirmation Dialogs**  
The system shall require confirmation for:
- Deleting records
- Large payment amounts (> ₦50,000)
- Changing critical settings

**REQ-USE-004: Currency Display**  
All monetary values shall display using Ghanaian Cedi symbol (GH₵) and format (e.g., GH₵12,500.00).

**REQ-USE-005: Date Format**  
Dates shall display in DD/MM/YYYY format or user's regional preference.

**REQ-USE-006: Loading Indicators**  
The system shall show loading indicators during:
- Page transitions
- Form submissions
- Report generation

### 3.3 Security Requirements

**REQ-SEC-001: Authentication**  
The system shall require user authentication to access any functionality.

**REQ-SEC-002: Password Security**  
User passwords shall be:
- Encrypted using bcrypt or equivalent
- Minimum 8 characters
- Never displayed or transmitted in plain text

**REQ-SEC-003: Session Management**  
User sessions shall:
- Timeout after 2 hours of inactivity
- Require re-authentication after timeout
- Be invalidated on logout

**REQ-SEC-004: Audit Trail**  
The system shall log all financial transactions with:
- User who performed action
- Timestamp
- Action type (create, update, delete)
- Original and new values (for updates)

**REQ-SEC-005: Input Validation**  
All user inputs shall be validated and sanitized to prevent:
- SQL injection
- Cross-site scripting (XSS)
- Code injection

### 3.4 Data Integrity Requirements

**REQ-DATA-001: Decimal Precision**  
All financial calculations shall use exactly 2 decimal places.

**REQ-DATA-002: Soft Deletes**  
The system shall use soft deletes (retention of deleted records) for:
- Products
- Customers
- Sales
- Expenses

Hard deletes only for system administration.

**REQ-DATA-003: Referential Integrity**  
The system shall maintain referential integrity:
- Cannot delete products with associated sales
- Cannot delete customers with payments
- Orphaned records shall not exist

**REQ-DATA-004: Stock Accuracy**  
Stock quantities must always be accurate and cannot be negative.

**REQ-DATA-005: Balance Accuracy**  
Customer balances must always equal: (Total unpaid sales - Total payments received)

**REQ-DATA-006: Transaction Atomicity**  
Multi-step operations (e.g., sale with stock reduction and balance update) shall be atomic - either all succeed or all fail.

### 3.5 Reliability Requirements

**REQ-REL-001: Uptime**  
The system shall maintain 99% uptime during business hours (8 AM - 8 PM local time).

**REQ-REL-002: Concurrent Users**  
The system shall handle at least 5 concurrent users without performance degradation (Phase 2).

**REQ-REL-003: Data Backup**  
System data shall be backed up:
- Automatically every 24 hours
- Retained for at least 30 days
- Stored in off-site location

**REQ-REL-004: Backup Recovery**  
The system shall be recoverable from backup within 4 hours in case of failure.

---

## 4. Technical Constraints

### 4.1 Technology Stack

**CONSTRAINT-TECH-001: Framework**  
Must use Laravel 12.x framework

**CONSTRAINT-TECH-002: Framework**  
Must use DaisyUI for frontend styling

**CONSTRAINT-TECH-003: PHP Version**  
Must run on PHP 8.4 or higher

**CONSTRAINT-TECH-004: Database**  
Must use MySQL 8.0+ or PostgreSQL 13+

**CONSTRAINT-TECH-005: Platform**  
Must be web-based (accessible via browser)

### 4.2 Business Constraints

**CONSTRAINT-BUS-001: Currency**  
Single currency only (Ghanaian Cedi) in MVP

**CONSTRAINT-BUS-002: Location**  
Single location/warehouse only in MVP

**CONSTRAINT-BUS-003: Users**  
Single user (business owner) in MVP

**CONSTRAINT-BUS-004: Language**  
English language only in MVP

---

## 5. Assumptions

1. User has stable internet connection
2. User has basic computer/smartphone literacy
3. Business operates during standard hours (not 24/7)
4. Inventory items are unique and tracked individually
5. All sales are final (no complex return/refund process in MVP)
6. Prices are set manually (no dynamic pricing)
7. Tax calculations not required in MVP
8. No integration with external systems in MVP
9. Payment always in Nigerian Naira
10. Date/time based on server timezone (WAT - West Africa Time)

---

## 6. Out of Scope (Future Enhancements)

The following features are NOT included in MVP but may be added in future versions:

### Phase 2 Features
- Multi-user access with role-based permissions
- Email/SMS notifications
- Barcode/QR code scanning
- Advanced reporting and analytics
- Data export in multiple formats

### Phase 3 Features
- Supplier management
- Purchase order system
- Product variants (sizes, colors, etc.)
- Online customer portal
- Mobile native apps (iOS/Android)

### Phase 4 Features
- Multi-currency support
- Multiple warehouse/location management
- Integration with accounting software (QuickBooks, Xero)
- Point of Sale (POS) hardware integration
- Loyalty program management
- Advanced inventory forecasting

---

## 7. Acceptance Criteria

The system will be considered complete and acceptable when:

### Functional Completeness
1. ✅ All REQ-INV requirements implemented and tested
2. ✅ All REQ-CUST requirements implemented and tested
3. ✅ All REQ-SALE requirements implemented and tested
4. ✅ All REQ-PAY requirements implemented and tested
5. ✅ All REQ-EXP requirements implemented and tested
6. ✅ All REQ-DASH requirements implemented and tested
7. ✅ All REQ-REP requirements implemented and tested

### Quality Standards
8. ✅ All user stories completed with acceptance criteria met
9. ✅ System passes security audit
10. ✅ Performance benchmarks achieved
11. ✅ Zero critical bugs
12. ✅ Less than 5 minor bugs

### Documentation
13. ✅ User manual completed
14. ✅ Technical documentation completed
15. ✅ API documentation completed (if applicable)

### Deployment
16. ✅ Successfully deployed to production
17. ✅ Backup system operational
18. ✅ Monitoring configured

### User Readiness
19. ✅ User training completed
20. ✅ User can perform all basic operations independently
21. ✅ User feedback collected and addressed

---

## 8. Success Metrics

The system will be considered successful when:

1. **Adoption:** User actively uses system daily for at least 30 days
2. **Accuracy:** Financial calculations verified 100% accurate
3. **Efficiency:** User reports time savings vs manual methods
4. **Reliability:** Zero data loss incidents
5. **Satisfaction:** User satisfaction rating ≥ 4/5

---

## Appendix A: Glossary

| Term | Definition |
|------|------------|
| Cost Price | The amount paid to acquire or produce a product |
| Selling Price | The amount charged to customers for a product |
| Credit Customer | Customer authorized to purchase on credit (pay later) |
| Credit Limit | Maximum amount a customer can owe at any time |
| Current Balance | Amount currently owed by a customer |
| Available Credit | Credit Limit - Current Balance |
| Cash Sale | Sale with immediate full payment |
| Credit Sale | Sale with payment deferred (pay later) |
| Partial Payment | Sale with part payment now, part later |
| Stock Movement | Any change to inventory quantity |
| Soft Delete | Marking record as deleted without removing from database |
| SKU | Stock Keeping Unit - unique product identifier |
| COGS | Cost of Goods Sold |
| Gross Profit | Revenue - COGS |
| Net Profit | Gross Profit - Expenses |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Business Owner | | | |
| Developer | | | |
| Project Manager | | | |

---

**Document Version History:**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Jan 29, 2026 | Product Team | Initial requirements document |

---

**END OF REQUIREMENTS DOCUMENT**