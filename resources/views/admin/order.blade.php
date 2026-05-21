<x-app-layout>
    <style>
        #status-description { position: relative; overflow: hidden; }

        /* Remove scrollbar arrows/buttons */
        ::-webkit-scrollbar-button {
            display: none;
            width: 0;
            height: 0;
        }

        /* Optional: cleaner scrollbar */
        ::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        #status-description::before {
            content: ""; position: absolute; left: 0; top: 12px; bottom: 12px; width: 5px;
            border-radius: 50px; background-color: #228B22; transition: 0.3s;
        }
        .focus-green-consistent { border: 2px solid #d1d5db; background-color: #f9fafb; color: #1f2937; transition: all 0.2s; }
        .focus-green-consistent:focus { border-color: #228B22 !important; background-color: #fff; box-shadow: 0 0 0 4px rgba(34,139,34,0.1); outline: none !important; }
        .full-modal-backdrop { position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px); }
        .modal-animate-in { animation: modalPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes modalPop { 0%{opacity:0;transform:scale(0.9)} 100%{opacity:1;transform:scale(1)} }
        .order-table { width:100%; border-collapse:collapse; }
        .order-table td, .order-table th { overflow:hidden; word-break:break-word; }
    </style>

    <div class="space-y-6">
        {{-- Header --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-[#1a5d1a] to-[#228B22] px-10 py-10 rounded-[32px] shadow-xl shadow-green-100">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <h1 class="text-[28px] font-bold text-white">Order Management</h1>
                <p class="text-[14px] text-green-50 mt-1">Review, approve, and manage all customer milk orders.</p>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex items-center gap-4 border-b border-gray-200 w-full overflow-x-hidden">
            @foreach(['all'=>'All Orders','pending'=>'Pending','approved'=>'Approved','processing'=>'Processing','in transit'=>'In Transit','delivered'=>'Delivered','declined'=>'Declined'] as $key=>$label)
            <button onclick="filterStatus('{{ $key }}')" id="tab-{{ $key }}"
                class="flex items-center gap-2 px-2 pb-4 text-sm whitespace-nowrap transition-all -mb-[2px]
                {{ $key==='all'?'font-bold border-b-4 border-[#228B22] text-[#228B22]':'font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent' }}">
                {{ $label }}
                <span id="count-{{ $key }}" class="{{ $key==='all'?'bg-green-100 text-[#228B22]':'bg-gray-100 text-gray-500' }} px-2 py-0.5 rounded-full text-[11px] font-bold">0</span>
            </button>
            @endforeach
        </div>

        {{-- Search --}}
        <div id="status-description" style="background-color:rgba(34,139,34,0.05);" class="p-5 pl-8 rounded-xl shadow-sm flex flex-col xl:flex-row xl:items-center justify-between gap-4 border border-gray-100">
            <div class="flex-1">
                <h4 id="desc-title" class="text-[13px] font-bold text-[#228B22] uppercase tracking-wider">All Orders</h4>
                <p id="desc-text" class="text-[14px] text-gray-600 font-medium mt-0.5">Viewing all customer orders.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative w-full md:w-[300px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4"><i class="fas fa-search text-gray-400"></i></span>
                    <input type="text" id="order-search" onkeyup="updateTable()" placeholder="Search orders..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-sm font-medium focus-green-consistent">
                </div>
                <select id="sort-filter" onchange="updateTable()" class="pl-4 pr-10 py-3 bg-white border border-gray-300 rounded-xl text-sm font-medium focus-green-consistent cursor-pointer">
                    <option value="recent">Most Recent</option>
                    <option value="oldest">Oldest</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-300 overflow-hidden">
            <div class="overflow-x-auto">
            <table class="order-table text-left" id="main-order-table">
                <thead>
                    <tr id="table-header-row" class="bg-gray-100 border-b border-gray-300"></tr>
                </thead>
                <tbody id="order-table-body" class="text-black text-[14px]">
                    @forelse($orders as $order)
                    @php
                        $det = is_array($order->product_details) ? $order->product_details : json_decode($order->product_details, true);
                        $prods = collect($det ?? [])->pluck('name')->map(fn($n)=>ucfirst($n))->implode(', ');
                        $sl = strtolower($order->status);
                        $sc = match($sl) {
                            'pending'=>'bg-orange-100 text-orange-700',
                            'approved'=>'bg-blue-100 text-blue-700',
                            'processing'=>'bg-amber-100 text-amber-700',
                            'in transit'=>'bg-purple-100 text-purple-700',
                            'delivered'=>'bg-green-100 text-green-700',
                            'declined'=>'bg-red-100 text-red-700',
                            default=>'bg-gray-100 text-gray-700'
                        };
                        $pc = $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                        $picon = $order->payment_status === 'paid' ? 'fa-check-circle' : 'fa-clock';
                        $plabel = $order->payment_status === 'paid' ? 'Paid' : 'Unpaid';
                    @endphp
                    <tr class="item-row border-b border-gray-200 hover:bg-gray-50/50 transition"
                        data-status="{{ $sl }}" 
                        data-timestamp="{{ optional($order->created_at)->timestamp ?? 0 }}">
                        <td class="py-4 px-4 font-bold text-[#228B22] text-sm whitespace-nowrap">#{{ $order->order_number }}</td>
                        <td class="py-4 px-4 text-gray-700 font-semibold text-sm">{{ $order->customer_name }}</td>
                        <td class="py-4 px-4 text-gray-600 text-sm">{{ Str::limit($prods ?: 'N/A', 30) }}</td>
                        <td class="py-4 px-4 text-sm font-bold text-[#228B22] whitespace-nowrap">₱{{ number_format($order->total_price, 2) }}</td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex flex-col items-center gap-1.5">

                            {{-- STATUS --}}
                            <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-full text-[11px] font-bold uppercase {{ $sc }}">
                                {{ $order->status }}
                            </span>

                            {{-- PAYMENT --}}
                            <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-[11px] font-bold {{ $pc }}">
                                <i class="fas {{ $picon }}"></i>
                                {{ $plabel }}
                            </span>

                        </div>
                        </td>
                        </td>
                        <td class="reason-cell hidden py-4 px-4 text-red-600 text-xs italic">{{ $order->decline_reason ?? 'N/A' }}</td>
                        <td class="actions-cell py-4 px-8 text-center">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                <button onclick='openViewModal(@json($order))' class="px-3 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 shadow-sm transition-all" title="View">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                                @if($sl === 'pending')
                                <button onclick="openApproveModal('{{ route('admin.orders.approve', $order->id) }}','{{ $order->order_number }}')" title="Approve" class="px-3 py-2 bg-[#228B22] text-white rounded-xl hover:bg-[#1b6e1b] shadow-sm transition-all">
                                    <i class="fas fa-check text-sm"></i>
                                </button>
                                <button onclick="openDeclineModal('{{ route('admin.orders.decline', $order->id) }}','{{ $order->order_number }}')" title="Decline" class="px-3 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 shadow-sm transition-all">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                                @elseif($sl === 'approved')
                                <button onclick="openAssignModal('{{ route('admin.orders.assign', $order->id) }}','{{ $order->order_number }}')" class="bg-purple-600 text-white px-3 py-2 rounded-xl text-xs font-bold hover:bg-purple-700 flex items-center gap-1">
                                    <i class="fas fa-truck"></i> Assign
                                </button>
                                @elseif($sl === 'processing')
                                <button onclick="openShipModal('{{ route('admin.orders.ship', $order->id) }}','{{ $order->order_number }}')" class="bg-orange-500 text-white px-3 py-2 rounded-xl text-xs font-bold hover:bg-orange-600 flex items-center gap-1">
                                    <i class="fas fa-shipping-fast"></i> Ship
                                </button>
                                @elseif($sl === 'in transit')
                                <button onclick="openDeliverModal('{{ route('admin.orders.deliver', $order->id) }}','{{ $order->order_number }}')" class="bg-green-600 text-white px-3 py-2 rounded-xl text-xs font-bold hover:bg-green-700 flex items-center gap-1">
                                    <i class="fas fa-box-open"></i> Delivered
                                </button>
                                @elseif($sl === 'declined')
                                <button onclick="openRestoreModal('{{ route('admin.orders.restore', $order->id) }}','{{ $order->order_number }}')" class="bg-amber-500 text-white px-3 py-2 rounded-xl text-xs font-bold hover:bg-amber-600 flex items-center gap-1">
                                    <i class="fas fa-undo-alt"></i> Restore
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                    <tr id="empty-row" style="display:none;">
                        <td colspan="7" class="py-32 text-center text-gray-500">
                            <div class="flex flex-col items-center"><i class="fas fa-receipt text-5xl mb-4 opacity-20"></i><span>No orders found.</span></div>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>

            <div class="px-8 py-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                <div class="text-sm font-bold text-black uppercase tracking-widest">
                    <span id="row-count-on-page">0</span> of <span id="total-filtered-count">0</span> Orders
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-sm font-bold text-black uppercase">PAGE <span id="current-page-text">1</span></div>
                    <div class="flex gap-3">
                        <button onclick="changePage(-1)" id="prev-btn" style="display:none;" class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 shadow-sm">
                            <i class="fas fa-chevron-left"></i> PREVIOUS
                        </button>
                        <button onclick="changePage(1)" id="next-btn" style="display:none;" class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 shadow-sm">
                            NEXT <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CENTERED SUCCESS TOAST --}}
    <div id="toast-alert" class="hidden fixed inset-0 z-[9999] flex items-center justify-center pointer-events-none">
        <div class="bg-white rounded-[32px] px-10 py-8 text-center shadow-2xl border border-gray-100 modal-animate-in pointer-events-auto">
            <div class="w-16 h-16 bg-[#228B22] rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-white text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900" id="toast-msg"></h3>
            <p class="text-sm text-gray-500 mt-1">Action completed successfully.</p>
        </div>
    </div>

    {{-- VIEW MODAL --}}
    <div id="view-order-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" style="display:none!important;">
        <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl">
            <div class="p-6 border-b bg-gray-50">
                <h3 id="view-modal-title" class="font-black text-xl uppercase text-gray-800">Order Items</h3>
                <p class="text-sm text-gray-600 font-medium">Review all products in this order</p>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="bg-white border border-gray-200 rounded-xl p-3"><p class="text-[11px] font-black text-gray-500">Client:</p><p id="view-client" class="text-sm font-bold text-gray-800 mt-1"></p></div>
                    <div class="bg-white border border-gray-200 rounded-xl p-3"><p class="text-[11px] font-black text-gray-500">Contact:</p><p id="view-phone" class="text-sm font-bold text-gray-800 mt-1"></p></div>
                    <div class="bg-white border border-gray-200 rounded-xl p-3 col-span-2"><p class="text-[11px] font-black text-gray-500">Address:</p><p id="view-address" class="text-sm font-bold text-gray-800 mt-1"></p></div>
                    <div id="view-msg-box" class="hidden bg-white border border-gray-200 rounded-xl p-3 col-span-2"><p class="text-[11px] font-black text-gray-500">Message:</p><p id="view-msg" class="text-sm font-bold text-gray-800 mt-1"></p></div>
                </div>
            </div>
            <div id="view-items-container" class="p-6 space-y-3 max-h-[45vh] overflow-y-auto bg-gray-50"></div>
            <div class="p-6 border-t bg-gray-50">
                <button onclick="closeViewModal()" class="w-full px-8 py-4 bg-[#228B22] text-white rounded-xl font-bold text-lg uppercase hover:bg-[#1a6b1a] shadow-md">Close</button>
            </div>
        </div>
    </div>

    {{-- APPROVE MODAL --}}
    <div id="approveModal" class="hidden full-modal-backdrop" style="display:none!important;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-[#228B22]">
            <div class="p-6 border-b flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center"><i class="fas fa-check text-xl text-[#228B22]"></i></div>
                <div><h3 class="text-lg font-bold text-green-800 uppercase">Confirm Approval</h3><p class="text-xs text-gray-500">Order <span id="approve-num" class="font-bold text-black"></span></p></div>
            </div>
            <div class="p-8 text-center text-sm text-gray-600">Are you sure you want to approve this order?</div>
            <form id="approveForm" method="POST" action="#">@csrf
                <div class="p-4 bg-gray-50 flex gap-3">
                    <button type="button" onclick="closeApproveModal()" class="flex-1 py-3.5 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100">Cancel</button>
                    <button type="submit" class="flex-1 py-3.5 bg-[#228B22] text-white text-xs font-bold rounded-xl uppercase hover:bg-[#1b6e1b] shadow-md">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ASSIGN MODAL --}}
    <div id="assignModal" class="hidden full-modal-backdrop" style="display:none!important;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-purple-600">
            <div class="p-6 border-b flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center"><i class="fas fa-truck text-xl text-purple-600"></i></div>
                <div><h3 class="text-lg font-bold text-purple-700 uppercase">Assign Truck Driver</h3><p class="text-xs text-gray-500">Order <span id="assign-num" class="font-bold text-black"></span></p></div>
            </div>
            <form id="assignForm" method="POST" action="#">@csrf
                <div class="p-6 space-y-4">
                    <label class="block text-[11px] font-bold text-black uppercase tracking-widest mb-2">Select Available Truck Driver</label>
                    <select name="truck_id" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-medium bg-gray-50 focus:border-purple-500 outline-none cursor-pointer">
                        <option value="">-- Select Driver --</option>
                        @foreach($trucks as $truck)
                            @if($truck->status === 'unassigned')
                            <option value="{{ $truck->truck_id }}">{{ $truck->truck_id }} — {{ $truck->driver_name }} (Available)</option>
                            @else
                            <option value="{{ $truck->truck_id }}" disabled class="text-gray-400">{{ $truck->truck_id }} — {{ $truck->driver_name }} ({{ ucfirst(str_replace('_',' ',$truck->status)) }})</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="p-4 bg-gray-50 flex gap-3">
                    <button type="button" onclick="closeAssignModal()" class="flex-1 py-3.5 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100">Cancel</button>
                    <button type="submit" class="flex-1 py-3.5 bg-purple-600 text-white text-xs font-bold rounded-xl uppercase hover:bg-purple-700 shadow-md">Assign & Process</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SHIP MODAL --}}
    <div id="shipModal" class="hidden full-modal-backdrop" style="display:none!important;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-orange-500">
            <div class="p-6 border-b flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center"><i class="fas fa-shipping-fast text-xl text-orange-500"></i></div>
                <div><h3 class="text-lg font-bold text-orange-600 uppercase">Ship Order</h3><p class="text-xs text-gray-500">Order <span id="ship-num" class="font-bold text-black"></span></p></div>
            </div>
            <div class="p-8 text-center text-sm text-gray-600">Mark this order as In Transit?</div>
            <form id="shipForm" method="POST" action="#">@csrf
                <div class="p-4 bg-gray-50 flex gap-3">
                    <button type="button" onclick="closeShipModal()" class="flex-1 py-3.5 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100">Cancel</button>
                    <button type="submit" class="flex-1 py-3.5 bg-orange-500 text-white text-xs font-bold rounded-xl uppercase hover:bg-orange-600 shadow-md">Confirm Ship</button>
                </div>
            </form>
        </div>
    </div>

    {{-- DELIVER MODAL --}}
    <div id="deliverModal" class="hidden full-modal-backdrop" style="display:none!important;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-green-600">
            <div class="p-6 border-b flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center"><i class="fas fa-box-open text-xl text-green-600"></i></div>
                <div><h3 class="text-lg font-bold text-green-700 uppercase">Mark as Delivered</h3><p class="text-xs text-gray-500">Order <span id="deliver-num" class="font-bold text-black"></span></p></div>
            </div>
            <div class="p-8 text-center text-sm text-gray-600">Confirm that this order has been delivered?</div>
            <form id="deliverForm" method="POST" action="#">@csrf
                <div class="p-4 bg-gray-50 flex gap-3">
                    <button type="button" onclick="closeDeliverModal()" class="flex-1 py-3.5 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100">Cancel</button>
                    <button type="submit" class="flex-1 py-3.5 bg-green-600 text-white text-xs font-bold rounded-xl uppercase hover:bg-green-700 shadow-md">Confirm Delivered</button>
                </div>
            </form>
        </div>
    </div>

    {{-- DECLINE MODAL --}}
    <div id="declineModal" class="hidden full-modal-backdrop" style="display:none!important;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-red-600">
            <div class="p-6 border-b flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center"><i class="fas fa-times text-xl text-red-600"></i></div>
                <div><h3 class="text-lg font-bold text-red-700 uppercase">Decline Order</h3><p class="text-xs text-gray-500">Order <span id="decline-num" class="font-bold text-black"></span></p></div>
            </div>
            <form id="declineForm" method="POST" action="#">@csrf
                <div class="p-6">
                    <label class="block text-[11px] font-bold text-black uppercase tracking-widest mb-3">Reason for Declining</label>
                    <textarea name="reason" required rows="4" placeholder="e.g. Out of delivery area..." class="w-full p-4 border border-gray-200 rounded-xl text-sm outline-none bg-gray-50 resize-none font-medium focus:ring-2 focus:ring-red-200"></textarea>
                </div>
                <div class="p-4 bg-gray-50 flex gap-3">
                    <button type="button" onclick="closeDeclineModal()" class="flex-1 py-3.5 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100">Cancel</button>
                    <button type="submit" class="flex-1 py-3.5 bg-red-600 text-white text-xs font-bold rounded-xl uppercase hover:bg-red-700 shadow-md">Confirm Decline</button>
                </div>
            </form>
        </div>
    </div>

    {{-- RESTORE MODAL --}}
    <div id="restoreModal" class="hidden full-modal-backdrop" style="display:none!important;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-amber-500">
            <div class="p-6 border-b flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center"><i class="fas fa-undo-alt text-xl text-amber-600"></i></div>
                <div><h3 class="text-lg font-bold text-amber-700 uppercase">Restore Order</h3><p class="text-xs text-gray-500">Order <span id="restore-num" class="font-bold text-black"></span></p></div>
            </div>
            <div class="p-8 text-center text-sm text-gray-600">Restore this order back to Pending?</div>
            <form id="restoreForm" method="POST" action="#">@csrf
                <div class="p-4 bg-gray-50 flex gap-3">
                    <button type="button" onclick="closeRestoreModal()" class="flex-1 py-3.5 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100">Cancel</button>
                    <button type="submit" class="flex-1 py-3.5 bg-amber-500 text-white text-xs font-bold rounded-xl uppercase hover:bg-amber-600 shadow-md">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    let currentStatus = 'all', currentPage = 1;
    const rowsPerPage = 10;
    const allTabs = ['all','pending','approved','processing','in transit','delivered','declined'];

    const descriptions = {
        'all': 'Viewing all customer orders.',
        'pending': 'New orders awaiting review.',
        'approved': 'Approved orders ready to be assigned to a driver.',
        'processing': 'Orders being processed and packed.',
        'in transit': 'Orders on the way to the customer.',
        'delivered': 'Orders successfully delivered.',
        'declined': 'Orders that were declined.',
    };

    // Column headers per tab - Status+Payment combined, no extra empty columns
    const tabHeaders = {
        'all':        ['Order #','Client','Product(s)','Amount','Status'],
        'pending':    ['Order #','Client','Product(s)','Amount','Status','Actions'],
        'approved':   ['Order #','Client','Product(s)','Amount','Status','Actions'],
        'processing': ['Order #','Client','Product(s)','Amount','Status','Actions'],
        'in transit': ['Order #','Client','Product(s)','Amount','Status','Actions'],
        'delivered':  ['Order #','Client','Product(s)','Amount','Status','Actions'],
        'declined':   ['Order #','Client','Product(s)','Amount','Status','Reason','Actions'],
    };

    function renderHeaders(tab) {
        const headers = tabHeaders[tab] || tabHeaders['all'];

        document.getElementById('table-header-row').innerHTML = headers.map((h,i) => {

            let extraClass = '';

            // STATUS COLUMN
            if (h === 'Status') {
                extraClass = 'w-[220px] text-center';
            }

            // ACTIONS COLUMN
            if (h === 'Actions') {
                extraClass = 'w-[220px] text-center pl-10';
            }

            return `
                <th class="py-4 px-6 text-black text-[12px] uppercase font-bold tracking-tight whitespace-nowrap ${extraClass}">
                    ${h}
                </th>
            `;
        }).join('');
    }

    function showModal(id) {
        const el = document.getElementById(id);
        el.classList.remove('hidden');
        el.style.removeProperty('display');
        el.style.display = 'flex';
    }
    function hideModal(id) {
        const el = document.getElementById(id);
        el.classList.add('hidden');
        el.style.display = 'none';
    }

    function filterStatus(s) { currentStatus = s; currentPage = 1; updateTable(); }
    function changePage(d) { currentPage += d; updateTable(); }

    function updateTable() {
        const search = document.getElementById('order-search').value.toLowerCase();
        const sort = document.getElementById('sort-filter').value;
        const tbody = document.getElementById('order-table-body');
        const rows = Array.from(document.querySelectorAll('.item-row'));

        renderHeaders(currentStatus);

        const isAll = currentStatus === 'all';
        const isDeclined = currentStatus === 'declined';

        rows.forEach(row => {
            row.querySelector('.reason-cell')?.classList.toggle('hidden', !isDeclined);
            const actionsCell = row.querySelector('.actions-cell');
            if (actionsCell) actionsCell.style.display = isAll ? 'none' : 'table-cell';
        });

        const counts = {};
        allTabs.forEach(t => counts[t] = 0);
        rows.forEach(row => {
            const s = row.getAttribute('data-status');
            counts['all']++;
            if(counts[s] !== undefined) counts[s]++;
        });

        let filtered = rows.filter(row => {
            const s = row.getAttribute('data-status');
            return (currentStatus === 'all' || s === currentStatus) && row.innerText.toLowerCase().includes(search);
        });

        filtered.sort((a,b) => {
            const tA = parseFloat(a.getAttribute('data-timestamp'))||0;
            const tB = parseFloat(b.getAttribute('data-timestamp'))||0;
            return sort==='recent' ? tB-tA : tA-tB;
        });
        filtered.forEach(r => tbody.appendChild(r));

        const total = filtered.length, totalPages = Math.ceil(total/rowsPerPage)||1;
        if(currentPage>totalPages) currentPage=totalPages;
        if(currentPage<1) currentPage=1;
        const start=(currentPage-1)*rowsPerPage;
        rows.forEach(r=>r.style.display='none');
        filtered.slice(start,start+rowsPerPage).forEach(r=>r.style.display='table-row');

        document.getElementById('row-count-on-page').innerText = filtered.slice(start,start+rowsPerPage).length;
        document.getElementById('total-filtered-count').innerText = total;
        const emptyRow = document.getElementById('empty-row');
        emptyRow.style.display = total===0?'table-row':'none';
        if(total===0) tbody.appendChild(emptyRow);
        document.getElementById('current-page-text').innerText = currentPage;
        document.getElementById('prev-btn').style.display = currentPage>1?'flex':'none';
        document.getElementById('next-btn').style.display = currentPage<totalPages&&total>rowsPerPage?'flex':'none';

        allTabs.forEach(tab => {
            const el = document.getElementById('tab-'+tab);
            const sp = document.getElementById('count-'+tab);
            if(!el) return;
            if(sp) sp.innerText = counts[tab]??0;
            if(tab===currentStatus) {
                el.className="flex items-center gap-2 px-2 pb-4 text-sm font-bold border-[#228B22] text-[#228B22] border-b-4 transition-all -mb-[2px] whitespace-nowrap";
                if(sp) sp.className="bg-green-100 text-[#228B22] px-2 py-0.5 rounded-full text-[11px] font-bold";
            } else {
                el.className="flex items-center gap-2 px-2 pb-4 text-sm font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent transition-all -mb-[2px] whitespace-nowrap";
                if(sp) sp.className="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-[11px] font-bold";
            }
        });

        document.getElementById('desc-title').innerText = (currentStatus==='in transit'?'In Transit':currentStatus.charAt(0).toUpperCase()+currentStatus.slice(1)) + ' Orders';
        document.getElementById('desc-text').innerText = descriptions[currentStatus]||'';
    }

    // View Modal
    function openViewModal(order) {
        let items = order.product_details||[];
        if(typeof items==='string'){try{items=JSON.parse(items);}catch(e){items=[];}}
        if(!Array.isArray(items)) items=[];
        document.getElementById('view-modal-title').innerText='Shopping Cart ('+items.length+' items)';
        document.getElementById('view-client').innerText=order.customer_name??'N/A';
        document.getElementById('view-phone').innerText=order.phone_number??'N/A';
        document.getElementById('view-address').innerText=order.address??'N/A';
        const mb=document.getElementById('view-msg-box');
        if(order.message_instructions){document.getElementById('view-msg').innerText=order.message_instructions;mb.classList.remove('hidden');}else mb.classList.add('hidden');
        document.getElementById('view-items-container').innerHTML = items.length===0
            ?'<div class="text-center py-10 text-gray-400 font-bold text-sm">No items found.</div>'
            :'<div class="space-y-3">'+items.map(item=>`
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-2xl shadow-md gap-3">
                    <div class="w-16 h-16 flex-shrink-0"><img src="${item.image??''}" class="w-full h-full object-cover rounded-lg"></div>
                    <div class="flex-1"><p class="font-bold text-black text-sm">${item.name}</p><p class="text-[10px] text-black uppercase">${item.category}</p><p class="text-[10px] text-black">Vol: ${item.litre??''} | Exp: ${item.expiration}</p></div>
                    <div class="text-right"><p class="font-black text-[#228B22]">₱${parseFloat(item.total).toFixed(2)}</p><p class="text-[10px] text-black font-bold">Qty: ${item.qty}</p></div>
                </div>`).join('')+'</div>';
        showModal('view-order-modal');
        document.body.style.overflow='hidden';
    }
    function closeViewModal(){ hideModal('view-order-modal'); document.body.style.overflow='auto'; }

    function openApproveModal(url,n){
        document.getElementById('approve-num').innerText='#'+n;
        document.getElementById('approveForm').action=url;
        showModal('approveModal');
    }
    function closeApproveModal(){ hideModal('approveModal'); }

    function openAssignModal(url,n){
        document.getElementById('assign-num').innerText='#'+n;
        document.getElementById('assignForm').action=url;
        showModal('assignModal');
    }
    function closeAssignModal(){ hideModal('assignModal'); }

    function openShipModal(url,n){
        document.getElementById('ship-num').innerText='#'+n;
        document.getElementById('shipForm').action=url;
        showModal('shipModal');
    }
    function closeShipModal(){ hideModal('shipModal'); }

    function openDeliverModal(url,n){
        document.getElementById('deliver-num').innerText='#'+n;
        document.getElementById('deliverForm').action=url;
        showModal('deliverModal');
    }
    function closeDeliverModal(){ hideModal('deliverModal'); }

    function openDeclineModal(url,n){
        document.getElementById('decline-num').innerText='#'+n;
        document.getElementById('declineForm').action=url;
        showModal('declineModal');
    }
    function closeDeclineModal(){ hideModal('declineModal'); }

    function openRestoreModal(url,n){
        document.getElementById('restore-num').innerText='#'+n;
        document.getElementById('restoreForm').action=url;
        showModal('restoreModal');
    }
    function closeRestoreModal(){ hideModal('restoreModal'); }

    document.addEventListener('DOMContentLoaded', () => {
        // Force hide all modals on load
        ['approveModal','assignModal','shipModal','deliverModal','declineModal','restoreModal','view-order-modal'].forEach(id => {
            hideModal(id);
        });
        document.body.style.overflow = 'auto';
        updateTable();

        @if(session('success'))
        const toast = document.getElementById('toast-alert');
        document.getElementById('toast-msg').innerText = "{{ session('success') }}";
        toast.classList.remove('hidden');
        setTimeout(()=>toast.classList.add('hidden'), 3000);
        @endif
    });
    </script>
</x-app-layout>