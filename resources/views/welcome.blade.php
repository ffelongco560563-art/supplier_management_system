<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>DairyBest | Premium Milk Products</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-white text-gray-900 scroll-smooth overflow-x-hidden">

    {{-- ========================= NAVBAR ========================= --}}
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-xl border-b border-gray-100 shadow-sm">

        <div class="max-w-7xl mx-auto px-6 lg:px-10">

            <div class="flex items-center justify-between h-20">

                {{-- Logo --}}
                <div class="flex items-center gap-3">

                    <img src="{{ asset('images/logo.png') }}"
                         alt="DairyBest Logo"
                         class="w-12 h-12 object-contain">

                    <div>
                        <h1 class="text-2xl font-extrabold text-[#228B22] leading-none">
                            DairyBest
                        </h1>

                        <p class="text-xs text-gray-500">
                            Davao, Philippines
                        </p>
                    </div>
                </div>

                {{-- Desktop Menu --}}
                <nav class="hidden md:flex items-center gap-8">

                    <a href="#home"
                       class="font-medium text-gray-700 hover:text-[#228B22] transition">
                        Home
                    </a>

                    <a href="#about"
                       class="font-medium text-gray-700 hover:text-[#228B22] transition">
                        About
                    </a>

                    <a href="#products"
                       class="font-medium text-gray-700 hover:text-[#228B22] transition">
                        Products
                    </a>

                    <a href="#features"
                       class="font-medium text-gray-700 hover:text-[#228B22] transition">
                        Features
                    </a>

                    <a href="#contact"
                       class="font-medium text-gray-700 hover:text-[#228B22] transition">
                        Contact
                    </a>
                </nav>

                {{-- Buttons --}}
                <div class="flex items-center gap-3">

                    <a href="{{ route('login') }}"
                       class="hidden sm:flex px-5 py-2.5 rounded-xl border border-[#228B22] text-[#228B22] font-bold hover:bg-[#228B22] hover:text-white transition">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                       class="px-5 py-2.5 rounded-xl bg-[#228B22] text-white font-bold shadow-lg hover:opacity-90 transition">
                        Get Started
                    </a>
                </div>

            </div>

        </div>

    </header>


    {{-- ========================= HERO SECTION ========================= --}}
    <section id="home"
             class="relative min-h-screen bg-gradient-to-br from-[#efffef] via-white to-[#e8f8e8] overflow-hidden pt-32">

        {{-- Background Blur --}}
        <div class="absolute top-0 right-0 w-[500px] h-[500px] rounded-full bg-[#228B22]/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] rounded-full bg-[#228B22]/10 blur-3xl"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 py-20">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                {{-- LEFT --}}
                <div>

                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#228B22]/10 border border-[#228B22]/20 text-[#228B22] font-bold text-sm mb-6">

                        <i class="fas fa-leaf"></i>

                        Premium Dairy Supplier System

                    </div>

                    <h1 class="text-5xl md:text-6xl font-extrabold leading-tight text-gray-900 mb-6">

                        Fresh Dairy Products

                        <span class="block text-[#228B22]">
                            Delivered Smarter
                        </span>

                    </h1>

                    <p class="text-lg text-gray-600 leading-relaxed mb-8 max-w-xl">

                        DairyBest helps manage dairy inventory,
                        customer orders, payments, and logistics
                        in one modern supplier management system.

                    </p>

                    <div class="flex flex-wrap gap-4 mb-10">

                        <a href="{{ route('register') }}"
                           class="px-8 py-4 rounded-2xl bg-[#228B22] text-white font-bold shadow-xl hover:scale-105 transition duration-300">

                            Start Ordering

                        </a>

                        <a href="#about"
                           class="px-8 py-4 rounded-2xl bg-white border border-gray-200 text-gray-700 font-bold hover:border-[#228B22] hover:text-[#228B22] transition duration-300">

                            Learn More

                        </a>

                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-3 gap-6 max-w-lg">

                        <div>
                            <h3 class="text-3xl font-extrabold text-[#228B22]">
                                100%
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Fresh Dairy
                            </p>
                        </div>

                        <div>
                            <h3 class="text-3xl font-extrabold text-[#228B22]">
                                24/7
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Order Tracking
                            </p>
                        </div>

                        <div>
                            <h3 class="text-3xl font-extrabold text-[#228B22]">
                                500+
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Happy Customers
                            </p>
                        </div>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="relative">

                    <div class="absolute -top-5 -left-5 w-full h-full rounded-[40px] bg-[#228B22]/10"></div>

                    <div class="relative bg-white rounded-[40px] p-8 border border-gray-100 shadow-2xl overflow-hidden">

                        <img src="{{ asset('images/logo.png') }}"
                             alt="DairyBest"
                             class="w-full h-[420px] object-contain">

                        {{-- Floating Card --}}
                        <div class="absolute top-6 right-6 bg-white border border-gray-100 rounded-2xl px-5 py-4 shadow-lg">

                            <div class="flex items-center gap-3">

                                <div class="w-12 h-12 rounded-full bg-[#228B22]/10 flex items-center justify-center text-[#228B22] text-xl">
                                    <i class="fas fa-truck"></i>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500">
                                        Fast Delivery
                                    </p>

                                    <h4 class="font-bold text-gray-900">
                                        Same Day Service
                                    </h4>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ========================= ABOUT ========================= --}}
    <section id="about" class="py-24 bg-white">

        <div class="max-w-7xl mx-auto px-6 lg:px-10">

            <div class="text-center max-w-3xl mx-auto mb-16">

                <h2 class="text-4xl font-extrabold text-gray-900 mb-5">
                    About DairyBest
                </h2>

                <p class="text-lg text-gray-600 leading-relaxed">

                    DairyBest is committed to providing high-quality
                    dairy products while simplifying supplier management,
                    customer ordering, inventory monitoring,
                    and delivery logistics.

                </p>

            </div>

            <div class="grid md:grid-cols-3 gap-8">

                {{-- Card 1 --}}
                <div class="bg-[#f8fff8] border border-[#228B22]/10 rounded-3xl p-8 hover:shadow-xl transition duration-300">

                    <div class="w-16 h-16 rounded-2xl bg-[#228B22] text-white flex items-center justify-center text-2xl mb-6 shadow-lg">

                        <i class="fas fa-cow"></i>

                    </div>

                    <h3 class="text-2xl font-bold mb-4 text-gray-900">
                        Premium Quality
                    </h3>

                    <p class="text-gray-600 leading-relaxed">

                        We ensure all milk products meet the highest standards
                        for freshness, safety, and nutrition.

                    </p>

                </div>

                {{-- Card 2 --}}
                <div class="bg-[#f8fff8] border border-[#228B22]/10 rounded-3xl p-8 hover:shadow-xl transition duration-300">

                    <div class="w-16 h-16 rounded-2xl bg-[#228B22] text-white flex items-center justify-center text-2xl mb-6 shadow-lg">

                        <i class="fas fa-boxes-stacked"></i>

                    </div>

                    <h3 class="text-2xl font-bold mb-4 text-gray-900">
                        Smart Inventory
                    </h3>

                    <p class="text-gray-600 leading-relaxed">

                        Manage stock levels, product movement,
                        and order processing with ease.

                    </p>

                </div>

                {{-- Card 3 --}}
                <div class="bg-[#f8fff8] border border-[#228B22]/10 rounded-3xl p-8 hover:shadow-xl transition duration-300">

                    <div class="w-16 h-16 rounded-2xl bg-[#228B22] text-white flex items-center justify-center text-2xl mb-6 shadow-lg">

                        <i class="fas fa-users"></i>

                    </div>

                    <h3 class="text-2xl font-bold mb-4 text-gray-900">
                        Customer Focused
                    </h3>

                    <p class="text-gray-600 leading-relaxed">

                        Built for both administrators and customers
                        with a smooth and easy-to-use experience.

                    </p>

                </div>

            </div>

        </div>

    </section>


    {{-- ========================= PRODUCTS ========================= --}}
    <section id="products" class="py-24 bg-gray-50">

        <div class="max-w-7xl mx-auto px-6 lg:px-10">

            <div class="text-center mb-16">

                <h2 class="text-4xl font-extrabold text-gray-900 mb-4">
                    Our Products
                </h2>

                <p class="text-lg text-gray-600">
                    Fresh dairy products available for ordering
                </p>

            </div>

            <div class="grid md:grid-cols-3 gap-8">

                {{-- Product --}}
                <div class="bg-white rounded-3xl shadow-lg overflow-hidden border border-gray-100 hover:-translate-y-2 transition duration-300">

                    <div class="h-56 bg-[#228B22]/10 flex items-center justify-center">

                        <i class="fas fa-bottle-water text-7xl text-[#228B22]"></i>

                    </div>

                    <div class="p-8">

                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            Fresh Milk
                        </h3>

                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Pure and fresh locally sourced milk.
                        </p>

                        <button class="w-full py-3 rounded-xl bg-[#228B22] text-white font-bold hover:opacity-90 transition">
                            Order Now
                        </button>

                    </div>

                </div>

                {{-- Product --}}
                <div class="bg-white rounded-3xl shadow-lg overflow-hidden border border-gray-100 hover:-translate-y-2 transition duration-300">

                    <div class="h-56 bg-[#228B22]/10 flex items-center justify-center">

                        <i class="fas fa-mug-hot text-7xl text-[#228B22]"></i>

                    </div>

                    <div class="p-8">

                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            Chocolate Milk
                        </h3>

                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Delicious creamy chocolate flavored milk.
                        </p>

                        <button class="w-full py-3 rounded-xl bg-[#228B22] text-white font-bold hover:opacity-90 transition">
                            Order Now
                        </button>

                    </div>

                </div>

                {{-- Product --}}
                <div class="bg-white rounded-3xl shadow-lg overflow-hidden border border-gray-100 hover:-translate-y-2 transition duration-300">

                    <div class="h-56 bg-[#228B22]/10 flex items-center justify-center">

                        <i class="fas fa-cheese text-7xl text-[#228B22]"></i>

                    </div>

                    <div class="p-8">

                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            Dairy Products
                        </h3>

                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Premium dairy products crafted with care.
                        </p>

                        <button class="w-full py-3 rounded-xl bg-[#228B22] text-white font-bold hover:opacity-90 transition">
                            Order Now
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ========================= FEATURES ========================= --}}
    <section id="features" class="py-24 bg-white">

        <div class="max-w-7xl mx-auto px-6 lg:px-10">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                {{-- LEFT --}}
                <div>

                    <h2 class="text-4xl font-extrabold text-gray-900 mb-6">

                        Powerful Supplier Management Features

                    </h2>

                    <p class="text-lg text-gray-600 leading-relaxed mb-10">

                        Everything you need to manage your dairy operations efficiently.

                    </p>

                    <div class="space-y-6">

                        <div class="flex gap-4">

                            <div class="w-14 h-14 rounded-2xl bg-[#228B22]/10 text-[#228B22] flex items-center justify-center text-xl flex-shrink-0">

                                <i class="fas fa-chart-line"></i>

                            </div>

                            <div>

                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    Real-Time Dashboard
                                </h3>

                                <p class="text-gray-600">
                                    Monitor inventory, orders, payments, and logistics instantly.
                                </p>

                            </div>

                        </div>

                        <div class="flex gap-4">

                            <div class="w-14 h-14 rounded-2xl bg-[#228B22]/10 text-[#228B22] flex items-center justify-center text-xl flex-shrink-0">

                                <i class="fas fa-credit-card"></i>

                            </div>

                            <div>

                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    Payment Monitoring
                                </h3>

                                <p class="text-gray-600">
                                    Track customer payments and transaction records.
                                </p>

                            </div>

                        </div>

                        <div class="flex gap-4">

                            <div class="w-14 h-14 rounded-2xl bg-[#228B22]/10 text-[#228B22] flex items-center justify-center text-xl flex-shrink-0">

                                <i class="fas fa-truck-fast"></i>

                            </div>

                            <div>

                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    Delivery Logistics
                                </h3>

                                <p class="text-gray-600">
                                    Easily manage deliveries and order transportation.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="bg-gradient-to-br from-[#228B22] to-[#1a5d1a] rounded-[40px] p-10 shadow-2xl text-white relative overflow-hidden">

                    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/10"></div>

                    <div class="absolute bottom-0 left-0 w-52 h-52 rounded-full bg-white/5"></div>

                    <div class="relative z-10">

                        <h3 class="text-3xl font-extrabold mb-8">
                            Why Choose DairyBest?
                        </h3>

                        <div class="space-y-5">

                            <div class="flex items-center gap-4">
                                <i class="fas fa-check-circle text-2xl"></i>
                                <span class="text-lg font-medium">Modern Dashboard</span>
                            </div>

                            <div class="flex items-center gap-4">
                                <i class="fas fa-check-circle text-2xl"></i>
                                <span class="text-lg font-medium">Fast Order Processing</span>
                            </div>

                            <div class="flex items-center gap-4">
                                <i class="fas fa-check-circle text-2xl"></i>
                                <span class="text-lg font-medium">Real-Time Tracking</span>
                            </div>

                            <div class="flex items-center gap-4">
                                <i class="fas fa-check-circle text-2xl"></i>
                                <span class="text-lg font-medium">Customer-Friendly System</span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ========================= CTA ========================= --}}
    <section class="py-24 bg-[#228B22] relative overflow-hidden">

        <div class="absolute top-0 left-0 w-full h-full opacity-10">

            <div class="absolute top-10 left-10 w-32 h-32 border border-white rounded-full"></div>

            <div class="absolute bottom-10 right-10 w-52 h-52 border border-white rounded-full"></div>

        </div>

        <div class="relative z-10 max-w-4xl mx-auto text-center px-6">

            <h2 class="text-5xl font-extrabold text-white mb-6">

                Ready to Get Started?

            </h2>

            <p class="text-xl text-white/90 leading-relaxed mb-10">

                Join DairyBest today and experience smarter dairy management.

            </p>

            <div class="flex flex-wrap justify-center gap-4">

                <a href="{{ route('register') }}"
                   class="px-8 py-4 rounded-2xl bg-white text-[#228B22] font-extrabold shadow-xl hover:scale-105 transition duration-300">

                    Create Account

                </a>

                <a href="{{ route('login') }}"
                   class="px-8 py-4 rounded-2xl border border-white text-white font-extrabold hover:bg-white hover:text-[#228B22] transition duration-300">

                    Login Now

                </a>

            </div>

        </div>

    </section>


    {{-- ========================= FOOTER ========================= --}}
    <footer id="contact" class="bg-[#111827] text-white py-16">

        <div class="max-w-7xl mx-auto px-6 lg:px-10">

            <div class="grid md:grid-cols-4 gap-12 mb-12">

                {{-- Brand --}}
                <div>

                    <div class="flex items-center gap-3 mb-5">

                        <img src="{{ asset('images/logo.png') }}"
                             class="w-12 h-12 object-contain">

                        <div>

                            <h3 class="text-2xl font-extrabold">
                                DairyBest
                            </h3>

                            <p class="text-sm text-gray-400">
                                Davao, Philippines
                            </p>

                        </div>

                    </div>

                    <p class="text-gray-400 leading-relaxed">

                        Delivering premium dairy products with modern supplier management.

                    </p>

                </div>

                {{-- Quick Links --}}
                <div>

                    <h4 class="text-lg font-bold mb-5">
                        Quick Links
                    </h4>

                    <div class="space-y-3 text-gray-400">

                        <a href="#home" class="block hover:text-white transition">
                            Home
                        </a>

                        <a href="#about" class="block hover:text-white transition">
                            About
                        </a>

                        <a href="#products" class="block hover:text-white transition">
                            Products
                        </a>

                        <a href="#features" class="block hover:text-white transition">
                            Features
                        </a>

                    </div>

                </div>

                {{-- Services --}}
                <div>

                    <h4 class="text-lg font-bold mb-5">
                        Services
                    </h4>

                    <div class="space-y-3 text-gray-400">

                        <p>Milk Delivery</p>
                        <p>Inventory Management</p>
                        <p>Logistics Tracking</p>
                        <p>Customer Ordering</p>

                    </div>

                </div>

                {{-- Contact --}}
                <div>

                    <h4 class="text-lg font-bold mb-5">
                        Contact
                    </h4>

                    <div class="space-y-4 text-gray-400">

                        <div class="flex items-center gap-3">
                            <i class="fas fa-location-dot"></i>
                            <span>Davao City, Philippines</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <i class="fas fa-phone"></i>
                            <span>+63 912 345 6789</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <i class="fas fa-envelope"></i>
                            <span>support@dairybest.com</span>
                        </div>

                    </div>

                </div>

            </div>

            <div class="border-t border-white/10 pt-8 text-center text-gray-500 text-sm">

                © 2026 DairyBest. All rights reserved.

            </div>

        </div>

    </footer>

</body>
</html>