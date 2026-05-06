<x-app-layout>
    <div class="p-10 max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900">Admin Dashboard</h2>
        <p class="text-gray-500 mt-1">Complete system overview and management controls</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-green-600 rounded-lg text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-width="2"/></svg>
                    </div>
                    <span class="text-green-500 text-sm font-bold">+12%</span>
                </div>
                <p class="text-gray-500 font-medium text-sm">Total Stock Level</p>
                <h3 class="text-3xl font-bold mt-1">850L</h3>
                <p class="text-[11px] text-gray-400 mt-1">All products combined</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-green-400/50 rounded-lg text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/></svg>
                    </div>
                </div>
                <p class="text-gray-500 font-medium text-sm">Pending Orders</p>
                <h3 class="text-3xl font-bold mt-1">18</h3>
                <p class="text-[11px] text-gray-400 mt-1">Awaiting processing</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-blue-600 rounded-lg text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" stroke-width="2"/></svg>
                    </div>
                    <span class="text-green-500 text-sm font-bold">+2</span>
                </div>
                <p class="text-gray-500 font-medium text-sm">Deliveries Today</p>
                <h3 class="text-3xl font-bold mt-1">5</h3>
                <p class="text-[11px] text-gray-400 mt-1">3 completed, 2 in transit</p>
            </div>
        </div>

        <div class="mt-8 bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
            <h4 class="font-bold text-gray-900">System Overview - Last 7 Days</h4>
            <p class="text-xs text-gray-400 mb-6">Orders, deliveries, and stock trends</p>
            <div class="h-64 flex items-end gap-2 bg-gray-50 rounded-lg border-dashed border-2 border-gray-100 justify-center items-center text-gray-300">
                [ Chart Placeholder - Use Chart.js here later ]
            </div>
        </div>
    </div>
</x-app-layout>