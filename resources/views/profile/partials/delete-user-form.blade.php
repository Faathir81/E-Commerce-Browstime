<section class="space-y-6 bg-white border border-[#f5cdd1] rounded-2xl shadow-sm p-6 sm:p-8">
    <header class="flex items-start gap-3">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#ffe6ea] text-[#d43f5e]">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6H6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9 6V5C9 4.44772 9.44772 4 10 4H14C14.5523 4 15 4.44772 15 5V6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10 11V16" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M14 11V16" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M19 6V19C19 20.1046 18.1046 21 17 21H7C5.89543 21 5 20.1046 5 19V6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <div>
            <h2 class="text-lg font-semibold text-[#d43f5e]">
                {{ __('Delete Account') }}
            </h2>
            <p class="mt-1 text-sm text-[#9b5c6a]">
                {{ __('Permanently delete your account and all of your data.') }}
            </p>
        </div>
    </header>

    <p class="text-sm text-[#7a5b44]">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>

    <x-danger-button
        x-data=""
        class="inline-flex items-center gap-2 rounded-full bg-[#d43f5e] hover:bg-[#b73552] text-white px-4 py-2 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#f2a5b5] focus:ring-offset-2"
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 6H6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M9 6V5C9 4.44772 9.44772 4 10 4H14C14.5523 4 15 4.44772 15 5V6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10 11V16" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M14 11V16" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M19 6V19C19 20.1046 18.1046 21 17 21H7C5.89543 21 5 20.1046 5 19V6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{ __('Delete Account') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-[#3b241a]">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="text-sm text-[#7a5b44]">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="text-sm font-semibold text-[#3b241a]">{{ __('Password') }}</label>

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full rounded-2xl border border-[#e9dccf] bg-white px-3 py-2 focus:border-[#c79c68] focus:ring-2 focus:ring-[#c79c68]"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button class="rounded-full px-4 py-2 text-sm" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3 inline-flex items-center gap-2 rounded-full bg-[#d43f5e] hover:bg-[#b73552] text-white px-4 py-2 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#f2a5b5] focus:ring-offset-2">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
