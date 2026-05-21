<x-app-layout>
    <style>
        /* Status Description Box Styling */
        #status-description {
            position: relative;
            overflow: hidden;
        }
        #status-description::before {
            content: "";
            position: absolute;
            left: 0;
            top: 12px;
            bottom: 12px;
            width: 5px;
            border-radius: 50px;
            background-color: #228B22;
            transition: background-color 0.3s ease;
        }

        /* Product Card Base */
        .product-card-enhanced {
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.07);
            transition: all 0.3s ease;
            height: fit-content;
        }
        .product-card-enhanced:hover {
            box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
        }

        /* Large Centered Quantity Buttons */
        .qty-btn {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid #d1d5db;
            border-radius: 12px;
            background: white;
            font-size: 1.5rem; 
            font-weight: bold;
            transition: all 0.2s;
            line-height: 0;
            padding: 0;
        }
        .qty-btn:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
        }

        /* Filter icon select styling */
        .filter-container select {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            appearance: none;
        }

        .input-small {
            padding-top: 0.6rem !important;
            padding-bottom: 0.6rem !important;
            font-size: 0.8rem !important;
        }

        .cart-input-field {
            background-color: #f3f4f6 !important;
            border: 1px solid #d1d5db !important;
            border-radius: 12px;
            padding: 1rem;
            width: 100%;
            font-size: 0.875rem;
            font-weight: 500;
            color: #111827;
            outline: none;
            transition: all 0.2s;
        }

        .cart-input-field::placeholder {
            color: #6b7280;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .cart-input-field:focus {
            border-color: #228B22 !important;
            box-shadow: 0 0 0 1px #228B22;
        }

        /* Custom Cancel Button */
        .btn-cancel-custom {
            background-color: white;
            border: 1.5px solid #d1d5db;
            color: black;
            transition: all 0.2s;
        }
        .btn-cancel-custom:hover {
            background-color: #f3f4f6;
        }

        /* Add this to your <style> section */
        .summary-container {
            border: 1px solid #9ca3af;
            border-radius: 16px;
            padding: 1.25rem;
            background-color: #f9fafb; /* Light Grey Background */
        }

        /* Modal Pop-up Animation */
        .modal-animate-in {
            animation: modalPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes modalPop {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* Checkmark Animation */
        .check-bounce {
            animation: checkBounce 0.6s ease-in-out forwards;
        }

        @keyframes checkBounce {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* Removed green left border */
        #summary-container-wrapper {
            transition: all 0.3s ease;
        }
        
        /* Slide-left animation layout for the trash button */
        .trash-container {
            width: 0;
            opacity: 0;
            overflow: hidden;
            transition: width 0.3s ease, opacity 0.2s ease;
        }
        .edit-mode .trash-container {
            width: 36px;
            opacity: 1;
        }
    </style>

    {{-- Header Container - Full Width with Original Styles --}}
    <div class="w-full pb-10">
        {{-- Container: Removed max-w, increased horizontal padding to fill gaps --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-[#1a5d1a] to-[#228B22] px-10 py-10 rounded-[32px] shadow-xl shadow-green-100">
            
            {{-- Decorative background patterns --}}
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>

            <div class="relative z-10 flex justify-between items-center">
                {{-- Original Text Styles --}}
                <div>
                    <h1 class="text-[28px] font-bold text-white tracking-tight">Order Milk Products</h1>
                    <p class="text-[14px] text-green-50 font-medium">Select from our fresh farm-to-table dairy products.</p>
                </div>  
                
                {{-- Original Button Styles --}}
                <button id="checkout-btn" onclick="openCheckout(); setTimeout(() => document.getElementById('phone_number').focus(), 100);" class="flex items-center justify-center gap-2 px-10 py-3 bg-white text-[#228B22] rounded-xl text-sm font-bold hover:bg-gray-100 transition-all tracking-wider">
                    <i class="fas fa-shopping-cart leading-none"></i>
                    <span id="checkout-text" class="leading-none">Check Out</span>
                </button>
            </div>
        </div>
    </div>

        {{-- Tabs --}}
       {{-- Tabs Container --}}
        <div class="flex items-center gap-6 border-b border-gray-200 w-full overflow-x-auto no-scrollbar">
            @php
                $allCount = $products->count();
                $bottleCount = $products->filter(fn($p) => str_contains(strtolower($p->category), 'bottle'))->count();
                $barCount = $products->filter(fn($p) => str_contains(strtolower($p->category), 'bar'))->count();
                $iceCount = $products->filter(fn($p) => str_contains(strtolower($p->category), 'ice'))->count();
            @endphp

            <button onclick="filterCategory('all')" id="tab-all" class="flex items-center gap-2 px-2 pb-4 text-sm font-bold border-b-4 border-[#228B22] text-[#228B22] transition-all whitespace-nowrap">
                All Products <span class="bg-green-100 text-black px-2 py-0.5 rounded-full text-[11px]">{{ $allCount }}</span>
            </button>
            <button onclick="filterCategory('bottle milk')" id="tab-bottle" class="flex items-center gap-2 px-2 pb-4 text-sm font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent transition-all whitespace-nowrap">
                Bottle Milk <span class="bg-gray-100 text-black px-2 py-0.5 rounded-full text-[11px]">{{ $bottleCount }}</span>
            </button>
            <button onclick="filterCategory('milk bar')" id="tab-bar" class="flex items-center gap-2 px-2 pb-4 text-sm font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent transition-all whitespace-nowrap">
                Milk Bar <span class="bg-gray-100 text-black px-2 py-0.5 rounded-full text-[11px]">{{ $barCount }}</span>
            </button>
            <button onclick="filterCategory('ice cream')" id="tab-ice" class="flex items-center gap-2 px-2 pb-4 text-sm font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent transition-all whitespace-nowrap">
                Ice Cream <span class="bg-gray-100 text-black px-2 py-0.5 rounded-full text-[11px]">{{ $iceCount }}</span>
            </button>
        </div><br>

        {{-- Status Description Box --}}
        <div id="status-description" style="background-color: rgba(34, 139, 34, 0.05);" class="p-5 pl-8 rounded-xl shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4 border border-gray-100">
            <div class="flex-1">
                <h4 id="desc-title" class="text-[13px] font-bold text-[#228B22] uppercase tracking-wider">ALL PRODUCTS</h4>
                <p id="desc-text" class="text-[14px] text-gray-600 font-medium mt-0.5">Browse all available farm-fresh products and add them to your cart.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="relative w-full md:w-[350px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <input type="text" id="search-box" placeholder="Search Product and others..." class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-sm text-black focus:border-[#228B22] focus:ring-2 focus:ring-[#228B22]/20 outline-none transition-all shadow-sm font-medium">
                </div>

                <div class="filter-container relative flex items-center justify-center w-11 h-11 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
                    <i class="fas fa-filter text-gray-500"></i>
                    <select id="sort-filter" onchange="sortProducts()">
                        <option value="recent">Sort By</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                    </select>
                </div>
            </div>
        </div><br>

        {{-- Product Grid --}}
        <div id="product-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($products as $product)
            @php 
                $isOutOfStock = $product->stock <= 0; 
                $badgeColor = ($product->stock < 10) ? '#FF8C00' : '#228B22';
                $normalizedCategory = strtolower(trim($product->category));
                $expDate = $product->expiration_date ? \Carbon\Carbon::parse($product->expiration_date)->format('M d, Y') : 'N/A';
                $textColor = $isOutOfStock ? 'text-gray-400' : 'text-black';
                $priceColor = $isOutOfStock ? 'text-gray-400' : 'text-[#228B22]';
            @endphp

            <div class="product-card flex flex-col rounded-[24px] overflow-hidden product-card-enhanced
                {{ $isOutOfStock ? 'bg-[#f3f4f6] opacity-95' : 'bg-white' }}"
                data-id="{{ $product->id }}"
                data-category="{{ $normalizedCategory }}"
                data-name="{{ strtolower($product->name) }}"
                data-price="{{ $product->price_unit }}"
                data-litre="{{ $product->litre }}"
                data-stock="{{ $product->stock }}"
                data-expiration="{{ $expDate }}"
                data-image="{{ $product->image_path ? asset('storage/' . $product->image_path) : '' }}"
                data-raw-category="{{ $product->category }}">
                
                <div class="relative w-full h-[200px] bg-[#f8f9fa] border-b border-gray-100">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover {{ $isOutOfStock ? 'grayscale' : '' }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fas fa-image text-3xl"></i></div>
                    @endif
                    
                    @if($isOutOfStock)
                        <div class="absolute inset-0 flex items-center justify-center bg-black/5">
                            <div class="border-[3px] border-red-600 px-4 py-1 rotate-[-15deg] bg-white/95 shadow-lg">
                                <span class="text-red-600 font-black text-xl uppercase">OUT OF STOCK</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="p-6 flex flex-col flex-1">
                    <div class="mb-4">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-bold uppercase tracking-tight {{ $textColor }}">{{ $product->category }}</p>
                            @if(!$isOutOfStock)
                                @php
                                    // Changed to solid backgrounds (bg-[#228B22] and bg-orange-500) and forced white text (text-white)
                                    $stockBadgeClasses = $product->stock >= 11 
                                        ? 'bg-[#228B22] border-[#228B22] text-white' 
                                        : 'bg-orange-500 border-orange-500 text-white';
                                @endphp
                                <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1.5 border rounded-full shadow-sm {{ $stockBadgeClasses }}">
                                    {{ $product->stock }} LEFT
                                </span>
                            @endif
                        </div>
                        <h3 class="text-[18px] font-bold leading-tight mt-1 {{ $textColor }}">
                            {{ $product->name }} - {{ $product->litre }}
                        </h3>
                    </div>

                    <div class="mb-5">
                        <span class="text-[24px] font-semibold {{ $priceColor }}">₱{{ number_format($product->price_unit, 2) }}</span>
                    </div>

                    <div class="mt-auto">
                        <div id="add-to-cart-container-{{ $product->id }}" class="flex items-center gap-2">
                            <button onclick="initiateCart('{{ $product->id }}', {{ $product->price_unit }})" 
                                {{ $isOutOfStock ? 'disabled' : '' }}
                                class="flex-1 px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 
                                {{ $isOutOfStock ? 'bg-gray-400 text-gray-200 cursor-not-allowed' : 'bg-[#228B22] text-white hover:bg-[#1a6b1a]' }}">
                                <i class="fas fa-shopping-cart"></i> <span>ADD TO CART</span>
                            </button>
                            <button onclick="openDetails(this)" class="px-4 py-2.5 border border-gray-300 rounded-xl text-xs font-bold text-black hover:bg-gray-50 uppercase">
                                Details
                            </button>
                        </div>

                        <div id="quantity-controls-{{ $product->id }}" class="hidden space-y-4">
                            <div class="flex items-center justify-between px-2">
                                <button onclick="updateQty('{{ $product->id }}', -1)" class="qty-btn">
                                    <span class="leading-none">-</span>
                                </button>
                                
                                <div class="text-center flex flex-col items-center">
                                    <span id="qty-display-{{ $product->id }}" class="text-xl font-bold text-black leading-none mb-1">1</span>
                                    <span id="price-display-{{ $product->id }}" class="text-sm font-bold text-[#007bff]">₱{{ number_format($product->price_unit, 2) }}</span>
                                </div>
                                
                                <button onclick="updateQty('{{ $product->id }}', 1)" class="qty-btn">
                                    <span class="leading-none">+</span>
                                </button>
                            </div>

                            <div class="flex items-center gap-2">
                                <button onclick="removeFromCart('{{ $product->id }}')" class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-xl text-xs font-bold hover:bg-red-700 transition-all flex items-center justify-center gap-2 uppercase">
                                    Remove
                                </button>
                                <button onclick="openDetails(this)" class="px-4 py-2.5 border border-gray-300 rounded-xl text-xs font-bold text-black hover:bg-gray-50 uppercase">
                                    Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            @endforelse
            {{-- This is the code to paste --}}
            {{-- EXACT MATCH DESIGN: Circular Icon and Fonts from My Orders --}}
            <div id="no-products-message" class="hidden col-span-full py-32 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-box-open text-[#228B22] text-4xl"></i>
                    </div>
                    <p class="text-gray-400 font-bold text-sm tracking-wide uppercase">No products found</p>
                    <p class="text-gray-300 text-xs font-medium mt-1">Try selecting a different category or checking back later.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- PRODUCT DETAILS MODAL --}}
    <div id="details-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-2xl rounded-[32px] overflow-hidden shadow-2xl relative">
            <div class="flex flex-col md:flex-row h-full">
                <div class="w-full md:w-1/2 bg-[#f8f9fa] h-[250px] md:h-auto border-r border-gray-100 flex items-center justify-center overflow-hidden">
                    <img id="modal-image" src="" class="w-full h-full object-cover">
                </div>
                <div class="w-full md:w-1/2 flex flex-col">
                    <div class="bg-[#228B22] w-full py-4 shadow-sm text-center">
                        <h2 class="text-lg font-bold text-white uppercase tracking-wider">Product Details</h2>
                    </div>
                    <div class="p-8 pt-6 space-y-3">
                        <div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-gray-400">Name:</span><span id="modal-name" class="text-sm font-bold text-gray-800"></span></div>
                        <div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-gray-400">Category:</span><span id="modal-category" class="text-sm font-bold text-gray-800"></span></div>
                        <div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-gray-400">Net Volume:</span><span id="modal-litre" class="text-sm font-bold text-gray-800"></span></div>
                        <div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-gray-400">Price:</span><span id="modal-price" class="text-base font-bold text-[#228B22]"></span></div>
                        <div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-gray-400">Stock:</span><span id="modal-stock-text" class="text-sm font-bold text-gray-800"></span></div>
                        <div class="flex flex-col"><span class="text-[10px] uppercase font-bold text-gray-400">Expiration:</span><span id="modal-expiration" class="text-sm font-bold text-gray-800"></span></div>
                        <div class="mt-8">
                            <button onclick="closeDetails()" class="w-full bg-[#228B22] text-white py-3.5 rounded-xl font-bold text-xs uppercase tracking-widest">Back</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHECKOUT MODAL --}}
    <div id="checkout-modal" class="hidden fixed inset-0 z-[110] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div id="shopping-cart-modal-box" class="bg-white w-full max-w-2xl rounded-[35px] overflow-hidden shadow-2xl flex flex-col">
        
            
            {{-- Empty State Content --}}
            <div id="checkout-empty-state" class="p-12 text-center hidden">
                <div class="w-full max-w-xs mx-auto flex flex-col items-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                       <i class="fas fa-shopping-cart text-[#228B22] text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-2">Your cart is empty</h3>
                    <p class="text-gray-500 font-medium mb-8 text-sm">Review your items before checkout</p>
                    <button onclick="closeCheckout()" class="w-full py-4 bg-[#228B22] text-white rounded-2xl font-bold text-sm uppercase transition-all hover:bg-[#1a6b1a]">Continue Shopping</button>
                </div>
            </div>

            {{-- Active Cart Content --}}
            <div id="checkout-active-content" class="hidden">
                {{-- Header with Green Background --}}
                <div class="bg-[#228B22] p-8 flex items-center gap-4 shadow-md">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-shopping-cart text-[#228B22] text-xl"></i>
                    </div>
                    <div>
                        <h2 id="checkout-modal-title" class="text-xl font-bold text-white tracking-tight">Shopping Cart (0 items)</h2>
                        <p class="text-[14px] text-white/90 font-medium mt-0.5">Review your items before checkout</p>
                    </div>
                </div>

                {{-- Container --}}
                <div id="cart-items-container" class="p-8 space-y-6 overflow-y-auto max-h-[80vh]">
                    {{-- Order Summary --}}
                    <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-[15px] font-bold text-black">Order Summary:</h4>
                        <button id="edit-summary-btn" onclick="toggleSummaryEdit()" class="text-[#228B22] text-[15px] font-bold tracking-normal hover:underline transition-all">Edit</button>
                    </div>
                    <div id="summary-container-wrapper" class="summary-container transition-all duration-300"> <div id="cart-items-list" class="space-y-2">
                            {{-- Dynamic items here --}}
                        </div>
                    </div> </div>

                    {{-- Validation Error Message --}}
                    <div id="validation-error" class="hidden mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <p id="error-message-text" class="text-xs font-bold text-red-700 uppercase tracking-tight"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2"> <h4 class="text-[14px] font-bold text-black">Contact Name:</h4>
                            <input type="text" id="contact_name" value="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}" readonly class="cart-input-field input-small bg-gray-200 cursor-not-allowed text-gray-600">
                        </div>
                        <div class="space-y-2">
                            <h4 class="text-[14px] font-bold text-black">Phone Number:</h4>
                            <input type="text" id="phone_number" placeholder="Enter your Phone Number" class="cart-input-field input-small">
                        </div>
                    </div>

                    {{-- Delivery Address --}}
                    <div class="space-y-2">
                        <h4 class="text-[14px] font-bold text-black">Delivery Address:</h4>
                        <input type="text" id="delivery_address" placeholder="Enter Full Delivery Address" class="cart-input-field input-small">
                    </div>

                    {{-- Message Instructions --}}
                    <div class="space-y-3">
                        <h4 class="text-[14px] font-bold text-black">Message Instructions: (Optional)</h4>
                        <textarea id="order_notes" placeholder="Add a note to your order..." class="cart-input-field min-h-[80px]"></textarea>
                    </div>

                    {{-- Footer Buttons --}}
                    <div class="flex items-center gap-4 pt-2">
                        <button onclick="closeCheckout()" class="flex-1 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest btn-cancel-custom">
                            Cancel
                        </button>
                        <button id="place-order-btn" onclick="submitOrderRequest()" class="flex-[2] py-4 bg-[#228B22] text-white rounded-2xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-green-100 hover:bg-[#1a6b1a] transition-all">
                            Place Order (₱0.00)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- REMOVE ITEM CONFIRM MODAL --}}
    <div id="remove-confirm-modal" class="hidden fixed inset-0 z-[130] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-sm rounded-2xl overflow-hidden shadow-2xl border-t-8 border-red-500">
            <div class="p-6 flex items-center gap-3 border-b">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-trash text-red-500"></i>
                </div>
                <div>
                    <h3 class="font-bold text-red-700 uppercase text-sm tracking-tight">Remove Item</h3>
                    <p class="text-xs text-gray-500 font-medium">This will remove it from your cart</p>
                </div>
            </div>
            <div class="p-6 text-center text-sm font-medium text-gray-600">
                Are you sure you want to remove <span id="remove-item-name" class="font-bold text-black"></span>?
            </div>
            <input type="hidden" id="remove-item-id">
            <div class="p-4 bg-gray-50 flex gap-3">
                <button onclick="closeRemoveModal()" class="flex-1 py-3 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100 transition-all">Cancel</button>
                <button onclick="confirmRemoveAction()" class="flex-1 py-3 bg-red-500 text-white text-xs font-bold rounded-xl uppercase hover:bg-red-600 shadow-md transition-all">Remove</button>
            </div>
        </div>
    </div>

    {{-- REMOVE SUCCESS MODAL --}}
    <div id="remove-success-toast" class="hidden fixed inset-0 z-[140] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-sm rounded-[32px] p-8 text-center shadow-2xl modal-animate-in border border-gray-100">
            <div class="w-16 h-16 bg-[#228B22] rounded-full flex items-center justify-center mx-auto mb-4 check-bounce shadow-md shadow-green-100">
                <i class="fas fa-check text-white text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Success!</h3>
            <p class="text-sm font-medium text-gray-500 mt-1">Item removed from cart.</p>
        </div>
    </div>


    {{-- SUCCESS POPUP MODAL --}}
    <div id="success-modal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div id="success-content" class="bg-white w-full max-w-lg rounded-[40px] p-10 shadow-2xl text-center">
            
            <div id="success-icon" class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check-circle text-5xl text-[#228B22]"></i>
            </div>
            
            <h2 class="text-2xl font-black text-gray-900 mb-2">Order Request Submitted!</h2>
            <p class="text-gray-500 font-medium mb-6 text-sm">Your fresh milk request is now being processed. Thank you!</p>

            <div class="border border-gray-200 rounded-2xl p-5 mb-8 text-left bg-gray-50/50">
                <h4 class="text-[11px] font-black text-black uppercase tracking-widest mb-3">What happens next:</h4>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2 text-xs font-bold text-gray-700"><span class="text-[#228B22]">✓</span> The admin will review your order request</li>
                    <li class="flex items-start gap-2 text-xs font-bold text-gray-700"><span class="text-[#228B22]">✓</span> You'll receive approval confirmation</li>
                    <li class="flex items-start gap-2 text-xs font-bold text-gray-700"><span class="text-[#228B22]">✓</span> Track your order in "My Orders"</li>
                    <li class="flex items-start gap-2 text-xs font-bold text-gray-700"><span class="text-[#228B22]">✓</span> Estimated processing: 1-2 days</li>
                </ul>
            </div>

            <div class="space-y-3">
                <a href="{{ route('client.my-orders') }}" class="block w-full py-4 bg-[#228B22] text-white rounded-2xl font-bold text-sm hover:bg-[#1a6b1a] transition-all uppercase text-center">
                    View Orders
                </a>
                <button onclick="closeSuccessModal()" class="block w-full py-4 bg-white text-black border border-gray-300 rounded-2xl font-bold text-sm hover:bg-gray-50 transition-all uppercase text-center shadow-md shadow-gray-200">
                    Continue Shopping
                </button>
            </div>
        </div>
    </div>
    <script>
        let cartData = {};

        function initiateCart(productId, price) {
            cartData[productId] = { qty: 1, basePrice: price };
            document.getElementById(`add-to-cart-container-${productId}`).classList.add('hidden');
            document.getElementById(`quantity-controls-${productId}`).classList.remove('hidden');
            updateCheckoutCounter();
        }

        function updateQty(productId, delta) {
            let item = cartData[productId];
            if (!item) return;
            let newQty = item.qty + delta;
            if (newQty > 0) {
                item.qty = newQty;
                document.getElementById(`qty-display-${productId}`).innerText = item.qty;
                let total = item.qty * item.basePrice;
                document.getElementById(`price-display-${productId}`).innerText = '₱' + total.toLocaleString(undefined, {minimumFractionDigits: 2});
                updateCheckoutCounter();
            }
        }

        function removeFromCart(productId) {
            delete cartData[productId];
            document.getElementById(`quantity-controls-${productId}`).classList.add('hidden');
            document.getElementById(`add-to-cart-container-${productId}`).classList.remove('hidden');
            document.getElementById(`qty-display-${productId}`).innerText = "1";
            updateCheckoutCounter();
        }

        function updateCheckoutCounter() {
            const checkoutText = document.getElementById('checkout-text');
            const modalBox = document.getElementById('shopping-cart-modal-box');
            let totalItemsCount = 0;
            let grandTotal = 0;
            Object.keys(cartData).forEach(id => { 
                totalItemsCount += cartData[id].qty; 
                grandTotal += (cartData[id].qty * cartData[id].basePrice);
            });
            
            if (checkoutText) {
                checkoutText.innerText = totalItemsCount > 0 ? `Check Out (${totalItemsCount})` : "Check Out";
            }
            
            const modalTitle = document.getElementById('checkout-modal-title');
            if(modalTitle) modalTitle.innerText = `Shopping Cart (${totalItemsCount} items)`;

            const placeOrderBtn = document.getElementById('place-order-btn');
            if(placeOrderBtn) placeOrderBtn.innerText = `Place Order (₱${grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2})})`;
        }

        function openCheckout() {
            const emptyState = document.getElementById('checkout-empty-state');
            const activeContent = document.getElementById('checkout-active-content');
            const itemsList = document.getElementById('cart-items-list');
            itemsList.innerHTML = '';
            
            let totalItemsCount = 0;
            let grandTotal = 0;

            Object.keys(cartData).forEach(id => {
                totalItemsCount += cartData[id].qty;
                const card = document.querySelector(`.product-card[data-id="${id}"]`);
                const name = card.getAttribute('data-name');
                const litre = card.getAttribute('data-litre');
                const img = card.getAttribute('data-image');
                const totalPrice = cartData[id].qty * cartData[id].basePrice;
                grandTotal += totalPrice;

                itemsList.innerHTML += `
                <div class="flex items-center justify-between py-2 gap-3">
                    <input type="checkbox" id="chk-${id}" checked onchange="updateGrandTotal()"
                    class="w-4 h-4 rounded border-gray-300 text-[#228B22] accent-[#228B22] flex-shrink-0 cursor-pointer focus:ring-[#228B22]">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-8 h-8 rounded bg-gray-100 overflow-hidden border border-gray-100 flex-shrink-0">
                            <img src="${img}" class="w-full h-full object-cover">
                        </div>
                        <p class="text-sm font-medium text-gray-800">${name.charAt(0).toUpperCase() + name.slice(1)} (${litre})</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button onclick="cartQtyDown('${id}')" class="w-7 h-7 flex items-center justify-center border border-gray-300 rounded-lg bg-white hover:bg-gray-100 text-black font-bold text-sm transition-all">-</button>
                        <span id="summary-qty-${id}" class="text-sm font-bold text-black w-5 text-center">${cartData[id].qty}</span>
                        <button onclick="cartQtyUp('${id}')" class="w-7 h-7 flex items-center justify-center border border-gray-300 rounded-lg bg-white hover:bg-gray-100 text-black font-bold text-sm transition-all">+</button>
                    </div>
                    <p id="summary-price-${id}" class="text-sm font-bold text-black w-20 text-right">₱${totalPrice.toLocaleString(undefined, {minimumFractionDigits: 2})}</p>
                    
                    <div class="trash-container flex justify-end items-center flex-shrink-0">
                        <button class="w-7 h-7 flex items-center justify-center bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-all" onclick="confirmRemoveItem('${id}', '${name.charAt(0).toUpperCase() + name.slice(1)} (${litre})')">
                            <i class="fas fa-trash text-red-500 text-xs"></i>
                        </button>
                    </div>
                </div>
                `;
            });

            

            if (totalItemsCount > 0) {
                itemsList.innerHTML += `
                    <hr class="border-gray-400 my-4">
                    <div class="flex items-center justify-between pt-1">
                        <h3 class="text-base font-bold text-black">Total:</h3>
                        <p class="text-xl font-black text-[#228B22]">
                            ₱${grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2})}
                        </p>
                    </div>
                `;
            }

            

            if (totalItemsCount === 0) {
                emptyState.classList.remove('hidden');
                activeContent.classList.add('hidden');
            } else {
                emptyState.classList.add('hidden');
                activeContent.classList.remove('hidden');
            }

            document.getElementById('checkout-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cartQtyUp(id) {
            if (!cartData[id]) return;
            cartData[id].qty++;
            openCheckout();
            updateQty(id, 0);
            updateCheckoutCounter();
        }

        function cartQtyDown(id) {
            if (!cartData[id]) return;
            if (cartData[id].qty <= 1) {
                removeFromCart(id);
                openCheckout();
                return;
            }
            cartData[id].qty--;
            openCheckout();
            updateCheckoutCounter();
        }

        function updateGrandTotal() {
            let total = 0;
            Object.keys(cartData).forEach(id => {
                const chk = document.getElementById(`chk-${id}`);
                if (chk && chk.checked) {
                    total += cartData[id].qty * cartData[id].basePrice;
                }
            });
            const placeOrderBtn = document.getElementById('place-order-btn');
            if (placeOrderBtn) placeOrderBtn.innerText = `Place Order (₱${total.toLocaleString(undefined, {minimumFractionDigits: 2})})`;

            // Update total display
            const totalDisplay = document.getElementById('grand-total-display');
            if (totalDisplay) totalDisplay.innerText = `₱${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        }  

        let summaryEditMode = false;

        function toggleSummaryEdit() {
            summaryEditMode = !summaryEditMode;
            const wrapper = document.getElementById('summary-container-wrapper');
            const btn = document.getElementById('edit-summary-btn');
            if (summaryEditMode) {
                wrapper.classList.add('edit-mode');
                btn.innerText = 'Done';
            } else {
                wrapper.classList.remove('edit-mode');
                btn.innerText = 'Edit';
            }
        }

        function confirmRemoveItem(id, name) {
            document.getElementById('remove-item-name').innerText = name;
            document.getElementById('remove-item-id').value = id;
            document.getElementById('remove-confirm-modal').classList.remove('hidden');
        }

        function closeRemoveModal() {
            document.getElementById('remove-confirm-modal').classList.add('hidden');
        }

        function confirmRemoveAction() {
            const id = document.getElementById('remove-item-id').value;
            closeRemoveModal();
            removeFromCart(id);

            // Check if there are any remaining items in the cart data
            const remainingItemsCount = Object.keys(cartData).length;

            if (remainingItemsCount === 0) {
                // Turn off edit mode styling seamlessly and close checkout directly 
                summaryEditMode = false;
                const wrapper = document.getElementById('summary-container-wrapper');
                const btn = document.getElementById('edit-summary-btn');
                if (wrapper) wrapper.classList.remove('edit-mode');
                if (btn) btn.innerText = 'Edit';
                
                closeCheckout();
            } else {
                // Refresh the checkout contents to show remaining items
                openCheckout();
            }

            // Display the updated centered remove success modal
            const toast = document.getElementById('remove-success-toast');
            toast.classList.remove('hidden');
            
            // Keep the main screen overflow locked while success modal is up
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                toast.classList.add('hidden');
                // Re-enable page scrolling if checkout was closed completely
                if (remainingItemsCount === 0) {
                    document.body.style.overflow = 'auto';
                }
            }, 2000);
        }

        function closeCheckout() {
            // Hide the modal
            document.getElementById('checkout-modal').classList.add('hidden');
            // Re-enable scrolling on the main page
            document.body.style.overflow = 'auto';
        }

        function openDetails(btn) {
            const card = btn.closest('.product-card');
            document.getElementById('modal-name').innerText = card.getAttribute('data-name').toUpperCase();
            document.getElementById('modal-category').innerText = card.getAttribute('data-raw-category');
            document.getElementById('modal-price').innerText = '₱' + parseFloat(card.getAttribute('data-price')).toFixed(2);
            document.getElementById('modal-litre').innerText = card.getAttribute('data-litre');
            document.getElementById('modal-stock-text').innerText = card.getAttribute('data-stock');
            document.getElementById('modal-expiration').innerText = card.getAttribute('data-expiration');
            document.getElementById('modal-image').src = card.getAttribute('data-image');
            document.getElementById('details-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDetails() {
            document.getElementById('details-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function sortProducts() {
            const container = document.getElementById('product-container');
            const cards = Array.from(container.getElementsByClassName('product-card'));
            const sortVal = document.getElementById('sort-filter').value;
            if (sortVal === 'recent') return;
            cards.sort((a, b) => {
                const priceA = parseFloat(a.getAttribute('data-price'));
                const priceB = parseFloat(b.getAttribute('data-price'));
                return sortVal === 'price_low' ? priceA - priceB : priceB - priceA;
            });
            cards.forEach(card => container.appendChild(card));
        }

      function filterCategory(category) {
        const target = category.toLowerCase().trim();
        const noProductsMsg = document.getElementById('no-products-message');
        
        // 1. RESET ALL TABS
        document.querySelectorAll('button[id^="tab-"]').forEach(btn => {
            // Reset Button Styles
            btn.classList.remove('border-[#228B22]', 'text-[#228B22]', 'font-bold');
            btn.classList.add('border-transparent', 'text-gray-500', 'font-medium');
            
            // Reset Span (Badge) Styles to Gray
            const span = btn.querySelector('span');
            if (span) {
                span.classList.remove('bg-green-100');
                span.classList.add('bg-gray-100');

                
            }
        });

        // 2. ACTIVATE CLICKED TAB
        const activeId = target.includes('all') ? 'tab-all' : 
                        target.includes('bottle') ? 'tab-bottle' : 
                        target.includes('bar') ? 'tab-bar' : 'tab-ice';
        
        const activeTab = document.getElementById(activeId);
        if (activeTab) {
            // Add Thick Green Border and Green Text
            activeTab.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
            activeTab.classList.add('border-[#228B22]', 'text-[#228B22]', 'font-bold');
            
            // Change Span (Badge) to Green
            const span = activeTab.querySelector('span');
            if (span) {
                span.classList.remove('bg-gray-100');
                span.classList.add('bg-green-100');
            }
          
        }

        // 3. FILTER PRODUCT CARDS
        let visibleCount = 0;
        document.querySelectorAll('.product-card').forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            const isMatch = (target === 'all' || cardCategory.includes(target.replace(' milk','').replace(' cream','')));
            
            if (isMatch) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // 4. SHOW NO PRODUCTS MESSAGE IF EMPTY
        if (visibleCount === 0) {
            noProductsMsg.classList.remove('hidden');
        } else {
            noProductsMsg.classList.add('hidden');
        }
          updateCheckoutCounter();
    }

        document.getElementById('search-box').addEventListener('input', function(e) {
            const val = e.target.value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                card.style.display = card.getAttribute('data-name').includes(val) ? 'flex' : 'none';
            });
        });

        // PO ID Logic
        function generatePOID() {
            const year = new Date().getFullYear();
            let sequence = localStorage.getItem('order_sequence') || 0;
            sequence = parseInt(sequence) + 1;
            localStorage.setItem('order_sequence', sequence);
            const paddedNum = String(sequence).padStart(3, '0');
            return `PO-${year}-${paddedNum}`;
        }

        async function submitOrderRequest() {

    const errorBox = document.getElementById('validation-error');
    const errorText = document.getElementById('error-message-text');

    const name = document.getElementById('contact_name').value.trim();
    const phone = document.getElementById('phone_number').value.trim();
    const address = document.getElementById('delivery_address').value.trim();
    const notes = document.getElementById('order_notes').value.trim();

    errorBox.classList.add('hidden');

    if (!name || !phone || !address) {
        showError("Please fill in all necessary fields.");
        return;
    }

    if (!phone.startsWith('09') || phone.length !== 11) {
        showError("Phone number must start with 09 and be 11 digits long.");
        return;
    }

    if (Object.keys(cartData).length === 0) {
        showError("Your cart is empty.");
        return;
    }

    let grandTotal = 0;

    const cartItems = [];

        Object.keys(cartData).forEach(id => {
        const chk = document.getElementById(`chk-${id}`);
        if (!chk || !chk.checked) return;
        const card = document.querySelector(`.product-card[data-id="${id}"]`);
        const itemTotal = cartData[id].qty * cartData[id].basePrice;
        grandTotal += itemTotal;
        cartItems.push({
            product_id: id,
            name: card.getAttribute('data-name'),
            category: card.getAttribute('data-raw-category'),
            litre: card.getAttribute('data-litre'),
            expiration: card.getAttribute('data-expiration'),
            image: card.getAttribute('data-image'),
            qty: cartData[id].qty,
            price: cartData[id].basePrice,
            total: itemTotal
        });
    });

    try {

        const response = await fetch("{{ route('client.place-order') }}", {

            method: "POST",

            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },

            body: JSON.stringify({
                contact_name: name,
                phone_number: phone,
                address: address,
                message_instructions: notes,
                total_price: grandTotal,
                cart_items: cartItems
            })
        });

        const data = await response.json();

        if (data.success) {

            closeCheckout();

            const modal = document.getElementById('success-modal');
            const content = document.getElementById('success-content');
            const icon = document.getElementById('success-icon');

            content.classList.add('modal-animate-in');
            icon.classList.add('check-bounce');

            modal.classList.remove('hidden');

        } else {

            showError(data.message || 'Failed to place order.');

        }

    } catch (error) {

        console.error(error);

        showError('Something went wrong while placing order.');

    }
}

    // Helper function to show the error
    function showError(msg) {
        const errorBox = document.getElementById('validation-error');
        const errorText = document.getElementById('error-message-text');
        errorText.innerText = msg;
        errorBox.classList.remove('hidden');
    }

       function closeSuccessModal() {
        // Hide success modal
        document.getElementById('success-modal').classList.add('hidden');
        // Clear the cart
        cartData = {};
        // Update the UI
        updateCheckoutCounter();
        // Re-enable scrolling
        document.body.style.overflow = 'auto';
        // Refresh to show "Add to Cart" buttons again
        window.location.reload();
    }

    
       window.onclick = function(event) {
            const detailsModal = document.getElementById('details-modal');
            if (event.target == detailsModal) {
                closeDetails();
            }
        }

        // Handle Enter Key Navigation and Phone Number Logic

        document.addEventListener('DOMContentLoaded', function() {
            const fields = [
                document.getElementById('contact_name'),
                document.getElementById('phone_number'),
                document.getElementById('delivery_address'),
                document.getElementById('place-order-btn')
            ];

            // GLOBAL ENTER LISTENER: Focuses phone number after clicking checkout, +, or - buttons
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    const checkoutModal = document.getElementById('checkout-modal');
                    const phoneNumberField = document.getElementById('phone_number');
                    
                    // Check if the shopping cart modal is open
                    if (checkoutModal && !checkoutModal.classList.contains('hidden')) {
                        // If the user is NOT typing inside the fields array already, automatically jump focus to phone number
                        if (!fields.includes(document.activeElement)) {
                            e.preventDefault();
                            if (phoneNumberField) phoneNumberField.focus();
                        }
                    }
                }
            });

            fields.forEach((field, index) => {
                if (!field) return;

                // Move to next field sequentially on Enter when inside the form inputs
                field.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        e.stopPropagation(); // Prevents triggering the global listener above
                        if (index < fields.length - 1) {
                            fields[index + 1].focus();
                        } else {
                            submitOrderRequest();
                        }
                    }
                });

                // Phone Number Restrictions (Numbers only)
                if (field.id === 'phone_number') {
                    field.addEventListener('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, ''); // Remove non-numbers
                        if (this.value.length > 11) this.value = this.value.slice(0, 11); // Limit to 11
                    });
                }
            });
        });
    </script>
</x-app-layout>