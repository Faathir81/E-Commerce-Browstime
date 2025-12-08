<footer class="w-full mt-20 bg-[#ffffff] border-t border-[#e8ded3]">
    <div class="mx-auto max-w-screen-2xl px-6 sm:px-8 lg:px-14 py-12">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- LOGO + TAGLINE --}}
            <div>
                <div class="flex items-center gap-2">
                    @include('components.logo')
                </div>
                <p class="mt-3 text-sm text-[#7c6a5a] leading-relaxed">
                    Crafting delicious memories, one bite at a time.
                </p>
            </div>

            {{-- SHOP --}}
            <div>
                <h3 class="text-[#3b241a] font-semibold mb-3">Shop</h3>
                <ul class="space-y-2 text-sm text-[#7c6a5a]">
                    <li><a href="#" class="hover:text-[#3b241a]">Cookies</a></li>
                    <li><a href="#" class="hover:text-[#3b241a]">Brownies</a></li>
                    <li><a href="#" class="hover:text-[#3b241a]">Gift Boxes</a></li>
                </ul>
            </div>

            {{-- SUPPORT --}}
            <div>
                <h3 class="text-[#3b241a] font-semibold mb-3">Support</h3>
                <ul class="space-y-2 text-sm text-[#7c6a5a]">
                    <li><a href="#" class="hover:text-[#3b241a]">Contact Us</a></li>
                    <li><a href="#" class="hover:text-[#3b241a]">Delivery Info</a></li>
                    <li><a href="#" class="hover:text-[#3b241a]">Returns</a></li>
                </ul>
            </div>

            {{-- FOLLOW US --}}
            <div>
                <h3 class="text-[#3b241a] font-semibold mb-3">Follow Us</h3>
                <p class="text-sm text-[#7c6a5a]">
                    Stay updated with our latest treats and offers!
                </p>
            </div>

        </div>

        {{-- COPYRIGHT --}}
        <div class="mt-10 pt-6 border-t border-[#e8ded3] text-center">
            <p class="text-xs text-[#7c6a5a]">
                © 2025 BROWSTIME. All rights reserved.
            </p>
        </div>

    </div>
</footer>
