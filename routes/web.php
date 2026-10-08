<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Backend\ClientRegisterController;
use App\Http\Controllers\Backend\CapitalTransactionController;
use App\Http\Controllers\Backend\InvoiceStatusController;
use App\Http\Controllers\Backend\UserCapitalController;
use App\Http\Controllers\Backend\PurchaseController;
use App\Http\Controllers\Backend\VehicleController;
use App\Http\Controllers\Client\ClientVehicleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUser;

Route::get('/', function () {
    return view('frontend.master');
});

Route::middleware(['auth', IsUser::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('client.index');
    })->name('dashboard');

    Route::get('/client/profile', [ClientController::class, 'ClientProfile'])->name('client.profile');
    Route::post('/client/profile/update', [ClientController::class, 'ClientProfileUpdate'])->name('client.profile.update');
    Route::get('/client/logout', [ClientController::class, 'ClientLogout'])->name('client.logout');
    Route::get('/client/change/password', [ClientController::class, 'ClientChangePassword'])->name('client.change.password');
    Route::post('/client/password/update', [ClientController::class, 'ClientPasswordUpdate'])->name('client.password.update');

      Route::controller(ClientVehicleController::class)->group(function () {
            Route::get('/my-vehicles', 'MyVehicles')->name('client.vehicles');
            Route::get('/my-vehicles/{id}', 'MyVehiclesView')->name('client.vehicle.view');
            
        });



});

//end user Routes






Route::prefix('admin')->middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.index');
    })->name('admin.dashboard');

    Route::get('/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');


    Route::get('/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/password/update', [AdminController::class, 'AdminPasswordUpdate'])->name('admin.password.update');


    Route::controller(ClientRegisterController::class)->group(function () {
        Route::get('/client/register', 'ClientRegister')->name('client.register');
        Route::get('/add/client', 'AddClient')->name('add.client');
        Route::post('/store/client', 'StoreClient')->name('store.client');

        Route::controller(CapitalTransactionController::class)->group(function () {
            Route::get('/capital/transactions', 'CapitalTransactions')->name('capital.transactions');
            Route::get('/add/capital/transaction', 'AddCapitalTransaction')->name('add.capital.transaction');
            Route::post('/store/capital/transaction', 'StoreCapitalTransaction')->name('store.capital.transaction');
            Route::get('/edit/capital/transaction/{id}', 'EditCapitalTransaction')->name('edit.capital.transaction');
            Route::post('/update/capital/transaction', 'UpdateCapitalTransaction')->name('update.capital.transaction');
            Route::get('/delete/capital/transaction/{id}', 'DeleteCapitalTransaction')->name('delete.capital.transaction');
        });

        // end Capital Transaction Routes

        Route::controller(UserCapitalController::class)->group(function () {
            Route::get('/user/all/capital', 'UserCapital')->name('user.capital');
            Route::get('/add/user/capital', 'AddUserCapital')->name('add.user.capital');
            Route::post('/store/user/capital', 'StoreUserCapital')->name('store.user.capital');
            Route::get('/edit/user/capital/{id}', 'EditUserCapital')->name('edit.user.capital');
            Route::post('/update/user/capital', 'UpdateUserCapital')->name('update.user.capital');
            Route::get('/delete/user/capital/{id}', 'DeleteUserCapital')->name('delete.user.capital');
        });

        Route::controller(PurchaseController::class)->group(function () {
            Route::get('/all/purchases', 'AllPurchases')->name('all.purchases');
            Route::get('/add/purchase', 'AddPurchase')->name('add.purchase');
            Route::post('/store/purchase', 'StorePurchase')->name('store.purchase');
            Route::get('/edit/purchase/{id}', 'EditPurchase')->name('edit.purchase');
            // Open Sale form from Purchase
            Route::get('/sale/purchase/{id}', 'SalePurchase')->name('sale.purchase');
            // Store Sale information
            Route::post('/sale/purchase/{id}', 'StoreSale')->name('store.sale');
            Route::post('/update/purchase', 'UpdatePurchase')->name('update.purchase');
            Route::get('/delete/purchase/{id}', 'DeletePurchase')->name('delete.purchase');
            // ==============================
            // Excel
            // ==============================
            Route::get('/purchases/export', 'ExportPurchases')->name('purchases.export');
            Route::post('/purchases/import', 'ImportPurchases')->name('purchases.import');
        });

        Route::controller(VehicleController::class)->group(function () {
            Route::get('/vehicle/status', 'VehicleStatus')->name('vehicle.status');
            Route::post('/vehicle/status/update/{id}', 'UpdateVehicleStatus')->name('vehicle.status.update');
            Route::get('/vehicle/status/view/{id}', 'VehicleStatusView')->name('vehicle.status.view');
            Route::get('/vehicle/{id}/invoice/download', 'DownloadInvoice')->name('vehicle.invoice.download');
            
        });

        Route::controller(InvoiceStatusController::class)->group(function () {
            Route::get('/invoice/status', 'InvoiceStatus')->name('invoice.status');
            Route::get('/invoice/status/add', 'AddInvoiceStatus')->name('invoice.status.add');
            Route::post('/invoice/status/store', 'StoreInvoiceStatus')->name('invoice.status.store');
            
            
        });


    });
});

//end Admin  Routes




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
