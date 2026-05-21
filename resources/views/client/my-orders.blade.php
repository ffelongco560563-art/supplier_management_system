<x-app-layout>
    
    <div class="w-full pb-10">
        
        {{-- Header Container - Match Order Milk Design --}}
    <div class="w-full pb-10">
    <div class="relative overflow-hidden bg-gradient-to-r from-[#1a5d1a] to-[#228B22] px-10 py-10 rounded-[32px] shadow-xl shadow-green-100">
        
        {{-- Decorative background patterns --}}
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>

        <div class="relative z-10 flex justify-between items-center">
            
            {{-- Text Content --}}
            <div>
                <h1 class="text-[28px] font-bold text-white tracking-tight">
                    My Purchase History
                </h1>

                <p class="text-[14px] text-green-50 font-medium">
                    Track your orders and view your previous transactions with us.
                </p>
            </div>

        </div>
    </div>
</div>

        <div class="px-2">
        {{-- Filter Tabs (Admin Style) --}}
        <div class="flex items-center gap-4 border-b border-gray-200 w-full overflow-x-hidden">
        @foreach([
                'all' => 'All Orders',
                'pending' => 'Pending',
                'approved' => 'Approved',
                'processing' => 'Processing',
                'in transit' => 'In Transit',
                'delivered' => 'Delivered',
                'declined' => 'Declined'
            ] as $key => $label)
    <a href="{{ route('client.my-orders', ['status' => $key]) }}" 
    class="flex items-center gap-2 px-2 pb-4 text-sm whitespace-nowrap transition-all -mb-[2px]
            {{ request('status', 'all') == $key
                ? 'font-bold border-b-4 border-[#228B22] text-[#228B22]'
                : 'font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent' }}">

        {{ $label }} 
        <span class="{{ request('status', 'all') == $key
                ? 'bg-green-100 text-[#228B22]'
                : 'bg-gray-100 text-gray-500' }}
                px-2 py-0.5 rounded-full text-[11px] font-bold">
            {{ $counts[$key] ?? 0 }}
        </span>
    </a>
