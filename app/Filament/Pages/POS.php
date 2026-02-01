<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Enums\PaymentStatus;
use BackedEnum;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Livewire\Attributes\Computed;
use Filament\Support\Icons\Heroicon;

class POS extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';

    protected string $view = 'filament.resources.sales.pages.p-o-s';

    public $search = '';
    public $cart = [];
    public $customerId = null;
    public $discount = 0;
    public $amountPaid = 0;

    protected static ?string $navigationLabel = 'Point of Sale';
    protected static ?string $title = 'Jewelry POS';

    public function mount()
    {
        $this->cart = [];
    }

    #[Computed]
    public function products(): Collection
    {
        return Product::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('sku', 'like', "%{$this->search}%"))
            ->where('quantity_in_stock', '>', 0)
            ->limit(12)
            ->get();
    }

    public function updatedSearch($value)
    {
        if (strlen($value) < 2) return;

        $product = Product::query()
            ->where('sku', $value)
            ->where('quantity_in_stock', '>', 0)
            ->first();

        if ($product) {
            $this->addToCart($product->id);
            $this->search = '';
        }
    }

    #[Computed]
    public function customers(): Collection
    {
        return Customer::all();
    }

    #[Computed]
    public function subtotal()
    {
        return collect($this->cart)->sum('subtotal');
    }

    #[Computed]
    public function grandTotal()
    {
        return max(0, $this->subtotal - (float) $this->discount);
    }

    public function addToCart(int $productId)
    {
        $product = Product::find($productId);
        
        if (!$product) return;

        $cartItemIndex = collect($this->cart)->search(fn ($item) => $item['id'] === $productId);

        if ($cartItemIndex !== false) {
            if ($this->cart[$cartItemIndex]['quantity'] >= $product->quantity_in_stock) {
                 Notification::make()
                    ->title('Stock limit reached')
                    ->warning()
                    ->send();
                return;
            }
            
            $this->cart[$cartItemIndex]['quantity']++;
            $this->cart[$cartItemIndex]['subtotal'] = $this->cart[$cartItemIndex]['quantity'] * $this->cart[$cartItemIndex]['price'];
        } else {
            $this->cart[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->selling_price,
                'cost' => $product->cost_price,
                'quantity' => 1,
                'subtotal' => $product->selling_price,
                'photo' => $product->photo,
            ];
        }

        Notification::make()->title('Added to cart')->success()->send();
        $this->dispatch('added-to-cart');
    }

    public function removeFromCart(int $productId)
    {
        $this->cart = collect($this->cart)->reject(fn ($item) => $item['id'] === $productId)->values()->toArray();
    }

    public function updateQuantity(int $productId, int $quantity)
    {
         $product = Product::find($productId);
         if ($quantity > $product->quantity_in_stock) {
              Notification::make()->title('Insufficient stock')->danger()->send();
              return;
         }

         $cartItemIndex = collect($this->cart)->search(fn ($item) => $item['id'] === $productId);
         if ($cartItemIndex !== false) {
             $this->cart[$cartItemIndex]['quantity'] = $quantity;
             $this->cart[$cartItemIndex]['subtotal'] = $this->cart[$cartItemIndex]['quantity'] * $this->cart[$cartItemIndex]['price'];
         }
    }

    public function checkout()
    {
        if (empty($this->cart)) {
             Notification::make()->title('Cart is empty')->danger()->send();
             return;
        }

        if (!$this->customerId) {
             Notification::make()->title('Select a customer')->danger()->send();
             return;
        }

        $sale = Sale::create([
            'customer_id' => $this->customerId,
            'sale_date' => now(),
            'total_amount' => $this->grandTotal,
            'amount_paid' => (float) $this->amountPaid,
            'discount' => (float) $this->discount,
            'payment_status' => (float) $this->amountPaid >= $this->grandTotal ? PaymentStatus::PAID : PaymentStatus::PARTIAL,
            'total_cost' => collect($this->cart)->sum(fn($i) => $i['cost'] * $i['quantity']),
        ]);

        foreach ($this->cart as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_cost_price' => $item['cost'],
                'unit_selling_price' => $item['price'],
            ]);

            Product::query()->where('id', $item['id'])->decrement('quantity_in_stock', $item['quantity']);
        }

        // Update customer balance if not fully paid
        $balance = $this->grandTotal - (float) $this->amountPaid;
        if ($balance > 0) {
            Customer::query()->where('id', $this->customerId)->increment('current_balance', $balance);
        }

        Notification::make()->title('Sale completed successfully')->success()->send();
        
        // Dispatch browser events
        $this->dispatch('print-receipt', saleId: $sale->id);
        $this->dispatch('sale-completed');

        // Trigger low stock check
        Artisan::call('ims:check-low-stock');

        $this->reset(['cart', 'customerId', 'discount', 'amountPaid', 'search']);
        $this->cart = [];
    }
}
