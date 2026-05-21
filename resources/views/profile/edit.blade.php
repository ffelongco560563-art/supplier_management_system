<x-app-layout>
    {{-- Header Container - Premium Dark Forest Green Design with Decorative Blurs (Icon Removed) --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-[#1a5d1a] to-[#228B22] px-10 py-10 rounded-[32px] shadow-xl shadow-green-100/40 mb-6">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-white/5 rounded-full blur-2xl"></div>

        <div class="relative flex items-center justify-between">
            <div>
                <span class="bg-white/20 text-white font-bold text-[11px] uppercase tracking-widest px-3 py-1 rounded-full backdrop-blur-sm">
                    Account Profile
                </span>
                <h1 class="text-[32px] font-extrabold text-white tracking-tight mt-2">
                    My Profile Setup
                </h1>
                <p class="text-white/80 font-medium text-[14px] mt-1">
                    Manage your internal identity details, application password configurations, and record retention
                </p>
            </div>
        </div>
    </div>

    {{-- Main Container Workspace - Enhanced Corner Shadow Effects --}}
    <div class="space-y-6">
        {{-- Profile Information Card Container with Premium Shadows --}}
        <div class="bg-white rounded-[32px] shadow-md shadow-gray-100 border border-gray-100 p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- Password Control Card Container with Premium Shadows --}}
        <div class="bg-white rounded-[32px] shadow-md shadow-gray-100 border border-gray-100 p-8">
            @include('profile.partials.update-password-form')
        </div>

        {{-- Retention / Danger Card Container with Premium Shadows --}}
        <div class="bg-white rounded-[32px] shadow-md shadow-red-50/50 border border-red-100/60 bg-gradient-to-b from-white to-red-50/10 p-8">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>