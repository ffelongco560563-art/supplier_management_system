<x-app-layout>
    <style>
        /* Sidebar indicator style for boxes */
        #dashboard-summary-box, .stat-card-custom { position: relative; overflow: hidden; }
        
        /* Shared style for the vertical pill indicator */
        #dashboard-summary-box::before,
        .stat-card-custom::before {
            content: ""; 
            position: absolute; 
            left: 0; 
            top: 15px; 
            bottom: 15px; 
            width: 6px; /* Slightly thicker for better visibility */
            border-radius: 50px; /* This creates the circle/pill edge */
            z-index: 10;
        }

        /* Specific Colors for Indicators */
        #dashboard-summary-box::before { background-color: #228B22; }
        .border-forest::before { background-color: #228B22; }
        .border-orange::before { background-color: #FF8C00; }
        .border-red::before { background-color: #ef4444; }

        .stat-card { transition: all 0.2s ease; border: 1px solid #e5e7eb; }
        .stat-card:hover { transform: translateY(-3px); border-color: #228B22; }

        .action-card {
            display: flex; align-items: center; gap: 16px; padding: 20px;
            border: 1px solid #e5e7eb; border-radius: 16px;
            transition: all 0.2s ease;
        }
        .action-card:hover {
            border-color: #228B22;
            box-shadow: 0 10px 15px -3px rgba(34, 139, 34, 0.1);
            filter: brightness(95%);
        }
    </style>

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-[28px] font-bold text-black tracking-tight">Admin Dashboard</h1>
                <p class="text-[14px] text-gray-500 font-medium mt-1">Complete system overview and management controls</p>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Total Stock --}}
            <div class="bg-white p-8 rounded-2xl border border-gray-100 border-l-[6px] border-l-[#228B22] shadow-lg hover:-translate-y-1 transition-all duration-300 min-h-[140px]">
                <div class="flex justify-between items-center h-full">

                    <div class="flex flex-col justify-center gap-2">
                        <p class="text-sm font-bold text-black uppercase">
                            Total Stock Available
                        </p>

                        <h3 class="text-4xl font-black text-[#228B22]">
                            {{ number_format($totalStock ?? 0) }}
                        </h3>

                        <p class="text-[13px] text-gray-500 font-medium">
                            All products combined
                        </p>
                    </div>

                    <div class="bg-green-50 p-4 rounded-xl">
                        <i class="fas fa-layer-group text-[#228B22] text-2xl"></i>
                    </div>

                </div>
            </div>

            {{-- Pending Process --}}
            <div class="bg-white p-8 rounded-2xl border border-gray-100 border-l-[6px] border-l-[#FF8C00] shadow-lg hover:-translate-y-1 transition-all duration-300 min-h-[140px]">
                <div class="flex justify-between items-center h-full">

                    <div class="flex flex-col justify-center gap-2">
                        <p class="text-sm font-bold text-black uppercase">
                            Pending Process
                        </p>

                        <h3 class="text-4xl font-black text-[#FF8C00]">
                            18
                        </h3>

                        <p class="text-[13px] text-gray-500 font-medium">
                            Awaiting processing
                        </p>
                    </div>

                    <div class="bg-orange-50 p-4 rounded-xl">
                        <i class="fas fa-shopping-cart text-[#FF8C00] text-2xl"></i>
                    </div>

                </div>
            </div>

            {{-- Low Stock --}}
            <div class="bg-white p-8 rounded-2xl border border-gray-100 border-l-[6px] border-l-red-500 shadow-lg hover:-translate-y-1 transition-all duration-300 min-h-[140px]">
                <div class="flex justify-between items-center h-full">

                    <div class="flex flex-col justify-center gap-2">
                        <p class="text-sm font-bold text-black uppercase">
                            Low Stock Products
                        </p>

                        <h3 class="text-4xl font-black text-red-500">
                            {{ $lowStockCount ?? 0 }}
                        </h3>

                        <p class="text-[13px] text-gray-500 font-medium">
                            Requires replenishment
                        </p>
                    </div>

                    <div class="bg-red-50 p-4 rounded-xl">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    </div>

                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Inventory Overview Box --}}
            <div id="dashboard-summary-box"
    class="lg:col-span-2 bg-white p-8 rounded-2xl border border-gray-300 shadow-md">

    <div class="mb-8">

        <h4 class="text-sm font-bold text-black uppercase tracking-wider">
            Inventory Overview
        </h4>

        <p class="text-xs text-gray-500 mt-1">
            Product inventory distribution and stock analytics
        </p>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- MODERN BAR GRAPH --}}
        <div class="xl:col-span-2 bg-gradient-to-b from-gray-50 to-white rounded-2xl border border-gray-200 p-6">

            <div class="flex items-end justify-between h-72 gap-6">

                {{-- BOTTLE MILK --}}
                <div class="flex flex-col items-center w-full">

                    <div class="relative flex items-end w-full h-56">

                        <div class="w-full rounded-2xl bg-[#228B22] shadow-lg shadow-green-200/60 transition-all hover:scale-105"
                            style="height: 85%;">

                            <div class="flex items-start justify-center h-full pt-4">
                                <span class="text-white text-xs font-black">85%</span>
                            </div>

                        </div>

                    </div>

                    <div class="mt-4 text-center">

                        <p class="text-sm font-bold text-gray-800">
                            Bottle Milk
                        </p>

                        <p class="text-[11px] text-gray-400 font-medium">
                            High Stock
                        </p>

                    </div>

                </div>

                {{-- MILK BAR --}}
                <div class="flex flex-col items-center w-full">

                    <div class="relative flex items-end w-full h-56">

                        <div class="w-full rounded-2xl bg-[#FF8C00] shadow-lg shadow-orange-200/60 transition-all hover:scale-105"
                            style="height: 58%;">

                            <div class="flex items-start justify-center h-full pt-4">
                                <span class="text-white text-xs font-black">58%</span>
                            </div>

                        </div>

                    </div>

                    <div class="mt-4 text-center">

                        <p class="text-sm font-bold text-gray-800">
                            Milk Bar
                        </p>

                        <p class="text-[11px] text-gray-400 font-medium">
                            Medium Stock
                        </p>

                    </div>

                </div>

                {{-- ICE CREAM --}}
                <div class="flex flex-col items-center w-full">

                    <div class="relative flex items-end w-full h-56">

                        <div class="w-full rounded-2xl bg-[#007BFF] shadow-lg shadow-blue-200/60 transition-all hover:scale-105"
                            style="height: 40%;">

                            <div class="flex items-start justify-center h-full pt-4">
                                <span class="text-white text-xs font-black">40%</span>
                            </div>

                        </div>

                    </div>

                    <div class="mt-4 text-center">

                        <p class="text-sm font-bold text-gray-800">
                            Ice Cream
                        </p>

                        <p class="text-[11px] text-gray-400 font-medium">
                            Low Stock
                        </p>

                    </div>

                </div>

            </div>

        </div>

        {{-- MODERN PIE CHART --}}
        <div class="bg-gradient-to-b from-gray-50 to-white rounded-2xl border border-gray-200 p-6 flex flex-col items-center justify-center">

            <div class="relative w-56 h-56 rounded-full shadow-inner"
                style="
                    background:
                    conic-gradient(
                        #228B22 0% 50%,
                        #FF8C00 50% 80%,
                        #007BFF 80% 100%
                    );
                ">

                <div class="absolute inset-7 bg-white rounded-full flex flex-col items-center justify-center shadow-md">

                    <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400">
                        Products
                    </p>

                    <h3 class="text-3xl font-black text-[#228B22] mt-1">
                        3
                    </h3>

                </div>

            </div>

            {{-- LEGEND --}}
            <div class="w-full mt-8 space-y-3">

                <div class="flex items-center justify-between text-sm">

                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#228B22]"></span>
                        <span class="font-semibold text-gray-700">Bottle Milk</span>
                    </div>

                    <span class="text-gray-500 font-bold">50%</span>

                </div>

                <div class="flex items-center justify-between text-sm">

                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#FF8C00]"></span>
                        <span class="font-semibold text-gray-700">Milk Bar</span>
                    </div>

                    <span class="text-gray-500 font-bold">30%</span>

                </div>

                <div class="flex items-center justify-between text-sm">

                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#007BFF]"></span>
                        <span class="font-semibold text-gray-700">Ice Cream</span>
                    </div>

                    <span class="text-gray-500 font-bold">20%</span>

                </div>

            </div>

        </div>

    </div>

