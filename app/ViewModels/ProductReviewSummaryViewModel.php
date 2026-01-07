<?php

namespace App\ViewModels;

use App\Models\Produk;
use App\Models\Ulasan;
use Illuminate\Support\Collection;

class ProductReviewSummaryViewModel
{
    public function __construct(
        public readonly float $average_rating,
        public readonly int $total_reviews,
        public readonly array $rating_breakdown,
        public readonly array $reviews,
    ) {
    }

    public static function fromProduct(Produk $product): self
    {
        $reviews = Ulasan::query()
            ->whereHas('detailPesanan', function ($query) use ($product) {
                $query->where('produk_id', $product->id);
            })
            ->with('pelanggan:id,nama')
            ->latest()
            ->get();

        return self::fromReviews($reviews);
    }

    protected static function fromReviews(Collection $reviews): self
    {
        $total = $reviews->count();
        $average = $total > 0 ? round((float) $reviews->avg('rating'), 1) : 0.0;

        $ratingCounts = $reviews->groupBy('rating')->map->count();
        $breakdown = [];
        for ($rating = 5; $rating >= 1; $rating--) {
            $count = (int) ($ratingCounts[$rating] ?? 0);
            $percent = $total > 0 ? (int) round(($count / $total) * 100) : 0;

            $breakdown[] = [
                'rating' => $rating,
                'count' => $count,
                'percent' => $percent,
            ];
        }

        $items = $reviews->map(function ($review) {
            $name = $review->pelanggan->nama ?? 'Pelanggan';

            return [
                'name' => $name,
                'initials' => self::initials($name),
                'rating' => (int) $review->rating,
                'comment' => $review->komentar,
                'date' => $review->created_at?->format('Y-m-d') ?? '',
            ];
        })->all();

        return new self(
            average_rating: $average,
            total_reviews: $total,
            rating_breakdown: $breakdown,
            reviews: $items,
        );
    }

    protected static function initials(string $name): string
    {
        $clean = trim($name);
        if ($clean === '') {
            return '?';
        }

        $parts = preg_split('/\s+/', $clean) ?: [];
        $first = strtoupper(substr($parts[0] ?? '', 0, 1));
        $second = strtoupper(substr($parts[1] ?? '', 0, 1));

        return $first . $second;
    }
}
