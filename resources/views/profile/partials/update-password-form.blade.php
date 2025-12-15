<section class="bg-white border border-[#f0e5d9] rounded-2xl shadow-sm p-6 sm:p-8 space-y-6">
    <header class="flex items-start gap-3">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#f7ede1] text-[#7a4b24]">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17 10H7C5.89543 10 5 10.8954 5 12V17C5 18.1046 5.89543 19 7 19H17C18.1046 19 19 18.1046 19 17V12C19 10.8954 18.1046 10 17 10Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 10V7C8 5.34315 9.34315 4 11 4H13C14.6569 4 16 5.34315 16 7V10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <div>
            <h2 class="text-lg font-semibold text-[#3b241a]">
                {{ __('Update Password') }}
            </h2>
            <p class="mt-1 text-sm text-[#7a5b44]">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-2 max-w-xl">
            <label for="update_password_current_password" class="text-sm font-semibold text-[#3b241a]">{{ __('Current Password') }}</label>
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full rounded-2xl border border-[#e9dccf] bg-white px-3 py-2 focus:border-[#c79c68] focus:ring-2 focus:ring-[#c79c68]" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div class="space-y-2 max-w-xl">
            <label for="update_password_password" class="text-sm font-semibold text-[#3b241a]">{{ __('New Password') }}</label>
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full rounded-2xl border border-[#e9dccf] bg-white px-3 py-2 focus:border-[#c79c68] focus:ring-2 focus:ring-[#c79c68]" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div class="space-y-2 max-w-xl">
            <label for="update_password_password_confirmation" class="text-sm font-semibold text-[#3b241a]">{{ __('Confirm Password') }}</label>
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-2xl border border-[#c79c68] bg-white px-3 py-2 focus:border-[#c79c68] focus:ring-2 focus:ring-[#c79c68]" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#7a4b24] text-white px-4 py-2 text-sm font-semibold hover:bg-[#643c1d] transition focus:outline-none focus:ring-2 focus:ring-[#d7b08a] focus:ring-offset-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 12.5L9 16.5L19 6.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
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
