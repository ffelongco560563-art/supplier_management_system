<x-guest-layout>
    <div class="mb-6 text-left">
        <h2 class="text-2xl font-bold text-gray-900">Create Client Account</h2>
        <p class="text-gray-500 mt-1 text-sm">Register to start ordering premium milk products</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <x-input-label for="first_name" :value="__('First Name:')" class="font-bold text-gray-700 mb-1 text-sm" />
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" /></svg>
                    </span>
                    <x-text-input id="first_name" class="pl-10" type="text" name="first_name" placeholder="First name" required />
                </div>
            </div>
            <div class="flex-1">
                <x-input-label for="last_name" :value="__('Last Name:')" class="font-bold text-gray-700 mb-1 text-sm" />
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" /></svg>
                    </span>
                    <x-text-input id="last_name" class="pl-10" type="text" name="last_name" placeholder="Last name" required />
                </div>
            </div>
        </div>

        <div class="mb-4">
            <x-input-label for="email" :value="__('Email Address:')" class="font-bold text-gray-700 mb-1 text-sm" />
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" /></svg>
                </span>
                <x-text-input id="email" class="pl-11" type="email" name="email" placeholder="Enter your email" required />
            </div>
        </div>

        <!-- Password (Toggle Added) -->
        <div class="mb-4" x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password:')" class="font-bold text-gray-700 mb-1 text-sm" />
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2" /></svg>
                </span>
                <x-text-input id="password" class="pl-11 pr-11" ::type="show ? 'text' : 'password'" name="password" placeholder="Password" required />
                
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition">
                    <svg x-show="!show" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    </svg>
                    <svg x-show="show" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A10.07 10.07 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" stroke-linecap="round"/>
                        <line x1="1" y1="1" x2="23" y2="23" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Confirm Password (Toggle Added) -->
        <div class="mb-6" x-data="{ show: false }">
            <x-input-label for="password_confirmation" :value="__('Confirm Password:')" class="font-bold text-gray-700 mb-1 text-sm" />
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2" /></svg>
                </span>
                <x-text-input id="password_confirmation" class="pl-11 pr-11" ::type="show ? 'text' : 'password'" name="password_confirmation" placeholder="Confirm Password" required />
                
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition">
                    <svg x-show="!show" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    </svg>
                    <svg x-show="show" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A10.07 10.07 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" stroke-linecap="round"/>
                        <line x1="1" y1="1" x2="23" y2="23" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="w-full text-white font-bold py-3.5 rounded-lg hover:opacity-95 shadow-md text-base uppercase tracking-wider transition duration-150 ease-in-out" style="background-color: #228B22;">
            Create Account
        </button>

        <p class="text-center mt-4 text-sm text-gray-500">
            Already have an account? <a href="{{ route('login') }}" class="font-bold text-[#228B22] hover:underline">Login here</a>
        </p>
    </form>
</x-guest-layout>