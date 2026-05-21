<section class="relative">
    {{-- Form Content Card --}}
    <div id="profile-form-card" class="space-y-6">
        <header>
            <h2 class="text-[16px] font-bold text-gray-900 uppercase tracking-wider">
                {{ __('Profile Information') }}
            </h2>

            <p class="text-[13px] text-gray-500 font-medium mt-0.5">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        </header>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6 max-w-2xl">
            @csrf
            @method('patch')

            {{-- Name Field Container --}}
            <div class="space-y-1.5">
                <label for="name" class="block text-[14px] font-bold text-gray-700 tracking-tight">
                    {{ __('Name') }}
                </label>
                <input id="name" name="name" type="text" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-medium text-[15px] text-gray-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-[#228B22]/20 focus:border-[#228B22] placeholder-gray-400" 
                    value="{{ old('name', $user->name) }}" 
                    required autofocus autocomplete="name" />
                @if($errors->get('name'))
                    <p class="text-[13px] text-red-600 font-semibold flex items-center gap-1 mt-1">
                        <i class="fas fa-circle-exclamation text-[11px]"></i> {{ $errors->first('name') }}
                    </p>
                @endif
            </div>

            {{-- Email Field Container --}}
            <div class="space-y-1.5">
                <label for="email" class="block text-[14px] font-bold text-gray-700 tracking-tight">
                    {{ __('Email Address') }}
                </label>
                <input id="email" name="email" type="email" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-medium text-[15px] text-gray-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-[#228B22]/20 focus:border-[#228B22] placeholder-gray-400" 
                    value="{{ old('email', $user->email) }}" 
                    required autocomplete="username" />
                @if($errors->get('email'))
                    <p class="text-[13px] text-red-600 font-semibold flex items-center gap-1 mt-1">
                        <i class="fas fa-circle-exclamation text-[11px]"></i> {{ $errors->first('email') }}
                    </p>
                @endif

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3 p-4 bg-amber-50 rounded-2xl border border-amber-100">
                        <p class="text-[13px] text-amber-800 font-medium flex items-center gap-2">
                            <i class="fas fa-triangle-exclamation text-[14px]"></i>
                            <span>{{ __('Your email address is unverified.') }}</span>
                            <button form="send-verification" class="ml-1 font-bold underline text-amber-900 hover:text-amber-950 transition">
                                {{ __('Click here to re-send verification.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-[12px] font-bold text-[#228B22] flex items-center gap-1.5">
                                <i class="fas fa-circle-check"></i> {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Form Action Footer --}}
            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="px-6 py-3 bg-[#228B22] text-white hover:bg-[#1a5d1a] font-bold text-[14px] rounded-xl shadow-md shadow-green-100 transition duration-200 flex items-center gap-2">
                    <i class="fas fa-floppy-disk"></i> {{ __('Save Changes') }}
                </button>

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        x-init="setTimeout(() => show = false, 3000)"
                        class="text-[14px] font-semibold text-[#228B22] flex items-center gap-1.5"
                    >
                        <i class="fas fa-circle-check"></i> {{ __('Changes saved successfully.') }}
                    </p>
                @endif
            </div>
        </form>
    </div>
</section>