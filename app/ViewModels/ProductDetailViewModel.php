<?php

namespace App\ViewModels;

use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class ProductDetailViewModel
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly string $image_url,
        public readonly string $category_name,
        public readonly bool $in_stock,
        public readonly string $price_formatted,
        public readonly string $delivery_time,
        public readonly ?int $available_units,
        public readonly string $available_units_text,
        public readonly array $ingredients,
        public readonly string $add_button_classes,
    ) {
    }

    public static function fromProduct(Produk $product, ?int $availableUnits): self
    {
        $inStock = $product->hasSufficientStock();

        return new self(
            id: $product->id,
            name: $product->nama,
            description: $product->deskripsi ?? 'Delicious handcrafted product made with premium ingredients.',
            image_url: self::resolveImageUrl($product),
            category_name: $product->kategori->nama ?? '',
            in_stock: $inStock,
            price_formatted: number_format($product->harga ?? 0, 0, ',', '.'),
            delivery_time: self::formatDeliveryTime($product->waktu_produksi),
            available_units: $availableUnits,
            available_units_text: self::formatAvailableUnits($availableUnits),
            ingredients: self::mapIngredients($product),
            add_button_classes: self::buildAddButtonClasses($inStock),
        );
    }

    protected static function resolveImageUrl(Produk $product): string
    {
        if ($product->gambar) {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('public');
            return $disk->url($product->gambar);
        }

        return asset('img/placeholder.svg');
    }

    protected static function formatDeliveryTime(mixed $rawTime): string
    {
        if ($rawTime === null || $rawTime === '') {
            return '1-2 Days';
        }

        if (is_numeric($rawTime)) {
            return $rawTime . ' Minutes';
        }

        return (string) $rawTime;
    }

    protected static function mapIngredients(Produk $product): array
    {
        $details = $product->resep?->detail ?? collect();

        return $details->map(function ($detail) {
            $amount = $detail->jumlah;
            $formattedAmount = $amount === null
                ? '-'
                : self::formatAmount($amount);

            $unit = $detail->satuan->symbol ?? $detail->satuan->nama ?? '';

            return [
                'name' => $detail->bahan->nama ?? 'Bahan',
                'amount' => trim($formattedAmount . ' ' . $unit),
            ];
        })->all();
    }

    protected static function formatAmount(mixed $amount): string
    {
        $number = (string) $amount;
        return rtrim(rtrim($number, '0'), '.');
    }

    protected static function formatAvailableUnits(?int $availableUnits): string
    {
        if ($availableUnits === null) {
            return 'Ready to order';
        }

        return $availableUnits . ' units';
    }

    protected static function buildAddButtonClasses(bool $inStock): string
    {
        $base = 'w-full mt-2 inline-flex items-center justify-center gap-3 rounded-full text-white py-3 text-sm font-semibold transition ';
        return $inStock
            ? $base . 'bg-[#7a4b24] hover:bg-[#693f1d]'
            : $base . 'bg-gray-400 cursor-not-allowed';
    }
}
