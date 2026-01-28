@extends('layouts.app')

@section('content')
<div class="bg-[#fbf6f1] text-[#3b241a]">
    <section class="bg-[#f1e2d6]">
        <div class="mx-auto max-w-screen-2xl px-6 py-10 sm:px-8 lg:px-14">
            <div class="mt-8 text-center">
                <span class="inline-flex items-center rounded-full bg-[#7a4b24] px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-white">
                    Tentang Browstime
                </span>
                <h1 class="mt-4 text-3xl font-semibold text-[#3b241a] sm:text-4xl">
                    Memanggang Kebahagiaan, Satu Gigitan Sekaligus
                </h1>
                <p class="mx-auto mt-3 max-w-2xl text-sm text-[#4b2f22] sm:text-base">
                    Sejak 2020, kami membuat cookies dan brownies premium dengan penuh kasih,
                    memakai bahan terbaik agar setiap gigitan membawa rasa bahagia.
                </p>
            </div>
        </div>
    </section>

    <section class="bg-[#fbf6f1]">
        <div class="mx-auto max-w-screen-2xl px-6 py-12 sm:px-8 lg:px-14">
            <div class="grid items-center gap-10 lg:grid-cols-[1.05fr,0.95fr]">
                <div class="max-w-md">
                    <p class="text-xs font-semibold uppercase tracking-widest text-[#9a6b4f]">Kisah Kami</p>
                    <h2 class="mt-3 text-2xl font-semibold text-[#3b241a]">BROWSTIME</h2>
                    <div class="mt-4 space-y-3 text-sm leading-relaxed text-[#4b2f22]">
                        <p>
                            BROWSTIME berawal dari dapur rumah dengan misi sederhana: menghadirkan cookies
                            dan brownies senikmat buatan nenek.
                        </p>
                        <p>
                            Bermula dari hobi, kini menjadi usaha serius. Teman dan keluarga jatuh cinta
                            dengan rasa otentik kami, lalu kabar menyebar tentang kualitas yang dijaga.
                        </p>
                        <p>
                            Kini kami melayani ratusan pelanggan di seluruh Indonesia, dengan nilai yang sama:
                            bahan segar, dibuat manual dengan hati, dan dikirim dengan senyum.
                        </p>
                    </div>
                </div>
                <div class="relative">
                    <div class="overflow-hidden rounded-3xl border border-[#f1e8df] bg-white shadow-[0_20px_40px_rgba(0,0,0,0.12)]">
                        <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=900&q=80" alt="Browstime story" class="h-full w-full object-cover" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#f6ede4]">
        <div class="mx-auto max-w-screen-2xl px-6 py-10 sm:px-8 lg:px-14">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-[#3b241a]">Kenapa Pilih BROWSTIME?</h2>
                <p class="mt-2 text-sm text-[#4b2f22]">
                    Kami bukan sekadar toko roti biasa. Ini yang membuat kami berbeda.
                </p>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-[#efe3d7] bg-white p-5 text-center shadow-sm">
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#f4e9dc] text-[#7a4b24]">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M5 10H15" stroke="#7a4b24" stroke-width="1.6" stroke-linecap="round"/>
                            <path d="M10 5V15" stroke="#7a4b24" stroke-width="1.6" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold">Selalu Segar</h3>
                    <p class="mt-2 text-xs text-[#4b2f22]">
                        Setiap adonan dipanggang setelah dipesan, menjaga rasa dan kesegaran maksimal.
                    </p>
                </div>
                <div class="rounded-2xl border border-[#efe3d7] bg-white p-5 text-center shadow-sm">
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#f4e9dc] text-[#7a4b24]">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M6 14L10 6L14 14" stroke="#7a4b24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold">Kualitas Premium</h3>
                    <p class="mt-2 text-xs text-[#4b2f22]">
                        Hanya memakai bahan pilihan: cokelat Belgia, butter asli, dan kacang premium.
                    </p>
                </div>
                <div class="rounded-2xl border border-[#efe3d7] bg-white p-5 text-center shadow-sm">
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#f4e9dc] text-[#7a4b24]">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M10 17C10 17 4 12.5 4 8.5C4 6.01472 6.01472 4 8.5 4C9.88 4 11.16 4.68 12 5.7C12.84 4.68 14.12 4 15.5 4C17.9853 4 20 6.01472 20 8.5C20 12.5 14 17 14 17H10Z" fill="#7a4b24"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold">Dibuat dengan Cinta</h3>
                    <p class="mt-2 text-xs text-[#4b2f22]">
                        Setiap cookies dan brownies dibuat manual oleh baker kami dengan penuh passion.
                    </p>
                </div>
                <div class="rounded-2xl border border-[#efe3d7] bg-white p-5 text-center shadow-sm">
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#f4e9dc] text-[#7a4b24]">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M10 2V10L14 12" stroke="#7a4b24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="10" cy="10" r="8" stroke="#7a4b24" stroke-width="1.6"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-semibold">Pengiriman Cepat</h3>
                    <p class="mt-2 text-xs text-[#4b2f22]">
                        Pengiriman cepat dan terpercaya 1-3 hari langsung ke depan rumahmu.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#fbf6f1]">
        <div class="mx-auto max-w-screen-2xl px-6 py-10 sm:px-8 lg:px-14">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-[#3b241a]">Komitmen Kami untuk Kamu</h2>
                <p class="mt-2 text-sm text-[#4b2f22]">
                    Nilai inti ini selalu kami pegang di BROWSTIME.
                </p>
            </div>

            <div class="mt-8 grid gap-6 text-sm text-[#4b2f22] sm:grid-cols-2 lg:grid-cols-3">
                <div class="flex items-start gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f4e9dc] text-[#7a4b24]">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M6 9L9 12L14 7" stroke="#7a4b24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="10" cy="10" r="8" stroke="#7a4b24" stroke-width="1.6"/>
                        </svg>
                    </span>
                    <div>
                        <p class="font-semibold text-[#3b241a]">Jaminan Kualitas</p>
                        <p class="mt-1 text-xs">Setiap produk melewati pengecekan ketat agar sampai ke kamu dengan standar terbaik kami.</p>
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
                        <p class="font-semibold text-[#3b241a]">Pelanggan Nomor Satu</p>
                        <p class="mt-1 text-xs">Kepuasanmu prioritas kami. Kami dengarkan masukan dan terus meningkatkan produk serta layanan.</p>
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
                        <p class="font-semibold text-[#3b241a]">Keaslian Rasa</p>
                        <p class="mt-1 text-xs">Tanpa jalan pintas, tanpa perasa buatan. Hanya rasa rumahan yang jujur di setiap gigitan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#edd7c6]">
        <div class="mx-auto max-w-screen-2xl px-6 py-8 sm:px-8 lg:px-14">
            <div class="grid gap-6 text-center text-[#7a4b24] sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <p class="text-2xl font-semibold text-[#6b3f26]">5000+</p>
                    <p class="text-xs text-[#4b2f22]">Pelanggan Puas</p>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-[#6b3f26]">15k+</p>
                    <p class="text-xs text-[#4b2f22]">Pesanan Terkirim</p>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-[#6b3f26]">4.8</p>
                    <p class="text-xs text-[#4b2f22]">Rata-Rata Rating</p>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-[#6b3f26]">20+</p>
                    <p class="text-xs text-[#4b2f22]">Varian Produk</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#fbf6f1]">
        <div class="mx-auto max-w-screen-2xl px-6 py-12 sm:px-8 lg:px-14">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-[#3b241a]">Siap Merasakan Bedanya?</h2>
                <p class="mx-auto mt-2 max-w-xl text-sm text-[#4b2f22]">
                    Gabung bersama ribuan pelanggan puas dan rasakan pengalaman BROWSTIME hari ini.
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('landing') }}" class="rounded-full bg-[#7a4b24] px-6 py-3 text-sm font-semibold text-white shadow-[0_12px_28px_rgba(122,75,36,0.35)] transition hover:bg-[#6b3f26]">
                        Mulai Belanja
                    </a>
                    <a href="{{ route('register') }}" class="rounded-full border border-[#e6d4c4] bg-white px-6 py-3 text-sm font-semibold text-[#7a4b24] transition hover:bg-[#fff8f1]">
                        Buat Akun
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
