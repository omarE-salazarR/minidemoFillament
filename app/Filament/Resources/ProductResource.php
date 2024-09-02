<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

     /**
     * Crea el formulario de Formulario
     *
     * @param Form $form
     */
    public static function form(Form $form): Form
    {
        $quantity = 0;
        return $form
            ->schema([
                TextInput::make('name')->required(),
                Textarea::make('description')->required(),
                TextInput::make('price')->numeric()->required(),
                TextInput::make('quantity')->numeric()->required()
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
                TextColumn::make('name')->label('Name')->sortable()->searchable(),
                TextColumn::make('description')->label('Description'),
                TextColumn::make('price')->label('Price')->sortable(),
                TextColumn::make('quantity')->label('Stock')->getStateUsing(function ($record) {
                    return $record->quantities()->latest()->value('quantity') ?? 0;
                }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);

    }
    
    /**
     * Configura la relaciones
     *
     * @param Table $table
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
     * @param Table $table
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }        
}
