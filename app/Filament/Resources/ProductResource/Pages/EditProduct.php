<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    /**
     * Obtiene las cantidades relacionadas con el producto de la tabla de stock (productQuantity)
     *
     * @param array $data
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['quantity'] = $this->record->quantities()->where('product_id', $this->record->id)->latest()->value('quantity') ?? 0;
        return $data;
    }
    
    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
