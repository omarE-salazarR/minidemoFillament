<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Pages\Actions;
use App\Models\Order;
use App\Models\OrderDetail;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

   
    /**
     * Configura los totales de acuerdo a los detalles caturados 
     * en el repeater
     * 
     * @param array $data
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $request = request();
        $content = json_decode($request->getContent(), true);
        $orderDetails = $content['serverMemo']['data']['data']['orderDetails'];
    
        $total = 0;
        foreach ($orderDetails as $orderDetail) {
            $total += $orderDetail['total'];
        }
        $data['order_date'] = now();
        $data['total'] = $total;
        return $data;
    }
}
