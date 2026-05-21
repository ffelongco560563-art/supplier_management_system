<x-app-layout>
    <style>
        #status-description { position: relative; overflow: hidden; }
        #status-description::before {
            content: ""; position: absolute; left: 0; top: 12px; bottom: 12px; width: 5px;
            border-radius: 50px; background-color: var(--border-color, #228B22); transition: 0.3s;
        }
        .truck-card { transition: all 0.3s ease; }
        .truck-card:hover { transform: translateY(-3px); box-shadow: 0 12px 25px -5px rgba(0,0,0,0.1); }

        .modal-animate-in {
            animation: modalPop 0.35s ease;
        }

        @keyframes modalPop {

            0% {
                opacity: 0;
                transform: scale(0.92);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    <div class="space-y-6">
        {{-- Header --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-[#1a5d1a] to-[#228B22] px-10 py-10 rounded-[32px] shadow-xl shadow-green-100">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <h1 class="text-[28px] font-bold text-white">Logistics Management</h1>
                    <p class="text-[14px] text-green-50 mt-1">Manage truck drivers and track all active deliveries.</p>
                </div>
                <button onclick="document.getElementById('addTruckModal').classList.remove('hidden')"
                    class="flex items-center gap-2 px-6 py-3 bg-white text-[#228B22] rounded-xl text-sm font-bold hover:bg-gray-100 transition-all shadow-lg">
                    <i class="fas fa-truck"></i> Add Truck Driver
                </button>
            </div>
        </div>

       <div id="toast-alert"
    class="hidden fixed inset-0 z-[9999] flex items-center justify-center pointer-events-none">

    <div class="bg-white rounded-[32px] px-10 py-8 text-center shadow-2xl border border-gray-100 pointer-events-auto">

        <div class="w-16 h-16 bg-[#228B22] rounded-full flex items-center justify-center mx-auto mb-4">

            <i class="fas fa-check text-white text-2xl"></i>

        </div>

        <h3 class="text-lg font-bold text-gray-900"
            id="toast-msg"></h3>

        <p class="text-sm text-gray-500 mt-1">
            Action completed successfully.
        </p>

    </div>

</div>

        {{-- Truck Driver Cards --}}
        <div>
            <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-black">Truck Drivers</h2>
            <button
                onclick="toggleTruckEditMode()"
                id="truck-edit-toggle"
                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700 transition-all text-sm font-black shadow-md">

                <i class="fas fa-pen"></i>

                <span>Edit</span>

            </button>
        </div>
            @if($trucks->isEmpty())
            <div class="flex flex-col items-center py-16 bg-white rounded-2xl border border-gray-200">
                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-4 border border-green-100">
                    <i class="fas fa-truck text-[#228B22] text-3xl"></i>
                </div>
                <p class="text-gray-400 font-bold text-sm uppercase">No truck drivers yet</p>
                <p class="text-gray-300 text-xs mt-1">Click "Add Truck Driver" to get started.</p>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($trucks as $truck)
                @php
                    $statusColor = match($truck->status) {
                        'unassigned' => [
                            'bg' => 'bg-green-100',
                            'text' => 'text-green-700',
                            'dot' => 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.9)]'
                        ],

                        'processing' => [
                            'bg' => 'bg-amber-100',
                            'text' => 'text-amber-700',
                            'dot' => 'bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.9)]'
                        ],

                        'in_transit' => [
                            'bg' => 'bg-purple-100',
                            'text' => 'text-purple-700',
                            'dot' => 'bg-purple-500 shadow-[0_0_10px_rgba(168,85,247,0.9)]'
                        ],

                        'delivered' => [
                            'bg' => 'bg-green-100',
                            'text' => 'text-green-700',
                            'dot' => 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.9)]'
                        ],
                        default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'dot' => 'bg-gray-400'],
                    };
                    $statusLabel = match($truck->status) {
                        'unassigned' => 'Available',
                        'processing' => 'Processing',
                        'in_transit' => 'In Transit',
                        'delivered' => 'Delivered',
                        default => ucfirst($truck->status),
                    };
                @endphp
                <div
                    class="truck-card bg-white rounded-2xl border border-gray-200 shadow-md p-6 flex flex-col gap-4"
                    data-driver-status="{{ $truck->status }}">
                    <div class="flex items-start justify-between">
                    <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center border border-green-100">
                        <i class="fas fa-truck text-[#228B22] text-2xl"></i>
                    </div>

                    <div class="flex items-center gap-2">

                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $statusColor['bg'] }} {{ $statusColor['text'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusColor['dot'] }}"></span>
                            {{ $statusLabel }}
                        </span>

                        {{-- EDIT BUTTON --}}
                        
                        <button 
                            onclick="openEditTruckModal(
                                '{{ $truck->id }}',
                                '{{ $truck->truck_id }}',
                                '{{ $truck->driver_name }}'
                            )"
                           class="truck-edit-actions hidden w-9 h-9 rounded-xl bg-red-600 hover:bg-red-700 text-white transition-all items-center justify-center shadow-md">

                            <i class="fas fa-pen text-xs"></i>

                        </button>

                    </div>

                </div>
                    <div>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Truck ID</p>
                        <p class="text-lg font-black text-[#228B22]">{{ $truck->truck_id }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Driver Name</p>
                        <p class="text-base font-bold text-gray-800">{{ $truck->driver_name }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Active Orders Table --}}
        <div>
            <h2 class="text-xl font-bold text-black mb-4">Active Shipments</h2>

            {{-- Tabs --}}
            <div class="flex items-center gap-6 border-b border-gray-200 w-full mb-4">
                @foreach(['all'=>'All','processing'=>'Processing','in transit'=>'In Transit','delivered'=>'Delivered'] as $k=>$v)
                <button onclick="filterLogistics('{{ $k }}')" id="ltab-{{ $k }}"
                    class="flex items-center gap-2 px-2 pb-4 text-sm whitespace-nowrap transition-all -mb-[2px]
                    {{ $k==='all'?'font-bold border-b-4 border-[#228B22] text-[#228B22]':'font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent' }}">
                    {{ $v }} <span id="lcount-{{ $k }}" class="{{ $k==='all'?'bg-green-100 text-[#228B22]':'bg-gray-100 text-gray-500' }} px-2 py-0.5 rounded-full text-[11px] font-bold">0</span>
                </button>
                @endforeach
            </div>

            {{-- DESCRIPTION + SEARCH --}}
            <div id="status-description"
                style="background-color: rgba(34,139,34,0.05); --border-color:#228B22;"
                class="p-5 pl-8 rounded-xl shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4 border border-gray-100 mb-4">

                <div>
                    <h4 id="ldesc-title"
                        class="text-[13px] font-bold text-[#228B22] uppercase tracking-wider">

                        All Shipments

                    </h4>

                    <p id="ldesc-text"
                        class="text-[14px] text-gray-600 font-medium mt-1">

                        View and monitor all logistics deliveries.

                    </p>
                </div>

                <div class="flex items-center gap-4 w-full lg:w-auto">

                {{-- SEARCH --}}
                <div class="relative w-full md:w-[420px]">

                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>

                    <input type="text"
                        id="logistics-search"
                        onkeyup="updateLogisticsTable()"
                        placeholder="Search shipment..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-sm focus:border-[#228B22] outline-none">

                </div>

                {{-- FILTER --}}
                <select
                    id="logistics-filter"
                    onchange="updateLogisticsTable()"
                    class="w-[240px] px-4 py-3 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:border-[#228B22] outline-none shadow-sm">

                    <option value="all">All Status</option>
                    <option value="processing">Processing</option>
                    <option value="in transit">In Transit</option>
                    <option value="delivered">Delivered</option>

                </select>

            </div>

            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-300 overflow-hidden">
                <table class="w-full text-left border-collapse" style="table-layout:fixed;">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-300">
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold" style="width:15%">Order #</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center" style="width:20%">Client</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center" style="width:25%">Destination</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center" style="width:20%">Driver</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center" style="width:20%">Status</th>
                        </tr>
                    </thead>
                    <tbody id="logistics-table-body">
                        @foreach($orders as $o)
                        @php
                            $ls = strtolower($o->status);
                            $lkey = $ls === 'in transit' ? 'in transit' : $ls;
                            $lc = match($ls) {
                                'processing'=>'bg-amber-100 text-amber-700',
                                'in transit'=>'bg-purple-100 text-purple-700',
                                'delivered'=>'bg-green-100 text-green-700',
                                default=>'bg-gray-100 text-gray-700'
                            };
                            $driver = $trucks->where('truck_id', $o->truck_id)->first();
                        @endphp
                        <tr class="litem-row border-b border-gray-200 hover:bg-gray-50 transition" data-lstatus="{{ $lkey }}">
                            <td class="py-4 px-6 font-bold text-[#228B22] text-sm">#{{ $o->order_number }}</td>
                            <td class="py-4 px-6 text-center text-gray-700 font-semibold text-sm">{{ $o->customer_name }}</td>
                            <td class="py-4 px-6 text-center text-gray-600 text-sm">{{ Str::limit($o->address, 40) }}</td>
                            <td class="py-4 px-6 text-center text-gray-700 text-sm font-medium">
                                {{ $driver ? $driver->driver_name . ' (' . $driver->truck_id . ')' : 'Not Assigned' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center justify-center py-1 px-3 rounded-full text-xs font-bold uppercase {{ $lc }}">{{ $o->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                        <tr id="logistics-empty" style="display:none;">
                            <td colspan="5" class="py-20 text-center text-gray-500">
                                <div class="flex flex-col items-center"><i class="fas fa-truck text-5xl mb-4 opacity-20"></i><span>No shipments found.</span></div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="px-8 py-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                    <div class="text-sm font-bold text-black uppercase tracking-widest">
                        <span id="lrow-count">0</span> of <span id="ltotal-count">0</span> Shipments
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="text-sm font-bold text-black uppercase">PAGE <span id="lpage-text">1</span></div>
                        <div class="flex gap-3">
                            <button onclick="changeLogisticsPage(-1)" id="lprev-btn" class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 shadow-sm">
                                <i class="fas fa-chevron-left"></i> PREVIOUS
                            </button>
                            <button onclick="changeLogisticsPage(1)" id="lnext-btn" class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 shadow-sm">
                                NEXT <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ADD TRUCK MODAL --}}
    <div id="addTruckModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-md z-[99999] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-[#228B22]">
            <div class="p-6 border-b flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center"><i class="fas fa-truck text-xl text-[#228B22]"></i></div>
                <div><h3 class="text-lg font-bold text-green-800 uppercase">Add Truck Driver</h3><p class="text-xs text-gray-500">Truck ID will be auto-generated</p></div>
            </div>
            <form method="POST" action="{{ route('admin.logistics.trucks.store') }}">@csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold text-black uppercase tracking-widest mb-2">Driver's Full Name</label>
                        <input type="text" name="driver_name" required placeholder="e.g. Juan Dela Cruz"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-medium bg-gray-50 focus:border-[#228B22] outline-none">
                    </div>
                    <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                        <p class="text-[11px] font-black text-gray-500 uppercase tracking-widest">Auto-generated Truck ID</p>
                        <p class="text-lg font-black text-[#228B22] mt-1">TRK-{{ date('Y') }}-{{ str_pad(\App\Models\Truck::count() + 1, 2, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                <div class="p-4 bg-gray-50 flex gap-3">
                    <button type="button" onclick="document.getElementById('addTruckModal').classList.add('hidden')"
                        class="flex-1 py-3.5 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100">Cancel</button>
                    <button type="submit" class="flex-1 py-3.5 bg-[#228B22] text-white text-xs font-bold rounded-xl uppercase hover:bg-[#1b6e1b] shadow-md">Add Driver</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT TRUCK MODAL --}}
    <div id="editTruckModal"
        class="hidden fixed inset-0 bg-black/60 backdrop-blur-md z-[99999] flex items-center justify-center p-4">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-[#228B22]">

            <div class="p-6 border-b flex items-center gap-3">

                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-pen text-[#228B22]"></i>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-green-800 uppercase">
                        Edit Truck Driver
                    </h3>

                    <p class="text-xs text-gray-500">
                        Update or delete truck driver
                    </p>
                </div>

            </div>

            <form id="editTruckForm" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4">

                    <div>
                        <label class="block text-[11px] font-bold uppercase mb-2">
                            Truck ID
                        </label>

                        <input type="text"
                            id="edit-truck-id"
                            disabled
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-100 font-bold text-[#228B22]">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase mb-2">
                            Driver Name
                        </label>

                        <input type="text"
                            name="driver_name"
                            id="edit-driver-name"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 focus:border-[#228B22] outline-none">
                    </div>

                </div>

                <div class="p-4 bg-gray-50 flex gap-3">

                    <button type="button"
                        onclick="closeEditTruckModal()"
                        class="flex-1 py-3 bg-white border border-gray-300 rounded-xl text-xs font-bold uppercase">
                        Cancel
                    </button>

                    <button
                        type="button"
                        onclick="openEditConfirmModal()"
                        class="flex-1 py-3 bg-[#228B22] text-white rounded-xl text-xs font-bold uppercase">
                        Save Changes
                    </button>

                </div>

            </form>

            <div class="px-4 pb-4">

                <form id="deleteTruckForm" method="POST">

                    @csrf
                    @method('DELETE')

                    <button
                        type="button"
                        onclick="openDeleteModal()"
                        class="w-full py-3 bg-red-600 text-white rounded-xl text-xs font-bold uppercase hover:bg-red-700">

                        Delete Truck Driver

                    </button>

                </form>

            </div>

        </div>

    </div>

    {{-- EDIT CONFIRMATION MODAL --}}
    <div id="editConfirmModal"
        class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[100000] flex items-center justify-center p-4">

        <div class="bg-white rounded-[32px] shadow-2xl w-full max-w-md overflow-hidden border border-gray-100 modal-animate-in">

            <div class="p-8 text-center">

                <div class="w-20 h-20 bg-[#228B22]/10 rounded-full flex items-center justify-center mx-auto mb-5">

                    <i class="fas fa-pen text-[#228B22] text-3xl"></i>

                </div>

                <h3 class="text-2xl font-black text-gray-900">
                    Save Changes?
                </h3>

                <p class="text-sm text-gray-500 mt-3 leading-relaxed">
                    Are you sure you want to update this truck driver's information?
                </p>

            </div>

            <div class="px-6 pb-6 flex gap-3">

                <button
                    onclick="closeEditConfirmModal()"
                    class="flex-1 py-4 bg-gray-100 hover:bg-gray-200 rounded-2xl text-sm font-bold uppercase transition-all">

                    Cancel

                </button>

                <button
                    onclick="submitEditTruck()"
                    class="flex-1 py-4 bg-[#228B22] hover:bg-[#1a5d1a] text-white rounded-2xl text-sm font-bold uppercase transition-all shadow-lg shadow-green-200">

                    Confirm

                </button>

            </div>

        </div>

    </div>

    {{-- DELETE CONFIRMATION MODAL --}}
    <div id="deleteConfirmModal"
        class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[100000] flex items-center justify-center p-4">

        <div class="bg-white rounded-[32px] shadow-2xl w-full max-w-md overflow-hidden border border-gray-100 modal-animate-in">

            <div class="p-8 text-center">

                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-5">
                    <i class="fas fa-trash text-red-600 text-3xl"></i>
                </div>

                <h3 class="text-2xl font-black text-gray-900">
                    Delete This Truck Driver?
                </h3>

                <p class="text-sm text-gray-500 mt-3 leading-relaxed">
                    Are you sure you want to delete this truck driver? This action cannot be undone.</p>

            </div>

            <div class="px-6 pb-6 flex gap-3">

                <button
                    onclick="closeDeleteModal()"
                    class="flex-1 py-4 bg-gray-100 hover:bg-gray-200 rounded-2xl text-sm font-bold uppercase transition-all">

                    Cancel

                </button>

                <button
                    onclick="submitDeleteTruck()"
                    class="flex-1 py-4 bg-red-600 hover:bg-red-700 text-white rounded-2xl text-sm font-bold uppercase transition-all shadow-lg shadow-red-200">

                    Delete

                </button>

            </div>

        </div>

    </div>

    <script>

    let truckEditMode = false;
    let pendingDeleteForm = null;
    let pendingEditForm = null;

    function toggleTruckEditMode() {

        truckEditMode = !truckEditMode;

        const actions =
            document.querySelectorAll('.truck-edit-actions');

        const toggle =
            document.getElementById('truck-edit-toggle');

        actions.forEach(action => {

            if(truckEditMode) {
                action.classList.remove('hidden');
                action.classList.add('flex');
            } else {
                action.classList.add('hidden');
                action.classList.remove('flex');
            }

        });

        toggle.innerHTML = truckEditMode
            ? '<i class="fas fa-check"></i><span>Done</span>'
            : '<i class="fas fa-pen"></i><span>Edit</span>';
    }

    function openEditConfirmModal() {

    pendingEditForm =
        document.getElementById('editTruckForm');

    document
        .getElementById('editConfirmModal')
        .classList
        .remove('hidden');
}

    function closeEditConfirmModal() {

        document
            .getElementById('editConfirmModal')
            .classList
            .add('hidden');
    }

    function submitEditTruck() {

        if(pendingEditForm) {
            pendingEditForm.submit();
        }
    }

    function openDeleteModal() {

        pendingDeleteForm =
            document.getElementById('deleteTruckForm');

        document
            .getElementById('deleteConfirmModal')
            .classList
            .remove('hidden');
    }

    function closeDeleteModal() {

        document
            .getElementById('deleteConfirmModal')
            .classList
            .add('hidden');
    }

    function submitDeleteTruck() {

        if(pendingDeleteForm) {
            pendingDeleteForm.submit();
        }
    }    

    function openEditTruckModal(id, truckId, driverName) {

        document.getElementById('edit-truck-id').value = truckId;

        document.getElementById('edit-driver-name').value = driverName;

        document.getElementById('editTruckForm').action =
            '/admin/logistics/trucks/' + id;

        document.getElementById('deleteTruckForm').action =
            '/admin/logistics/trucks/' + id;

        document.getElementById('editTruckModal').classList.remove('hidden');
    }

    function closeEditTruckModal() {
        document.getElementById('editTruckModal').classList.add('hidden');
    }

    let logisticsStatus = 'all', logisticsPage = 1;
    const lRowsPerPage = 10;

    function filterLogistics(status) {
        logisticsStatus = status;
        logisticsPage = 1;
        updateLogisticsTable();
    }
    function changeLogisticsPage(d) { logisticsPage += d; updateLogisticsTable(); }

    function updateLogisticsTable() {
        const rows = Array.from(document.querySelectorAll('.litem-row'));

        // Counts
        const counts = {all:0, processing:0, 'in transit':0, delivered:0};
        rows.forEach(r => { const s=r.getAttribute('data-lstatus'); counts.all++; if(counts[s]!==undefined) counts[s]++; });
        Object.keys(counts).forEach(k => {
            const sp = document.getElementById('lcount-'+k);
            if(sp) sp.innerText = counts[k];
        });

        // Filter
        const search =
        document.getElementById('logistics-search')
        .value
        .toLowerCase();
        const filter =
        document.getElementById('logistics-filter')
        .value;

        let filtered = rows.filter(r => {

            const matchesStatus =
                logisticsStatus === 'all' ||
                r.getAttribute('data-lstatus') === logisticsStatus;

            const matchesSearch =
                r.innerText.toLowerCase().includes(search);

            const driverStatus =
                r.getAttribute('data-lstatus');

            let matchesFilter = true;

            if(filter !== 'all') {
                matchesFilter = driverStatus === filter;
            }

            return matchesStatus &&
                matchesSearch &&
                matchesFilter;
        });

        const total = filtered.length, totalPages = Math.ceil(total/lRowsPerPage)||1;
        if(logisticsPage>totalPages) logisticsPage=totalPages;
        if(logisticsPage<1) logisticsPage=1;
        const start=(logisticsPage-1)*lRowsPerPage;
        rows.forEach(r=>r.style.display='none');
        filtered.slice(start,start+lRowsPerPage).forEach(r=>r.style.display='table-row');

        document.getElementById('lrow-count').innerText=filtered.slice(start,start+lRowsPerPage).length;
        document.getElementById('ltotal-count').innerText=total;
        document.getElementById('lpage-text').innerText=logisticsPage;
        document.getElementById('logistics-empty').style.display=total===0?'table-row':'none';
        document.getElementById('lprev-btn').style.display=logisticsPage>1?'flex':'none';
        document.getElementById('lnext-btn').style.display=logisticsPage<totalPages&&total>lRowsPerPage?'flex':'none';

        // Tab styling
        ['all','processing','in transit','delivered'].forEach(tab => {
            const el=document.getElementById('ltab-'+tab);
            const sp=document.getElementById('lcount-'+tab);
            if(!el) return;
            if(tab===logisticsStatus){
                el.className="flex items-center gap-2 px-2 pb-4 text-sm font-bold border-[#228B22] text-[#228B22] border-b-4 transition-all -mb-[2px] whitespace-nowrap";
                if(sp) sp.className="bg-green-100 text-[#228B22] px-2 py-0.5 rounded-full text-[11px] font-bold";
            } else {
                el.className="flex items-center gap-2 px-2 pb-4 text-sm font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent transition-all -mb-[2px] whitespace-nowrap";
                if(sp) sp.className="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-[11px] font-bold";
            }
        });
    }

    function showToast(message) {

        const toast = document.getElementById('toast-alert');

        document.getElementById('toast-msg').innerText = message;

        toast.classList.remove('hidden');

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateLogisticsTable();
        @if(session('success'))
            showToast("{{ session('success') }}");
        @endif
    });
    </script>
</x-app-layout>