<section class="space-y-6">
    <header>
        <h2 class="text-[16px] font-bold text-red-600 uppercase tracking-wider">
            {{ __('Delete Account') }}
        </h2>

        <p class="text-[13px] text-gray-500 font-medium mt-0.5 max-w-2xl">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    {{-- Trigger button with shadow depth --}}
    <button type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-3 bg-red-600 text-white hover:bg-red-700 font-bold text-[14px] rounded-xl shadow-md shadow-red-100 transition duration-200 flex items-center gap-2"
    >
        <i class="fas fa-trash-can"></i> {{ __('Delete Account') }}
    </button>

    {{-- Modal redesigned with clean corner shadow definitions --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="p-8 space-y-6 bg-white rounded-3xl shadow-xl border border-gray-100">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="space-y-1.5">
                    <h2 class="text-[18px] font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                        <i class="fas fa-triangle-exclamation text-red-500"></i> {{ __('Are you sure you want to delete your account?') }}
                    </h2>
                    <p class="text-[13px] text-gray-500 font-medium leading-relaxed">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your account authorization password to confirm you would like to permanently delete your workspace assets.') }}
                    </p>
                </div>

                {{-- Input Field --}}
                <div class="space-y-1.5 max-w-xl mt-6">
                    <label for="password" class="block text-[14px] font-bold text-gray-700 tracking-tight">
                        {{ __('Confirm Account Password') }}
                    </label>
                    <input id="password" name="password" type="password" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 shadow-sm shadow-gray-50 rounded-xl font-medium text-[15px] text-gray-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500/10 focus:border-red-500 placeholder-gray-400" 
                        placeholder="Enter password to authenticate" />
                    @if($errors->userDeletion->get('password'))
                        <p class="text-[13px] text-red-600 font-semibold flex items-center gap-1 mt-1">
                            <i class="fas fa-circle-exclamation text-[11px]"></i> {{ $errors->userDeletion->first('password') }}
                        </p>
                    @endif
                </div>

                {{-- Custom Modal Button Footer --}}
                <div class="flex items-center justify-end gap-3 pt-4 mt-6 border-t border-gray-100">
                    <button type="button" x-on:click="$dispatch('close')" 
                        class="px-6 py-3 bg-gray-100 text-gray-600 hover:bg-gray-200 font-bold text-[14px] rounded-xl transition duration-200">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" 
                        class="px-6 py-3 bg-red-600 text-white hover:bg-red-700 font-bold text-[14px] rounded-xl shadow-md shadow-red-200 transition duration-200">
                        {{ __('Permanently Delete') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</section>