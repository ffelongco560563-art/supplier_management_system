<x-guest-layout>
    {{-- We removed the outer <div> box here because Guest Layout already provides it --}}
    <div class="flex flex-col items-center">
        
        <div class="mb-8">
            <div class="w-24 h-24 rounded-full flex items-center justify-center shadow-md {{ auth()->user()->status === 'declined' ? 'bg-red-600' : 'bg-[#228B22]' }}">
                @if(auth()->user()->status === 'declined')
                    <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                @else
                    <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                @endif
            </div>
        </div>

        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                {{ auth()->user()->status === 'declined' ? 'Account Declined' : 'Account Pending' }}
            </h2>
            <p class="text-gray-500 mt-4 text-base leading-relaxed max-w-sm">
                @if(auth()->user()->status === 'declined')
                    Unfortunately, your supplier registration was not approved. Please see the reason below.
                @else
                    We have received your registration! To keep our milk supply chain secure, our team is currently reviewing your account details.
                @endif
            </p>
        </div>

        <div class="w-full rounded-2xl p-6 mb-10 {{ auth()->user()->status === 'declined' ? 'bg-red-50 border border-red-100' : 'bg-gray-50' }}">
            @if(auth()->user()->status === 'declined')
                <div class="text-left">
                    <p class="text-xs font-bold text-red-600 uppercase mb-1 tracking-widest text-left">Reason for Rejection:</p>
                    <p class="text-lg font-bold text-black italic text-left">
                        "{{ auth()->user()->decline_reason }}"
                    </p>
                </div>
            @else
                <p class="text-sm text-gray-600">
                    You will receive an email notification once your account is approved. Usually, this takes less than 24 hours. Thank you for your patience!
                </p>
            @endif
        </div>

        <div class="w-full px-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-white font-bold py-4 rounded-xl hover:opacity-95 shadow-md text-base uppercase tracking-wider transition duration-200" 
                    style="background-color: {{ auth()->user()->status === 'declined' ? '#dc2626' : '#228B22' }};">
                    Back to Login
                </button>
            </form>
        </div>

    </div>
</x-guest-layout>