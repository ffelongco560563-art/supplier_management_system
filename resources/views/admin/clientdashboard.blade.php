<x-app-layout>
    <div class="p-10 max-w-7xl mx-auto">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800">Welcome, {{ Auth::user()->first_name }}!</h2>
            <p class="text-gray-500 mt-2">Your supplier account is active. You can now start managing your milk deliveries and orders.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <div class="p-6 bg-green-50 rounded-2xl border border-green-100">
                    <h3 class="font-bold text-green-800">New Order</h3>
                    <p class="text-sm text-green-600">Start a new milk delivery request.</p>
                </div>
                <div class="p-6 bg-blue-50 rounded-2xl border border-blue-100">
                    <h3 class="font-bold text-blue-800">History</h3>
                    <p class="text-sm text-blue-600">View your previous transactions.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>