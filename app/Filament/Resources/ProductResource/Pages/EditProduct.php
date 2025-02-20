<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Product;
use Filament\Notifications\Notification;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\RestoreAction::make()
                ->using(function ($record) {
                    $product = Product::withTrashed()->where('slug', $record->slug)->first();
                    if ($product) {
                        $product->restore();
                    }
                }),

            Actions\ForceDeleteAction::make()
                ->before(function ($record) {
                    $product = Product::withTrashed()->where('slug', $record->slug)->first();
                    if ($product) {
                        $product->sizes()->forceDelete();
                        $product->photos()->forceDelete();
                    }
                })
                ->using(function ($record) {
                    $product = Product::withTrashed()->where('slug', $record->slug)->first();
                    if ($product) {
                        $product->forceDelete();
                    }
                })
                ->after(function () {
                    Notification::make()
                        ->title('Produk berhasil dihapus!')
                        ->success()
                        ->send();

                    return redirect()->route('filament.admin.resources.products.index');
                }),
        ];
    }
}
