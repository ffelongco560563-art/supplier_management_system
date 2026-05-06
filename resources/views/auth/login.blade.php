<x-guest-layout>
    <div class="mb-6 text-left">
        <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
        <p class="text-gray-500 mt-1 text-sm">Please login to access your dashboard</p>
    </div>

    @if ($errors->any())
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="mb-5 p-4 bg-red-100 border-l-4 border-red-600 rounded-r-xl shadow-sm">
            <div class="flex items-center mb-1">
                <svg class="w-4 h-4 text-red-700 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span class="text-red-900 font-bold text-xs uppercase tracking-wider">Invalid Credentials</span>
            </div>
            <ul class="list-none text-xs text-red-700 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <x-input-label for="email" :value="__('Email:')" class="font-bold text-gray-700 mb-1 text-sm" />
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2"/>
                    </svg>
                </span>
                <x-text-input id="email" 
                    class="pl-11" 
                    type="email" name="email" :value="old('email')" placeholder="Enter your email" required autofocus />
            </div>
        </div>

        <div class="mb-4" x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password:')" class="font-bold text-gray-700 mb-1 text-sm" />
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2"/>
                    </svg>
                </span>
                <x-text-input id="password" 
                    class="pl-11 pr-11" 
                    ::type="show ? 'text' : 'password'" name="password" placeholder="Enter your password" required />
                
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 transition hover:text-gray-600">
                    
                    <svg x-show="!show" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    </svg>

                    <svg x-show="show" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A10.07 10.07 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" stroke-linecap="round"/>
                        <line x1="1" y1="1" x2="23" y2="23" stroke-linecap="round"/>
                    </svg>

                </button>
            </div>
        </div>

        <div class="flex items-center justify-between mb-6 text-sm">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 border-gray-300 bg-[#F3F4F6] text-[#228B22] focus:ring-[#228B22] rounded shadow-inner">
                <span class="ml-2 text-gray-600">Remember me</span>
            </label>
            <a href="{{ route('password.request') }}" class="font-bold text-[#228B22] hover:underline">Forgot password?</a>
        </div>

        <button type="submit" class="w-full text-white font-bold py-3.5 rounded-xl hover:opacity-95 shadow-md text-base uppercase tracking-wider mb-6 transition" style="background-color: #228B22;">
            Login
        </button>

        <p class="text-center text-sm text-gray-500">
            Don't have an account? <a href="{{ route('register') }}" class="font-bold text-[#228B22] hover:underline">Register as Client</a>
        </p>
    </form>
</x-guest-layout>