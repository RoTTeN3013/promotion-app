<?php

namespace App\Filament\Resources\Promotions\Pages;

use App\Filament\Resources\Promotions\PromotionResource;
use App\Models\PromotionItem;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPromotion extends EditRecord
{
    protected static string $resource = PromotionResource::class;

    protected array $productIds = [];

    protected function fillForm(): void
    {
        parent::fillForm();

        $this->productIds = $this->record->promotionItems()
            ->pluck('product_id')
            ->toArray();

        $this->form->fill([
            ...$this->record->attributesToArray(),
            'product_ids' => $this->productIds,
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->productIds = $data['product_ids'] ?? [];
        unset($data['product_ids']);

        return $data;
    }

    protected function afterSave(): void
    {
        PromotionItem::where('promotion_id', $this->record->id)->delete();

        foreach ($this->productIds as $productId) {
            PromotionItem::create([
                'promotion_id' => $this->record->id,
                'product_id'   => $productId,
            ]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
