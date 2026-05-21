<x-app-layout>
    <style>
    .client-stat-card {
        position: relative;
        overflow: hidden;
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
    }

    .client-stat-card:hover {
        transform: translateY(-3px);
    }

    .client-stat-card::before {
        content: "";
        position: absolute;
        left: 0;
        top: 15px;
        bottom: 15px;
        width: 6px;
        border-radius: 50px;
        z-index: 10;
    }

    .client-border-dark::before {
        background-color: #111827;
    }

    .client-border-green::before {
        background-color: #228B22;
    }

    .client-border-blue::before {
        background-color: #2563eb;
    }
</style>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-[28px] font-bold text-black tracking-tight">Welcome, {{ Auth::user()->first_name }}!</h1>
                <p class="text-[14px] text-gray-500 font-medium">Manage your orders and track your fresh milk deliveries.</p>
            </div>
        </div>

        {{-- Customer Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Total Orders Card --}}
            <div class="bg-white p-8 rounded-2xl border border-gray-100 border-l-[6px] border-l-black shadow-lg hover:-translate-y-1 transition-all duration-300 min-h-[140px]">
                
                <div class="flex justify-between items-center h-full">

                    <div class="flex flex-col justify-center gap-2">

                        <p class="text-sm font-bold text-black uppercase">
                            Total Orders Placed
                        </p>

                        <h3 class="text-4xl font-black text-black">
                            {{ $myOrdersCount }}
                        </h3>

                        <p class="text-[13px] text-gray-500 font-medium">
                            Orders successfully created
                        </p>

                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <i class="fas fa-box text-black text-2xl"></i>
                    </div>

                </div>

            </div>

            {{-- Products Available Card --}}
            <div class="bg-white p-8 rounded-2xl border border-gray-100 border-l-[6px] border-l-[#228B22] shadow-lg hover:-translate-y-1 transition-all duration-300 min-h-[140px]">
                
                <div class="flex justify-between items-center h-full">

                    <div class="flex flex-col justify-center gap-2">

                        <p class="text-sm font-bold text-black uppercase">
                            Total Products Available
                        </p>

                        <h3 class="text-4xl font-black text-[#228B22]">
                            {{ \App\Models\Product::where('stock', '>', 0)->count() }}
                        </h3>

                        <p class="text-[13px] text-gray-500 font-medium">
                            Products ready to order
                        </p>

                    </div>

                    <div class="bg-green-50 p-4 rounded-xl">
                        <i class="fas fa-boxes-stacked text-[#228B22] text-2xl"></i>
                    </div>

                </div>

            </div>

            {{-- Customer ID Card --}}
            <div class="bg-white p-8 rounded-2xl border border-gray-100 border-l-[6px] border-l-blue-600 shadow-lg hover:-translate-y-1 transition-all duration-300 min-h-[140px]">
                
                <div class="flex justify-between items-center h-full">

                    <div class="flex flex-col justify-center gap-2">

                        <p class="text-sm font-bold text-black uppercase">
                            Customer ID
                        </p>

                        <h3 class="text-4xl font-black text-blue-600">
                            #{{ Auth::id() }}
                        </h3>

                        <p class="text-[13px] text-gray-500 font-medium">
                            Registered customer account
                        </p>

                    </div>

                    <div class="bg-blue-50 p-4 rounded-xl">
                        <i class="fas fa-user-tag text-blue-600 text-2xl"></i>
                    </div>

                </div>

            </div>

        </div>


        <div class="relative overflow-hidden bg-gradient-to-r from-[#1a5d1a] to-[#228B22] rounded-[32px] p-8 md:p-12 my-8 shadow-xl shadow-green-100">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>

        <div class="relative flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-white text-3xl md:text-4xl font-black leading-tight tracking-tight mb-4">
                    Fresh Milk, <br>Delivered to Your Door.
                </h2>
                <p class="text-green-50 text-sm md:text-base font-medium opacity-90 max-w-md mb-8">
                    Experience the pure taste of DairyBest products.<br> Order today and get your favorite milk products now!
                </p>
                
                <a href="{{ route('client.shop') }}" class="inline-flex items-center gap-3 bg-white text-[#228B22] px-8 py-2 rounded-2xl font-black text-sm uppercase tracking-wider hover:bg-gray-50 transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    Order Milk Now
                </a>
            </div>

            <div class="hidden lg:flex items-center justify-center w-64 h-64 bg-white/10 backdrop-blur-md rounded-full border border-white/30 shadow-2xl relative">
            <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-white/5 to-transparent pointer-events-none"></div>
            
            <img src="{{ asset('images/logo.png') }}" 
                alt="DairyBest Logo" 
                class="w-40 h-40 object-contain drop-shadow-[0_10px_10px_rgba(0,0,0,0.3)] brightness-110 relative z-10">
        </div>
                </div>
            </div>

        {{-- Recent Order History Table --}}
        <div class="bg-white rounded-3xl border border-gray-300 shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
            {{-- Table Header --}}
            <div class="p-8 border-b border-gray-300 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Recent Order History</h2>
                    <p class="text-[14px] text-gray-500 font-medium mt-1">Track and manage your recent milk product purchases and their current delivery status.</p>
                </div>
            <a href="{{ route('client.my-orders') }}" class="flex items-center gap-2 bg-[#228B22] text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-[#1a6b1a] transition-all shadow-md shadow-green-100 group">
                <span>View All Orders</span>
                <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
            </a>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="py-5 px-8 border-b border-gray-300">Order Number</th>
                            <th class="py-5 px-8 border-b border-gray-300">Date</th>
                            <th class="py-5 px-8 border-b border-gray-300">Category</th>
                            <th class="py-5 px-8 border-b border-gray-300">Amount</th>
                            <th class="py-5 px-8 border-b border-gray-300">Status</th>
                            <th class="py-5 px-8 text-center border-b border-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-5 px-8 border-b border-gray-300 font-bold text-gray-800 text-sm">#{{ $order->order_number }}</td>
                                <td class="py-5 px-8 border-b border-gray-300 text-sm text-gray-500 font-medium">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="py-5 px-8 border-b border-gray-300 text-sm text-gray-600 font-medium">
                                    @php
                                        $details = is_array($order->product_details) ? $order->product_details : json_decode($order->product_details, true);
                                        $categories = collect($details ?? [])->pluck('category')->unique()->implode(', ');
                                    @endphp
                                    {{ $categories ?: 'N/A' }}
                                </td>
                                <td class="py-5 px-8 border-b border-gray-300 text-sm font-bold text-[#228B22]">₱{{ number_format($order->total_price ?? 0, 2) }}</td>
                                @php
                                    $status = strtolower($order->status);
                                    $statusClass = match($status) {
                                        'pending' => 'bg-orange-100 text-orange-700',
                                        'approved' => 'bg-blue-100 text-blue-700',
                                        'delivered' => 'bg-green-100 text-green-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <td class="py-5 px-8 border-b border-gray-300">
                                    <span class="inline-flex items-center justify-center py-1 px-3 rounded-full text-xs font-bold uppercase {{ $statusClass }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-5 px-8 border-b border-gray-300 text-center">
                                    <button onclick='viewOrderDetails(@json($order))' class="px-3 py-2 flex items-center justify-center bg-blue-500 text-white rounded-xl hover:bg-blue-600 shadow-sm transition-all mx-auto">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-shopping-cart text-[#228B22] text-3xl"></i>
                                        </div>

                                        <p class="text-gray-400 font-bold text-sm tracking-wide uppercase">
                                            No orders found
                                        </p>

                                         <p class="text-gray-300 text-xs font-medium mt-1">No orders found. Your recent purchases will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Details Modal --}}
    <div id="details-modal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl max-w-2xl w-full overflow-hidden shadow-2xl">
            
            <!-- Modal Header -->
            <div class="p-6 border-b bg-gray-50">
                <h3 class="font-black text-xl uppercase text-gray-800">Order Items</h3>
                <p class="text-sm text-gray-600 font-medium">Review all products included in this order</p>
                <div id="modal-order-info" class="mt-4 grid grid-cols-2 gap-3">
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <p class="text-[11px] font-black text-gray-500">Address:</p>
                        <p id="modal-address" class="text-sm font-bold text-gray-800 mt-1"></p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                        <p class="text-[11px] font-black text-gray-500">Contact Number:</p>
                        <p id="modal-phone" class="text-sm font-bold text-gray-800 mt-1"></p>
                    </div>
                    <div id="modal-message-box" class="hidden col-span-2 w-full bg-white border border-gray-200 rounded-xl p-3">
                        <p class="text-[11px] font-black text-gray-500">Message Instructions:</p>
                        <p id="modal-message" class="text-sm font-bold text-gray-800 mt-1"></p>
                    </div>
                </div>
            </div>

            <!-- Product Items Container -->
            <div id="modal-items-container" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto bg-gray-50">
                <!-- Product cards injected dynamically -->
            </div>

            <!-- Bottom Close Button -->
            <div class="p-6 border-t bg-gray-50 flex justify-center">
                <button onclick="closeDetailsModal()"
                    class="w-1/2 px-8 py-4 bg-[#228B22] text-white rounded-xl font-bold text-lg uppercase tracking-widest hover:bg-[#1a6b1a] transition-all shadow-md">
                    Close
                </button>
            </div>

        </div>
    </div>

    <script>
    function viewOrderDetails(order) {
        const container = document.getElementById('modal-items-container');
        container.innerHTML = '';

        let items = order.product_details || [];

        if (typeof items === 'string') {
            try { items = JSON.parse(items); } catch(e) { items = []; }
        }

        if (!Array.isArray(items)) items = [];

        if (items.length === 0) {
            container.innerHTML = `
                <div class="text-center py-10 text-gray-400 font-bold text-sm">
                    No items found in this order.
                </div>
            `;
        } else {
            let productsHTML = '';
            items.forEach(item => {
                productsHTML += `
                    <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-2xl shadow-md hover:shadow-lg transition-all gap-4">
                        <div class="w-20 h-20 flex-shrink-0">
                            <img src="${item.image}" alt="Product Image" class="w-full h-full object-cover rounded-lg">
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-black text-sm">${item.name}</p>
                            <p class="text-[10px] text-black uppercase">${item.category}</p>
                            <p class="text-[10px] text-black mt-1">Expiration: ${item.expiration}</p>
                            <p class="text-[10px] text-black">Net Volume: ${item.litre ?? ''}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-black text-[#228B22]">₱${parseFloat(item.total).toFixed(2)}</p>
                            <p class="text-[10px] text-black font-bold">Quantity: ${item.qty}</p>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = `<div class="space-y-4">${productsHTML}</div>`;
        }

        document.getElementById('modal-address').innerText = order.address ?? 'N/A';
        document.getElementById('modal-phone').innerText = order.phone_number ?? 'N/A';
        const msgBox = document.getElementById('modal-message-box');
        const msgText = document.getElementById('modal-message');
        if (order.message_instructions) {
            msgText.innerText = order.message_instructions;
            msgBox.classList.remove('hidden');
        } else {
            msgBox.classList.add('hidden');
        }
        document.getElementById('details-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('details-modal').scrollTop = 0;
    }

    function closeDetailsModal() {
        document.getElementById('details-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    </script>
</x-app-layout>