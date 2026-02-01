<div class="space-y-6 lg:p-6">

    <div class="stats stats-vertical lg:stats-horizontal shadow-base-300/10 shadow-md bg-base-100 w-full">

        <!-- Orders -->
        <div class="stat">
            <div class="stat-figure">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-primary/10 text-primary">
                    <span class="icon-[tabler--shopping-bag] size-6"></span>
                </div>
            </div>
            <div class="stat-title">Orders</div>
            <div class="stat-value text-primary">{{ $monthOrders }}</div>
            <div class="stat-desc">
                <span class="text-success inline-flex items-center gap-1">
                    <span class="icon-[tabler--calendar] size-3"></span>
                    This Month
                </span>
            </div>
        </div>

        <!-- Customers -->
        <div class="stat">
            <div class="stat-figure">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-secondary/10 text-secondary">
                    <span class="icon-[tabler--users] size-6"></span>
                </div>
            </div>
            <div class="stat-title">Customers</div>
            <div class="stat-value text-secondary">{{ $totalCustomers }}</div>
            <div class="stat-desc">
                <span class="{{ $customerGrowth >= 0 ? 'text-success' : 'text-error' }} inline-flex items-center gap-1">
                    <span class="icon-[tabler--{{ $customerGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}] size-3"></span>
                    {{ number_format(abs($customerGrowth), 1) }}% Growth
                </span>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="stat">
            <div class="stat-figure">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-info/10 text-info">
                    <span class="icon-[tabler--chart-pie] size-6"></span>
                </div>
            </div>
            <div class="stat-title">Net Profit</div>
            <div class="stat-value text-info">₵{{ number_format($netProfit, 0) }}</div>
            <div class="stat-desc">
                <span class="{{ $salesGrowth >= 0 ? 'text-success' : 'text-error' }} inline-flex items-center gap-1">
                    <span class="icon-[tabler--{{ $salesGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}] size-3"></span>
                    {{ number_format(abs($salesGrowth), 1) }}% Cash Flow
                </span>
            </div>
        </div>

        <!-- Revenue -->
        <div class="stat">
            <div class="stat-figure">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-warning/10 text-warning">
                    <span class="icon-[tabler--currency-dollar] size-6"></span>
                </div>
            </div>
            <div class="stat-title">Revenue</div>
            <div class="stat-value text-warning">₵{{ number_format($monthRevenue, 0) }}</div>
            <div class="stat-desc">
                <span class="{{ $revenueGrowth >= 0 ? 'text-success' : 'text-error' }} inline-flex items-center gap-1">
                    <span class="icon-[tabler--{{ $revenueGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}] size-3"></span>
                    {{ number_format(abs($revenueGrowth), 1) }}% vs Last Month
                </span>
            </div>
        </div>

    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Revenue Overview (Area Chart) -->
        <div class="card col-span-1 xl:col-span-2 shadow-sm bg-base-100">
            <div class="card-header border-none bg-transparent pt-6 pb-0 px-6">
                <div class="flex flex-col">
                    <h3 class="card-title text-base-content text-lg font-bold">Revenue Overview</h3>
                    <p class="text-base-content/50 text-sm">Monthly revenue comparison</p>
                </div>
            </div>
            <div class="card-body px-2 sm:px-4">
                <div id="revenueChart" class="w-full h-80"></div>
            </div>
        </div>

        <!-- Order Statistics (Donut Chart) -->
        <div class="card col-span-1 shadow-sm bg-base-100">
            <div class="card-header border-none bg-transparent pt-6 pb-0 px-6">
                <div class="flex flex-col">
                    <h3 class="card-title text-base-content text-lg font-bold">Sales by Category</h3>
                    <p class="text-base-content/50 text-sm">Distribution of product sales</p>
                </div>
            </div>
            <div class="card-body">
                <div id="orderStatsChart" class="w-full h-80 flex items-center justify-center"></div>
            </div>
        </div>

        <!-- Weekly Sales (Bar Chart) -->
        <div class="card col-span-1 xl:col-span-2 shadow-sm bg-base-100">
            <div class="card-header border-none bg-transparent pt-6 pb-0 px-6 flex justify-between items-center">
                <div class="flex flex-col">
                    <h3 class="card-title text-base-content text-lg font-bold">Weekly Performance</h3>
                    <p class="text-base-content/50 text-sm">Sales for the last 7 days</p>
                </div>
                <div class="badge badge-soft badge-success">{{ $salesGrowth }}% vs last week</div>
            </div>
            <div class="card-body px-2 sm:px-4">
                <div id="weeklySalesChart" class="w-full h-64"></div>
            </div>
        </div>

        <!-- Top Products List -->
        <div class="card col-span-1 shadow-sm bg-base-100">
            <div class="card-header border-none bg-transparent pt-6 pb-0 px-6">
                <h3 class="card-title text-base-content text-lg font-bold">Top Selling Products</h3>
            </div>
            <div class="card-body">
                <ul class="flex flex-col gap-4">
                    @foreach($topProducts as $product)
                        <li class="flex items-center gap-3 pb-3 border-b border-base-content/10 last:border-none">
                            <div class="avatar placeholder">
                                <div class="size-10 rounded-lg bg-base-200">
                                    @if($product->photo)
                                        <img src="{{ Storage::url($product->photo) }}" />
                                    @else
                                        <span class="text-xs font-bold">{{ substr($product->name, 0, 1) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="grow min-w-0">
                                <div class="flex justify-between items-center mb-1">
                                    <h4 class="font-medium text-sm truncate text-base-content">{{ $product->name }}</h4>
                                    <span
                                        class="font-bold text-sm text-base-content">₵{{ number_format($product->selling_price * $product->sold_count, 0) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs text-base-content/50">
                                    <span>{{ $product->sold_count }} items sold</span>
                                    <progress class="progress progress-primary w-20 h-1.5"
                                        value="{{ $product->sold_count }}"
                                        max="{{ $topProducts->max('sold_count') * 1.2 }}"></progress>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>

    <!-- Recent Invoices & Top Customers Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Recent Invoices Table -->
        <div class="rounded-box bg-base-100 w-full shadow-md xl:col-span-2">
            <div class="border-b border-base-content/10 p-6 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold">Recent Invoices</h2>
                    <div class="text-sm opacity-60">Latest sales transactions</div>
                </div>
                <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">+ New Sale</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSales as $sale)
                            <tr>
                                <td class="font-mono font-medium font-bold text-primary">INV-{{ $sale->id }}</td>
                                <td class="font-medium">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                                <td>
                                    @php
                                        $badgeClass = match ($sale->payment_status->value ?? '') {
                                            'paid' => 'badge-soft badge-success',
                                            'partial' => 'badge-soft badge-warning',
                                            'credit' => 'badge-soft badge-error',
                                            default => 'badge-soft badge-info'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} text-xs">{{ $sale->payment_status->label() }}</span>
                                </td>
                                <td class="font-bold">₵{{ number_format($sale->total_amount, 2) }}</td>
                                <td>
                                    @if($sale->total_amount - $sale->amount_paid > 0)
                                        <span class="text-error font-medium">₵{{ number_format($sale->total_amount - $sale->amount_paid, 2) }}</span>
                                    @else
                                        <span class="text-success text-sm">Paid</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-primary btn-sm" aria-label="View invoice">
                                        <span class="icon-[tabler--eye] size-5"></span>
                                    </a>
                                    
                                    @if($sale->payment_status->value !== 'paid')
                                        <a href="{{ route('payments.create', ['sale_id' => $sale->id]) }}" class="btn btn-circle btn-text btn-sm text-success" aria-label="Record payment">
                                            <span class="icon-[tabler--credit-card] size-5"></span>
                                        </a>
                                    @endif
                                   
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 opacity-50">
                                    No recent sales found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Customers by Amount Spent -->
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold">Top Customers</h2>
                        <div class="text-sm opacity-60">By total spent</div>
                    </div>
                    <a href="{{ route('customers.index') }}" class="btn btn-ghost btn-xs">View All</a>
                </div>
                <ul class="flex flex-col gap-4">
                    @forelse($topCustomers as $index => $customer)
                        <li class="flex items-center gap-3 pb-3 border-b border-base-content/10 last:border-none">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full shrink-0
                                {{ $index === 0 ? 'bg-warning text-warning-content' : ($index === 1 ? 'bg-base-300 text-white' : ($index === 2 ? 'bg-amber-700 text-white' : 'bg-base-200')) }}">
                                <span class="text-sm font-bold">{{ $index + 1 }}</span>
                            </div>
                            <div class="grow min-w-0">
                                <div class="flex justify-between items-center mb-1">
                                    <h4 class="font-medium text-sm truncate">{{ $customer->name }}</h4>
                                    <span class="font-bold text-success shrink-0">₵{{ number_format($customer->total_spent, 0) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs text-base-content/50">
                                    <span>{{ $customer->phone ?? 'No phone' }}</span>
                                    <progress class="progress progress-success w-16 h-1.5"
                                        value="{{ $customer->total_spent }}"
                                        max="{{ $topCustomers->max('total_spent') * 1.1 }}"></progress>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-center py-6 opacity-50">No customers found.</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const initDashboardCharts = () => {
                    if (typeof window.buildChart !== 'function') {
                        console.warn('buildChart not loaded yet, retrying...');
                        setTimeout(initDashboardCharts, 300);
                        return;
                    }

                    // 1. Revenue Overview (Area Chart)
                    const revenueData = @json($revenueChartData);
                    const revenueLastYearData = @json($revenueLastYearData);
                    const categories = @json($revenueChartCategories);

                    window.buildChart('#revenueChart', () => ({
                        series: [
                            { name: 'Current Year', data: revenueData },
                            { name: 'Last Year', data: revenueLastYearData }
                        ],
                        chart: {
                            type: 'area',
                            height: 320,
                            toolbar: { show: false },
                            animations: { enabled: true }
                        },
                        colors: ['#4f46e5', '#9ca3af'],
                        fill: {
                            type: 'gradient',
                            gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] }
                        },
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 2 },
                        xaxis: {
                            categories: categories,
                            labels: { style: { colors: '#9ca3af', fontFamily: 'inherit' } },
                            axisBorder: { show: false },
                            axisTicks: { show: false }
                        },
                        yaxis: {
                            labels: { style: { colors: '#9ca3af', fontFamily: 'inherit' }, formatter: (val) => '₵' + val }
                        },
                        grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
                        tooltip: { theme: 'light' }
                    }));

                    // 2. Order Statistics (Donut Chart)
                    const orderData = @json($orderByCategoryData);
                    const orderLabels = @json($orderByCategoryLabels);

                    if (orderData && orderData.length > 0) {
                        window.buildChart('#orderStatsChart', () => ({
                            series: orderData,
                            labels: orderLabels,
                            chart: { type: 'donut', height: 280, fontFamily: 'inherit' },
                            colors: ['#4f46e5', '#ec4899', '#22c55e', '#eab308', '#3b82f6'],
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '70%',
                                        labels: {
                                            show: true,
                                            name: { fontSize: '14px', color: '#9ca3af' },
                                            value: { fontSize: '24px', fontWeight: 600, color: '#1f2937', formatter: (val) => val },
                                            total: {
                                                show: true,
                                                label: 'Total',
                                                color: '#9ca3af',
                                                formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                            }
                                        }
                                    }
                                }
                            },
                            dataLabels: { enabled: false },
                            legend: { show: true, position: 'bottom', horizontalAlign: 'center', fontSize: '14px', fontFamily: 'inherit' },
                            stroke: { show: false }
                        }));
                    }

                    // 3. Weekly Sales (Bar Chart)
                    const weeklyData = @json($weeklySalesData);
                    const weeklyLabels = @json($weeklySalesLabels);

                    window.buildChart('#weeklySalesChart', () => ({
                        series: [{ name: 'Earnings', data: weeklyData }],
                        chart: { type: 'bar', height: 250, toolbar: { show: false }, fontFamily: 'inherit' },
                        colors: ['#22c55e'],
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                columnWidth: '50%',
                                distributed: false
                            }
                        },
                        dataLabels: { enabled: false },
                        xaxis: {
                            categories: weeklyLabels,
                            labels: { style: { colors: '#9ca3af', fontFamily: 'inherit' } },
                            axisBorder: { show: false },
                            axisTicks: { show: false }
                        },
                        yaxis: {
                            labels: { style: { colors: '#9ca3af', fontFamily: 'inherit' }, formatter: (val) => '₵' + val }
                        },
                        grid: { borderColor: '#f3f4f6', strokeDashArray: 4, yaxis: { lines: { show: true } } },
                        tooltip: { theme: 'light' }
                    }));
                };

                initDashboardCharts();
            });
        </script>
    @endpush