@endforeach
        </div>
        </div>
            <br>
        {{-- Status Description Box --}}
        <div id="orders-status-description" class="relative overflow-hidden p-5 pl-8 rounded-xl shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4 border border-gray-100 mb-6" style="background-color: rgba(34, 139, 34, 0.05);">
            <style>
                #orders-status-description::before {
                    content: "";
                    position: absolute;
                    left: 0;
                    top: 12px;
                    bottom: 12px;
                    width: 5px;
                    border-radius: 50px;
                    background-color: var(--border-color, #228B22);
                }
            </style>
            <div class="flex-1">
                <h4 id="orders-desc-title" class="text-[13px] font-bold text-[#228B22] uppercase tracking-wider">All Orders</h4>
                <p id="orders-desc-text" class="text-[14px] text-gray-600 font-medium mt-0.5">Browse and track all your milk product orders and their current status.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative w-full md:w-[300px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <input type="text" id="orders-search" placeholder="Search orders..." oninput="filterOrders()" class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-sm text-black focus:border-[#228B22] outline-none transition-all shadow-sm font-medium">
                </div>
                <div class="relative flex items-center justify-center w-12 h-12 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
                    <i class="fas fa-filter text-gray-500"></i>
                    <select id="orders-sort" onchange="filterOrders()" class="absolute inset-0 opacity-0 cursor-pointer">
                        <option value="recent">Recent</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Orders Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-300 overflow-hidden">

    <table class="w-full border-collapse">
        
        {{-- TABLE HEADER --}}
        <thead>
            <tr class="bg-gray-100 border-b border-gray-300">

                <th class="w-[18%] py-4 px-6 text-left text-black text-[12px] uppercase font-bold whitespace-nowrap">
                    Order #
                </th>

                <th class="w-[18%] py-4 px-6 text-left text-black text-[12px] uppercase font-bold whitespace-nowrap">
                    Client
                </th>

                <th class="w-[26%] py-4 px-6 text-left text-black text-[12px] uppercase font-bold whitespace-nowrap">
                    Product(s)
                </th>

                <th class="w-[14%] py-4 px-6 text-left text-black text-[12px] uppercase font-bold whitespace-nowrap">
                    Amount
                </th>

                <th class="w-[16%] py-4 px-6 text-center text-black text-[12px] uppercase font-bold whitespace-nowrap">
                    Status
                </th>

                <th class="w-[8%] py-4 px-6 text-center text-black text-[12px] uppercase font-bold whitespace-nowrap">
                    Actions
                </th>

            </tr>
        </thead>

        {{-- TABLE BODY --}}
        <tbody>

        @forelse($orders as $order)

            @php
                $details = is_array($order->product_details)
                    ? $order->product_details
                    : json_decode($order->product_details, true);

                $products = collect($details ?? [])
                    ->pluck('name')
                    ->map(fn($n) => ucfirst($n))
                    ->implode(', ');

                $status = strtolower($order->status);

                $statusClass = match($status) {
                    'pending' => 'bg-orange-100 text-orange-700',
                    'approved' => 'bg-blue-100 text-blue-700',
                    'processing' => 'bg-amber-100 text-amber-700',
                    'in transit' => 'bg-purple-100 text-purple-700',
                    'delivered' => 'bg-green-100 text-green-700',
                    'declined' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-700',
                };

                $paymentClass = $order->payment_status === 'paid'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700';

                $paymentIcon = $order->payment_status === 'paid'
                    ? 'fa-check-circle'
                    : 'fa-clock';

                $paymentLabel = $order->payment_status === 'paid'
                    ? 'Paid'
                    : 'Unpaid';
            @endphp

            <tr class="pending-row border-b border-gray-200 hover:bg-gray-50 transition-all">

                {{-- ORDER NUMBER --}}
                <td class="py-5 px-6 align-middle">
                    <p class="font-bold text-gray-800 text-sm whitespace-nowrap">
                        #{{ $order->order_number }}
                    </p>
                </td>

                {{-- CLIENT --}}
                <td class="py-5 px-6 align-middle">
                    <p class="text-sm font-semibold text-gray-700 whitespace-nowrap">
                        {{ $order->customer_name }}
                    </p>
                </td>

                {{-- PRODUCTS --}}
                <td class="py-5 px-6 align-middle">
                    <p class="text-sm text-gray-600 truncate">
                        {{ Str::limit($products ?: 'N/A', 40) }}
                    </p>
                </td>

                {{-- AMOUNT --}}
                <td class="py-5 px-6 align-middle">
                    <p class="text-sm font-bold text-[#228B22] whitespace-nowrap">
                        ₱{{ number_format($order->total_price ?? 0, 2) }}
                    </p>
                </td>

                {{-- STATUS --}}
                <td class="py-5 px-6 align-middle">
                    <div class="flex flex-col items-center justify-center gap-2">

                        {{-- ORDER STATUS --}}
                        <span class="inline-flex items-center justify-center min-w-[120px] px-4 py-1.5 rounded-full text-[11px] font-bold uppercase {{ $statusClass }}">
                            {{ $order->status }}
                        </span>

                        {{-- PAYMENT STATUS --}}
                        <span class="inline-flex items-center justify-center gap-1 min-w-[120px] px-4 py-1.5 rounded-full text-[11px] font-bold {{ $paymentClass }}">
                            <i class="fas {{ $paymentIcon }}"></i>
                            {{ $paymentLabel }}
                        </span>

                    </div>
                </td>

                {{-- ACTIONS --}}
                <td class="py-5 px-6 align-middle text-center">

                    <button
                        onclick='viewOrderDetails(@json($order))'
                        class="w-10 h-10 inline-flex items-center justify-center bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-all shadow-sm"
                    >
                        <i class="fas fa-eye text-sm"></i>
                    </button>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="6" class="py-24 text-center">

                    <div class="flex flex-col items-center">

                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-shopping-cart text-[#228B22] text-4xl"></i>
                        </div>

                        <p class="text-gray-400 font-bold text-sm tracking-wide uppercase">
                            No orders found
                        </p>

                        <p class="text-gray-300 text-xs font-medium mt-1">
                            There are currently no orders available in this tab.
                        </p>

                    </div>

                </td>
            </tr>

        @endforelse

        </tbody>
    </table>

    {{-- PAGINATION --}}
    <div class="px-8 py-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between">

        <div class="text-sm font-bold text-black uppercase tracking-widest">
            <span id="row-count-pending">0</span>
            of
            <span id="total-count-pending">0</span>
            Orders
        </div>

        <div class="flex items-center gap-6">

            <div class="text-sm font-bold text-black uppercase tracking-widest">
                PAGE
                <span id="current-page-pending">1</span>
            </div>

            <div class="flex items-center gap-3">

                <button
                    onclick="changePage(-1)"
                    id="prev-btn-pending"
                    class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 transition-all shadow-sm"
                >
                    <i class="fas fa-chevron-left"></i>
                    PREVIOUS
                </button>

                <button
                    onclick="changePage(1)"
                    id="next-btn-pending"
                    class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 transition-all shadow-sm"
                >
                    NEXT
                    <i class="fas fa-chevron-right"></i>
                </button>

            </div>
        </div>

    </div>

</div>

{{-- Details Modal --}}
<div id="details-modal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl">
        
        <!-- Modal Header -->
        <div class="p-6 border-b bg-gray-50">
            <h3 id="modal-title-text" class="font-black text-xl uppercase text-gray-800">Order Items</h3>
            <p class="text-sm text-gray-600 font-medium">Review all products included in this order</p>
            <div id="modal-order-info" class="mt-4 grid grid-cols-2 gap-3">
                <div class="bg-white border border-gray-200 rounded-xl p-3">
                    <p class="text-[11px] font-black text-gray-500 ">Address:</p>
                    <p id="modal-address" class="text-sm font-bold text-gray-800 mt-1"></p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-3">
                    <p class="text-[11px] font-black text-gray-500">Contact Number:</p>
                    <p id="modal-phone" class="text-sm font-bold text-gray-800 mt-1"></p>
                </div>
                <div id="modal-message-box" class="hidden mt-3 bg-white border border-gray-200 rounded-xl p-3 col-span-2 w-full">
                    <p class="text-[11px] font-black text-gray-500">Message Instructions:</p>
                    <p id="modal-message" class="text-sm font-bold text-gray-800 mt-1"></p>
                </div>
                <div id="modal-decline-box" class="hidden mt-3 bg-red-50 border border-red-200 rounded-xl p-3 col-span-2 w-full">
                    <p class="text-[11px] font-black text-red-500">Decline Reason:</p>
                    <p id="modal-decline-reason" class="text-sm font-bold text-red-700 mt-1"></p>
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
                class="w-full px-8 py-4 bg-[#228B22] text-white rounded-xl font-bold text-lg uppercase tracking-widest hover:bg-[#1a6b1a] transition-all shadow-md">
                Close
            </button>
        </div>

    </div>
</div>

<script>
const rowsPerPage = 10;
let currentPage = 1;

function changePage(step) {
    currentPage += step;
    updateTable();
}

function updateTable() {
    const search = document.getElementById('orders-search')?.value.toLowerCase() || '';
    const sort = document.getElementById('orders-sort')?.value || 'recent';
    let rows = Array.from(document.querySelectorAll('.pending-row'));

    // Filter by search
    let filtered = rows.filter(row => row.innerText.toLowerCase().includes(search));

    // Sort
    filtered.sort((a, b) => {
        const dateA = new Date(a.querySelector('td:nth-child(2)')?.innerText || 0);
        const dateB = new Date(b.querySelector('td:nth-child(2)')?.innerText || 0);
        return sort === 'oldest' ? dateA - dateB : dateB - dateA;
    });

    // Re-append sorted rows to DOM
    const tbody = rows[0]?.parentElement;
    if (tbody) filtered.forEach(row => tbody.appendChild(row));

    const total = filtered.length;
    const totalPages = Math.ceil(total / rowsPerPage) || 1;
    if (currentPage < 1) currentPage = 1;
    if (currentPage > totalPages) currentPage = totalPages;

    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    rows.forEach(row => row.style.display = 'none');
    filtered.slice(start, end).forEach(row => row.style.display = 'table-row');

    document.getElementById('row-count-pending').innerText = filtered.slice(start, end).length;
    document.getElementById('total-count-pending').innerText = total;
    document.getElementById('current-page-pending').innerText = currentPage;

    const prevBtn = document.getElementById('prev-btn-pending');
    const nextBtn = document.getElementById('next-btn-pending');

    prevBtn.style.display = currentPage > 1 ? 'flex' : 'none';
    nextBtn.style.display = currentPage < totalPages && total > rowsPerPage ? 'flex' : 'none';
}

const orderDescriptions = {
    'all': { title: 'All Orders', text: 'Browse and track all your milk product orders and their current status.' },
    'pending': { title: 'Pending Orders', text: 'Orders that are currently awaiting admin review and approval.' },
    'approved': { title: 'Approved Orders', text: 'Orders that have been reviewed and approved by the admin.' },
    'processing': {
    title: 'Processing Orders',
    text: 'Orders that are currently being processed and packed.'
},

'in transit': {
    title: 'In Transit Orders',
    text: 'Orders that are currently on the way to your delivery address.'
},
    'delivered': { title: 'Delivered Orders', text: 'Orders that have been successfully delivered to you.' },
};

function filterOrders() {
    currentPage = 1;
    updateTable();
}

document.addEventListener('DOMContentLoaded', updateTable);
// Update description box based on active tab
const activeStatus = decodeURIComponent(
        new URLSearchParams(window.location.search).get('status') || 'all'
    );
const desc = orderDescriptions[activeStatus] || orderDescriptions['all'];
document.getElementById('orders-desc-title').innerText = desc.title;
document.getElementById('orders-desc-text').innerText = desc.text;

// Color the left border based on status
const borderColors = {
    'all': '#228B22',
    'pending': '#228B22',
    'approved': '#228B22',
    'processing': '#228B22',
    'in transit': '#228B22',
    'delivered': '#228B22',
    'declined': '#228B22',
};
document.getElementById('orders-status-description').style.setProperty('--border-color', '#228B22');
document.getElementById('orders-desc-title').style.color = '#228B22';
document.getElementById('orders-status-description').style.backgroundColor = 'rgba(34, 139, 34, 0.05)';

updateTable();

/* FIXED MODAL LOGIC ONLY */
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
        console.log(item.image);
        
    productsHTML += `
        <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-2xl shadow-md hover:shadow-lg transition-all gap-4">
            <!-- Product Image -->
            <div class="w-20 h-20 flex-shrink-0">
                <img src="${item.image}" alt="Product Image" class="w-full h-full object-cover rounded-lg">
            </div>

            <!-- Product Details -->
            <div class="flex-1">
                <p class="font-bold text-black text-sm">${item.name}</p>
                <p class="text-[10px] text-black uppercase">${item.category}</p>
                <p class="text-[10px] text-black mt-1">Expiration: ${item.expiration}</p>
                <p class="text-[10px] text-black">Net Volume: ${item.litre ?? ''}</p>
            </div>

            <!-- Product Price & Qty -->
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

    const declineBox = document.getElementById('modal-decline-box');
    const declineReason = document.getElementById('modal-decline-reason');
    if (order.decline_reason) {
        declineReason.innerText = order.decline_reason;
        declineBox.classList.remove('hidden');
    } else {
        declineBox.classList.add('hidden');
    }

    const msgBox = document.getElementById('modal-message-box');
    const msgText = document.getElementById('modal-message');
    if (order.message_instructions) {
        msgText.innerText = order.message_instructions;
        msgBox.classList.remove('hidden');
    } else {
        msgBox.classList.add('hidden');
    }
    document.getElementById('modal-title-text').innerText = 'Shopping Cart (' + items.length + ' items)';
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