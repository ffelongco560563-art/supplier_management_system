<x-guest-layout>
    <div class="flex flex-col items-center">
        
        <div class="mb-8">
            <div class="w-24 h-24 rounded-full flex items-center justify-center shadow-md 
                @if(auth()->user()->status === 'revoked') bg-red-800 
                @elseif(auth()->user()->status === 'declined') bg-red-600 
                @else bg-[#228B22] @endif">
                
                @if(auth()->user()->status === 'revoked')
                    <i class="fas fa-user-slash text-4xl text-white"></i>
                @elseif(auth()->user()->status === 'declined')
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
                @if(auth()->user()->status === 'revoked') Access Revoked
                @elseif(auth()->user()->status === 'declined') Account Declined
                @else Account Pending @endif
            </h2>
            <p class="text-gray-500 mt-4 text-base leading-relaxed max-w-sm">
                @if(auth()->user()->status === 'revoked')
                    Your system access has been revoked by the administrator.
                @elseif(auth()->user()->status === 'declined')
                    Unfortunately, your supplier registration was not approved.
                @else
                    Our team is currently reviewing your account details.
                @endif
            </p>
        </div>

        <div class="w-full rounded-2xl p-8 mb-10 shadow-sm flex flex-col items-center justify-center text-center
            @if(auth()->user()->status === 'revoked') bg-red-50 border border-red-200
            @elseif(auth()->user()->status === 'declined') bg-red-50 border border-red-100 
            @else bg-gray-50 border border-gray-100 @endif">
            
            @if(auth()->user()->status === 'revoked' || auth()->user()->status === 'declined')
                <div>
                    <p class="text-[10px] font-bold @if(auth()->user()->status === 'revoked') text-red-800 @else text-red-600 @endif uppercase mb-2 tracking-widest">
                        Reason for {{ auth()->user()->status === 'revoked' ? 'Revocation' : 'Rejection' }}:
                    </p>
                    <p class="text-xl font-extrabold text-black italic leading-tight px-4">
                        "{{ auth()->user()->decline_reason ?? 'No specific reason provided.' }}"
                    </p>
                </div>
            @else
                {{-- This is the box for the Pending state --}}
                <p class="text-sm text-gray-600 leading-relaxed max-w-xs font-medium">
                    You will receive an email notification once your account is approved. Usually, this takes less than 24 hours. Thank you for your patience!
                </p>
            @endif
        </div>

        <div class="w-full px-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-white font-bold py-4 rounded-xl hover:opacity-95 shadow-md text-base uppercase tracking-wider transition duration-200 active:scale-95" 
                    style="background-color: 
                        @if(auth()->user()->status === 'revoked') #991b1b; 
                        @elseif(auth()->user()->status === 'declined') #dc2626; 
                        @else #228B22; @endif">
                    Back to Login
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>