@props([
    'rating' => 0,
    'size' => 16,
    'class' => '',
    'idPrefix' => null,
])

@php
    $idPrefix = $idPrefix ?? uniqid('rating-', false);
    $ratingValue = max(0, min(5, (float) $rating));
@endphp

<div class="inline-flex items-center gap-1 {{ $class }}" aria-label="Rating {{ $ratingValue }} dari 5">
    @for ($i = 1; $i <= 5; $i++)
        @php
            $fill = max(min($ratingValue - ($i - 1), 1), 0);
            $percent = (int) round($fill * 100);
            $gradientId = $idPrefix . '-' . $i;
        @endphp
        <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 20 20" fill="none" aria-hidden="true">
            <defs>
                <linearGradient id="{{ $gradientId }}">
                    <stop offset="{{ $percent }}%" stop-color="#c58d52" />
                    <stop offset="{{ $percent }}%" stop-color="#e7d7c9" />
                </linearGradient>
            </defs>
            <path
                d="M10 2.5L12.2361 7.02786L17.2361 7.75336L13.6181 11.2779L14.4721 16.25L10 13.9028L5.52786 16.25L6.38186 11.2779L2.76386 7.75336L7.76386 7.02786L10 2.5Z"
                fill="url(#{{ $gradientId }})"
                stroke="#c58d52"
                stroke-width="0.6"
                stroke-linejoin="round"
            />
        </svg>
    @endfor
</div>
