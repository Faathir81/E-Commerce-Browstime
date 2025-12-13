<x-app-layout>
    <div class="py-10 px-4 sm:px-6 lg:px-8 bg-[#f9f1e8] min-h-screen">
        <div class="max-w-5xl mx-auto space-y-6">
            @include('profile.partials.update-profile-information-form')
            @include('profile.partials.update-password-form')
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
