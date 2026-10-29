<?php

use App\Http\Controllers\Admin\AdminAuditLogController;
use App\Http\Controllers\Admin\AdminBusinessController;
use App\Http\Controllers\Admin\AdminCompanyController;
use App\Http\Controllers\Admin\AdminCurrencyController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminFeatureController;
use App\Http\Controllers\Admin\AdminInventoryController;
use App\Http\Controllers\Admin\AdminInvoiceController;
use App\Http\Controllers\Admin\AdminLeadController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\Admin\AdminSubscriptionPlanController;
use App\Http\Controllers\Admin\AdminTaskController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PosCartController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Sales\QuoteController;
use App\Http\Controllers\Sales\SalesOrderController;
use App\Http\Controllers\Sales\SalesReceiptController;
use App\Http\Controllers\Sales\PaymentReceivedController;
use App\Http\Controllers\Sales\CreditNoteController;
use App\Http\Controllers\Purchases\BillController;
use App\Http\Controllers\Purchases\PurchaseOrderController;
use App\Http\Controllers\Purchases\PaymentMadeController;
use App\Http\Controllers\Purchases\CreditorController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Admin\AdminFeedbackController;
use App\Http\Controllers\Admin\AdminPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - TBM Enterprise & Uganda Multi-Business SaaS Suite
|--------------------------------------------------------------------------
*/

// Multi-Currency Session Switcher
Route::post('/currency/switch', [CurrencyController::class, 'switchCurrency'])->name('currency.switch');

// Multi-Business Switcher
Route::post('/businesses/switch', [BusinessController::class, 'switchBusiness'])->name('businesses.switch');

// Public Dynamic Pricing Page (/pricing)
Route::get('/pricing', [PricingController::class, 'index'])->name('subscription.pricing');

// Public marketing pages (before auth)
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/features', [PublicController::class, 'features'])->name('features');
Route::get('/how-it-works', [PublicController::class, 'howItWorks'])->name('how-it-works');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/faq', [PublicController::class, 'faq'])->name('faq');

// Authentication Routes (guest only – logged-in users redirect to dashboard)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Public Contact Center
Route::get('/contact', [DashboardController::class, 'contact'])->name('contact');
Route::post('/contact', [DashboardController::class, 'submitContact'])->name('contact.submit');

