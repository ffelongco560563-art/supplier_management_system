<x-app-layout>
    <style>
        .full-modal-backdrop { position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px); }
    </style>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-[#1a5d1a] to-[#228B22] px-10 py-10 rounded-[32px] shadow-xl shadow-green-100">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <h1 class="text-[28px] font-bold text-white">Payment Management</h1>
                <p class="text-[14px] text-green-50 mt-1">Track and confirm client order payments.</p>
            </div>
        </div>

        {{-- Toast --}}
        @if(session('success'))
        <div id="admin-pay-toast" class="fixed inset-0 z-[9999] flex items-center justify-center pointer-events-none">
            <div class="bg-white rounded-[32px] px-10 py-8 text-center shadow-2xl border border-gray-100 pointer-events-auto" style="animation:modalPop 0.4s cubic-bezier(0.175,0.885,0.32,1.275);">
                <div class="w-16 h-16 bg-[#228B22] rounded-full flex items-center justify-center mx-auto mb-4"><i class="fas fa-check text-white text-2xl"></i></div>
                <h3 class="text-lg font-bold text-gray-900">{{ session('success') }}</h3>
            </div>
        </div>
        <style>@keyframes modalPop{0%{opacity:0;transform:scale(0.9)}100%{opacity:1;transform:scale(1)}}</style>
        @endif

        {{-- Tabs --}}
        <div class="flex items-center gap-6 border-b border-gray-200 w-full">
            @foreach(['all'=>'All','unpaid'=>'Unpaid','paid'=>'Paid'] as $k=>$v)
            <button onclick="filterPayments('{{ $k }}')" id="ptab-{{ $k }}"
                class="flex items-center gap-2 px-2 pb-4 text-sm whitespace-nowrap transition-all -mb-[2px]
                {{ $k==='all'?'font-bold border-b-4 border-[#228B22] text-[#228B22]':'font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent' }}">
                {{ $v }} <span id="pcount-{{ $k }}" class="{{ $k==='all'?'bg-green-100 text-[#228B22]':'bg-gray-100 text-gray-500' }} px-2 py-0.5 rounded-full text-[11px] font-bold">{{ $counts[$k] }}</span>
            </button>
            @endforeach
        </div>
        
        {{-- Search / Filter Description Area --}}
        <div id="payment-description"
            style="background-color: rgba(34, 139, 34, 0.05);"
            class="p-5 pl-8 rounded-xl shadow-sm flex flex-col xl:flex-row xl:items-center justify-between gap-4 border border-gray-100">

            <div class="flex-1">
                <h4 id="payment-desc-title"
                    class="text-[13px] font-bold text-[#228B22] uppercase tracking-wider">
                    All Payments
                </h4>

                <p id="payment-desc-text"
                    class="text-[14px] text-gray-600 font-medium mt-0.5">
                    Manage and monitor all customer payment transactions.
                </p>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-3">

                {{-- Payment Filter --}}
                <div class="relative w-full md:w-[220px]">
                    <select id="payment-filter"
                        onchange="filterPayments(payStatus)"
                        class="w-full pl-4 pr-10 py-3 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-[#228B22]">

                        <option value="all">All Payments</option>
                        <option value="paid">Paid Only</option>
                        <option value="unpaid">Unpaid Only</option>

                    </select>
                </div>

                {{-- Search --}}
                <div class="relative w-full md:w-[280px]">

                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>

                    <input type="text"
                        id="payment-search"
                        onkeyup="filterPayments(payStatus)"
                        placeholder="Search client or order..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-[#228B22] transition-all">

                </div>

            </div>

        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-300 overflow-hidden">
            <table class="w-full text-left border-collapse" style="table-layout:fixed;">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-300">
                        <th class="py-4 px-6 text-black text-[13px] uppercase font-bold" style="width:15%">Order #</th>
                        <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center" style="width:20%">Client</th>
                        <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center" style="width:20%">Amount</th>
                        <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center" style="width:15%">Order Status</th>
                        <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center" style="width:15%">Payment</th>
                        <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center" style="width:15%">Actions</th>
                    </tr>
                </thead>
                <tbody id="payment-table-body">
                    @forelse($orders as $order)
                    @php
                        $sc = match(strtolower($order->status)) {
                            'pending' => 'bg-orange-100 text-orange-700',
                            'approved' => 'bg-blue-100 text-blue-700',
                            'processing' => 'bg-amber-100 text-amber-700',
                            'in transit' => 'bg-purple-100 text-purple-700',
                            'delivered' => 'bg-green-100 text-green-700',
                            default => 'bg-gray-100 text-gray-700',
                        };
                    @endphp
                    <tr class="prow border-b border-gray-200 hover:bg-gray-50 transition" data-pstatus="{{ $order->payment_status }}">
                        <td class="py-4 px-6 font-bold text-[#228B22] text-sm">#{{ $order->order_number }}</td>
                        <td class="py-4 px-6 text-center text-gray-700 font-semibold text-sm">{{ $order->customer_name }}</td>
                        <td class="py-4 px-6 text-center font-bold text-[#228B22] text-sm">₱{{ number_format($order->total_price, 2) }}</td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center py-1 px-3 rounded-full text-xs font-bold uppercase {{ $sc }}">{{ $order->status }}</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($order->payment_status === 'paid')
                                <span class="inline-flex items-center gap-1 py-1 px-3 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                    <i class="fas fa-check-circle"></i> Paid
                                </span>
                                @if($order->payment_method)
                                <p class="text-[10px] text-gray-400 mt-1">via {{ $order->payment_method }}</p>
                                @endif
                            @else
                                <span class="inline-flex items-center gap-1 py-1 px-3 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                    <i class="fas fa-clock"></i> Unpaid
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($order->payment_status === 'unpaid')
                                <button
                                    type="button"
                                    onclick="openPaymentModal('{{ route('admin.payments.confirm', $order->id) }}')"
                                    class="px-4 py-2 bg-[#228B22] text-white rounded-xl text-xs font-bold hover:bg-[#1b6e1b] transition-all shadow-sm flex items-center gap-1.5 mx-auto">
                                    <i class="fas fa-check"></i> Confirm Paid
                                </button>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr id="pay-empty">
                        <td colspan="6" class="py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-4 border border-green-100">
                                    <i class="fas fa-credit-card text-[#228B22] text-3xl"></i>
                                </div>
                                <p class="text-gray-400 font-bold text-sm uppercase">No payment records</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

{{-- TABLE FOOTER PAGINATION --}}
<div class="px-8 py-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between">

    {{-- LEFT --}}
    <div class="text-sm font-bold text-black uppercase tracking-widest">
        <span id="payment-row-count">0</span> of
        <span id="payment-total-count">0</span> Payments
    </div>

    {{-- RIGHT --}}
    <div class="flex items-center gap-6">

        <div class="text-sm font-bold text-black uppercase">
            PAGE <span id="payment-current-page">1</span>
        </div>

        <div class="flex gap-3">

            {{-- PREVIOUS --}}
            <button
                onclick="changePaymentPage(-1)"
                id="payment-prev-btn"
                style="display:none;"
                class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 shadow-sm">

                <i class="fas fa-chevron-left"></i>
                PREVIOUS
            </button>

            {{-- NEXT --}}
            <button
                onclick="changePaymentPage(1)"
                id="payment-next-btn"
                style="display:none;"
                class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 shadow-sm">

                NEXT
                <i class="fas fa-chevron-right"></i>
            </button>

        </div>
    </div>
</div>
</div>
    </div>

    {{-- PAYMENT CONFIRM MODAL --}}
    <div id="paymentConfirmModal"
        class="hidden fixed inset-0 bg-black/30 backdrop-blur-xl z-[999999] flex items-center justify-center p-4">

        <div class="relative z-[999999] bg-white rounded-[32px] shadow-2xl w-full max-w-md overflow-hidden border border-gray-100">

            <div class="p-8 text-center">

                <div class="w-20 h-20 bg-[#228B22]/10 rounded-full flex items-center justify-center mx-auto mb-5">

                    <i class="fas fa-credit-card text-[#228B22] text-3xl"></i>

                </div>

                <h3 class="text-2xl font-black text-gray-900">
                    Confirm Payment?
                </h3>

                <p class="text-sm text-gray-500 mt-3 leading-relaxed">
                    Are you sure this payment has been received from the customer?
                </p>

            </div>

            <div class="px-6 pb-6 flex gap-3">

                <button
                    type="button"
                    onclick="closePaymentModal()"
                    class="flex-1 py-4 bg-gray-100 hover:bg-gray-200 rounded-2xl text-sm font-bold uppercase transition-all">

                    Cancel

                </button>

                <form id="paymentConfirmForm" method="POST" class="flex-1">

                    @csrf

                    <button
                        type="submit"
                        class="w-full py-4 bg-[#228B22] hover:bg-[#1a5d1a] text-white rounded-2xl text-sm font-bold uppercase transition-all shadow-lg shadow-green-200">

                        Confirm

                    </button>

                </form>

            </div>

        </div>

    </div>
    <script>

let payStatus = 'all';

const paymentRowsPerPage = 10;

let paymentCurrentPage = 1;

function changePaymentPage(step) {

    paymentCurrentPage += step;

    filterPayments(payStatus);
}

function filterPayments(status) {

    payStatus = status;

    const rows = Array.from(document.querySelectorAll('.prow'));

    const search = document
        .getElementById('payment-search')
        .value
        .toLowerCase();

    const filter = document
        .getElementById('payment-filter')
        .value;

    let counts = {
        all: rows.length,
        unpaid: 0,
        paid: 0
    };

    rows.forEach(r => {

        const s = r.getAttribute('data-pstatus');

        if(counts[s] !== undefined)
            counts[s]++;
    });

    let filtered = rows.filter(r => {

        const s = r.getAttribute('data-pstatus');

        const rowText = r.innerText.toLowerCase();

        const matchesTab =
            (status === 'all' || s === status);

        const matchesFilter =
            (filter === 'all' || s === filter);

        const matchesSearch =
            rowText.includes(search);

        return matchesTab && matchesFilter && matchesSearch;
    });

    const total = filtered.length;

    const totalPages =
        Math.ceil(total / paymentRowsPerPage) || 1;

    if(paymentCurrentPage < 1)
        paymentCurrentPage = 1;

    if(paymentCurrentPage > totalPages)
        paymentCurrentPage = totalPages;

    const start =
        (paymentCurrentPage - 1) * paymentRowsPerPage;

    const end = start + paymentRowsPerPage;

    rows.forEach(r => r.style.display = 'none');

    filtered.slice(start, end).forEach(r => {
        r.style.display = 'table-row';
    });

    document.getElementById('payment-row-count').innerText =
        filtered.slice(start, end).length;

    document.getElementById('payment-total-count').innerText =
        total;

    document.getElementById('payment-current-page').innerText =
        paymentCurrentPage;

    document.getElementById('payment-prev-btn').style.display =
        paymentCurrentPage > 1 ? 'flex' : 'none';

    document.getElementById('payment-next-btn').style.display =
        paymentCurrentPage < totalPages
            ? 'flex'
            : 'none';

    ['all','unpaid','paid'].forEach(tab => {

        const el = document.getElementById('ptab-'+tab);

        const sp = document.getElementById('pcount-'+tab);

        if(!el) return;

        if(sp)
            sp.innerText = counts[tab];

        if(tab === status) {

            el.className =
            "flex items-center gap-2 px-2 pb-4 text-sm font-bold border-[#228B22] text-[#228B22] border-b-4 transition-all -mb-[2px] whitespace-nowrap";

            if(sp)
                sp.className =
                "bg-green-100 text-[#228B22] px-2 py-0.5 rounded-full text-[11px] font-bold";

        } else {

            el.className =
            "flex items-center gap-2 px-2 pb-4 text-sm font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent transition-all -mb-[2px] whitespace-nowrap";

            if(sp)
                sp.className =
                "bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-[11px] font-bold";
        }
    });

    const titles = {
        all: "All Payments",
        unpaid: "Unpaid Payments",
        paid: "Paid Payments"
    };

    const texts = {
        all: "Manage and monitor all customer payment transactions.",
        unpaid: "Review unpaid customer orders awaiting confirmation.",
        paid: "Track all completed and confirmed customer payments."
    };

    document.getElementById('payment-desc-title').innerText =
        titles[status];

    document.getElementById('payment-desc-text').innerText =
        texts[status];
}

let selectedPaymentAction = '';

function openPaymentModal(action) {

    selectedPaymentAction = action;

    document
        .getElementById('paymentConfirmForm')
        .action = action;

    document
        .getElementById('paymentConfirmModal')
        .classList
        .remove('hidden');
}

function closePaymentModal() {

    document
        .getElementById('paymentConfirmModal')
        .classList
        .add('hidden');
}

document.addEventListener('DOMContentLoaded', () => {

    filterPayments('all');
});

@if(session('success'))
document.addEventListener('DOMContentLoaded', () => {

    const toast = document.getElementById('admin-pay-toast');

    if(toast)
        setTimeout(() => toast.remove(), 3000);
});
@endif

</script>
</x-app-layout>