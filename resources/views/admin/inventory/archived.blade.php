<x-app-layout>
    <style>
        /* Product Image Icon Styling */
        .product-icon-container {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #228B22;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .product-icon-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>

    {{-- Header Container - Premium Dark Forest Green Design with Centered Action Button --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-[#1a5d1a] to-[#228B22] px-10 py-10 rounded-[32px] shadow-xl shadow-green-100 mb-6">
            
            {{-- Decorative Organic Blurs --}}
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                {{-- Left Text Side --}}
                <div>
                    <h1 class="text-[28px] font-bold text-white tracking-tight">Archived Products</h1>
                    <p class="text-[14px] text-green-50 font-medium mt-1">Manage and restore products that have been moved to the archives.</p>
                </div>
                
                {{-- Right Navigation Button - Perfectly Centered and holding Arrow Icon --}}
                <div class="flex items-center h-full">
                    <a href="{{ route('admin.inventory.index') }}" class="bg-white text-[#228B22] px-6 py-3 rounded-xl text-sm font-bold hover:bg-gray-100 transition-all shadow-sm flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-arrow-left text-xs"></i>
                        <span>Back to Inventory</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-300 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" style="table-layout: fixed;">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-300">
                            {{-- tracking-tighter applied to make column text letters closer --}}
                            <th class="py-4 px-8 text-black text-[13px] uppercase font-bold w-[22%] tracking-tighter">Product Name</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center w-[13%] tracking-tighter">Category</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center w-[10%] tracking-tighter">Litre</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center w-[12%] tracking-tighter">Price Unit</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center w-[13%] tracking-tighter">Stock Level</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center w-[15%] tracking-tighter">Status</th>
                            <th class="py-4 px-8 text-black text-[13px] uppercase font-bold text-center w-[15%] tracking-tighter">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inventory-table-body" class="text-black text-[14px]">
                        @forelse($archivedProducts as $product)
                        <tr class="item-row border-b border-gray-200 hover:bg-gray-50/50 transition">
                            <td class="py-4 px-8">
                                <div class="flex items-center gap-3">
                                    <div class="product-icon-container flex-shrink-0">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="Product">
                                        @else
                                            <i class="fas fa-glass-whiskey text-white text-[12px]"></i>
                                        @endif
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center text-gray-600 font-medium truncate">{{ $product->category }}</td>
                            <td class="py-4 px-6 text-center truncate">{{ $product->litre }}</td>
                            <td class="py-4 px-6 text-center font-bold text-[#228B22] truncate">₱{{ number_format($product->price_unit, 2) }}</td>
                            <td class="py-4 px-6 text-center font-bold truncate">{{ $product->stock }} <span class="text-[10px] text-gray-400 font-bold ml-1 uppercase">units</span></td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center justify-center py-1 px-3 rounded-full text-[11px] font-bold w-full max-w-[110px] uppercase
                                    {{ $product->stock <= 0 ? 'bg-red-100 text-red-700' : ($product->stock <= 10 ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                                    {{ $product->stock <= 0 ? 'OUT OF STOCK' : ($product->stock <= 10 ? 'LOW STOCK' : 'IN STOCK') }}
                                </span>
                            </td>
                            <td class="py-4 px-8">
                                <div class="flex justify-center gap-2">
                                    {{-- Blue View Button --}}
                                    <button onclick="openViewModal({{ $product->id }})" title="View Details" class="px-3 py-2 flex items-center justify-center bg-blue-500 text-white rounded-xl hover:bg-blue-600 shadow-sm transition-all min-w-[40px]">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                    {{-- Restore Button --}}
                                    <button onclick="confirmRestore({{ $product->id }}, '{{ addslashes($product->name) }}')" title="Restore" class="px-3 py-2 flex items-center justify-center bg-green-600 text-white rounded-xl hover:bg-green-700 shadow-sm transition-all min-w-[40px]">
                                        <i class="fas fa-undo-alt text-sm"></i>
                                    </button>
                                    {{-- Delete Button --}}
                                    <button onclick="confirmForceDelete({{ $product->id }}, '{{ addslashes($product->name) }}')" title="Delete Permanently" class="px-3 py-2 flex items-center justify-center bg-red-500 text-white rounded-xl hover:bg-red-600 shadow-sm transition-all min-w-[40px]">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="empty-row">
                            <td colspan="7" class="py-20 text-center text-gray-500 font-medium">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-archive text-5xl mb-4 opacity-20"></i>
                                    <span>No archived products found.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="px-8 py-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                <div class="text-sm font-bold text-black uppercase tracking-widest">
                    <span id="row-count-on-page">0</span> of <span id="total-filtered-count">0</span> Products
                </div>
                <div class="flex items-center gap-6">
                    <div id="page-indicator" class="text-sm font-bold text-black uppercase tracking-widest">
                        PAGE <span id="current-page-text">1</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="changePage(-1)" id="prev-btn" class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 transition-all shadow-sm">
                            <i class="fas fa-chevron-left"></i> PREVIOUS
                        </button>
                        <button onclick="changePage(1)" id="next-btn" class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 transition-all shadow-sm">
                            NEXT <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- VIEW MODAL --}}
    <div id="view-modal" class="fixed inset-0 z-[120] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity"></div>
        <div class="bg-white rounded-[2rem] shadow-2xl z-[130] w-full max-w-2xl overflow-hidden transform scale-95 transition-all duration-300 mx-4 border-t-[15px] border-blue-600">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="bg-gray-50 h-64 md:h-full flex items-center justify-center border-r border-gray-100 overflow-hidden">
                    <img id="view-image" src="" class="w-full h-full object-cover hidden">
                    <div id="view-no-image" class="text-gray-300 flex flex-col items-center">
                        <i class="fas fa-image text-7xl mb-3"></i>
                        <span class="text-xs font-black uppercase tracking-widest">No Image Available</span>
                    </div>
                </div>
                <div class="p-10 flex flex-col justify-between h-full bg-white">
                    <div class="space-y-6 text-left">
                        <div>
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Category</label>
                            <p id="view-category" class="text-sm font-bold text-gray-800">---</p>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Product Name</label>
                            <p id="view-name" class="text-2xl font-black text-gray-900 leading-tight">---</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Size</label>
                                <p id="view-litre" class="text-sm font-bold text-gray-800">---</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Price</label>
                                <p id="view-price" class="text-sm font-bold text-gray-800">₱0.00</p>
                            </div>
                        </div>
                    </div>
                    <div class="pt-10">
                        <button onclick="closeViewModal()" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg active:scale-95">
                            Close Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RESTORE MODAL --}}
    <div id="restore-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
        <div class="bg-white rounded-2xl shadow-2xl z-[110] w-full max-w-md overflow-hidden transform scale-95 transition-all duration-300 mx-4 border-t-[12px] border-[#228B22]" id="restore-modal-content">
            <div class="px-8 py-10 flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-6 border-4 border-green-100 shadow-inner">
                    <i class="fas fa-undo-alt text-4xl text-[#228B22]"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-black uppercase tracking-tight">Restore Product?</h3>
                <p class="text-[15px] text-gray-500 mt-3 font-medium">Restore <span id="restore-product-name" class="font-bold text-black"></span> to active inventory?</p>
            </div>
            <div class="px-8 pb-10 flex items-center gap-4">
                <button type="button" onclick="closeModal('restore')" class="flex-1 px-6 py-3 rounded-xl text-xs font-bold text-black bg-white border-2 border-gray-300 uppercase tracking-widest">Cancel</button>
                <form id="restore-form" action="" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-[#228B22] text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-[#1a6b1a]">Restore</button>
                </form>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div id="force-delete-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
        <div class="bg-white rounded-2xl shadow-2xl z-[110] w-full max-w-md overflow-hidden transform scale-95 transition-all duration-300 mx-4 border-t-[12px] border-red-500" id="force-delete-modal-content">
            <div class="px-8 py-10 flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6 border-4 border-red-100 shadow-inner">
                    <i class="fas fa-trash-alt text-4xl text-red-500"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-black uppercase tracking-tight">Delete Permanently?</h3>
                <p class="text-[15px] text-gray-500 mt-3 font-medium">Permanently delete <span id="delete-product-name" class="font-bold text-black"></span>?</p>
            </div>
            <div class="px-8 pb-10 flex items-center gap-4">
                <button type="button" onclick="closeModal('force-delete')" class="flex-1 px-6 py-3 rounded-xl text-xs font-bold text-black bg-white border-2 border-gray-300 uppercase tracking-widest">Cancel</button>
                <form id="force-delete-form" action="" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-red-600">Delete</button>
                </form>
            </div>
        </div>
    </div>

    {{-- SUCCESS POPUP --}}
<div id="success-popup"
    class="fixed inset-0 z-[9999] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">

    <div id="success-popup-content"
        class="bg-white rounded-[32px] px-10 py-8 text-center shadow-2xl border border-gray-100 transform scale-95 transition-all duration-300">

        <div class="w-16 h-16 bg-[#228B22] rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-white text-2xl"></i>
        </div>

        <h3 id="success-message"
            class="text-lg font-bold text-gray-900">
            Action completed successfully.
        </h3>

        <p class="text-sm text-gray-500 mt-1">
            Product action completed successfully.
        </p>

    </div>
</div>

<style>
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

    

        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showSuccessPopup(@json(session('success')));
            });
        </script>
        @endif

    <script>
        let currentPage = 1;
        const rowsPerPage = 10;

        function updatePagination() {
            const rows = Array.from(document.querySelectorAll('.item-row'));
            const totalRows = rows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage) || 1;
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            rows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });
            document.getElementById('total-filtered-count').innerText = totalRows;
            document.getElementById('row-count-on-page').innerText = totalRows > 0 ? (end > totalRows ? totalRows : end) : 0;
            document.getElementById('current-page-text').innerText = totalRows > 0 ? currentPage : 0;
            document.getElementById('prev-btn').style.display = (currentPage > 1) ? 'flex' : 'none';
            document.getElementById('next-btn').style.display = (currentPage < totalPages && totalRows > rowsPerPage) ? 'flex' : 'none';
        }

        function changePage(direction) {
            currentPage += direction;
            updatePagination();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function openViewModal(id) {
            fetch(`/admin/inventory/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('view-name').innerText = data.name;
                    document.getElementById('view-category').innerText = data.category;
                    document.getElementById('view-litre').innerText = data.litre;
                    document.getElementById('view-price').innerText = '₱' + parseFloat(data.price_unit).toLocaleString(undefined, {minimumFractionDigits: 2});
                    const img = document.getElementById('view-image');
                    const noImg = document.getElementById('view-no-image');
                    if (data.image_path) {
                        img.src = '/storage/' + data.image_path;
                        img.classList.remove('hidden');
                        noImg.classList.add('hidden');
                    } else {
                        img.classList.add('hidden');
                        noImg.classList.remove('hidden');
                    }
                    const modal = document.getElementById('view-modal');
                    modal.classList.remove('opacity-0', 'pointer-events-none');
                    modal.querySelector('.transform').classList.replace('scale-95', 'scale-100');
                    document.body.style.overflow = 'hidden';
                });
        }

        function closeViewModal() {
            const modal = document.getElementById('view-modal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.querySelector('.transform').classList.replace('scale-100', 'scale-95');
            document.body.style.overflow = 'auto';
        }

        function confirmRestore(id, name) {
            document.getElementById('restore-form').action = `/admin/inventory/${id}/restore`; 
            document.getElementById('restore-product-name').innerText = name;
            openModal('restore');
        }

        function confirmForceDelete(id, name) {
            document.getElementById('force-delete-form').action = `/admin/inventory/${id}/force-delete`; 
            document.getElementById('delete-product-name').innerText = name;
            openModal('force-delete');
        }

        function openModal(type) {
            const modal = document.getElementById(`${type}-modal`);
            modal.classList.remove('opacity-0', 'pointer-events-none');
            document.getElementById(`${type}-modal-content`).classList.replace('scale-95', 'scale-100');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(type) {
            const modal = document.getElementById(`${type}-modal`);
            modal.classList.add('opacity-0', 'pointer-events-none');
            document.getElementById(`${type}-modal-content`).classList.replace('scale-100', 'scale-95');
            document.body.style.overflow = 'auto';
        }

        function showSuccessPopup(message) {
            const popup = document.getElementById('success-popup');
            const content = document.getElementById('success-popup-content');

            document.getElementById('success-message').innerText = message;

            popup.classList.remove('opacity-0', 'pointer-events-none');
            popup.classList.add('opacity-100');

            content.style.animation = 'modalPop 0.4s cubic-bezier(0.175,0.885,0.32,1.275)';

            setTimeout(() => {
                popup.classList.remove('opacity-100');
                popup.classList.add('opacity-0', 'pointer-events-none');
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', updatePagination);
    </script>
</x-app-layout>