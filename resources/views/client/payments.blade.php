<x-app-layout>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes modalPop {
            0% {
                opacity: 0;
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    <div class="w-full pb-10">

        {{-- HEADER --}}
        <div class="w-full pb-10">
            <div class="relative overflow-hidden bg-gradient-to-r from-[#1a5d1a] to-[#228B22] px-10 py-10 rounded-[32px] shadow-xl shadow-green-100">

                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>

                <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>

                <div class="relative z-10">
                    <h1 class="text-[28px] font-bold text-white tracking-tight">
                        Payments Management
                    </h1>

                    <p class="text-[14px] text-green-50 font-medium">
                        Manage and track your order payments.
                    </p>
                </div>
            </div>
        </div>

        {{-- TABS --}}
        <div class="px-2 mb-6">
            <div class="flex items-center gap-6 border-b border-gray-200 w-full overflow-x-auto no-scrollbar">

                @foreach([
                    'all' => 'All',
                    'unpaid' => 'Unpaid',
                    'paid' => 'Paid'
                ] as $key => $label)

                <a href="{{ route('client.payments', ['status' => $key]) }}"
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

        {{-- DESCRIPTION / SEARCH --}}
        <div id="payment-description"
            style="background-color:rgba(34,139,34,0.05);"
            class="p-5 pl-8 rounded-xl shadow-sm flex flex-col xl:flex-row xl:items-center justify-between gap-4 border border-gray-100 mb-6 relative overflow-hidden">

            {{-- LEFT ACCENT --}}
            <div class="absolute left-0 top-3 bottom-3 w-[5px] rounded-full bg-[#228B22]"></div>

            {{-- DESCRIPTION --}}
            <div class="flex-1">
                <h4 class="text-[13px] font-bold text-[#228B22] uppercase tracking-wider">
                    Payment Records
                </h4>

                <p class="text-[14px] text-gray-600 font-medium mt-0.5">
                    View and manage all your paid and unpaid order transactions.
                </p>
            </div>

            {{-- SEARCH + FILTER --}}
            <div class="flex flex-col md:flex-row items-center gap-3">

                {{-- FILTER --}}
                <div class="relative w-full md:w-[220px]">

                    <select id="payment-filter"
                        onchange="filterClientPayments()"
                        class="w-full pl-4 pr-10 py-3 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:border-[#228B22] focus:ring-4 focus:ring-green-100 transition-all appearance-none cursor-pointer">

                        <option value="all">All Payments</option>
                        <option value="paid">Paid</option>
                        <option value="unpaid">Unpaid</option>
                    </select>

                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </div>
                </div>

                {{-- SEARCH --}}
                <div class="relative w-full md:w-[280px]">

                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>

                    <input type="text"
                        id="payment-search"
                        onkeyup="filterClientPayments()"
                        placeholder="Search order number..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-[#228B22] focus:ring-4 focus:ring-green-100 transition-all">
                </div>
            </div>
        </div>

        {{-- SUCCESS TOAST --}}
        @if(session('success'))

        <div id="pay-toast"
            class="fixed inset-0 z-[9999] flex items-center justify-center pointer-events-none">

            <div class="bg-white rounded-[32px] px-10 py-8 text-center shadow-2xl border border-gray-100 pointer-events-auto"
                style="animation:modalPop 0.4s cubic-bezier(0.175,0.885,0.32,1.275);">

                <div class="w-16 h-16 bg-[#228B22] rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-white text-2xl"></i>
                </div>

                <h3 class="text-lg font-bold text-gray-900">
                    {{ session('success') }}
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Payment completed successfully.
                </p>
            </div>
        </div>

        @endif

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-300 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-left border-collapse">

                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-300">

                            <th class="py-5 px-8 text-black text-[12px] uppercase font-bold tracking-tight whitespace-nowrap">
                                Order #
                            </th>

                            <th class="py-5 px-8 text-black text-[12px] uppercase font-bold tracking-tight whitespace-nowrap">
                                Date
                            </th>

                            <th class="py-5 px-8 text-black text-[12px] uppercase font-bold tracking-tight whitespace-nowrap">
                                Amount
                            </th>

                            <th class="py-5 px-8 text-black text-[12px] uppercase font-bold tracking-tight whitespace-nowrap">
                                Order Status
                            </th>

                            <th class="py-5 px-8 text-black text-[12px] uppercase font-bold tracking-tight whitespace-nowrap">
                                Payment
                            </th>

                            <th class="py-5 px-8 text-center text-black text-[12px] uppercase font-bold tracking-tight whitespace-nowrap">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @php
                            $filterStatus = request('status', 'all');
                        @endphp

                        @forelse($orders->filter(fn($o) =>
                            $filterStatus === 'all' ||
                            $o->payment_status === $filterStatus) as $order)

                        <tr class="payment-row border-b border-gray-200 hover:bg-gray-50/50 transition"
                            data-payment="{{ $order->payment_status }}"
                            data-order="#{{ $order->order_number }}"
                            data-date="{{ $order->created_at->format('M d, Y') }}"
                            data-amount="{{ number_format($order->total_price, 2) }}">

                            {{-- ORDER --}}
                            <td class="py-5 px-8 font-bold text-[#228B22] text-sm whitespace-nowrap">
                                #{{ $order->order_number }}
                            </td>

                            {{-- DATE --}}
                            <td class="py-5 px-8 text-gray-600 text-sm font-medium whitespace-nowrap">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>

                            {{-- AMOUNT --}}
                            <td class="py-5 px-8 text-sm font-bold text-[#228B22] whitespace-nowrap">
                                ₱{{ number_format($order->total_price, 2) }}
                            </td>

                            {{-- ORDER STATUS --}}
                            <td class="py-5 px-8">

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

                                <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-full text-[11px] font-bold uppercase {{ $sc }}">
                                    {{ $order->status }}
                                </span>
                            </td>

                            {{-- PAYMENT STATUS --}}
                            <td class="py-5 px-8">

                                @if($order->payment_status === 'paid')

                                <div class="flex flex-col gap-1">

                                    <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-[11px] font-bold bg-green-100 text-green-700 w-fit">
                                        <i class="fas fa-check-circle"></i>
                                        Paid
                                    </span>

                                    @if($order->payment_date)
                                    <span class="text-[10px] text-gray-400 font-medium">
                                        {{ \Carbon\Carbon::parse($order->payment_date)->format('M d, Y') }}
                                    </span>
                                    @endif

                                </div>

                                @else

                                <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-[11px] font-bold bg-red-100 text-red-700">
                                    <i class="fas fa-clock"></i>
                                    Unpaid
                                </span>

                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td class="py-5 px-8 text-center">

                                @if($order->payment_status === 'unpaid')

                                <button onclick="openPayModal(
                                    {{ $order->id }},
                                    '{{ $order->order_number }}',
                                    '{{ number_format($order->total_price, 2) }}'
                                )"
                                    class="px-4 py-2 bg-[#228B22] text-white rounded-xl text-xs font-bold hover:bg-[#1a6b1a] transition-all shadow-sm flex items-center gap-1.5 mx-auto">

                                    <i class="fas fa-credit-card"></i>
                                    Pay Now
                                </button>

                                @else

                                <span class="text-xs text-gray-400 font-medium">
                                    {{ $order->payment_method ?? '—' }}
                                </span>

                                @endif
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="6" class="py-32 text-center text-gray-500">

                                <div class="flex flex-col items-center">
                                    <i class="fas fa-credit-card text-5xl mb-4 opacity-20"></i>

                                    <span>No payments found.</span>
                                </div>
                            </td>
                        </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER PAGINATION --}}
            <div class="px-8 py-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between">

                {{-- LEFT --}}
                <div class="text-sm font-bold text-black uppercase tracking-widest">
                    <span id="payment-row-count">0</span>
                    of
                    <span id="payment-total-count">0</span>
                    Payments
                </div>

                {{-- RIGHT --}}
                <div class="flex items-center gap-6">

                    {{-- PAGE --}}
                    <div class="text-sm font-bold text-black uppercase tracking-widest">
                        PAGE
                        <span id="payment-current-page">1</span>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="flex items-center gap-3">

                        {{-- PREVIOUS --}}
                        <button onclick="changePaymentPage(-1)"
                            id="payment-prev-btn"
                            style="display:none;"
                            class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 shadow-sm transition-all duration-200">

                            <i class="fas fa-chevron-left text-[10px]"></i>
                            PREVIOUS
                        </button>

                        {{-- NEXT --}}
                        <button onclick="changePaymentPage(1)"
                            id="payment-next-btn"
                            style="display:none;"
                            class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 shadow-sm transition-all duration-200">

                            NEXT
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PAY MODAL --}}
    <div id="payModal"
        class="hidden fixed inset-0 z-[9999] items-center justify-center p-4 bg-black/60 backdrop-blur-sm">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-[#228B22]">

            <div class="p-6 border-b flex items-center gap-3">

                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-credit-card text-xl text-[#228B22]"></i>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-green-800 uppercase">
                        Pay Order
                    </h3>

                    <p class="text-xs text-gray-500">
                        Order
                        <span id="pay-order-num" class="font-bold text-black"></span>
                        —
                        <span class="font-bold text-[#228B22]">
                            ₱<span id="pay-amount"></span>
                        </span>
                    </p>
                </div>
            </div>

            <form id="payForm" method="POST">
                @csrf

                <div class="p-6 space-y-4">

                    <label class="block text-[11px] font-bold text-black uppercase tracking-widest mb-2">
                        Payment Method
                    </label>

                    <div class="grid grid-cols-2 gap-3">

                        @foreach([
                            'GCash',
                            'Maya',
                            'Bank Transfer',
                            'Cash on Delivery'
                        ] as $method)

                        <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-[#228B22] transition-all has-[:checked]:border-[#228B22] has-[:checked]:bg-green-50">

                            <input type="radio"
                                name="payment_method"
                                value="{{ $method }}"
                                required
                                class="accent-[#228B22]">

                            <span class="text-sm font-bold text-gray-700">
                                {{ $method }}
                            </span>
                        </label>

                        @endforeach
                    </div>
                </div>

                <div class="p-4 bg-gray-50 flex gap-3">

                    <button type="button"
                        onclick="closePayModal()"
                        class="flex-1 py-3.5 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100">

                        Cancel
                    </button>

                    <button type="submit"
                        class="flex-1 py-3.5 bg-[#228B22] text-white text-xs font-bold rounded-xl uppercase hover:bg-[#1b6e1b] shadow-md">

                        Confirm Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>

        function openPayModal(id, num, amount) {

            document.getElementById('pay-order-num').innerText = '#' + num;

            document.getElementById('pay-amount').innerText = amount;

            document.getElementById('payForm').action =
                '/client/payments/' + id + '/pay';

            const modal = document.getElementById('payModal');

            modal.classList.remove('hidden');

            modal.style.display = 'flex';

            document.body.style.overflow = 'hidden';
        }

        function closePayModal() {

            const modal = document.getElementById('payModal');

            modal.classList.add('hidden');

            modal.style.display = 'none';

            document.body.style.overflow = 'auto';
        }

        const paymentRowsPerPage = 10;

        let paymentCurrentPage = 1;

        function changePaymentPage(step) {

            paymentCurrentPage += step;

            updatePaymentTable();
        }

        function filterClientPayments() {

            paymentCurrentPage = 1;

            updatePaymentTable();
        }

        function updatePaymentTable() {

            const search =
                document.getElementById('payment-search')
                .value
                .toLowerCase();

            const filter =
                document.getElementById('payment-filter')
                .value;

            const rows =
                Array.from(document.querySelectorAll('.payment-row'));

            let filtered = rows.filter(row => {

                const payment =
                    row.getAttribute('data-payment').toLowerCase();

                const order =
                    row.getAttribute('data-order').toLowerCase();

                const date =
                    row.getAttribute('data-date').toLowerCase();

                const amount =
                    row.getAttribute('data-amount').toLowerCase();

                const content =
                    `${order} ${date} ${amount}`;

                const matchesSearch =
                    content.includes(search);

                const matchesFilter =
                    filter === 'all' || payment === filter;

                return matchesSearch && matchesFilter;
            });

            const total = filtered.length;

            const totalPages =
                Math.ceil(total / paymentRowsPerPage) || 1;

            if (paymentCurrentPage < 1)
                paymentCurrentPage = 1;

            if (paymentCurrentPage > totalPages)
                paymentCurrentPage = totalPages;

            const start =
                (paymentCurrentPage - 1) * paymentRowsPerPage;

            const end = start + paymentRowsPerPage;

            rows.forEach(row => row.style.display = 'none');

            filtered.slice(start, end).forEach(row => {
                row.style.display = 'table-row';
            });

            document.getElementById('payment-row-count').innerText =
                filtered.slice(start, end).length;

            document.getElementById('payment-total-count').innerText =
                total;

            document.getElementById('payment-current-page').innerText =
                paymentCurrentPage;

            document.getElementById('payment-prev-btn').style.display =
                paymentCurrentPage > 1
                    ? 'flex'
                    : 'none';

            document.getElementById('payment-next-btn').style.display =
                paymentCurrentPage < totalPages &&
                total > paymentRowsPerPage
                    ? 'flex'
                    : 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {

            updatePaymentTable();

            const toast = document.getElementById('pay-toast');

            if (toast) {
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        });

    </script>

</x-app-layout>