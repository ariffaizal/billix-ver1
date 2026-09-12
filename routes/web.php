<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Orders\OrderHistoryController;
use App\Http\Controllers\Orders\OrderItemsController;
use App\Http\Controllers\Orders\OrdersController;
use App\Http\Controllers\Prices\DiscountsController;
use App\Http\Controllers\Prices\OpenBillingController;
use App\Http\Controllers\Prices\PackagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Reports\ItemsReportController;
use App\Http\Controllers\Reports\ShiftReportController;
use App\Http\Controllers\Reports\UserReportController;
use App\Http\Controllers\Services\ShiftServiceController;
use App\Http\Controllers\Services\TableServicesController;
use App\Http\Controllers\Settings\FnBCategoryController;
use App\Http\Controllers\Settings\FnBMenusController;
use App\Http\Controllers\Settings\MemberController;
use App\Http\Controllers\Settings\StoreController;
use App\Http\Controllers\Settings\TablesController;
use App\Http\Controllers\Settings\TaxController;
use App\Http\Controllers\Settings\UserController;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/', '/login');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/changepassword', [ProfileController::class, 'changePassword']);

    // Open Shift
    Route::post('/shift/open', [ShiftServiceController::class, 'create'])->name('shift.open');
    Route::post('/shift/close', [ShiftServiceController::class, 'update'])->name('shift.close');

    // Settings APP
    Route::prefix('/settings')->group(function () {
        // Store
        Route::get('/store', [StoreController::class, 'index'])->name('settings.store');
        Route::get('/store/data', [StoreController::class, 'data']);
        Route::post('/store', [StoreController::class, 'create']);
        Route::post('/store/show', [StoreController::class, 'show']);
        Route::post('/store/update', [StoreController::class, 'update']);
        Route::delete('/store', [StoreController::class, 'delete']);

        // Tables
        Route::get('/table', [TablesController::class, 'index'])->name('settings.table');
        Route::get('/table/data', [TablesController::class, 'data']);
        Route::get('/table/available', [TablesController::class, 'available']);
        Route::get('/table/showallstatus', [TablesController::class, 'showAllTableStatus']);
        Route::get('/table/showavailable', [TablesController::class, 'showAvailable']);
        Route::get('/table/checktimeout', [TablesController::class, 'checkTimeOut']);
        Route::get('/table/deletetimeout', [TablesController::class, 'deleteTimeOut']);
        Route::post('/table', [TablesController::class, 'create']);
        Route::post('/table/show', [TablesController::class, 'show']);
        Route::post('/table/update', [TablesController::class, 'update']);
        Route::delete('/table', [TablesController::class, 'delete']);

        Route::post('/table/testrelay', [TableServicesController::class, 'testRelay']);

        // Menu FnB
        Route::get('/fnb', [FnBMenusController::class, 'index'])->name('settings.fnb');
        Route::get('/fnb/data', [FnBMenusController::class, 'data']);
        Route::get('/fnb/available', [FnBMenusController::class, 'available']);
        Route::post('/fnb', [FnBMenusController::class, 'create']);
        Route::post('/fnb/show', [FnBMenusController::class, 'show']);
        Route::post('/fnb/update', [FnBMenusController::class, 'update']);
        Route::delete('/fnb', [FnBMenusController::class, 'delete']);

        // Category FNB
        Route::get('/fnb/category', [FnBCategoryController::class, 'index'])->name('settings.fnb.category');
        Route::get('/fnb/category/data', [FnBCategoryController::class, 'data']);
        Route::post('/fnb/category', [FnBCategoryController::class, 'create']);
        Route::post('/fnb/category/show', [FnBCategoryController::class, 'show']);
        Route::post('/fnb/category/update', [FnBCategoryController::class, 'update']);
        Route::delete('/fnb/category', [FnBCategoryController::class, 'delete']);

        // Users
        Route::get('/user', [UserController::class, 'index'])->name('settings.user');
        Route::get('/user/data', [UserController::class, 'data']);
        Route::post('/user', [UserController::class, 'create']);
        Route::post('/user/show', [UserController::class, 'show']);
        Route::post('/user/update', [UserController::class, 'update']);
        Route::delete('/user', [UserController::class, 'delete']);

        // Members
        Route::get('/member', [MemberController::class, 'index'])->name('settings.member');
        Route::get('/member/data', [MemberController::class, 'data']);
        Route::post('/member', [MemberController::class, 'create']);
        Route::post('/member/show', [MemberController::class, 'show']);
        Route::post('/member/update', [MemberController::class, 'update']);
        Route::delete('/member', [MemberController::class, 'delete']);

        // Tax
        Route::get('/tax', [TaxController::class, 'index'])->name('settings.tax');
        Route::get('/tax/data', [TaxController::class, 'data']);
        Route::post('/tax', [TaxController::class, 'create']);
        Route::post('/tax/show', [TaxController::class, 'show']);
        Route::post('/tax/update', [TaxController::class, 'update']);
        Route::delete('/tax', [TaxController::class, 'delete']);
    });

    // Package & Prices
    Route::prefix('/prices')->group(function () {
        // Packages
        Route::get('/package', [PackagesController::class, 'index'])->name('prices.package');
        Route::get('/package/data', [PackagesController::class, 'data']);
        Route::post('/package', [PackagesController::class, 'create']);
        Route::post('/package/show', [PackagesController::class, 'show']);
        Route::post('/package/update', [PackagesController::class, 'update']);
        Route::delete('/package', [PackagesController::class, 'delete']);

        // Open Billing
        Route::get('/openbill', [OpenBillingController::class, 'index'])->name('prices.openbill');
        Route::get('/openbill/data', [OpenBillingController::class, 'data']);
        Route::post('/openbill', [OpenBillingController::class, 'create']);
        Route::post('/openbill/show', [OpenBillingController::class, 'show']);
        Route::post('/openbill/update', [OpenBillingController::class, 'update']);
        Route::delete('/openbill', [OpenBillingController::class, 'delete']);

        // Open Discount
        Route::get('/discount', [DiscountsController::class, 'index'])->name('prices.discount');
        Route::get('/discount/data', [DiscountsController::class, 'data']);
        Route::post('/discount', [DiscountsController::class, 'create']);
        Route::post('/discount/show', [DiscountsController::class, 'show']);
        Route::post('/discount/update', [DiscountsController::class, 'update']);
        Route::delete('/discount', [DiscountsController::class, 'delete']);
    });

    // Main APP
    Route::prefix('/orders')->group(function () {

        Route::get('/', [OrdersController::class, 'index'])->name('orders');
        Route::get('/data', [OrdersController::class, 'data']);
        Route::post('/create', [OrdersController::class, 'create'])->name('orders.create');
        Route::get('/new/{id_order}', [OrdersController::class, 'new'])->name('orders.new');
        Route::post('/process', [OrdersController::class, 'process'])->name('orders.process');
        Route::get('/pay/{id_order}', [OrdersController::class, 'payment'])->name('orders.pay');
        Route::get('/openbill/{id_order}', [OrdersController::class, 'openbill'])->name('orders.openbill');
        Route::get('/estimate/{id_order}', [OrdersController::class, 'estimateOpenBillPrice']);
        Route::post('/statustodraft', [OrdersController::class, 'statusToDraft'])->name('orders.statustodraft');
        Route::post('/openbill_process', [OrdersController::class, 'processOpenBill'])->name('orders.openbill_process');
        Route::post('/pay_confirm', [OrdersController::class, 'payment_confirmation'])->name('orders.pay_confirm');
        Route::post('/cancel', [OrdersController::class, 'cancel']);
        Route::post('/refund_process', [OrdersController::class, 'processRefund']);
        Route::delete('/delete', [OrdersController::class, 'delete']);

        Route::get('/view/{id_order}', [OrdersController::class, 'view'])->name('orders.view');
        Route::get('/print/{id_order}', [OrdersController::class, 'print'])->name('orders.print');

        Route::get('/items/{id_order}/list', [OrderItemsController::class, 'getItemsList']);
        Route::post('/items/{id_order}/addFnb', [OrderItemsController::class, 'addItemsFnB']);
        Route::get('/items/{id_order}/openbillitems', [OrderItemsController::class, 'getOpenBillItems']);
        Route::post('/items/{id_order}/updateFnb', [OrderItemsController::class, 'updateItemsFnB']);
        Route::post('/items/{id_order}/addTables', [OrderItemsController::class, 'addItemsTables']);
        Route::post('/items/{id_order}/updateTables', [OrderItemsController::class, 'updateItemsTables']);
        Route::delete('/items/delete', [OrderItemsController::class, 'delete']);

        Route::get('/history', [OrderHistoryController::class, 'index'])->name('orders.history');
        Route::get('/history/data', [OrderHistoryController::class, 'data']);

        Route::get('/pending', [OrdersController::class, 'pending'])->name('orders.pending');
        Route::get('/success', [OrdersController::class, 'success'])->name('orders.success');

        Route::post('/services/start', [TableServicesController::class, 'startOrderTable']);
        Route::post('/services/stop', [TableServicesController::class, 'stopOrderTable']);
        Route::post('/services/changetable', [TableServicesController::class, 'changeTableOnSession']);

        // menampilkan data member di menu payment
        Route::get('/payment', [MemberController::class, 'view'])->name('orders.payment');
    });

    // Report
    Route::prefix('/reports')->group(function () {

        Route::get('/closeshift', [ShiftReportController::class, 'close'])->name('reports.closeshift');
        Route::get('/byshift', [ShiftReportController::class, 'index'])->name('reports.byshift');
        Route::get('/byshift/data', [ShiftReportController::class, 'data']);
        Route::get('/byshift/print', [ShiftReportController::class, 'print']);

        Route::get('/byitems', [ItemsReportController::class, 'index'])->name('reports.byitems');
        Route::get('/byitems/data', [ItemsReportController::class, 'data']);
        Route::get('/byitems/chart', [ItemsReportController::class, 'chart']);

        Route::get('/byuser', [UserReportController::class, 'index'])->name('reports.byuser');
        Route::get('/byuser/data', [UserReportController::class, 'data']);
    });
});

require __DIR__.'/auth.php';
