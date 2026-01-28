<footer class="w-full mt-20 bg-[#ffffff] border-t border-[#e8ded3]">
    <div class="mx-auto max-w-screen-2xl px-6 sm:px-8 lg:px-14 py-12">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- LOGO + TAGLINE --}}
            <div>
                <div class="flex items-center gap-2">
                    @include('components.logo')
                </div>
                <p class="mt-3 text-sm text-[#7c6a5a] leading-relaxed">
                    Menciptakan momen lezat, satu gigitan penuh kenangan.
                </p>
            </div>

            {{-- SHOP --}}
            <div>
                <h3 class="text-[#3b241a] font-semibold mb-3">Belanja</h3>
                <ul class="space-y-2 text-sm text-[#7c6a5a]">
                    <li><a href="#" class="hover:text-[#3b241a]">Cookies</a></li>
                    <li><a href="#" class="hover:text-[#3b241a]">Brownies</a></li>
                    <li><a href="#" class="hover:text-[#3b241a]">Kotak Hadiah</a></li>
                </ul>
            </div>

            {{-- SUPPORT --}}
            <div>
                <h3 class="text-[#3b241a] font-semibold mb-3">Bantuan</h3>
                <ul class="space-y-2 text-sm text-[#7c6a5a]">
                    <li><a href="#" class="hover:text-[#3b241a]">Hubungi Kami</a></li>
                </ul>
            </div>

            {{-- FOLLOW US --}}
            <div>
                <h3 class="text-[#3b241a] font-semibold mb-3">Ikuti Kami</h3>
                <p class="text-sm text-[#7c6a5a]">
                    Dapatkan kabar terbaru soal promo dan kreasi kami!
                </p>
            </div>

        </div>

        {{-- COPYRIGHT --}}
        <div class="mt-10 pt-6 border-t border-[#e8ded3] text-center">
            <p class="text-xs text-[#7c6a5a]">
                © 2025 BROWSTIME. Hak cipta dilindungi.
            </p>
        </div>

    </div>
</footer>
