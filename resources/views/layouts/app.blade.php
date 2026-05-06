<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DairyBest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 overflow-hidden">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-72 bg-[#228B22] text-white flex flex-col shadow-xl">
            {{-- Logo Section --}}
            <div class="pt-14 px-8 pb-8 flex items-center gap-0">
                <div class="w-16 h-16 flex items-center justify-center flex-shrink-0">
                    <img src="{{ asset('images/logo.png') }}" 
                        class="w-14 h-14 object-contain"
                        decoding="sync"
                        loading="eager">
                </div>
                <div class="flex flex-col">
                    <h1 class="text-[26px] font-bold leading-none tracking-tight text-white">
                        DairyBest
                    </h1>
                    <p class="text-[13px] font-normal leading-tight text-white mt-0.5">
                        Davao, Philippines
                    </p>
                </div>
            </div>

            <div class="w-full border-t-2 border-white/20"></div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 space-y-5 mt-8 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-white text-[#228B22] rounded-lg shadow-sm' : 'text-white hover:bg-white/10' }} transition rounded-lg group">
                    <i class="fas fa-border-all text-[20px] w-6 text-center"></i>
                    <span class="text-[16px] font-medium tracking-normal">Dashboard</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-white hover:bg-white/10 transition rounded-lg group">
                    <i class="fas fa-box text-[20px] w-6 text-center"></i>
                    <span class="text-[16px] font-medium tracking-normal">Inventory</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-white hover:bg-white/10 transition rounded-lg group">
                    <i class="fas fa-shopping-cart text-[20px] w-6 text-center"></i>
                    <span class="text-[16px] font-medium tracking-normal">Orders</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-white hover:bg-white/10 transition rounded-lg group">
                    <i class="fas fa-truck text-[20px] w-6 text-center"></i>
                    <span class="text-[16px] font-medium tracking-normal">Logistics</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-white hover:bg-white/10 transition rounded-lg group">
                    <i class="fas fa-file-lines text-[20px] w-6 text-center"></i>
                    <span class="text-[16px] font-medium tracking-normal">Reports</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.users.index') ? 'bg-white text-[#228B22] rounded-lg shadow-sm' : 'text-white hover:bg-white/10' }} transition rounded-lg group">
                    <i class="fas fa-user-group text-[20px] w-6 text-center"></i>
                    <span class="text-[16px] font-medium tracking-normal">Users</span>
                </a>
            </nav>

            <div class="w-full border-t-2 border-white/20"></div>

            {{-- SIDEBAR PROFILE (Bottom) --}}
            <div class="px-6 py-6 flex items-center gap-4 bg-[#228B22] mt-auto">
                {{-- Fixed Letter Size and Alignment here --}}
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center font-bold text-[#228B22] text-[26px] shadow-sm uppercase leading-none">
                    {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-[15px] font-semibold text-white leading-tight truncate">Hello, {{ Auth::user()->first_name ?? Auth::user()->name }}!</p>
                    <p class="text-[11px] text-white/80 font-medium tracking-wide">
                        {{ Auth::user()->role === 'admin' ? 'System Administrator' : 'Authorized Supplier' }}
                    </p>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden bg-white">
            
            <header class="w-full bg-white border-b border-gray-200 px-10 py-5 flex items-center justify-between shadow-sm z-10 h-[81px]">
                <div class="flex items-center gap-3 text-[#6B7280]">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="6" width="18" height="15" rx="3" stroke="currentColor" stroke-width="2"/>
                        <path d="M16 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M3 11H21" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <span class="text-[16px] font-normal tracking-tight">
                        {{ date('l, F j, Y') }} | <span id="live-clock" class="font-normal text-[#6B7280]">{{ date('h:i A') }}</span>
                    </span>
                </div>

                <div class="flex items-center gap-5">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center focus:outline-none">
                            {{-- HEADER PROFILE CIRCLE --}}
                            <div class="h-10 w-10 rounded-full bg-[#228B22] flex items-center justify-center text-white font-bold border-2 border-white shadow-sm hover:opacity-90 transition uppercase text-[20px] leading-none">
                                {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                <i class="far fa-user-circle"></i> Profile
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                            <hr class="my-1 border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2 font-bold">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gray-50 p-10">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const options = { hour: '2-digit', minute: '2-digit', hour12: true };
            document.getElementById('live-clock').innerText = now.toLocaleTimeString('en-US', options);
        }
        setInterval(updateClock, 1000);
    </script>
</body>
</html>