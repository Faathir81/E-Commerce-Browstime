<x-auth.layout>
    <div class="w-full max-w-xl space-y-6 text-center">
        <div class="flex items-center justify-center sm:justify-start sm:pl-2">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 rounded-full bg-transparent px-4 py-2 text-sm font-semibold text-[#3b241a] transition hover:bg-[#d4ad7d] hover:text-white hover:shadow">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.66675 12.6667L5.00008 8.00001L9.66675 3.33334" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <div class="bg-white/95 shadow-xl rounded-3xl px-8 sm:px-10 py-10 space-y-6 border border-[#f0e5d9]">
            <div class="w-16 h-16 mx-auto rounded-full bg-[#f7ede1] flex items-center justify-center">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.0001 2.66667C13.363 2.66667 10.7851 3.44866 8.59248 4.91374C6.39983 6.37883 4.69086 8.46121 3.68169 10.8976C2.67253 13.3339 2.40848 16.0148 2.92295 18.6012C3.43742 21.1876 4.7073 23.5634 6.572 25.4281C8.4367 27.2928 10.8125 28.5627 13.3989 29.0771C15.9853 29.5916 18.6662 29.3276 21.1025 28.3184C23.5389 27.3092 25.6213 25.6003 27.0863 23.4076C28.5514 21.215 29.3334 18.6371 29.3334 16C28.4068 16.2853 27.4199 16.3127 26.4788 16.079C25.5378 15.8454 24.6783 15.3597 23.9927 14.6741C23.3071 13.9885 22.8214 13.1289 22.5877 12.1879C22.3541 11.2469 22.3814 10.26 22.6668 9.33334C21.7401 9.61867 20.7532 9.64599 19.8122 9.41237C18.8712 9.17874 18.0116 8.69301 17.326 8.00741C16.6404 7.3218 16.1547 6.46227 15.9211 5.52125C15.6874 4.58023 15.7148 3.59333 16.0001 2.66667Z" stroke="#8B4513" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M11.3333 11.3333V11.3467" stroke="#8B4513" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M21.3333 20.6667V20.68" stroke="#8B4513" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 16V16.0133" stroke="#8B4513" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14.6667 22.6667V22.68" stroke="#8B4513" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9.33325 18.6667V18.68" stroke="#8B4513" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="space-y-1">
                <h1 class="text-xl font-semibold text-[#3b241a]">Selamat Datang Kembali</h1>
                <p class="text-sm text-[#7a5b44]">Masuk ke akun BROWSTIME kamu</p>
            </div>

            <x-auth-session-status class="mb-2 text-sm" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5 text-left">
                @csrf

                <x-auth.input
                    id="email"
                    name="email"
                    type="email"
                    label="Email"
                    placeholder="email@kamu.com"
                    :value="old('email')"
                    autofocus
                    required
                    icon='<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 7.5L12 13.5L3 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 7.5V16.5C21 17.3284 20.3284 18 19.5 18H4.5C3.67157 18 3 17.3284 3 16.5V7.5C3 6.67157 3.67157 6 4.5 6H19.5C20.3284 6 21 6.67157 21 7.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                />

                <div class="space-y-2">
                <x-auth.input
                    id="password"
                    name="password"
                    type="password"
                    label="Password"
                    placeholder="Masukkan kata sandi"
                    required
                    :show-toggle="true"
                    icon='<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 10H7C5.89543 10 5 10.8954 5 12V17C5 18.1046 5.89543 19 7 19H17C18.1046 19 19 18.1046 19 17V12C19 10.8954 18.1046 10 17 10Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 10V7C8 5.34315 9.34315 4 11 4H13C14.6569 4 16 5.34315 16 7V10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                />
                    @if (Route::has('password.request'))
                        <div class="text-right">
                            <a class="text-xs font-semibold text-[#7a4b24] hover:text-[#5a3a1f]" href="{{ route('password.request') }}">
                                Lupa kata sandi?
                            </a>
                        </div>
                    @endif
                </div>

                <x-auth.button type="submit">Masuk</x-auth.button>

                <div class="flex items-center gap-3 text-xs text-[#9b7a64]">
                    <span class="flex-1 h-px bg-[#e8dccf]"></span>
                    <span>atau</span>
                    <span class="flex-1 h-px bg-[#e8dccf]"></span>
                </div>

                <x-auth.button type="button" variant="secondary" onclick="window.location='{{ route('landing') }}'">
                    Lanjut sebagai Tamu
                </x-auth.button>
            </form>

            <div class="text-sm text-[#7a5b44]">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-[#7a4b24] hover:text-[#5a3a1f]">Daftar</a>
            </div>
        </div>

        <p class="text-xs text-[#9b7a64]">
            Dengan melanjutkan, kamu menyetujui
            <a href="#" class="underline hover:text-[#7a4b24]">Syarat Layanan</a>
            dan
            <a href="#" class="underline hover:text-[#7a4b24]">Kebijakan Privasi</a>
            BROWSTIME.
        </p>
    </div>
</x-auth.layout>
