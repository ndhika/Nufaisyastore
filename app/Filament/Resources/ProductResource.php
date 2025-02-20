<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\SelectFilter;



class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function getRecordRouteKeyName(): string
    {
        return 'slug'; // menggunakan slug pengganti id
    }
    
    protected static ?string $navigationLabel = 'Product';

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $label = 'Product';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Details')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name product')
                            ->required()
                            ->placeholder('Enter the product name'),
                        FileUpload::make('thumbnail')
                            ->label('Gambar Produk')
                            ->image()
                            ->directory('product')
                            ->required(),
                        Repeater::make('product_photo')
                                ->relationship('photos')
                                ->schema([
                                    FileUpload::make('photo')
                                        ->image()
                                        ->directory('product')
                                        ->required(),
                                ]),
                        Repeater::make('sizes')
                                ->relationship('sizes')
                                ->schema([
                                    TextInput::make('size')
                                        ->nullable(),
                                ]),
                        TextInput::make('price')
                            ->label('Harga')
                            ->required()
                            ->numeric()
                            ->prefix('IDR')
                            ->placeholder('Enter the product price'),
                        TextInput::make('stock')
                            ->label('Stok')
                            ->prefix('Qty')
                            ->required()
                            ->placeholder('Enter the product stock'),
                        TextInput::make('slug')
                            ->hiddenOn(['create', 'edit'])
                            ->dehydrated(false),
                    ]),
                Fieldset::make('Additional')
                    ->schema([
                        Textarea::make('about')
                            ->label('Tentang')
                            ->required()
                            ->placeholder('Enter the product description'),
                        Select::make('category')
                            ->label('Kategori')
                            ->required()
                            ->options([
                                'Tas' => 'Tas',
                                'Kacamata' => 'Kacamata',
                                'Gamis' => 'Gamis',
                            ]),
                        select::make('is_popular')
                            ->label('Rating')
                            ->required()
                            ->options([
                                true => 'Popular',
                                false => 'Not Popular',
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Gambar Produk')
                    ->width(100)
                    ->height(100)
                    ->defaultImageUrl(url('storage/product/default.png')),
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(function ($state) {
                        return 'IDR ' . number_format($state, 0, ',', '.');
                }),
                TextColumn::make('stock')
                    ->label('Stok'),   
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('category')
                    ->label('Category')
                    ->options([
                        'Tas' => 'Tas',
                        'Kacamata' => 'kacamata',
                        'Gamis' => 'Gamis',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make()
                    ->action(function (Collection $records) { // Gunakan Illuminate\Database\Eloquent\Collection
                        // Gunakan transaction untuk memastikan konsistensi
                        DB::transaction(function () use ($records) {
                            foreach ($records as $record) {
                                // Hapus semua record terkait terlebih dahulu
                                $record->photos()->forceDelete();
                                $record->sizes()->forceDelete();

                                // Hapus produk secara permanen
                                $record->forceDelete();
                            }
                        });

                        // Notifikasi sukses
                        Notification::make()
                            ->title('Produk berhasil dihapus permanen!')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-trash'),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
