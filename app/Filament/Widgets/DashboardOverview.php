<?php
namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Customer;
use Filament\Widgets\Widget;

class DashboardOverview extends Widget
{
    protected static string $view = 'filament.widgets.dashboard-overview';

    /**
     * Logica de relación con dashboard
     *
     */
    protected function getViewData(): array
    {
        $productsCount = Product::count();
        $ordersCount = Order::count();
        $ordersHistory = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $customersCount = Customer::count();
        $providersCount = Provider::count();
        return [
            'productsCount' => $productsCount,
            'ordersCount' => $ordersCount,
            'ordersHistory' => $ordersHistory,
            'providersCount' => $providersCount,
            'customersCount' => $customersCount
        ];
    }
}
