<?php
 
use App\Models\User; 
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\LogisticsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Client\ShopController;
use App\Http\Controllers\Client\MyOrdersController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Client\PaymentController;

// 1. Welcome Page
Route::get('/', function () {
    return redirect()->route('login');
});
 
// 2. Waiting Room
Route::get('/waiting-approval', function () {
    return view('auth.waiting-approval');
})->middleware(['auth'])->name('waiting.approval');
 
// 3. Protected Routes
Route::middleware(['auth', 'verified', 'check.revoke'])->group(function () {
    
    // CLIENT DASHBOARD
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return app(ClientDashboardController::class)->index();
    })->name('dashboard');
 
    // Order Milk
    Route::get('/order-milk', [ShopController::class, 'index'])->name('client.shop');
    Route::post('/order-milk/place-order', [ShopController::class, 'placeOrder'])->name('client.place-order');
 
    // My Orders
    Route::get('/my-orders', [MyOrdersController::class, 'index'])->name('client.my-orders');
    
    // Client Payments
    Route::get('/client/payments', [App\Http\Controllers\Client\PaymentController::class, 'index'])->name('client.payments');
    Route::post('/client/payments/{id}/pay', [App\Http\Controllers\Client\PaymentController::class, 'pay'])->name('client.payments.pay');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
 
    // --- ADMIN ONLY ROUTES ---
    Route::middleware(['auth', 'admin'])->group(function () {
        
        // A. Admin Dashboard
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
 
        // B. User Management
        Route::prefix('admin/users')->name('admin.users.')->group(function() {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/approve/{id}', [UserController::class, 'approve'])->name('approve');
            Route::post('/decline', [UserController::class, 'decline'])->name('decline');
            Route::post('/revoke', [UserController::class, 'revoke'])->name('revoke');
            Route::post('/archive', [UserController::class, 'archive'])->name('archive');
            Route::post('/restore', [UserController::class, 'restore'])->name('restore');
        });
 
        // C. Inventory Management
        Route::get('/admin/inventory', [ProductController::class, 'index'])->name('admin.inventory.index');
        Route::post('/admin/inventory', [ProductController::class, 'store'])->name('admin.inventory.store');
        Route::get('/admin/inventory/{id}/edit', [ProductController::class, 'edit'])->name('admin.inventory.edit');
        Route::put('/admin/inventory/{id}', [ProductController::class, 'update'])->name('admin.inventory.update');
        Route::delete('/admin/inventory/{id}', [ProductController::class, 'destroy'])->name('admin.inventory.destroy');
 
        // D. Archive Group
        Route::prefix('admin/inventory')->name('admin.inventory.')->group(function() {
            Route::get('/archived', [ProductController::class, 'archived'])->name('archived');
            Route::post('/{id}/restore', [ProductController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('forceDelete');
        });
        
        // E. Orders
        Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::post('/admin/orders/{id}/approve', [OrderController::class, 'approve'])->name('admin.orders.approve');
        Route::post('/admin/orders/{id}/decline', [OrderController::class, 'decline'])->name('admin.orders.decline');
        Route::post('/admin/orders/{id}/assign', [OrderController::class, 'assign'])->name('admin.orders.assign');
        Route::post('/admin/orders/{id}/ship', [OrderController::class, 'ship'])->name('admin.orders.ship');
        Route::post('/admin/orders/{id}/deliver', [OrderController::class, 'deliver'])->name('admin.orders.deliver');
        Route::post('/admin/orders/{id}/restore', [OrderController::class, 'restore'])->name('admin.orders.restore');

        // F. Logistics
        Route::get('/admin/logistics', [LogisticsController::class, 'index'])->name('admin.logistics.index');
        Route::post('/admin/logistics/trucks', [LogisticsController::class, 'storeTruck'])->name('admin.logistics.trucks.store');
        Route::put('/admin/logistics/trucks/{truck}',[LogisticsController::class, 'updateTruck']);
        Route::delete('/admin/logistics/trucks/{truck}',[LogisticsController::class, 'deleteTruck']);

        // H. Admin Payments
        Route::get('/admin/payments', [App\Http\Controllers\Admin\AdminPaymentController::class, 'index'])->name('admin.payments.index');
        Route::post('/admin/payments/{id}/confirm', [App\Http\Controllers\Admin\AdminPaymentController::class, 'confirm'])->name('admin.payments.confirm');
        
    }); // End Admin Middleware

}); // End Protected Routes
 
require __DIR__.'/auth.php';