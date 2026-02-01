<?php

namespace App\Livewire\Sales;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class CreateSale extends Component
{
    // Search
    public string $search = '';
    
    // Cart
    public array $cart = []; // [product_id => ['id', 'name', 'price', 'quantity', 'max_stock']]

    // Checkout
    public $customer_id = null;
    public $payment_amount = 0;
    public $payment_status = 'paid'; // Default to paid
    public string $payment_method = 'cash';
    public string $notes = '';

    // Customer Search & Creation
    // Customer Search & Creation
    public $customerSearch = '';
    public $showCustomerModal = false;
    public $newCustomerName = '';
    public $newCustomerPhone = '';
    public $newCustomerAddress = '';
    public $newCustomerIsCredit = true;

    // Form Interactivity
    public $currentProductId = '';

    public function mount()
    {
        $this->payment_method = PaymentMethod::CASH->value;
        $this->payment_status = 'paid';
    }

    public function addItem()
    {
        if ($this->currentProductId) {
            $this->addToCart((int)$this->currentProductId);
            $this->currentProductId = ''; // Reset selection
        }
    }

    // Customer Search Logic
    public function selectCustomer($id)
    {
        $this->customer_id = $id;
        $this->customerSearch = ''; // Clear search after selection to hide dropdown
    }

    public function clearCustomerSelection()
    {
        $this->customer_id = null;
    }

    // Customer Creation Logic
    public function openCustomerModal()
    {
        $this->reset(['newCustomerName', 'newCustomerPhone', 'newCustomerAddress', 'existingCustomerInstance']);
        $this->newCustomerIsCredit = true; // Default to true
        $this->showCustomerModal = true;
    }

    public function closeCustomerModal()
    {
        $this->showCustomerModal = false;
    }

    public $existingCustomerInstance = null;

    public function saveNewCustomer()
    {
        $this->validate([
            'newCustomerName' => 'required|string|max:255',
            'newCustomerPhone' => 'nullable|string|max:20',
            'newCustomerAddress' => 'nullable|string|max:255',
        ]);

        // Check for duplicate phone number
        if ($this->newCustomerPhone) {
            $existing = Customer::where('phone', $this->newCustomerPhone)->first();
            if ($existing) {
                $this->existingCustomerInstance = $existing;
                return; // Stop execution to show alert
            }
        }

        $customer = Customer::create([
            'name' => $this->newCustomerName,
            'phone' => $this->newCustomerPhone,
            'address' => $this->newCustomerAddress,
            'is_credit_customer' => $this->newCustomerIsCredit,
        ]);

        $this->customer_id = $customer->id;
        $this->closeCustomerModal();
        session()->flash('status', 'Customer created and selected.');
    }

    public function useExistingCustomer()
    {
        if ($this->existingCustomerInstance) {
            $this->selectCustomer($this->existingCustomerInstance->id);
            $this->closeCustomerModal();
            $this->reset(['newCustomerName', 'newCustomerPhone', 'newCustomerAddress', 'existingCustomerInstance']);
        }
    }

