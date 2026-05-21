<x-app-layout>
    <style>
        #status-description { position: relative; overflow: hidden; }
        #status-description::before {
            content: "";
            position: absolute;
            left: 0;
            top: 12px;
            bottom: 12px;
            width: 5px;
            border-radius: 50px;
            background-color: var(--border-color, #228B22);
            transition: background-color 0.3s ease;
        }

        /* Modal Transitions */
        .modal { transition: opacity 0.25s ease; }
        body.modal-active { overflow: hidden !important; }

        /* --- Custom Select & Placeholder Styling --- */
        #category-filter {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            color: #6b7280; 
            font-weight: 500;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }

        #category-filter::-ms-expand { display: none; }
        #category-filter option { color: #1f2937; font-weight: 500; }

        .filter-container select {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            appearance: none;
        }

        .form-input-consistent, 
        .focus-green-consistent {
            border: 2px solid #d1d5db; 
            background-color: #f9fafb; 
            color: #1f2937;
            transition: all 0.2s ease;
        }

        .form-input-consistent:focus,
        .focus-green-consistent:focus {
            border-color: #228B22 !important; 
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(34, 139, 34, 0.1);
            outline: none !important;
        }

        .btn-cancel-thick { border: 2px solid #9ca3af; }
        #inventory-search::placeholder { color: #6b7280; font-weight: 500; }

        .fixed-inventory-table {
            table-layout: fixed;
            width: 100%;
        }
        .col-name { width: 25%; }
        .col-cat { width: 15%; }
        .col-litre { width: 10%; }
        .col-price { width: 12%; }
        .col-stock { width: 13%; }
        .col-status { width: 15%; }
        .col-actions { width: 15%; }

        /* Style for the profile image icon */
        .product-icon-container {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background-color: #228B22;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .product-icon-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* --- Layout Stability Fix --- */
        #inventory-table-body {
            min-height: 400px; /* Keeps the container height consistent */
        }

        .empty-state-row {
            height: 400px; /* Forces the "No Products" message to fill the space */
        }
    </style>

    {{-- Header Container - Premium Dark Forest Green Client-Portal Design --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-[#1a5d1a] to-[#228B22] px-10 py-10 rounded-[32px] shadow-xl shadow-green-100 mb-6">
            
            {{-- Decorative Organic Blurs from client dashboards --}}
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
                {{-- Left Text Side --}}
                <div>
                    <h1 class="text-[28px] font-bold text-white tracking-tight">Inventory Management</h1>
                    <p class="text-[14px] text-green-50 font-medium mt-1">Track stock levels and manage products in one place.</p>
                </div>
                
                {{-- Right Buttons Side --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.inventory.archived') }}" class="bg-red-600 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-[#8B0000] transition-all shadow-sm flex items-center gap-2 h-fit whitespace-nowrap">
                        <i class="fas fa-archive"></i> Archive
                    </a>
                    
                    {{-- Updated Button to perfectly match Archive Font Size, Padding, Tracking, and Weight --}}
                    <button onclick="openAddModal()" class="bg-white text-[#228B22] px-6 py-3 rounded-xl text-sm font-bold hover:bg-gray-100 transition-all shadow-sm flex items-center gap-2 h-fit whitespace-nowrap">
                        <i class="fas fa-plus"></i> Add New Product
                    </button>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div id="success-toast" class="fixed inset-0 z-[9999] flex items-center justify-center pointer-events-none">
            <div class="bg-white rounded-[32px] px-10 py-8 text-center shadow-2xl border border-gray-100 pointer-events-auto" style="animation:modalPop 0.4s cubic-bezier(0.175,0.885,0.32,1.275);">
                <div class="w-16 h-16 bg-[#228B22] rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">{{ session('success') }}</h3>
                <p class="text-sm text-gray-500 mt-1">Action completed successfully.</p>
            </div>
        </div>
        <style>@keyframes modalPop{0%{opacity:0;transform:scale(0.9)}100%{opacity:1;transform:scale(1)}}</style>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const toast = document.getElementById('success-toast');
                if(toast) setTimeout(() => toast.remove(), 3000);
            });
        </script>
        @endif

        {{-- Tabs --}}
        <div class="flex items-center gap-6 border-b border-gray-200 w-full">
            <button onclick="filterInventory('all')" id="tab-all" class="flex items-center gap-2 px-2 pb-4 text-sm font-bold border-b-4 border-[#228B22] text-[#228B22] transition-all -mb-[2px]">
                All Items <span id="count-all" class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-[11px] font-bold transition-all">0</span>
            </button>
            <button onclick="filterInventory('in-stock')" id="tab-in-stock" class="flex items-center gap-2 px-2 pb-4 text-sm font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent transition-all -mb-[2px]">
                In Stock <span id="count-in-stock" class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-[11px] font-bold transition-all">0</span>
            </button>
            <button onclick="filterInventory('low-stock')" id="tab-low-stock" class="flex items-center gap-2 px-2 pb-4 text-sm font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent transition-all -mb-[2px]">
                Low Stock <span id="count-low-stock" class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-[11px] font-bold transition-all">0</span>
            </button>
            <button onclick="filterInventory('out-of-stock')" id="tab-out-of-stock" class="flex items-center gap-2 px-2 pb-4 text-sm font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent transition-all -mb-[2px]">
                Out of Stock <span id="count-out-of-stock" class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-[11px] font-bold transition-all">0</span>
            </button>
        </div><br>

        {{-- Search/Status/Category Area --}}
        <div id="status-description" style="background-color: rgba(34, 139, 34, 0.05);" class="p-5 pl-8 rounded-xl shadow-sm flex flex-col xl:flex-row xl:items-center justify-between gap-4 border border-gray-100">
            <div class="flex-1">
                <h4 id="desc-title" class="text-[13px] font-bold text-[#228B22] uppercase tracking-wider">All Items</h4>
                <p id="desc-text" class="text-[14px] text-gray-600 font-medium mt-0.5">Manage and view the total list of all products and supplies in your inventory.</p>
            </div>
            
            <div class="flex flex-col md:flex-row items-center gap-3">
                <div class="relative w-full md:w-[230px]">
                    <select id="category-filter" onchange="updateCategoryLabel(this)" 
                            class="w-full pl-4 pr-10 py-3 bg-white border border-gray-300 rounded-xl text-sm focus-green-consistent cursor-pointer transition-all">
                        <option value="all" id="category-default-option">Category: All</option>
                        <option value="all">All</option>
                        <option value="Bottle milk">Bottle milk</option>
                        <option value="Milk bar">Milk bar</option>
                        <option value="Ice cream">Ice cream</option>
                    </select>
                </div>

                <div class="relative w-full md:w-[280px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4"><i class="fas fa-search text-gray-400"></i></span>
                    <input type="text" id="inventory-search" onkeyup="filterInventory(currentStatus)" placeholder="Search product name..." 
                           class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-sm font-medium focus-green-consistent transition-all">
                </div>

                <div class="filter-container relative flex items-center justify-center w-12 h-12 bg-white border border-gray-300 rounded-xl shadow-sm hover:border-[#228B22] transition-all">
                    <i class="fas fa-filter text-gray-500"></i>
                    <select id="sort-filter" onchange="filterInventory(currentStatus)" title="Sort Inventory">
                        <option value="recent">Recently Updated</option>
                        <option value="az">Item: A to Z</option>
                        <option value="stock-high">Stock: High to Low</option>
                        <option value="stock-low">Stock: Low to High</option>
                    </select>
                </div>
            </div>
        </div> <br>

        {{-- Table Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-300 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="fixed-inventory-table text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-300">
                            <th class="py-4 px-8 text-black text-[13px] uppercase font-bold col-name tracking-tight">Product Name</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center col-cat tracking-tight">Category</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center col-litre tracking-tight">Litre</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center col-price tracking-tight">Price Unit</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center col-stock tracking-tight">Stock Level</th>
                            <th class="py-4 px-6 text-black text-[13px] uppercase font-bold text-center col-status tracking-tight">Status</th>
                            <th class="py-4 px-8 text-black text-[13px] uppercase font-bold text-center col-actions tracking-tight">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inventory-table-body" class="text-black text-[14px]">
                        @forelse($products as $product)
                        <tr class="item-row border-b border-gray-200 hover:bg-gray-50/50 transition" 
                            data-status="{{ $product->stock <= 0 ? 'out-of-stock' : ($product->stock <= 10 ? 'low-stock' : 'in-stock') }}" 
                            data-name="{{ $product->name }}"
                            data-category="{{ $product->category }}"
                            data-created="{{ $product->created_at }}"
                            data-updated="{{ $product->updated_at }}"
                            data-stock="{{ $product->stock }}">
                            
                            <td class="py-4 px-8 font-semibold text-gray-800">
                                <div class="flex items-center gap-3">
                                    <div class="product-icon-container">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="Product">
                                        @else
                                            <i class="fas fa-glass-whiskey text-white text-[12px]"></i>
                                        @endif
                                    </div>
                                    <span>{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center text-gray-600 font-medium truncate">{{ $product->category }}</td>
                            <td class="py-4 px-6 text-center truncate">{{ $product->litre }}</td>
                            <td class="py-4 px-6 text-center font-bold text-[#228B22] truncate">₱{{ number_format($product->price_unit, 2) }}</td>
                            <td class="py-4 px-6 text-center font-bold truncate">{{ $product->stock }} <span class="text-[10px] text-gray-400 font-bold ml-1">units</span></td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center justify-center py-1 px-3 rounded-full text-xs font-bold w-full max-w-[110px] 
                                    {{ $product->stock <= 0 ? 'bg-red-100 text-red-700' : ($product->stock <= 10 ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                                    {{ $product->stock <= 0 ? 'OUT OF STOCK' : ($product->stock <= 10 ? 'LOW STOCK' : 'IN STOCK') }}
                                </span>
                            </td>
                            
                            <td class="py-4 px-8">
                                <div class="flex justify-center gap-1.5">
                                    <button onclick="openViewModal({{ $product->id }})" title="View Details" class="px-3 py-2 flex items-center justify-center bg-blue-500 text-white rounded-xl hover:bg-blue-600 shadow-sm transition-all">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                    <button onclick="openEditModal({{ $product->id }})" class="px-3 py-2 flex items-center justify-center bg-[#FF8C00] text-white rounded-xl hover:bg-[#e67e00] shadow-sm transition-all min-w-[45px]">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                    <button onclick="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')" class="px-3 py-2 flex items-center justify-center bg-red-500 text-white rounded-xl hover:bg-red-600 shadow-sm transition-all min-w-[45px]">
                                        <i class="fas fa-archive text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        @endforelse

                        <tr id="empty-row" style="display: none;">
                            <td colspan="7" class="py-20 text-center text-gray-500 font-medium">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-box-open text-5xl mb-4 opacity-20"></i>
                                    <span>No inventory items found.</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

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

    {{-- PRODUCT MODAL (ADD/EDIT) --}}
    <div id="product-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none modal transition-all duration-300">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
        <div class="bg-white rounded-2xl shadow-2xl z-[110] w-full max-w-lg overflow-hidden transform scale-95 transition-all duration-300 mx-4 border-t-[12px] border-[#228B22]" id="modal-content">
            <div class="px-8 py-8 flex flex-col items-center text-center">
                <div id="modal-icon-container" class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 flex-shrink-0 border-4 border-gray-100 shadow-inner">
                    <i id="modal-icon" class="fas fa-glass-whiskey text-3xl text-[#228B22]"></i>
                </div>
                <div>
                    <h3 id="modal-title" class="text-2xl font-extrabold text-black uppercase tracking-tight">Add New Product</h3>
                    <p id="modal-subtitle" class="text-sm text-gray-500 mt-1 font-medium">Fill in the information below to add a new milk product.</p>
                </div>
            </div>
            <form id="product-form" action="" method="POST" enctype="multipart/form-data" class="px-10 pb-10 space-y-5" onsubmit="return validateProductForm()">
                @csrf
                <div id="method-container"></div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Product Name:</label>
                        <input type="text" name="name" id="field-name" required placeholder="Enter Product Name" 
                               class="w-full px-4 py-2.5 rounded-xl outline-none transition-all text-sm font-medium form-input-consistent">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Categories:</label>
                            <select name="category" id="field-category" required class="w-full px-4 py-2.5 rounded-xl outline-none transition-all text-sm font-medium form-input-consistent cursor-pointer">
                                <option value="" disabled selected>Select category</option>
                                <option value="Bottle milk">Bottle milk</option>
                                <option value="Milk bar">Milk bar</option>
                                <option value="Ice cream">Ice cream</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Litre:</label>
                            <select name="litre" id="field-litre" required class="w-full px-4 py-2.5 rounded-xl outline-none transition-all text-sm font-medium form-input-consistent cursor-pointer">
                                <option value="" disabled selected>Select size</option>
                                <option value="1000mL">1000mL</option>
                                <option value="500mL">500mL</option>
                                <option value="320mL">320mL</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Price Unit (₱):</label>
                            <input type="number" step="0.01" min="0.01" name="price_unit"id="field-price" required placeholder="0.00" 
                                   class="w-full px-4 py-2.5 rounded-xl outline-none transition-all text-sm font-medium form-input-consistent">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Stock Level:</label>
                            <input type="number" min="1" name="stock" id="field-stock" required placeholder="0" 
                                   onkeydown="handleEnter(event, 'field-expiry')"
                                   class="w-full px-4 py-2.5 rounded-xl outline-none transition-all text-sm font-medium form-input-consistent">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Expiration Date:</label>
                            <input type="date" name="expiration_date" id="field-expiry" required
                                   onkeydown="handleEnter(event, 'field-image')"
                                   onchange="validateYear(this)"
                                   class="w-full px-4 py-2.5 rounded-xl outline-none transition-all text-sm font-medium form-input-consistent">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Product Image:</label>
                            <input type="file" name="image" id="field-image" accept="image/*" 
                                class="w-full px-4 py-1.5 rounded-xl outline-none transition-all text-xs font-medium form-input-consistent">
                        </div>
                    </div>
                </div>
                <div class="pt-6 flex items-center gap-3">
                    <button type="button" onclick="closeModal()" 
                            class="flex-1 px-6 py-3.5 rounded-xl text-xs font-bold text-black bg-white btn-cancel-thick uppercase tracking-widest hover:bg-gray-100 transition-all text-center">
                        Cancel
                    </button>
                    <button type="submit" id="modal-submit-btn"
                            class="flex-1 bg-[#228B22] text-white px-6 py-3.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-md active:scale-95 text-center">
                        Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- BIG BLUE VIEW DETAILS MODAL --}}
    <div id="view-modal" class="fixed inset-0 z-[120] flex items-center justify-center opacity-0 pointer-events-none modal transition-all duration-300">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="bg-white rounded-[2rem] shadow-2xl z-[130] w-full max-w-2xl overflow-hidden transform scale-95 transition-all duration-300 mx-4 border-t-[15px] border-blue-600">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="bg-gray-50 h-64 md:h-full flex items-center justify-center border-r border-gray-100 overflow-hidden">
                <img id="view-image" src="" class="w-full h-full object-cover hidden">
                <div id="view-no-image" class="text-gray-300 flex flex-col items-center">
                    <i class="fas fa-image text-7xl mb-3"></i>
                    <span class="text-xs font-black uppercase">No Image</span>
                </div>
            </div>

            <div class="p-10 flex flex-col justify-between h-full bg-white">
                <div class="space-y-6">
                    <div>
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Category</label>
                        <p id="view-category" class="text-sm font-bold text-gray-800">---</p>
                    </div>
                    
                    <div>
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Product Name</label>
                        <p id="view-name" class="text-2xl font-black text-gray-900 leading-tight">---</p>
                    </div>

                    <div class="grid grid-cols-2 gap-y-6 gap-x-4">
                        <div>
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Size / Litre</label>
                            <p id="view-litre" class="text-sm font-bold text-gray-800">---</p>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Unit Price</label>
                            <p id="view-price" class="text-sm font-bold text-gray-800">₱0.00</p>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Stock Level</label>
                            <p id="view-stock" class="text-sm font-bold text-gray-800">0 Units</p>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Expiration</label>
                            <p id="view-expiry" class="text-sm font-bold text-gray-800">---</p>
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

    {{-- ARCHIVE CONFIRMATION MODAL --}}
    <div id="delete-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none modal transition-all duration-300">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
        <div class="bg-white rounded-2xl shadow-2xl z-[110] w-full max-w-md overflow-hidden transform scale-95 transition-all duration-300 mx-4 border-t-[12px] border-red-50" id="delete-modal-content">
            <div class="px-8 py-10 flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6 border-4 border-red-100 shadow-inner">
                    <i class="fas fa-archive text-4xl text-red-500"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-black uppercase tracking-tight">Archive Product?</h3>
                <p class="text-[15px] text-gray-500 mt-3 font-medium">Are you sure you want to archive <span id="delete-product-name" class="font-bold text-black"></span>? This will hide it from your main inventory.</p>
            </div>
            <div class="px-8 pb-10 flex items-center gap-4">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-6 py-3 rounded-xl text-xs font-bold text-black bg-white border-2 border-gray-300 uppercase tracking-widest hover:bg-gray-100 transition-all">
                    Cancel
                </button>
                <form id="delete-form" action="" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-red-600 transition-all shadow-md active:scale-95">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('product-modal');
        const modalContent = document.getElementById('modal-content');
        const productForm = document.getElementById('product-form');
        const submitBtn = document.getElementById('modal-submit-btn');

        // --- PREVENT SUBMIT UNLESS COMPLETE ---
        function validateProductForm() {
            const inputs = productForm.querySelectorAll('input[required], select[required]');

            for (let input of inputs) {
                if (!input.value) {
                    alert("Please fill in all details before submitting!");
                    input.focus();
                    return false;
                }
            }

            // --- VALIDATE PRICE UNIT ---
            const price = parseFloat(document.getElementById('field-price').value);

            if (price <= 0 || isNaN(price)) {
                alert("Price Unit must be greater than 0.");
                document.getElementById('field-price').focus();
                return false;
            }

            // --- VALIDATE STOCK LEVEL ---
            const stock = parseInt(document.getElementById('field-stock').value);

            if (stock <= 0 || isNaN(stock)) {
                alert("Stock Level must be greater than 0.");
                document.getElementById('field-stock').focus();
                return false;
            }

            return true;
        }

        // --- HANDLE ENTER KEY FLOW ---
        function handleEnter(event, nextFieldId) {
            if (event.key === "Enter") {
                event.preventDefault(); // Stop form from submitting
                const nextField = document.getElementById(nextFieldId);
                if (nextField) {
                    nextField.focus();
                    if(nextField.type === 'date') nextField.showPicker(); // Open calendar if date
                }
            }
        }

        // --- VALIDATE 4-DIGIT YEAR ---
        function validateYear(input) {
            const dateValue = input.value;
            if (dateValue) {
                const year = new Date(dateValue).getFullYear();
                if (year > 9999 || year < 1000) {
                    alert("Please enter a valid 4-digit year.");
                    input.value = "";
                }
            }
        }

        function updateCategoryLabel(select) {
            const defaultOption = document.getElementById('category-default-option');
            const selectionText = select.options[select.selectedIndex].text;
            const selectionValue = select.value; 
            if (selectionValue === "all" || selectionText === "All") {
                defaultOption.text = "Category: All";
                defaultOption.value = "all";
            } else {
                defaultOption.text = "Category: " + selectionText;
                defaultOption.value = selectionValue;
            }
            defaultOption.selected = true;
            filterInventory(currentStatus);
        }

        function openAddModal() {
        productForm.reset();
        productForm.action = "{{ route('admin.inventory.store') }}";
        document.getElementById('method-container').innerHTML = '';
        
        // Design: Forest Green for Add Mode
        modalContent.style.borderColor = "#228B22"; 
        const submitBtn = document.getElementById('modal-submit-btn');
        submitBtn.style.backgroundColor = "#228B22";
        submitBtn.innerText = "Add Product";
        
        // --- ADD MODE: Keep icon container gray-50 and whiskey glass forest green ---
        const iconContainer = document.getElementById('modal-icon-container');
        const iconElement = document.getElementById('modal-icon');
        
        iconContainer.className = "w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 flex-shrink-0 border-4 border-gray-100 shadow-inner";
        iconElement.className = "fas fa-glass-whiskey text-3xl text-[#228B22]";
        
        document.getElementById('modal-title').innerText = "Add New Product";
        document.getElementById('field-image').required = true; // Image required for new items
        toggleModal(true);
    }

    function openAddModal() {
        productForm.reset();
        productForm.action = "{{ route('admin.inventory.store') }}";
        document.getElementById('method-container').innerHTML = '';
        
        // Design: Forest Green border and button for Add Mode
        modalContent.style.borderColor = "#228B22"; 
        const submitBtn = document.getElementById('modal-submit-btn');
        submitBtn.style.backgroundColor = "#228B22";
        submitBtn.innerText = "Add Product";
        
        // --- ADD MODE: Keep icon container gray and whiskey glass forest green ---
        const iconContainer = document.getElementById('modal-icon-container');
        const iconElement = document.getElementById('modal-icon');
        
        iconContainer.className = "w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 flex-shrink-0 border-4 border-gray-100 shadow-inner";
        iconElement.className = "fas fa-glass-whiskey text-3xl text-[#228B22]";
        
        document.getElementById('modal-title').innerText = "Add New Product";
        document.getElementById('field-image').required = true; 
        toggleModal(true);
    }

    function openEditModal(id) {
        fetch(`/admin/inventory/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                productForm.action = `/admin/inventory/${id}`; 
                document.getElementById('method-container').innerHTML = '@method("PUT")';
                
                // Design: Orange border and button for Edit Mode
                modalContent.style.borderColor = "#FF8C00"; 
                const submitBtn = document.getElementById('modal-submit-btn');
                submitBtn.style.backgroundColor = "#FF8C00";
                submitBtn.innerText = "Edit Product";
                
                // --- EDIT MODE: Keep background circle gray, ONLY make the icon itself orange ---
                const iconContainer = document.getElementById('modal-icon-container');
                const iconElement = document.getElementById('modal-icon');
                
                iconContainer.className = "w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 flex-shrink-0 border-4 border-gray-100 shadow-inner";
                iconElement.className = "fas fa-glass-whiskey text-3xl text-orange-500";
                
                document.getElementById('modal-title').innerText = "Edit Product";
                
                // Populate fields
                document.getElementById('field-name').value = data.name;
                document.getElementById('field-category').value = data.category;
                document.getElementById('field-litre').value = data.litre;
                document.getElementById('field-price').value = data.price_unit;
                document.getElementById('field-stock').value = data.stock;
                document.getElementById('field-expiry').value = data.expiration_date;
                
                document.getElementById('field-image').required = false; 
                toggleModal(true);
            });
    }

        function openViewModal(id) {
            fetch(`/admin/inventory/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('view-name').innerText = data.name;
                    document.getElementById('view-category').innerText = data.category;
                    document.getElementById('view-litre').innerText = data.litre;
                    document.getElementById('view-price').innerText = '₱' + parseFloat(data.price_unit).toLocaleString(undefined, {minimumFractionDigits: 2});
                    document.getElementById('view-stock').innerText = data.stock + ' Units';
                    document.getElementById('view-expiry').innerText = data.expiration_date ? data.expiration_date : 'Not set';

                    const imgElement = document.getElementById('view-image');
                    const placeholder = document.getElementById('view-no-image');

                    if (data.image_path) {
                        imgElement.src = '/storage/' + data.image_path;
                        imgElement.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    } else {
                        imgElement.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                    }

                    const vModal = document.getElementById('view-modal');
                    const vContent = vModal.querySelector('.max-w-2xl');
                    vModal.classList.remove('opacity-0', 'pointer-events-none');
                    vContent.classList.replace('scale-95', 'scale-100');
                    document.body.classList.add('modal-active');
                });
        }

        function closeViewModal() {
            const vModal = document.getElementById('view-modal');
            const vContent = vModal.querySelector('.max-w-2xl');
            vModal.classList.add('opacity-0', 'pointer-events-none');
            vContent.classList.replace('scale-100', 'scale-95');
            document.body.classList.remove('modal-active');
        }

        function closeModal() { toggleModal(false); }
        function toggleModal(show) {
            if (show) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modalContent.classList.replace('scale-95', 'scale-100');
                document.body.classList.add('modal-active');
            } else {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modalContent.classList.replace('scale-100', 'scale-95');
                document.body.classList.remove('modal-active');
            }
        }

        const deleteModal = document.getElementById('delete-modal');
        const deleteModalContent = document.getElementById('delete-modal-content');
        const deleteForm = document.getElementById('delete-form');

        function confirmDelete(id, name) {
            deleteForm.action = `/admin/inventory/${id}`;
            document.getElementById('delete-product-name').innerText = name;
            deleteModal.classList.remove('opacity-0', 'pointer-events-none');
            deleteModalContent.classList.replace('scale-95', 'scale-100');
            document.body.classList.add('modal-active');
        }

        function closeDeleteModal() {
            deleteModal.classList.add('opacity-0', 'pointer-events-none');
            deleteModalContent.classList.replace('scale-100', 'scale-95');
            document.body.classList.remove('modal-active');
        }

        let currentStatus = 'all'; 
        let currentPage = 1; 
        const rowsPerPage = 10;
        const descriptions = {
            all: { title: "All Products", text: "Manage and view the total list of all products and supplies in your inventory.", color: "#228B22", bgColor: "rgba(34, 139, 34, 0.05)" },
            'in-stock': { title: "In Stock", text: "Items currently available and ready for sale.", color: "#059669", bgColor: "rgba(5, 150, 105, 0.05)" },
            'low-stock': { title: "Low Stock", text: "Items running below threshold (10 units). Please restock soon.", color: "#D97706", bgColor: "rgba(217, 119, 6, 0.05)" },
            'out-of-stock': { title: "Out of Stock", text: "Items with zero availability in the warehouse.", color: "#DC2626", bgColor: "rgba(220, 38, 38, 0.05)" }
        };

        function filterInventory(status) { currentStatus = status; currentPage = 1; updateTable(); }
        function changePage(step) { currentPage += step; updateTable(); }

        function updateTable() {
    const searchTerm = document.getElementById('inventory-search').value.toLowerCase();
    const categoryFilter = document.getElementById('category-filter').value;
    const sortBy = document.getElementById('sort-filter').value;
    const tableBody = document.getElementById('inventory-table-body');
    const rows = Array.from(document.querySelectorAll('.item-row'));
    
    const counts = { all: 0, 'in-stock': 0, 'low-stock': 0, 'out-of-stock': 0 };
    rows.forEach(row => {
        const status = row.getAttribute('data-status');
        counts.all++;
        if (counts.hasOwnProperty(status)) counts[status]++;
    });

    document.getElementById('count-all').innerText = counts.all;
    document.getElementById('count-in-stock').innerText = counts['in-stock'];
    document.getElementById('count-low-stock').innerText = counts['low-stock'];
    document.getElementById('count-out-of-stock').innerText = counts['out-of-stock'];

    rows.sort((a, b) => {
        if (sortBy === 'az') return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
        if (sortBy === 'recent') {
            const dateA = new Date(Math.max(new Date(a.getAttribute('data-created')), new Date(a.getAttribute('data-updated'))));
            const dateB = new Date(Math.max(new Date(b.getAttribute('data-created')), new Date(b.getAttribute('data-updated'))));
            return dateB - dateA;
        }
        if (sortBy === 'stock-high') return parseInt(b.getAttribute('data-stock')) - parseInt(a.getAttribute('data-stock'));
        if (sortBy === 'stock-low') return parseInt(a.getAttribute('data-stock')) - parseInt(b.getAttribute('data-stock'));
        return 0;
    });

    rows.forEach(row => tableBody.appendChild(row));

    let filteredRows = rows.filter(row => {
        // Updated: Checks text content of the entire row to search all columns
        const rowContent = row.innerText.toLowerCase(); 
        const category = row.getAttribute('data-category');
        const status = row.getAttribute('data-status');
        
        const matchesTab = (currentStatus === 'all' || status === currentStatus);
        const matchesCategory = (categoryFilter === 'all' || category === categoryFilter);
        
        // Updated: matchesSearch now checks against all columns
        const matchesSearch = rowContent.includes(searchTerm);

        return matchesTab && matchesCategory && matchesSearch;
    });

    const total = filteredRows.length;
    const totalPages = Math.ceil(total / rowsPerPage) || 1;
    const startIdx = (currentPage - 1) * rowsPerPage;
    const endIdx = startIdx + rowsPerPage;

    rows.forEach(row => row.style.display = 'none');
    const currentPageRows = filteredRows.slice(startIdx, endIdx);
    currentPageRows.forEach(row => row.style.display = 'table-row');

    document.getElementById('row-count-on-page').innerText = currentPageRows.length;
    document.getElementById('total-filtered-count').innerText = total; 

    document.getElementById('empty-row').style.display = total === 0 ? 'table-row' : 'none';
    document.getElementById('current-page-text').innerText = currentPage;

    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    prevBtn.style.display = (currentPage > 1) ? 'flex' : 'none';
    nextBtn.style.display = (currentPage < totalPages && total > rowsPerPage) ? 'flex' : 'none';

    // Update active tab button styles and count badges dynamically
    ['all', 'in-stock', 'low-stock', 'out-of-stock'].forEach(tab => {
        const el = document.getElementById('tab-' + tab);
        const countSpan = document.getElementById('count-' + tab);
        
        if (el && countSpan) {
            if (tab === currentStatus) {
                // Active Tab Button text and bottom indicator line
                el.className = "flex items-center gap-2 px-2 pb-4 text-sm font-bold border-b-4 border-[#228B22] text-[#228B22] transition-all -mb-[2px]";
                
                // Active Tab Badge () changes into custom client-side green 
                countSpan.className = "bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-[11px] font-bold transition-all";
            } else {
                // Inactive Tab Button styling
                el.className = "flex items-center gap-2 px-2 pb-4 text-sm font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent transition-all -mb-[2px]";
                
                // Inactive Tab Badges () reset completely back to plain gray
                countSpan.className = "bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-[11px] font-bold transition-all";
            }
        }
    });

    const desc = descriptions[currentStatus];
    const descBox = document.getElementById('status-description');
    if (descBox && desc) {
        // Enforces identical background color matching the client purchase view
        descBox.style.backgroundColor = "rgba(34, 139, 34, 0.05)";
        document.getElementById('desc-title').innerText = desc.title;
        document.getElementById('desc-text').innerText = desc.text;
        
        // Keeps title as forest green and strictly locks secondary descriptions to client gray color styling
        document.getElementById('desc-title').className = "text-[13px] font-bold text-[#228B22] uppercase tracking-wider";
        document.getElementById('desc-text').className = "text-[14px] text-gray-600 font-medium mt-0.5";
    }
}

        document.addEventListener('DOMContentLoaded', () => {
            updateTable();
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(() => {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 500);
                }, 3000); 
            }
        });
    </script>
</x-app-layout>