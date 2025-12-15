<section class="bg-white border border-[#f0e5d9] rounded-2xl shadow-sm p-6 sm:p-8 space-y-6">
    <header class="flex items-start gap-3">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#f7ede1] text-[#7a4b24]">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4 22C4 18.6863 7.58172 16 12 16C16.4183 16 20 18.6863 20 22" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <div>
            <h2 class="text-lg font-semibold text-[#3b241a]">
                {{ __('Profile Information') }}
            </h2>
            <p class="mt-1 text-sm text-[#7a5b44]">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-2 max-w-xl">
            <label for="name" class="text-sm font-semibold text-[#3b241a]">{{ __('Name') }}</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                class="w-full rounded-2xl border border-[#e9dccf] bg-white px-4 py-2.5 text-[#3b241a] focus:border-[#c79c68] focus:ring-2 focus:ring-[#c79c68]"
                required
                autocomplete="name"
            />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-2 max-w-xl">
            <label for="email" class="text-sm font-semibold text-[#3b241a]">{{ __('Email') }}</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                class="w-full rounded-2xl border border-[#e9dccf] bg-white px-4 py-2.5 text-[#3b241a] focus:border-[#c79c68] focus:ring-2 focus:ring-[#c79c68]"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-[#7a5b44]">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-[#7a4b24] hover:text-[#5a3a1f] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#d7b08a]">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-700">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#7a4b24] text-white px-4 py-2 text-sm font-semibold hover:bg-[#643c1d] transition focus:outline-none focus:ring-2 focus:ring-[#d7b08a] focus:ring-offset-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 12.5L9 16.5L19 6.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
