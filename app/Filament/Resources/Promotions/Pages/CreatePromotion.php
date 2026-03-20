<?php

namespace App\Filament\Resources\Promotions\Pages;

use App\Filament\Resources\Promotions\PromotionResource;
use App\Models\PromotionItem;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePromotion extends CreateRecord
{
    protected static string $resource = PromotionResource::class;

    protected array $productIds = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        $this->productIds = $data['product_ids'] ?? [];
        unset($data['product_ids']);

        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->productIds as $productId) {
            PromotionItem::create([
                'promotion_id' => $this->record->id,
                'product_id'   => $productId,
            ]);
        }
    }
}
