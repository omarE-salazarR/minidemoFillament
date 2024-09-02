<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Rules\CheckInventory;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    public static $total = 0;
    public static $totals = [];


    /**
     * Crea el formulario de Formulario
     *
     * @param Form $form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('orderDetails')
                    ->relationship('orderDetails')
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')->options(Product::withStock())->required()->reactive() 
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $product = Product::find($state);
                                $set('price', $product ? $product->price : 0);
                                $set('product_name', $product ? $product->name : '');
                                $quantity = $get('quantity');
                                $set('total', $quantity * ($product ? $product->price : 0));
                            }),
                        TextInput::make('quantity')->numeric()->required()->reactive() 
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $product = Product::find($get('product_id'));
                                $set('price', $product ? $product->price : 0);
                                $set('product_name', $product ? $product->name : '');
                                $quantity = $state;
                                $set('total', $quantity * ($product ? $product->price : 0));
                            }),
                        TextInput::make('total')->numeric()->disabled()->formatStateUsing(fn ($state) => number_format($state, 2)),
                        TextInput::make('product_name')->label(false)->extraAttributes(['style' => 'visibility: hidden;'])->dehydrated(),
                    ])->columns(3),
                    Select::make('customer_name')->label('Customer')->options(Customer::all()->pluck('name', 'name'))->required(),
                    TextInput::make('total')->label('Total Order')->disabled()->numeric(),
            ]);
    }

    /**
     * Lista las ordenes  realizadas con su detalle
     *
     * @param Table $table
     */
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('customer_name')->label('Customer')->sortable()->searchable(),
            TextColumn::make('order_date')->label('Order Date')->sortable()->searchable(),
            TextColumn::make('total')->label('Total Order')->sortable()->searchable(),
            TextColumn::make('orderDetails')
                ->label('Order Details')
                ->formatStateUsing(function ($record) {
                    return $record->orderDetails->map(function ($detail) {
                        return $detail->product_name . '(' . $detail->quantity . ')';
                    })->join(', ');
                }),
        ]);
    }
    
    /**
     * Configura las relaciones
     *
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    /**
     * Configura las rutas 
     *
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }    
}
