<section class="relative">
    <div id="password-form-card" class="space-y-6">
        <header>
            <h2 class="text-[16px] font-bold text-gray-900 uppercase tracking-wider">
                {{ __('Update Password') }}
            </h2>

            <p class="text-[13px] text-gray-500 font-medium mt-0.5">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>
        </header>

        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6 max-w-2xl">
            @csrf
            @method('put')

            {{-- Current Password Field --}}
            <div class="space-y-1.5">
                <label for="update_password_current_password" class="block text-[14px] font-bold text-gray-700 tracking-tight">
                    {{ __('Current Password') }}
                </label>
                <input id="update_password_current_password" name="current_password" type="password" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 shadow-sm shadow-gray-50 rounded-xl font-medium text-[15px] text-gray-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-[#228B22]/20 focus:border-[#228B22] placeholder-gray-400" 
                    autocomplete="current-password" />
                @if($errors->updatePassword->get('current_password'))
                    <p class="text-[13px] text-red-600 font-semibold flex items-center gap-1 mt-1">
                        <i class="fas fa-circle-exclamation text-[11px]"></i> {{ $errors->updatePassword->first('current_password') }}
                    </p>
                @endif
            </div>

            {{-- New Password Field --}}
            <div class="space-y-1.5">
                <label for="update_password_password" class="block text-[14px] font-bold text-gray-700 tracking-tight">
                    {{ __('New Password') }}
                </label>
                <input id="update_password_password" name="password" type="password" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 shadow-sm shadow-gray-50 rounded-xl font-medium text-[15px] text-gray-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-[#228B22]/20 focus:border-[#228B22] placeholder-gray-400" 
                    autocomplete="new-password" />
                @if($errors->updatePassword->get('password'))
                    <p class="text-[13px] text-red-600 font-semibold flex items-center gap-1 mt-1">
                        <i class="fas fa-circle-exclamation text-[11px]"></i> {{ $errors->updatePassword->first('password') }}
                    </p>
                @endif
            </div>

            {{-- Confirm Password Field --}}
            <div class="space-y-1.5">
                <label for="update_password_password_confirmation" class="block text-[14px] font-bold text-gray-700 tracking-tight">
                    {{ __('Confirm New Password') }}
                </label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 shadow-sm shadow-gray-50 rounded-xl font-medium text-[15px] text-gray-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-[#228B22]/20 focus:border-[#228B22] placeholder-gray-400" 
                    autocomplete="new-password" />
                @if($errors->updatePassword->get('password_confirmation'))
                    <p class="text-[13px] text-red-600 font-semibold flex items-center gap-1 mt-1">
                        <i class="fas fa-circle-exclamation text-[11px]"></i> {{ $errors->updatePassword->first('password_confirmation') }}
                    </p>
                @endif
            </div>

            {{-- Action Controls --}}
            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="px-6 py-3 bg-[#228B22] text-white hover:bg-[#1a5d1a] font-bold text-[14px] rounded-xl shadow-md shadow-green-100/80 transition duration-200 flex items-center gap-2">
                    <i class="fas fa-key"></i> {{ __('Update Password') }}
                </button>

                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        x-init="setTimeout(() => show = false, 3000)"
                        class="text-[14px] font-semibold text-[#228B22] flex items-center gap-1.5"
                    >
                        <i class="fas fa-circle-check"></i> {{ __('Password updated safely.') }}
                    </p>
                @endif
            </div>
        </form>
    </div>
</section>