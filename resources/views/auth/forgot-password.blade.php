<x-guest-layout>
    <div class="py-4 flex flex-col items-center text-center">
        
        @if (session('status'))
            <div class="mb-6">
                <div class="w-20 h-20 bg-[#228B22] rounded-full flex items-center justify-center shadow-md">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Email Sent!</h2>
                <p class="text-gray-500 mt-2 text-sm leading-relaxed max-w-sm">
                    We've sent a reset link to your email. Please check your inbox.
                </p>
            </div>

            <div class="w-full bg-gray-50 rounded-2xl p-4 mb-6">
                <p class="text-xs text-gray-600">
                    Check your spam folder if you don't see it within a few minutes.
                </p>
            </div>
        @else
            <div class="mb-6">
                <div class="w-20 h-20 bg-[#228B22] rounded-full flex items-center justify-center shadow-md">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
            </div>

            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Reset Password</h2>
                <p class="text-gray-500 mt-2 text-sm leading-relaxed max-w-xs">
                    Enter your email and we'll send you a recovery link.
                </p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="w-full">
            @csrf
            
            @if (!session('status'))
                <div class="mb-6 text-left">
                    <x-input-label for="email" :value="__('Email Address:')" class="font-bold text-gray-700 mb-1 text-xs" />
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" />
                            </svg>
                        </span>
                        <x-text-input id="email" class="pl-10 py-2" type="email" name="email" :value="old('email')" placeholder="Enter your email" required autofocus />
                    </div>
                </div>
            @endif

            <div class="flex flex-col gap-4">
                @if (!session('status'))
                    <button type="submit" class="w-full text-white font-bold py-3 rounded-lg hover:opacity-95 shadow-md text-sm uppercase tracking-wider transition" style="background-color: #228B22;">
                        Submit Reset Link
                    </button>
                @endif

                <a href="{{ route('login') }}" class="text-center font-bold text-xs text-[#228B22] hover:underline transition underline-offset-4">
                    Back to Login
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>