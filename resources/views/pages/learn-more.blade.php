@extends('layouts.app')

@section('content')
<div class="text-[#3b241a]">
    <section class="bg-[#e8d6c4] border-b border-[#f2e6db] bg-gradient-to-b from-[#f5e8db] to-[#fdf6ef]">
        <div class="mx-auto max-w-screen-2xl px-6 py-10 sm:px-8 lg:px-14">
            <div class="text-center">
                <span class="inline-flex items-center rounded-full bg-[#7a4b24] px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-white">
                    About Browstime
                </span>
                <h1 class="mt-4 text-3xl font-semibold sm:text-4xl">
                    Baking Happiness, One Treat at a Time
                </h1>
                <p class="mx-auto mt-3 max-w-2xl text-sm text-[#6f4c3b] sm:text-base">
                    Since 2020, we have been crafting premium cookies and brownies with love,
                    using only the finest ingredients to bring joy to every bite.
                </p>
            </div>
        </div>
    </section>

    

    <section class=" mx-auto max-w-screen-2xl px-6 py-12 sm:px-8 lg:px-14">
        <div class="grid items-center gap-10 lg:grid-cols-[1.05fr,0.95fr]">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-[#9a6b4f]">Our Story</p>
                <h2 class="mt-3 text-2xl font-semibold">From a small kitchen to your table</h2>
                <div class="mt-4 space-y-3 text-sm text-[#6f4c3b] leading-relaxed">
                    <p>
                        BROWSTIME began as a passion project in a home kitchen, with a simple mission:
                        create cookies and brownies that taste like they came straight from a family oven.
                    </p>
                    <p>
                        Friends and family quickly fell in love with our treats, and word spread for our
                        commitment to quality and authentic taste.
                    </p>
                    <p>
                        Today, we are proud to serve happy customers across Indonesia while keeping the
                        same values: fresh ingredients, handcrafted care, and honest goodness in every bite.
                    </p>
                </div>
            </div>
            <div class="relative">
                <div class="overflow-hidden rounded-3xl border border-[#f1e8df] bg-white shadow-[0_18px_40px_rgba(0,0,0,0.12)]">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=900&q=80" alt="Browstime story" class="h-full w-full object-cover" />
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#e8d6c4]">
        <div class="mx-auto max-w-screen-2xl px-6 py-12 sm:px-8 lg:px-14">
            <div class="text-center">
                <h2 class="text-2xl font-semibold">Why Choose BROWSTIME?</h2>
                <p class="mt-2 text-sm text-[#6f4c3b]">
                    We are not just another bakery. Here is what makes us special.
                </p>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-[#f1e8df] bg-white p-5 text-center shadow-sm">
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#f4e9dc] text-[#7a4b24]">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M5 10H15" stroke="#7a4b24" stroke-width="1.6" stroke-linecap="round"/>
                            <path d="M10 5V15" stroke="#7a4b24" stroke-width="1.6" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold">Fresh Daily</h3>
                    <p class="mt-2 text-xs text-[#6f4c3b]">
                        Every batch is baked fresh to order, ensuring maximum freshness and flavor.
                    </p>
                </div>
                <div class="rounded-2xl border border-[#f1e8df] bg-white p-5 text-center shadow-sm">
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#f4e9dc] text-[#7a4b24]">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M6 14L10 6L14 14" stroke="#7a4b24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold">Premium Quality</h3>
                    <p class="mt-2 text-xs text-[#6f4c3b]">
                        We use only the finest ingredients for a rich and balanced taste.
                    </p>
                </div>
                <div class="rounded-2xl border border-[#f1e8df] bg-white p-5 text-center shadow-sm">
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#f4e9dc] text-[#7a4b24]">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M10 17C10 17 4 12.5 4 8.5C4 6.01472 6.01472 4 8.5 4C9.88 4 11.16 4.68 12 5.7C12.84 4.68 14.12 4 15.5 4C17.9853 4 20 6.01472 20 8.5C20 12.5 14 17 14 17H10Z" fill="#7a4b24"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold">Made with Love</h3>
                    <p class="mt-2 text-xs text-[#6f4c3b]">
                        Every cookie and brownie is handcrafted with care by our bakers.
                    </p>
                </div>
                <div class="rounded-2xl border border-[#f1e8df] bg-white p-5 text-center shadow-sm">
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#f4e9dc] text-[#7a4b24]">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M10 2V10L14 12" stroke="#7a4b24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="10" cy="10" r="8" stroke="#7a4b24" stroke-width="1.6"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold">Fast Delivery</h3>
                    <p class="mt-2 text-xs text-[#6f4c3b]">
                        Quick and reliable delivery within 1-3 days, right to your doorstep.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-screen-2xl px-6 py-12 sm:px-8 lg:px-14">
        <div class="text-center">
            <h2 class="text-2xl font-semibold">Our Commitment to You</h2>
            <p class="mt-2 text-sm text-[#6f4c3b]">
                These core values guide everything we do at BROWSTIME.
            </p>
        </div>

        <div class="mt-8 grid gap-6 text-sm text-[#6f4c3b] sm:grid-cols-2 lg:grid-cols-3">
            <div class="flex items-start gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f4e9dc] text-[#7a4b24]">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M6 9L9 12L14 7" stroke="#7a4b24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="10" cy="10" r="8" stroke="#7a4b24" stroke-width="1.6"/>
                    </svg>
                </span>
                <div>
                    <p class="font-semibold text-[#3b241a]">Quality Assurance</p>
                    <p class="mt-1 text-xs">Every product goes through rigorous quality checks.</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f4e9dc] text-[#7a4b24]">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M10 11C12.2091 11 14 9.20914 14 7C14 4.79086 12.2091 3 10 3C7.79086 3 6 4.79086 6 7C6 9.20914 7.79086 11 10 11Z" stroke="#7a4b24" stroke-width="1.6"/>
                        <path d="M3 17C3.9 14.6 6.2 13 10 13C13.8 13 16.1 14.6 17 17" stroke="#7a4b24" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                </span>
                <div>
                    <p class="font-semibold text-[#3b241a]">Customer First</p>
                    <p class="mt-1 text-xs">We listen, learn, and continuously improve our service.</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f4e9dc] text-[#7a4b24]">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="M4 10C4 6.68629 6.68629 4 10 4C13.3137 4 16 6.68629 16 10C16 13.3137 13.3137 16 10 16C6.68629 16 4 13.3137 4 10Z" stroke="#7a4b24" stroke-width="1.6"/>
                        <path d="M10 7V10L12 12" stroke="#7a4b24" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                </span>
                <div>
                    <p class="font-semibold text-[#3b241a]">Authenticity</p>
                    <p class="mt-1 text-xs">No shortcuts, no artificial flavors, just honest goodness.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#e8d6c4]">
        <div class="mx-auto max-w-screen-2xl px-6 py-10 sm:px-8 lg:px-14">
            <div class="grid gap-6 text-center text-[#7a4b24] sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <p class="text-2xl font-semibold">5000+</p>
                    <p class="text-xs text-[#6f4c3b]">Happy Customers</p>
                </div>
                <div>
                    <p class="text-2xl font-semibold">15k+</p>
                    <p class="text-xs text-[#6f4c3b]">Orders Delivered</p>
                </div>
                <div>
                    <p class="text-2xl font-semibold">4.8</p>
                    <p class="text-xs text-[#6f4c3b]">Average Rating</p>
                </div>
                <div>
                    <p class="text-2xl font-semibold">20+</p>
                    <p class="text-xs text-[#6f4c3b]">Product Varieties</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-screen-2xl px-6 py-12 sm:px-8 lg:px-14">
        <div class="rounded-3xl border border-[#f1e8df] bg-white px-6 py-10 text-center shadow-sm">
            <h2 class="text-2xl font-semibold">Ready to Taste the Difference?</h2>
            <p class="mx-auto mt-2 max-w-xl text-sm text-[#6f4c3b]">
                Join thousands of satisfied customers and experience the BROWSTIME difference today.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ route('landing') }}" class="rounded-full bg-[#7a4b24] px-6 py-3 text-sm font-semibold text-white shadow-[0_12px_28px_rgba(122,75,36,0.35)] transition hover:bg-[#6b3f26]">
                    Start Shopping
                </a>
                <a href="{{ route('register') }}" class="rounded-full border border-[#e6d4c4] bg-[#fff8f1] px-6 py-3 text-sm font-semibold text-[#7a4b24] transition hover:bg-white">
                    Create Account
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