// =========================================================================
// Client Business Management Modules (Protected by Auth + Active Subscription)
// =========================================================================
Route::middleware(['auth', 'subscription.active'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/crm', [DashboardController::class, 'crm'])->name('crm');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications');

    // 1. Subscription & Billing Portal
    Route::get('/subscription', [PricingController::class, 'portal'])->name('subscription.portal');
    Route::get('/subscription/checkout', [PricingController::class, 'checkout'])->name('subscription.checkout');
    Route::post('/subscription/pay', [PricingController::class, 'processPayment'])->name('subscription.pay');

    // User Profile & Account Settings (self-service)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // 2. Multi-Business Management & Branches
    Route::resource('businesses', BusinessController::class)->only(['index', 'create', 'store']);
    Route::post('businesses/{business}/locations', [BusinessController::class, 'storeLocation'])->name('businesses.locations.store');

    // 3. Products & Catalog
    Route::resource('products', ProductController::class);

    // 4. Inventory & Warehouses
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::get('/inventory/{product}/history', [InventoryController::class, 'history'])->name('inventory.history');

    // 5. Invoicing & Billing
    Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'store', 'show']);

    // 6. POS & Shopping Cart
    Route::get('/pos', [PosCartController::class, 'pos'])->name('pos');
    Route::get('/cart', [PosCartController::class, 'cart'])->name('cart.index');
    Route::post('/cart/add', [PosCartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove/{key}', [PosCartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/clear', [PosCartController::class, 'clearCart'])->name('cart.clear');
    Route::post('/cart/checkout', [PosCartController::class, 'checkout'])->name('cart.checkout');

    // 7. Expense Management
    Route::resource('expenses', ExpenseController::class)->only(['index', 'create', 'store', 'destroy']);

    // 8. Payroll Management
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/staff/create', [PayrollController::class, 'createStaff'])->name('payroll.staff.create');
    Route::post('/payroll/staff', [PayrollController::class, 'storeStaff'])->name('payroll.staff.store');
    Route::post('/payroll/process', [PayrollController::class, 'process'])->name('payroll.process');
    Route::get('/payroll/{payroll}/payslip', [PayrollController::class, 'payslip'])->name('payroll.payslip');

    // 9. Tax Compliance
    Route::get('/taxes', [TaxController::class, 'index'])->name('taxes.index');
    Route::post('/taxes', [TaxController::class, 'store'])->name('taxes.store');

    // 10. Customers & Vendors (Parties)
    Route::resource('parties', PartyController::class)->only(['index', 'store', 'destroy']);

    // 11. Sales Orders & Purchase Orders (Legacy)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // 12. Reports & Accounting
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit-loss');
    Route::get('/reports/balance-sheet', [ReportController::class, 'balanceSheet'])->name('reports.balance-sheet');
    Route::get('/reports/trial-balance', [ReportController::class, 'trialBalance'])->name('reports.trial-balance');

    // 13. Tasks
    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');

    // =========================================================
    // 14. SALES MODULE
    // =========================================================
    Route::prefix('sales')->name('sales.')->group(function () {
        // Quotes & Estimates
        Route::get('quotes', [QuoteController::class, 'index'])->name('quotes.index');
        Route::get('quotes/create', [QuoteController::class, 'create'])->name('quotes.create');
        Route::post('quotes', [QuoteController::class, 'store'])->name('quotes.store');
        Route::get('quotes/{quote}', [QuoteController::class, 'show'])->name('quotes.show');
        Route::post('quotes/{quote}/convert-order', [QuoteController::class, 'convertToOrder'])->name('quotes.convert-order');
        Route::post('quotes/{quote}/convert-invoice', [QuoteController::class, 'convertToInvoice'])->name('quotes.convert-invoice');

        // Sales Orders
        Route::get('orders', [SalesOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [SalesOrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [SalesOrderController::class, 'store'])->name('orders.store');
        Route::post('orders/{order}/convert-invoice', [SalesOrderController::class, 'convertToInvoice'])->name('orders.convert-invoice');

        // Payments Received
        Route::get('payments', [PaymentReceivedController::class, 'index'])->name('payments.index');
        Route::get('payments/create', [PaymentReceivedController::class, 'create'])->name('payments.create');
        Route::post('payments', [PaymentReceivedController::class, 'store'])->name('payments.store');

        // Sales Receipts
        Route::get('receipts', [SalesReceiptController::class, 'index'])->name('receipts.index');
        Route::get('receipts/{receipt}', [SalesReceiptController::class, 'show'])->name('receipts.show');

        // Credit Notes & Returns
        Route::get('credit-notes', [CreditNoteController::class, 'index'])->name('credit-notes.index');
        Route::get('credit-notes/create', [CreditNoteController::class, 'create'])->name('credit-notes.create');
        Route::post('credit-notes', [CreditNoteController::class, 'store'])->name('credit-notes.store');
    });

    // =========================================================
    // 15. PURCHASES MODULE
    // =========================================================
    Route::prefix('purchases')->name('purchases.')->group(function () {
        // Purchase Orders
        Route::get('orders', [PurchaseOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [PurchaseOrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [PurchaseOrderController::class, 'store'])->name('orders.store');
        Route::post('orders/{order}/convert-bill', [PurchaseOrderController::class, 'convertToBill'])->name('orders.convert-bill');

        // Vendor Bills
        Route::get('bills', [BillController::class, 'index'])->name('bills.index');
        Route::get('bills/create', [BillController::class, 'create'])->name('bills.create');
        Route::post('bills', [BillController::class, 'store'])->name('bills.store');
        Route::get('bills/{bill}', [BillController::class, 'show'])->name('bills.show');

        // Payments Made
        Route::get('payments', [PaymentMadeController::class, 'index'])->name('payments.index');
        Route::get('payments/create', [PaymentMadeController::class, 'create'])->name('payments.create');
        Route::post('payments', [PaymentMadeController::class, 'store'])->name('payments.store');

        // Creditors
        Route::get('creditors', [CreditorController::class, 'index'])->name('creditors.index');
    });

    // =========================================================
    // 16. FEEDBACK SYSTEM
    // =========================================================
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/my', [FeedbackController::class, 'index'])->name('feedback.index');
});

// =========================================================================
// SUPER ADMINISTRATOR CONSOLE (Protected by super.admin Middleware)
// =========================================================================
Route::prefix('admin')->name('admin.')->middleware(['super.admin'])->group(function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [AdminDashboardController::class, 'notifications'])->name('notifications');

    // ✅ User Management – ONLY admin version (no public version)
    Route::resource('users', UserController::class);

    // Admin Feedback Management
    Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('feedback.index');
    Route::post('/feedback/{feedback}/status', [AdminFeedbackController::class, 'updateStatus'])->name('feedback.status');
    Route::delete('/feedback/{feedback}', [AdminFeedbackController::class, 'destroy'])->name('feedback.destroy');

    // Tenant Businesses & Organizations Directory
    Route::resource('businesses', AdminBusinessController::class)->only(['index', 'show', 'edit', 'update']);
    Route::post('businesses/{business}/toggle', [AdminBusinessController::class, 'toggleStatus'])->name('businesses.toggle');

    // Super Admin Subscriptions Suite
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/dashboard', [AdminSubscriptionController::class, 'dashboard'])->name('dashboard');
        
        // Plans Management
        Route::resource('plans', AdminSubscriptionPlanController::class);
        Route::post('plans/{plan}/duplicate', [AdminSubscriptionPlanController::class, 'duplicate'])->name('plans.duplicate');
        Route::post('plans/{plan}/toggle', [AdminSubscriptionPlanController::class, 'toggle'])->name('plans.toggle');

        // Features Manager
        Route::resource('features', AdminFeatureController::class)->only(['index', 'store', 'update', 'destroy']);

        // Subscribers & Details
        Route::get('/subscribers', [AdminSubscriptionController::class, 'subscribers'])->name('subscribers.index');
        Route::get('/subscribers/{subscription}', [AdminSubscriptionController::class, 'subscriberShow'])->name('subscribers.show');
        Route::post('/subscribers/{subscription}/action', [AdminSubscriptionController::class, 'updateSubscriber'])->name('subscribers.action');

        // Trials Management
        Route::get('/trials', [AdminSubscriptionController::class, 'trials'])->name('trials.index');

        // Payments & Invoices
        Route::get('/payments', [AdminSubscriptionController::class, 'payments'])->name('payments.index');
        Route::get('/invoices', [AdminSubscriptionController::class, 'invoices'])->name('invoices.index');

        // Subscription & Billing Settings
        Route::get('/settings', [AdminSubscriptionController::class, 'settings'])->name('settings.index');
        Route::post('/settings', [AdminSubscriptionController::class, 'updateSettings'])->name('settings.update');
    });

    // Audit Logs
    Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');

    // Admin Tasks Management CRUD
    Route::resource('tasks', AdminTaskController::class);

    // Admin Companies / Partners Showcase Management
    Route::resource('companies', AdminCompanyController::class)->only(['index', 'store', 'update', 'destroy']);

    // Products Management CRUD
    Route::resource('products', AdminProductController::class);

    // Inventory Control & Restock
    Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/{product}/adjust', [AdminInventoryController::class, 'adjust'])->name('inventory.adjust');

    // Currency Engine Management & Live Sync
    Route::get('/currencies', [AdminCurrencyController::class, 'index'])->name('currencies.index');
    Route::get('/currencies/{currency}/edit', [AdminCurrencyController::class, 'edit'])->name('currencies.edit');
    Route::put('/currencies/{currency}', [AdminCurrencyController::class, 'update'])->name('currencies.update');
    Route::post('/currencies/{currency}/toggle', [AdminCurrencyController::class, 'toggle'])->name('currencies.toggle');
    Route::get('/currencies-sync', [AdminCurrencyController::class, 'syncRates'])->name('currencies.sync');

    // Invoices Management
    Route::get('/invoices', [AdminInvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/invoices/{invoice}/status', [AdminInvoiceController::class, 'updateStatus'])->name('invoices.status');
    Route::delete('/invoices/{invoice}', [AdminInvoiceController::class, 'destroy'])->name('invoices.destroy');

    // CRM Leads & Support Tickets
    Route::resource('leads', AdminLeadController::class);
    Route::resource('tickets', AdminTicketController::class);
    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/{message}/read', [AdminMessageController::class, 'markAsRead'])->name('messages.read');
    Route::delete('/messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

    // Content Management - Public Pages
    Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index');
    Route::get('/pages/{page}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');

    // Admin Profile
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
});

// CREATE STORAGE SYMLINK (run once, then remove or protect)
Route::get('/fix-storage', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');

    if (file_exists($link) && is_link($link)) {
        return 'Symlink already exists and is valid.';
    }

    if (file_exists($link)) {
        unlink($link); // remove broken link
    }

    if (symlink($target, $link)) {
        return '✅ Storage symlink created successfully!<br>Visit /storage/avatars/... to test.';
    } else {
        return '❌ Failed to create symlink. Check folder permissions.';
    }
});