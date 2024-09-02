<x-filament::page class="filament-dashboard-page">
    <x-filament::widgets
        :widgets="[\App\Filament\Widgets\DashboardOverview::class,]"
        :columns="1"
    />
</x-filament::page>
