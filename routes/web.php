<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', \App\Livewire\Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';

use App\Livewire\Products\ListProducts;
use App\Livewire\Products\CreateProduct;
use App\Livewire\Products\EditProduct;
use App\Livewire\Customers\ListCustomers;
use App\Livewire\Customers\CreateCustomer;
use App\Livewire\Customers\EditCustomer;
use App\Livewire\Customers\ShowCustomer;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('products', ListProducts::class)->name('products.index');
    Route::get('categories', \App\Livewire\Categories\ListCategories::class)->name('categories.index');
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('users', \App\Livewire\Users\ListUsers::class)->name('users.index');
        Route::get('activities', \App\Livewire\System\ListActivities::class)->name('system.activities');
    });
    Route::get('products/create', CreateProduct::class)->name('products.create');
    Route::get('products/{product}/edit', EditProduct::class)->name('products.edit');

    // Route::get('customers/{customer}', ShowCustomer::class)->name('customers.show'); // Already defined above?
    
    // Explicitly defining them again to be sure, or just ensuring they are correct
    Route::get('customers', ListCustomers::class)->name('customers.index');
    Route::get('customers/create', CreateCustomer::class)->name('customers.create');
    Route::get('customers/{customer}/edit', EditCustomer::class)->name('customers.edit');
    Route::get('customers/{customer}', ShowCustomer::class)->name('customers.show');

    Route::get('sales', \App\Livewire\Sales\ListSales::class)->name('sales.index');
    Route::get('sales/create', \App\Livewire\Sales\CreateSale::class)->name('sales.create');
    Route::get('sales/{sale}', \App\Livewire\Sales\ShowSale::class)->name('sales.show');

    Route::get('payments', \App\Livewire\Payments\ListPayments::class)->name('payments.index');
    Route::get('payments/create', \App\Livewire\Payments\CreatePayment::class)->name('payments.create');
    Route::get('payments/{payment}', \App\Livewire\Payments\ShowPayment::class)->name('payments.show');
    Route::get('payments/{payment}/edit', \App\Livewire\Payments\EditPayment::class)->name('payments.edit');

    Route::get('expenses', \App\Livewire\Expenses\ListExpenses::class)->name('expenses.index');
    Route::get('expenses/create', \App\Livewire\Expenses\CreateExpense::class)->name('expenses.create');

    // POS Receipt Printing
    Route::get('admin/sales/receipt/{sale}', function (\App\Models\Sale $sale) {
        return view('filament.resources.sales.pages.receipt', ['sale' => $sale]);
    })->name('sales.receipt');
});