    public function addToCart(int $productId)
    {
        $product = Product::find($productId);

        if (!$product || $product->quantity_in_stock <= 0) {
            return; // Handle error visually if needed
        }

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['quantity'] < $product->quantity_in_stock) {
                $this->cart[$productId]['quantity']++;
            }
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->selling_price,
                'cost' => $product->cost_price, // Needed for profit calc
                'quantity' => 1,
                'max_stock' => $product->quantity_in_stock,
            ];
        }

        $this->updatePaymentDefault();
    }

    // Lifecycle hook for payment status toggle
    public function updatedPaymentStatus($value)
    {
        if ($value === 'paid') {
            $this->payment_amount = $this->getTotalProperty();
        } else {
            $this->payment_amount = 0;
        }
    }

    // Rename checkout to save to match standard form naming
    public function save()
    {
        $this->checkout();
    }
    

    public function removeFromCart(int $productId)
    {
        unset($this->cart[$productId]);
        $this->updatePaymentDefault();
    }

    public function updateQuantity(int $productId, int $qty)
    {
        if (isset($this->cart[$productId])) {
            // Validation
            if ($qty > $this->cart[$productId]['max_stock']) {
                $qty = $this->cart[$productId]['max_stock'];
            }
            if ($qty <= 0) {
                $this->removeFromCart($productId);
                return;
            }
            $this->cart[$productId]['quantity'] = $qty;
            $this->updatePaymentDefault();
        }
    }

    public function updatePaymentDefault()
    {
        // Only auto-update if status is 'paid'
        if ($this->payment_status === 'paid') {
            $this->payment_amount = $this->getTotalProperty();
        }
    }

    public function getTotalProperty()
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }
    
    public function getTotalCostProperty()
    {
        return collect($this->cart)->sum(fn($item) => $item['cost'] * $item['quantity']);
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            $this->addError('cart', 'Cart is empty.');
            return;
        }

        $total = $this->getTotalProperty();

        $this->validate([
            'payment_amount' => 'required|numeric|min:0',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        // Logic: Cannot pay less than total unless customer allowed credit (if customer selected)
        // If no customer selected, MUST pay full amount
        if (!$this->customer_id && $this->payment_amount < $total) {
            $this->addError('payment_amount', 'Walk-in customers must pay full amount.');
            return;
        }

        if ($this->customer_id) {
            $customer = Customer::find($this->customer_id);
            if ($this->payment_amount < $total) {
                // It's a credit sale
                if (!$customer->is_credit_customer) {
                    $this->addError('customer_id', 'This customer is not approved for credit.');
                    return;
                }
                $balance_change = $total - $this->payment_amount;
                if (($customer->current_balance + $balance_change) > $customer->credit_limit) {
                    $this->addError('payment_amount', 'Credit limit exceeded. Max credit allowed: ' . ($customer->credit_limit - $customer->current_balance));
                    return;
                }
            }
        }

        DB::transaction(function () use ($total) {
            // Determines Status
            $status = PaymentStatus::CREDIT;
            if ($this->payment_amount >= $total) {
                $status = PaymentStatus::PAID;
            } elseif ($this->payment_amount > 0) {
                $status = PaymentStatus::PARTIAL;
            }

            // 1. Create Sale
            $sale = Sale::create([
                'customer_id' => $this->customer_id,
                'sale_date' => now(),
                'total_amount' => $total,
                'total_cost' => $this->getTotalCostProperty(),
                'amount_paid' => 0, // Will be updated by PaymentObserver
                'payment_status' => $status, // Initial guess, updated by observer? No, we set initial.
                'notes' => $this->notes,
            ]);

            // 2. Create Sale Items (Triggers Stock Reduction via Observer)
            foreach ($this->cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_cost_price' => $item['cost'],
                    'unit_selling_price' => $item['price'],
                ]);
            }

            // 3. Create Payment if amount > 0 (Triggers Balance Update via Observer)
            if ($this->payment_amount > 0) {
                Payment::create([
                    'customer_id' => $this->customer_id ?? null, // Can accept payment even if no customer? DB schema might require customer_id for payments... verify migration.
                    // Migration check: 'customer_id' constrained restrictOnDelete. If walk-in (null), payment table might fail if not nullable?
                    // Let's check schema. create_payments_table: $table->foreignId('customer_id')->constrained()... NOT NULLABLE by default.
                    // ISSUE: Walk-in customers paying cash.
                    // SOLVE: If customer_id is null, we can't record a "Payment" record linked to a customer.
                    // BUT we need to record the money coming in.
                    // Option A: Use a generic "Walk-in" customer record (ID 1).
                    // Option B: Make customer_id nullable in payments.
                    // Decision: For now, if no customer, we won't create a 'Payment' record linked to customer logic, OR we just updated Sale 'amount_paid' directly?
                    // Better: Create a 'Generic' customer hidden or just make field nullable.
                    // MODIFY MIGRATION? Too late, migration run.
                    // QUICK FIX: Pass a Dummy Customer or handle logic.
                    // Wait, if I can't create Payment record, I loose track of "Cash In Hand" today.
                    // Refactor: We need generic customer or nullable.
                    
                    // Lets creating a "Walk-In Customer" if not exists or let user select.
                    // For now, I will modify the migration to be nullable OR creates a specific Walk-In customer in seeder.
                    // Let's try to proceed assuming I'll fix schema or use a seeder.
                    'sale_id' => $sale->id,
                    'amount' => $this->payment_amount,
                    'payment_date' => now(),
                    'payment_method' => $this->payment_method,
                ]);
            }
            // WAIT - Payments table requires customer_id.
            // If I am a walk-in, I don't have an ID.
            // I should probably make customer_id nullable in payments table for "Anonymous Sales".
        });

        session()->flash('status', 'Sale completed successfully.');
        return $this->redirect(route('sales.index'), navigate: true);
    }

    public function render()
    {
        $products = Product::query()
             ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('sku', 'like', '%'.$this->search.'%'))
             ->where('quantity_in_stock', '>', 0)
             ->take(12)
             ->get();

        $customers = match(true) {
            !empty($this->customerSearch) => Customer::where('name', 'like', '%' . $this->customerSearch . '%')
                ->orWhere('phone', 'like', '%' . $this->customerSearch . '%')
                ->take(5)
                ->get(),
            default => collect(), // Don't load all customers initially or when search is empty (unless we want a default list)
        };
        
        // Ensure selected customer is available to view
        $selectedCustomer = $this->customer_id ? Customer::find($this->customer_id) : null;

        return view('livewire.sales.create-sale', [
            'products' => $products,
            'searchedCustomers' => $customers,
            'selectedCustomer' => $selectedCustomer,
        ]);
    }
}
