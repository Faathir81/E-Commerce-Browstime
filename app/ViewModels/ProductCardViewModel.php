<?php

namespace App\ViewModels;

use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class ProductCardViewModel
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $url,
        public readonly string $image_url,
        public readonly bool $in_stock,
        public readonly array $stock_badge,
        public readonly string $category_name,
        public readonly string $formatted_price,
        public readonly string $add_button_classes,
    ) {
    }

    public static function fromProduct(Produk $product): self
    {
        $inStock = $product->hasSufficientStock();

        return new self(
            id: $product->id,
            name: $product->nama,
            url: route('product.show', $product->slug),
            image_url: self::resolveImageUrl($product),
            in_stock: $inStock,
            stock_badge: self::formatStockBadge($inStock),
            category_name: $product->kategori->nama ?? 'Produk',
            formatted_price: number_format($product->harga ?? 0, 0, ',', '.'),
            add_button_classes: self::buildAddButtonClasses($inStock),
        );
    }

    public static function formatStockBadge(bool $inStock): array
    {
        return $inStock
            ? [
                'label' => 'Stok Tersedia',
                'text_class' => 'text-[#7d6b5c]',
                'dot_class' => 'inline-block w-2.5 h-2.5 rounded-full bg-green-600 flex-shrink-0',
            ]
            : [
                'label' => 'Stok Habis',
                'text_class' => 'text-[#8b5a2b]',
                'dot_class' => 'inline-block w-2.5 h-2.5 rounded-full bg-red-500 flex-shrink-0',
            ];
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

    protected static function buildAddButtonClasses(bool $inStock): string
    {
        $base = 'w-full flex items-center justify-center gap-2 text-white text-sm py-2 rounded-xl transition ';
        return $inStock
            ? $base . 'bg-[#3b241a] hover:bg-[#2c1c14]'
            : $base . 'bg-gray-400 cursor-not-allowed';
    }
}
