<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductTransactionResource\Pages;
use App\Models\ProductTransaction;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;

class ProductTransactionResource extends Resource
{
    protected static ?string $model = ProductTransaction::class;

    protected static ?string $navigationLabel = 'Transaction';
    
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $label = 'Transaction';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Informasi Pelanggan')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama')
                        ->required(),
                    TextInput::make('phone_number')
                        ->label('Nomor Telepon')
                        ->required(),
                    TextInput::make('city')
                        ->label('Kota')
                        ->required(),
                    TextInput::make('address')
                        ->label('Alamat')
                        ->required(),
                ]),
        
            Section::make('Detail Transaksi')
                ->schema([
                    Select::make('product_id')
                    ->label('Produk')
                    ->options(Product::pluck('name', 'id')->toArray())
                    ->getSearchResultsUsing(fn (string $search) => 
                        Product::where('name', 'like', "%{$search}%")
                            ->pluck('name', 'id')
                    )
                    ->getOptionLabelUsing(fn ($value) => 
                        Product::find($value)?->name
                    )
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->required(),
                    TextInput::make('quantity')
                        ->label('Jumlah')
                        ->numeric()
                        ->required(),
                    TextInput::make('sub_total_amount')
                        ->label('Subtotal')
                        ->numeric()
                        ->required(),
                    TextInput::make('discount_amount')
                        ->label('Diskon')
                        ->numeric(),
                    TextInput::make('grand_total_amount')
                        ->label('Total Akhir')
                        ->numeric()
                        ->required(),
                    FileUpload::make('proof')
                        ->label('Bukti Pembayaran')
                        ->disk('public')
                        ->directory('buktipembayaran')
                        ->image(),
                    Select::make('status')
                        ->label('Status Transaksi')
                        ->options([
                            'failed' => 'Gagal',
                            'unpaid' => 'Belum Dibayar',
                            'paid' => 'Sudah Dibayar',
                            'processing' => 'Sedang Diproses',
                            'shipping' => 'Dalam Pengiriman',
                            'arrived' => 'Barang Tiba',
                            'waiting_confirmation' => 'Menunggu Konfirmasi',
                            'done' => 'Selesai',
                        ])
                        ->default('unpaid')
                        ->required(),
                ]),
        ]);        
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultSort('created_at', 'desc') 
        ->columns([
            ImageColumn::make('product_thumbnail')
            ->label('Thumbnail')
            ->disk('public')
            ->getStateUsing(function ($record) {
                $productIds = json_decode($record->product_id, true);
                return collect($productIds)
                    ->map(fn ($id) => Product::find($id)?->thumbnail)
                    ->filter()
                    ->toArray(); 
            })
            ->stacked(),
            TextColumn::make('product_name')
                ->label('Produk')
                ->getStateUsing(function ($record) {
                    $productIds = json_decode($record->product_id, true);
                    return collect($productIds)
                        ->map(fn ($id) => Product::find($id)?->name)
                        ->filter()
                        ->implode(', ');
                    })
                ->searchable(),
            TextColumn::make('user.name')
                ->label('Nama')
                ->searchable(),
            TextColumn::make('phone_number')
                ->label('Nomor Telepon')
                ->searchable(),
            TextColumn::make('address')
                ->label('Alamat')
                ->wrap()
                ->searchable(),
            TextColumn::make('city')
                ->label('Kota')
                ->sortable(),
            TextColumn::make('quantity')
                ->label('Jumlah Barang')
                ->sortable(),
            TextColumn::make('grand_total_amount')
                ->label('Total Pembayaran')
                ->money('IDR')
                ->sortable(),
            BadgeColumn::make('status')
                ->label('Status')
                ->sortable()
                ->formatStateUsing(fn ($state) => match ($state) {
                    'failed' => 'Gagal',
                    'unpaid' => 'Belum Dibayar',
                    'paid' => 'Sudah Dibayar',
                    'processing' => 'Sedang Diproses',
                    'shipping' => 'Dalam Pengiriman',
                    'arrived' => 'Barang Tiba',
                    'waiting_confirmation' => 'Menunggu Konfirmasi',
                    'done' => 'Selesai',
                    default => 'failed',
                })
                ->color(fn ($state) => match ($state) {
                    'failed' => 'gray',
                    'unpaid' => 'gray',
                    'paid' => 'green',
                    'processing' => 'blue',
                    'shipping' => 'yellow',
                    'arrived' => 'purple',
                    'waiting_confirmation' => 'orange',
                    'done' => 'success',
                    default => 'secondary',
                }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('nextStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-arrow-path')
                    ->visible(fn(ProductTransaction $record) => $record->status !== 'done')
                    ->action(function (ProductTransaction $record) {
                        $statuses = ['failed', 'unpaid', 'paid', 'processing', 'shipping', 'arrived', 'waiting_confirmation', 'done'];
                        $currentIndex = array_search($record->status, $statuses);
                        if ($currentIndex !== false && isset($statuses[$currentIndex + 1])) {
                            $record->update(['status' => $statuses[$currentIndex + 1]]);
                            Notification::make()
                                ->title('Status Diperbarui')
                                ->body('Status transaksi berhasil diperbarui ke ' . $statuses[$currentIndex + 1])
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductTransactions::route('/'),
            'create' => Pages\CreateProductTransaction::route('/create'),
            'edit' => Pages\EditProductTransaction::route('/{record}/edit'),
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
