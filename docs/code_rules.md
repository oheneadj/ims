# AI Coding Assistant Rules
## Jewelry Inventory Management System

**Purpose:** This document provides strict guidelines for AI coding assistants (Cursor, GitHub Copilot, Antigravity, etc.) when working on this project.

---


Generall

USE KISS, DRY and YAGNI

Create components for front end to make it resuable.



## 1. Technology Stack - NEVER DEVIATE

### Core Technologies (STRICT)
```yaml
Framework: Laravel 12.x
PHP Version: 8.4+ (Recommended: 8.4)
Database: MySQL 8.0+ OR PostgreSQL 16+
Frontend: Filament v4.x
CSS: Tailwind CSS (via Filament)
Node.js: 20.x LTS or 22.x
Currency: Ghanaian Cedi (GH₵)
Currency Code: GHS
```

### Forbidden Technologies
❌ DO NOT suggest or use:
- Vue.js, React (unless explicitly requested for specific isolated components)
- Bootstrap, Foundation, or other CSS frameworks
- jQuery
- Laravel versions below 11
- Custom authentication (use Laravel's built-in auth scaffolding or Jetstream/Breeze if present)

---

## 2. Code Structure Rules

### File Organization (STRICT)
```
app/
├── Livewire/               # ALL UI Components go here
│   ├── Products/           # Product related components
│   ├── Sales/             # Sales related components
│   └── Dashboard/         # Dashboard widgets/components
├── Models/                 # Eloquent models ONLY
├── Observers/              # Side effects (stock updates, etc.)
├── Enums/                  # For status types, categories
├── Services/               # Business logic if complex
└── View/Components/        # Reusable Blade components
```

### Naming Conventions (REQUIRED)
```php
// Models: Singular, PascalCase
Product.php, Customer.php, Sale.php

// Livewire Components: Action + Resource
CreateProduct.php, EditCustomer.php, ListSales.php

// Migrations: descriptive, snake_case
2026_01_26_create_products_table.php

// Database tables: plural, snake_case
products, customers, sales, sale_items

// Database columns: snake_case
cost_price, selling_price, quantity_in_stock

// Methods: camelCase
calculateProfit(), updateStock()

// Variables: camelCase
$totalAmount, $currentBalance
```

---

## 3. Laravel Best Practices (MANDATORY)

### Enums
DOnt use enum columns in migration rather create an enum class and use that throught the app.

### Models
```php
// ✅ CORRECT
class Product extends Model
{
    use SoftDeletes; // ALWAYS use soft deletes
    
    protected $fillable = [...]; // ALWAYS define fillable
    
    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ]; // ALWAYS cast decimals
    
    // Use accessors for calculated fields
    public function getProfitAttribute(): float
    {
        return $this->selling_price - $this->cost_price;
    }
    
    // Define relationships clearly
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}

// ❌ WRONG
class Product extends Model
{
    // No soft deletes
    // No fillable
    // No casts
    // Calculations in controller
}
```

### Migrations
```php
// ✅ CORRECT
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->decimal('cost_price', 10, 2); // ALWAYS 10,2 for money
    $table->decimal('selling_price', 10, 2);
    $table->timestamps(); // ALWAYS
    $table->softDeletes(); // ALWAYS
});

// Foreign keys MUST have proper constraints
$table->foreignId('customer_id')
      ->nullable()
      ->constrained()
      ->nullOnDelete(); // or cascadeOnDelete()

// ❌ WRONG
$table->float('cost_price'); // NEVER use float for money
$table->integer('customer_id'); // Missing foreign key constraint
// No timestamps or soft deletes
```

---

## 4. Livewire & Daisy UI Specific Rules

### Component Structure (REQUIRED)
```php
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

class CreateProduct extends Component
{
    #[Rule('required|min:3')]
    public $name = '';

    #[Rule('required|numeric|min:0')]
    public $cost_price = '';

    public function save()
    {
        $this->validate();
        
        Product::create($this->all());
        
        session()->flash('status', 'Product created successfully.');
        
        return $this->redirect('/products');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.products.create-product');
    }
}
```

### Form Components (USE DAISY UI)
```html
<!-- ✅ CORRECT - Daisy UI Components -->
<div class="form-control w-full max-w-xs">
  <label class="label">
    <span class="label-text">Product Name</span>
  </label>
  <input wire:model="name" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xs" />
  @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
</div>

<div class="form-control">
  <label class="label">
    <span class="label-text">Category</span>
  </label>
  <select wire:model="category" class="select select-bordered">
    <option disabled selected>Pick one</option>
    <option>Necklaces</option>
    <option>Rings</option>
  </select>
</div>

<!-- ❌ WRONG - Raw unstyled HTML -->
<input type="text" name="name">
```

### Tables (USE DAISY UI)
```html
<!-- ✅ CORRECT - Daisy UI Table -->
<div class="overflow-x-auto">
  <table class="table table-zebra">
    <!-- head -->
    <thead>
      <tr>
        <th>Name</th>
        <th>Price (GH₵)</th>
        <th>Stock</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $product)
      <tr>
        <td>{{ $product->name }}</td>
        <td>GH₵{{ number_format($product->selling_price, 2) }}</td>
        <td>{{ $product->quantity_in_stock }}</td>
        <td>
           <button class="btn btn-sm btn-ghost">Edit</button>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
```

---

## 5. Business Logic Rules

### Stock Management (CRITICAL)
```php
// ✅ CORRECT - Use Observer
class SaleObserver
{
    public function created(Sale $sale): void
    {
        foreach ($sale->items as $item) {
            $item->product->decrement('quantity_in_stock', $item->quantity);
            
            StockMovement::create([
                'product_id' => $item->product_id,
                'type' => 'sale',
                'quantity' => -$item->quantity,
                'reference' => "Sale #{$sale->id}",
            ]);
        }
    }
}

// ❌ WRONG - Manual stock updates in controller
$product->quantity_in_stock -= $quantity;
$product->save(); // Prone to errors, no logging
```

### Financial Calculations (STRICT)
```php
// ✅ CORRECT
// ALWAYS use bcmath for precision
$profit = bcsub($sellingPrice, $costPrice, 2);
$margin = bcdiv($profit, $sellingPrice, 4) * 100;

// OR use database calculations
DB::table('sales')
    ->selectRaw('SUM(total_amount - total_cost) as total_profit')
    ->first();

// ❌ WRONG
$profit = $sellingPrice - $costPrice; // Float imprecision
$margin = ($profit / $sellingPrice) * 100; // Can have rounding errors
```

### Credit Validation (REQUIRED)
```php
// ✅ CORRECT - Validate before sale
$customer = Customer::findOrFail($customerId);

if ($paymentStatus === 'credit') {
    if (!$customer->is_credit_customer) {
        throw new \Exception('Customer not approved for credit');
    }
    
    $availableCredit = $customer->credit_limit - $customer->current_balance;
    
    if ($totalAmount > $availableCredit) {
        throw new \Exception('Exceeds credit limit');
    }
}

// ❌ WRONG - No validation
Sale::create([...]) // Just create without checks
```

---

## 6. Database Query Optimization

### Eager Loading (REQUIRED)
```php
// ✅ CORRECT
$sales = Sale::with(['customer', 'items.product'])
    ->whereBetween('sale_date', [$start, $end])
    ->get();

// ❌ WRONG - N+1 queries
$sales = Sale::all();
foreach ($sales as $sale) {
    $sale->customer->name; // Separate query each time!
}
```

### Aggregations (USE DATABASE)
```php
// ✅ CORRECT
$totalSales = Sale::whereBetween('sale_date', [$start, $end])
    ->sum('total_amount');

$averageSale = Sale::whereBetween('sale_date', [$start, $end])
    ->avg('total_amount');

// ❌ WRONG - Loading all records
$sales = Sale::all();
$totalSales = $sales->sum('total_amount'); // Memory intensive!
```

---

## 7. Security Rules (NON-NEGOTIABLE)

### Input Validation
```php
// ✅ CORRECT - Use Livewire Validation
public function save()
{
    $this->validate([
        'amount' => 'required|numeric|min:0.01|regex:/^\d+(\.\d{1,2})?$/',
    ]);
}

// ❌ WRONG - Direct use without validation
$amount = $request->input('amount');
Sale::create(['total_amount' => $amount]); // SQL injection risk!
```

### Authorization
```php
// ✅ CORRECT - Use Policies with Livewire
public function delete(Product $product)
{
    $this->authorize('delete', $product);
    $product->delete();
}

// ❌ WRONG - No authorization checks
```

### SQL Injection Prevention
```php
// ✅ CORRECT - Use query builder or Eloquent
Product::where('type', $type)->get();

// ✅ CORRECT - Named bindings if raw SQL needed
DB::select('SELECT * FROM products WHERE type = :type', ['type' => $type]);

// ❌ WRONG - String concatenation
DB::select("SELECT * FROM products WHERE type = '$type'"); // NEVER!
```

---

## 8. UI/UX Rules

### Currency Display (REQUIRED)
```html
<!-- ✅ CORRECT -->
<span>GH₵{{ number_format($amount, 2) }}</span>

<!-- ❌ WRONG -->
₦{{ $amount }} <!-- Wrong currency (Nigerian Naira) -->
${{ $amount }} <!-- Wrong currency (US Dollar) -->
{{ $amount }} <!-- No formatting -->
```

### Date Formatting
```html
<!-- ✅ CORRECT -->
<span>{{ $sale_date->format('d/m/Y') }}</span>

<!-- ❌ WRONG -->
<span>{{ $sale_date }}</span>
```

### App Aesthetics (PREMIUM DESIGN)
- **Visuals**: Use vibrant colors, glassmorphism card backgrounds (`bg-base-100/50 backdrop-blur`), and subtle shadows (`shadow-lg`).
- **Interaction**: Add hover effects (`hover:scale-105 transition-transform`) to buttons and cards.
- **Typography**: Use modern sans-serif fonts. Headings should be bold and distinct.
- **Responsiveness**: Ensure ALL layouts work on mobile (`grid-cols-1 md:grid-cols-2`).

---

## 9. Testing Requirements

### What to Test (MINIMUM)
```php
// ✅ REQUIRED Tests

// Feature Tests with Livewire
test('can create product via livewire', function () {
    Livewire::test(CreateProduct::class)
        ->set('name', 'Gold Ring')
        ->set('cost_price', 1000)
        ->call('save')
        ->assertRedirect('/products');
        
    expect(Product::where('name', 'Gold Ring')->exists())->toBeTrue();
});

test('prevents credit sale exceeding limit', function () {
    $customer = Customer::factory()->create([
        'is_credit_customer' => true,
        'credit_limit' => 10000,
        'current_balance' => 8000,
    ]);
    
    // Attempt logic that should fail
});
```

---

## 10. Common Mistakes to AVOID

### ❌ NEVER DO THIS
```php
// 1. Manual foreign key columns
$table->integer('customer_id'); // Use foreignId()

// 2. Mixing query builder and Eloquent randomly
DB::table('sales')->get(); // In one place
Sale::all(); // In another
// Pick one approach and be consistent

// 3. Forgetting timestamps
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    // Missing $table->timestamps();
});

// 4. Not using transactions for multi-step operations
Sale::create([...]);
$customer->increment('current_balance', $amount);
// If second fails, first succeeds = data inconsistency

// ✅ Use transactions
DB::transaction(function () use ($saleData, $customer, $amount) {
    $sale = Sale::create($saleData);
    $customer->increment('current_balance', $amount);
});

// 5. Hardcoding values
if ($productType === 'necklace') { ... }
// Use enums or database references

// 6. Not handling edge cases
$profit = $sale->total_amount - $sale->total_cost;
// What if total_cost is NULL?

// ✅ Defensive programming
$profit = ($sale->total_amount ?? 0) - ($sale->total_cost ?? 0);

// 7. Exposing sensitive data
return Product::all(); // In API response
// ✅ Use API resources or select specific columns

// 8. Not validating stock availability
$saleItem->create(['product_id' => $id, 'quantity' => 100]);
// What if product only has 10 in stock?

// 9. Inconsistent decimal precision
$table->decimal('price', 8, 2); // In one migration
$table->decimal('amount', 10, 3); // In another
// ✅ Always use (10, 2) for money

// 10. Using arrays instead of pivot tables
$sale->items = json_encode([...]); // BAD!
// ✅ Use proper relationships
```

---

## 11. Code Review Checklist

Before submitting code, AI should verify:

- [ ] All money fields use `decimal(10, 2)`
- [ ] All models have `SoftDeletes`
- [ ] All models have `$fillable` or `$guarded`
- [ ] NO Filament references exist
- [ ] Daisy UI classes are used for styling
- [ ] Livewire components follow best practices
- [ ] Foreign keys have proper constraints
- [ ] No N+1 query problems
- [ ] Input is validated (Livewire validation)
- [ ] Business rules are enforced
- [ ] Transactions used for multi-step operations
- [ ] Currency displayed as GH₵ (Ghanaian Cedi)
- [ ] Dates formatted as DD/MM/YYYY
- [ ] No hardcoded values
- [ ] Stock updates logged
- [ ] Financial calculations use bcmath or DB aggregation

---

## 12. When Stuck - Decision Tree

```
Problem: How to implement feature X?

1. Is it a UI component?
   → YES: Use standard Livewire Component + Daisy UI
   → NO: Continue to 2

2. Is it a side effect?
   → YES: Use Observer
   → NO: Continue to 3

3. Is it complex business logic?
   → YES: Create Service class
   → NO: Put in Model method

4. Still unsure?
   → Check Livewire docs: https://livewire.laravel.com
   → Check Daisy UI docs: https://daisyui.com
   → Check Laravel docs: https://laravel.com/docs
```

---

## 13. Performance Targets

AI should optimize for:

- Dashboard load: < 2 seconds
- Database queries: < 100ms average
- N+1 queries: ZERO tolerance

---

## 14. Documentation Requirements

AI must add PHPDoc comments:

```php
/**
 * Calculate the total profit for this sale
 * 
 * @return float Total profit (selling price - cost price for all items)
 */
public function calculateTotalProfit(): float
{
    return $this->items->sum('profit');
}
```

---

## FINAL RULE

**When in doubt, favor:**
1. Standard Laravel + Livewire patterns over custom spaghetti code
2. Daisy UI utility classes over custom CSS
3. Explicit over implicit
4. Readability over brevity
5. Database constraints over application logic
6. Validation over hoping for good data
7. Transactions over fingers crossed

**Remember:** This is a financial system. Accuracy > Speed. Correctness > Features.

---

**AI Assistant Acknowledgment:**

I understand and will follow all rules in this document. I will:
- Use only approved technologies (Livewire + Daisy UI)
- Follow Laravel best practices
- Prioritize data accuracy and security
- Write defensive, well-documented code
- Test financial calculations thoroughly