</div>

            {{-- Quick Actions Section --}}
            <div class="bg-white p-8 rounded-2xl border border-gray-300 shadow-md">
                <h4 class="text-sm font-bold text-black mb-4">Quick Actions:</h4>
                
                <div class="flex flex-col gap-4">
                    <a href="{{ route('admin.inventory.index') }}" class="action-card bg-[#228B22]">
                        <div class="w-12 h-12 bg-white/20 text-white rounded-xl flex items-center justify-center shadow-inner">
                            <i class="fas fa-boxes text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">Manage Product</p>
                            <p class="text-[11px] text-white/80 font-medium">Update and track items</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.orders.index') }}" class="action-card bg-[#FF8C00]">
                        <div class="w-12 h-12 bg-white/20 text-white rounded-xl flex items-center justify-center shadow-inner">
                            <i class="fas fa-shopping-cart text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">Process Orders</p>
                            <p class="text-[11px] text-white/80 font-medium">Manage pending sales</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.logistics.index') }}" class="action-card bg-[#007BFF]">
                        <div class="w-12 h-12 bg-white/20 text-white rounded-xl flex items-center justify-center shadow-inner">
                            <i class="fas fa-truck text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">Logistics</p>
                            <p class="text-[11px] text-white/80 font-medium">Manage deliveries and drivers</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="action-card bg-[#6F42C1]">
                        <div class="w-12 h-12 bg-white/20 text-white rounded-xl flex items-center justify-center shadow-inner">
                            <i class="fas fa-users text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">Manage Users</p>
                            <p class="text-[11px] text-white/80 font-medium">Control system access</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.payments.index') }}" class="action-card bg-[#DC3545]">
                        <div class="w-12 h-12 bg-white/20 text-white rounded-xl flex items-center justify-center shadow-inner">
                            <i class="fas fa-credit-card text-lg"></i>
                        </div>

                        <div>
                            <p class="text-sm font-bold text-white">Payments</p>
                            <p class="text-[11px] text-white/80 font-medium">Track and confirm payments</p>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>