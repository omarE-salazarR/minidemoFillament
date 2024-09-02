<div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-2">
    <x-stats-card
        title="Total Products"
        value="{{ $productsCount }}"
        icon="heroicon-o-cube"
    />
    <x-stats-card
        title="Total Orders"
        value="{{ $ordersCount }}"
        icon="heroicon-o-shopping-cart"
    />
</div>

<div class="mt-6">
    <x-filament::card>
        <h2 class="text-xl font-bold">Orders History</h2>
        <div id="orders-history-chart"></div>
    </x-filament::card>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            chart: {
                type: 'line',
                height: 350
            },
            series: [{
                name: 'Orders',
                data: @json($ordersHistory->pluck('count'))
            }],
            xaxis: {
                categories: @json($ordersHistory->pluck('date'))
            }
        };

        var chart = new ApexCharts(document.querySelector("#orders-history-chart"), options);
        chart.render();
    });
</script>
@endpush
