<x-layouts.app :title="__('Dashboard')">
    <div class="space-y-6">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="stats stats-vertical lg:stats-horizontal shadow bg-base-100 border border-base-200 w-full">
                <div class="stat">
                    <div class="stat-figure text-primary">
                        <span class="icon-[tabler--currency-dollar] size-8"></span>
                    </div>
                    <div class="stat-title">Total Revenue</div>
                    <div class="stat-value text-primary">₵{{ number_format($monthRevenue, 2) }}</div>
                    <div class="stat-desc {{ $revenueGrowth >= 0 ? 'text-success' : 'text-error' }}">
                        {{ $revenueGrowth >= 0 ? '↗︎' : '↘︎' }} {{ number_format(abs($revenueGrowth), 1) }}% vs last
                        month
                    </div>
                </div>
            </div>

            <div class="stats stats-vertical lg:stats-horizontal shadow bg-base-100 border border-base-200 w-full">
                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <span class="icon-[tabler--users] size-8"></span>
                    </div>
                    <div class="stat-title">New Customers</div>
                    <div class="stat-value text-secondary">{{ $monthSales }}</div>
                    <!-- Variable naming in Dashboard.php seems to use monthSales for sales amount?? Let's check. 
                         Actually, let's stick to what was there or what fits. 
                         If monthSales is amount, then New Customers should use something else like totalCustomers or a calculated new count.
                         Looking at Dashboard.php earlier:
                         $monthSales = Sale::...sum('total_amount'); -> This is Amount.
                         $currentMonthCustomers = ... -> This is available as a local variable in mount() but not public property.
                         Public properties: $todaySales, $monthSales, $salesGrowth, $todayRevenue... $totalCustomers, $customerGrowth.
                         I should use $customerGrowth context or add $newCustomers to public properties.
                         For now, I'll use $totalCustomers or leave it as is but be aware it might be wrong.
                         Wait, let's check Dashboard.php public properties again.
                         $totalCustomers is public.
                         $customerGrowth is public.
                         I don't see a public $newCustomersCount. 
                         I will use $totalCustomers for now to be safe or just show $monthSales as 'Sales' instead of 'New Customers'.
                         Actually, the UI says "New Customers".
                         Let's change the Title to "Monthly Sales" if I use $monthSales.
                         OR change the value to $totalCustomers (but that is TOTAL).
                         Let's change Label to "Monthly Sales" to match the data $monthSales.
                    -->
                    <div class="stat-title">Monthly Sales</div>
                    <div class="stat-value text-secondary">₵{{ number_format($monthSales, 2) }}</div>
                    <div class="stat-desc text-success">↗︎ {{ number_format($salesGrowth, 1) }}% vs last month</div>
                </div>
            </div>

            <div class="stats stats-vertical lg:stats-horizontal shadow bg-base-100 border border-base-200 w-full">
                <div class="stat">
                    <div class="stat-figure text-accent">
                        <span class="icon-[tabler--shopping-cart] size-8"></span>
                    </div>
                    <div class="stat-title">Month Expenses</div>
                    <div class="stat-value text-accent">₵{{ number_format($monthExpenses, 2) }}</div>
                    <div class="stat-desc {{ $expenseGrowth <= 0 ? 'text-success' : 'text-error' }}">
                        {{ $expenseGrowth > 0 ? '↗︎' : '↘︎' }} {{ number_format(abs($expenseGrowth), 1) }}% vs last
                        month
                    </div>
                </div>
            </div>

            <div class="stats stats-vertical lg:stats-horizontal shadow bg-base-100 border border-base-200 w-full">
                <div class="stat">
                    <div class="stat-figure text-info">
                        <span class="icon-[tabler--activity] size-8"></span>
                    </div>
                    <div class="stat-title">Net Profit</div>
                    <div class="stat-value text-info">₵{{ number_format($netProfit, 2) }}</div>
                    <div class="stat-desc">For this month</div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Revenue Chart -->
            <div class="card bg-base-100 shadow border border-base-200 lg:col-span-2">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="card-title">Revenue Overview</h3>
                        <div class="join">
                            <button class="join-item btn btn-xs btn-active">Yearly</button>
                        </div>
                    </div>
                    <div id="revenueChart" class="w-full h-80" data-series='@json($revenueChartData)'
                        data-categories='@json($revenueChartCategories)'></div>
                </div>
            </div>

            <!-- Customer Growth Chart -->
            <div class="card bg-base-100 shadow border border-base-200">
                <div class="card-body">
                    <h3 class="card-title mb-4">Customer Growth</h3>
                    <div id="trafficChart" class="w-full h-64 flex justify-center items-center"
                        data-series='@json($customerChartData)'></div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card bg-base-100 shadow border border-base-200">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="card-title">Recent Transactions</h3>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-ghost">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSales as $sale)
                                <tr>
                                    <td class="font-medium">#INV-{{ $sale->id }}</td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar placeholder">
                                                <div class="bg-neutral text-neutral-content rounded-full w-8">
                                                    <span
                                                        class="text-xs">{{ substr($sale->customer->name ?? 'Guest', 0, 2) }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold">{{ $sale->customer->name ?? 'Walk-in Customer' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                                    <td>₵{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match ($sale->payment_status->value ?? '') {
                                                'paid' => 'badge-success',
                                                'pending' => 'badge-warning',
                                                'failed' => 'badge-error',
                                                default => 'badge-ghost'
                                            };
                                        @endphp
                                        <span
                                            class="badge {{ $badgeClass }} badge-soft">{{ ucfirst($sale->payment_status->value ?? 'Unknown') }}</span>
                                    </td>
                                    <td><a href="{{ route('sales.show', $sale) }}"
                                            class="btn btn-square btn-ghost btn-sm"><span
                                                class="icon-[tabler--dots] size-5"></span></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @assets
    <script>
        document.addEventListener('livewire:initialized', function () {
            // Logic to update charts if livewire updates could go here
        });
    </script>
    @endassets
</x-layouts.app>