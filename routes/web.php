<?php

use App\Http\Controllers\AccessRulesController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\CompanyGroupController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DistancePriceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ReceiveController;
use App\Http\Controllers\ReceiveOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UsageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

# Autentikasi
Route::get('/welcome', [LoginController::class, 'login'])->name('login');
Route::get('/', [PageController::class, 'index']);
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');

# Terkait tampilan awal
Route::get('home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('menu', [AccessRulesController::class, 'getAccessRolesByRoleName'])->middleware('auth');

# Terkait User
Route::get('user/registration', [UserController::class, 'index'])->middleware('auth');
Route::get('user', [UserController::class, 'search'])->middleware('auth');
Route::put('user/{id}', [UserController::class, 'update'])->middleware('auth');
Route::post('user', [UserController::class, 'simpan'])->middleware('auth');
Route::get('user/form/management', [UserController::class, 'formManagement'])->middleware('auth');
Route::get('user/management', [UserController::class, 'getPerCompanyGroup'])->middleware('auth');
Route::put('user/reset-password/{id}', [UserController::class, 'resetPassword'])->middleware('auth');

# Terkait seting akses user
Route::get('/setting/access', [AccessRulesController::class, 'index'])->middleware('auth');

Route::get('/version', function () {
    return app()->version();
});

Route::middleware('auth')->group(function () {
    # Terkait Company Group
    Route::prefix('company')->group(function () {
        Route::get('form', [CompanyGroupController::class, 'index'])->middleware('auth');
        Route::get('management-form', [CompanyGroupController::class, 'form'])->middleware('auth');
        Route::put('management-form/{id}', [CompanyGroupController::class, 'updateBranch'])->middleware('auth');
        Route::get('', [CompanyGroupController::class, 'search'])->middleware('auth');
        Route::post('', [CompanyGroupController::class, 'save'])->middleware('auth');
        Route::put('{id}', [CompanyGroupController::class, 'update'])->middleware('auth');
        Route::get('access/{id}', [CompanyGroupController::class, 'loadByNickName'])->middleware('auth');
        Route::post('access', [CompanyGroupController::class, 'saveAccess'])->middleware('auth');
        Route::delete('access/{id}', [CompanyGroupController::class, 'deleteAccess'])->middleware('auth');
    });

    # Terkait Company Group
    Route::prefix('payment-account')->group(function () {
        Route::post('form', [CompanyGroupController::class, 'savePaymentAccount']);
        Route::get('', [CompanyGroupController::class, 'getPaymentAccountCompanyBranch']);
        Route::delete('form/{id}', [CompanyGroupController::class, 'deletePaymentAccountCompanyBranch']);
    });

    # Terkait Usage Master
    Route::prefix('usage')->group(function () {
        Route::get('form', [UsageController::class, 'index']);
        Route::post('import', [UsageController::class, 'importFromAnotherCompany']);
        Route::get('', [UsageController::class, 'search']);
        Route::post('', [UsageController::class, 'simpan']);
        Route::put('{id}', [UsageController::class, 'update']);
    });

    # Terkait Harga Jarak
    Route::prefix('distance-price')->group(function () {
        Route::get('form', [DistancePriceController::class, 'index']);
        Route::post('import', [DistancePriceController::class, 'importFromAnotherCompany']);
        Route::get('', [DistancePriceController::class, 'search']);
        Route::post('', [DistancePriceController::class, 'simpan']);
        Route::put('{id}', [DistancePriceController::class, 'update']);
    });

    # Terkait SPK , Surat Perintah Kerja
    Route::prefix('SPK')->group(function () {
        Route::post('', [DeliveryController::class, 'saveSPK']);
        Route::get('', [DeliveryController::class, 'searchSPK']);
        Route::put('{id}', [DeliveryController::class, 'updateSPK']);
        Route::delete('{id}', [DeliveryController::class, 'deleteSPK']);
        Route::put('delivery-order/{id}', [DeliveryController::class, 'getSPKByDO']);
        Route::put('submit/{id}', [DeliveryController::class, 'submitSPK']);
    });

    # Terkait Supplier Master
    Route::prefix('supplier')->group(function () {
        Route::get('form', [SupplierController::class, 'index']);
        Route::post('import', [SupplierController::class, 'importFromAnotherCompany']);
        Route::get('', [SupplierController::class, 'search']);
        Route::post('', [SupplierController::class, 'simpan']);
        Route::put('{id}', [SupplierController::class, 'update']);
    });

    # Terkait Chart of Account Master
    Route::prefix('coa')->group(function () {
        Route::get('form', [CoaController::class, 'index']);
        Route::post('import', [CoaController::class, 'importFromAnotherCompany']);
        Route::get('', [CoaController::class, 'search']);
        Route::post('', [CoaController::class, 'simpan']);
        Route::put('{id}', [CoaController::class, 'update']);
    });

    # Terkait Chart of Account Master
    Route::prefix('condition')->group(function () {
        Route::get('form', [ConditionController::class, 'index']);
        Route::post('import', [ConditionController::class, 'importFromAnotherCompany']);
        Route::get('search', [ConditionController::class, 'search']);
        Route::post('', [ConditionController::class, 'simpan']);
        Route::put('{id}', [ConditionController::class, 'update']);
        Route::delete('{id}', [ConditionController::class, 'delete']);
        Route::get('', [QuotationController::class, 'getAllCondition'])->middleware('auth');
    });

    # Terkait Unit of Measurement Master
    Route::prefix('uom')->group(function () {
        Route::get('form', [MeasurementController::class, 'index']);
        Route::post('import', [MeasurementController::class, 'importFromAnotherCompany']);
        Route::get('', [MeasurementController::class, 'search']);
        Route::post('', [MeasurementController::class, 'simpan']);
        Route::put('{id}', [MeasurementController::class, 'update']);
    });

    # Terkait Item Master
    Route::prefix('item')->group(function () {
        Route::get('form', [ItemController::class, 'index']);
        Route::post('import', [ItemController::class, 'importFromAnotherCompany']);
        Route::get('', [ItemController::class, 'search']);
        Route::post('', [ItemController::class, 'simpan']);
        Route::put('{id}', [ItemController::class, 'update']);
    });

    # Terkait Customer Master
    Route::prefix('customer')->group(function () {
        Route::get('form', [CustomerController::class, 'index']);
        Route::post('import', [CustomerController::class, 'importFromAnotherCompany']);
        Route::get('file/{id}', [CustomerController::class, 'showFile']);
        Route::get('', [CustomerController::class, 'search']);
        Route::post('', [CustomerController::class, 'simpan']);
        Route::post('file/{id}', [CustomerController::class, 'changeFile']);
        Route::put('{id}', [CustomerController::class, 'update']);
    });

    #Terkait Approval
    Route::prefix('approval')->group(function () {
        Route::get('notifications', [HomeController::class, 'notifications']);
        Route::get('notifications/top-user', [HomeController::class, 'TopUserNotifications']);
        Route::get('quotation', [QuotationController::class, 'notifications']);
        Route::get('purchase-request', [PurchaseController::class, 'notifications']);
        Route::get('purchase-order', [PurchaseController::class, 'notificationsPO']);
        Route::get('sales-order-draft', [ReceiveOrderController::class, 'notificationsDraft']);
        Route::get('spk', [DeliveryController::class, 'notificationsSPK']);
        Route::get('form/quotation', [QuotationController::class, 'formApproval']);
        Route::get('form/purchase-request', [PurchaseController::class, 'formApproval']);
        Route::get('form/purchase-order', [PurchaseController::class, 'formApprovalPO']);
        Route::get('form/spk', [DeliveryController::class, 'formApprovalSPK']);
        Route::put('approve-spk/{id}', [DeliveryController::class, 'approveSPK']);
    });

    # Terkait Delivery
    Route::prefix('delivery')->group(function () {
        Route::get('form', [DeliveryController::class, 'index']);
        Route::get('unconfirmed-form', [DeliveryController::class, 'formUnconfirmed']);
        Route::get('unconfirmed', [DeliveryController::class, 'unconfirmed']);
        Route::post('confirm', [DeliveryController::class, 'confirmOutgoing']);
        Route::get('outstanding-warehouse', [DeliveryController::class, 'outstandingWarehouse']);
        Route::get('outstanding-warehouse/{id}', [DeliveryController::class, 'outstandingWarehousePerDocument']);
        Route::put('items/{id}', [DeliveryController::class, 'updateDODetail']);
        Route::post('', [DeliveryController::class, 'save']);
        Route::get('', [DeliveryController::class, 'search']);
        Route::put('{id}', [DeliveryController::class, 'update']);
        Route::get('document/{id}', [DeliveryController::class, 'loadByDocument']);
    });

    #Terkait Receive Order
    Route::prefix('receive-order')->group(function () {
        Route::get('form', [ReceiveOrderController::class, 'index']);
        Route::post('', [ReceiveOrderController::class, 'save']);
        Route::get('', [ReceiveOrderController::class, 'search']);
        Route::put('{id}', [ReceiveOrderController::class, 'update']);
        Route::get('{id}', [ReceiveOrderController::class, 'loadById']);
        Route::delete('items/{id}', [ReceiveOrderController::class, 'deleteItemById']);
        Route::put('items/{id}', [ReceiveOrderController::class, 'updateItem']);
    });

    Route::prefix('assignment-driver')->group(function () {
        Route::get('form/delivery', [DeliveryController::class, 'formDriverAssignment']);
        Route::get('data/delivery', [DeliveryController::class, 'emptyDriver']);
        Route::put('form/delivery/{id}', [DeliveryController::class, 'assignDriver']);
    });

    Route::prefix('cashier')->group(function () {
        Route::get('form', [CashierController::class, 'index']);
        Route::post('', [CashierController::class, 'save']);
        Route::get('', [CashierController::class, 'search']);
        Route::get('search', [CashierController::class, 'searchHeader']);
    });

    # Terkait Quotation Transaction
    Route::prefix('quotation')->group(function () {
        Route::get('form', [QuotationController::class, 'index']);
        Route::post('', [QuotationController::class, 'save']);
        Route::get('', [QuotationController::class, 'search']);
        Route::put('{id}', [QuotationController::class, 'update']);
        Route::get('{id}', [QuotationController::class, 'loadById']);
        Route::delete('conditions/{id}', [QuotationController::class, 'deleteConditionById']);
        Route::delete('items/{id}', [QuotationController::class, 'deleteItemById']);
        Route::put('items/{id}', [QuotationController::class, 'updateItem']);
        Route::post('items/{id}', [QuotationController::class, 'saveItem']);
    });
    Route::post('quotation-item', [QuotationController::class, 'saveItem']);
    Route::post('quotation-condition', [QuotationController::class, 'saveCondition']);

    #Terkait laporan berupa xls
    Route::prefix('report-form')->group(function () {
        Route::get('item-master', [ItemController::class, 'formReport']);
        Route::get('quotation', [QuotationController::class, 'formReport']);
        Route::get('received-order', [ReceiveOrderController::class, 'formReport']);
        Route::get('maintenance-schedule', [MaintenanceController::class, 'formReport']);
    });

    Route::prefix('report')->group(function () {
        Route::get('item-master', [ItemController::class, 'report']);
        Route::get('quotation', [QuotationController::class, 'report']);
        Route::get('received-order', [ReceiveOrderController::class, 'report']);
    });

    # Terkait Rejection
    Route::prefix('reject')->group(function () {
        Route::put('quotations/{id}', [QuotationController::class, 'reject']);
        Route::put('purchase-request/{id}', [PurchaseController::class, 'reject']);
        Route::put('purchase-order/{id}', [PurchaseController::class, 'rejectPO']);
    });

    # Terkait Suggest for Revision
    Route::prefix('revise')->group(function () {
        Route::put('quotations/{id}', [QuotationController::class, 'revise']);
        Route::put('purchase-request/{id}', [PurchaseController::class, 'reject']);
        Route::put('purchase-order/{id}', [PurchaseController::class, 'rejectPO']);
    });
});

Route::put('approve/quotations/{id}', [QuotationController::class, 'approve'])->middleware('auth');
Route::put('approve/purchase-request/{id}', [PurchaseController::class, 'approve'])->middleware('auth');
Route::put('approve/sales-order-draft/{id}', [ReceiveOrderController::class, 'approve'])->middleware('auth');
Route::put('approve/purchase-order/{id}', [PurchaseController::class, 'approvePO'])->middleware('auth');
Route::get('approved/form/quotation', [QuotationController::class, 'formApproved'])->middleware('auth');
Route::get('approved/form/purchase-request', [PurchaseController::class, 'formStatus'])->middleware('auth');
Route::get('approved/form/sales-order-draft', [ReceiveOrderController::class, 'formApprovalDraft'])->middleware('auth');

#Terkait Dasbor
Route::get('dashboard-resource', [HomeController::class, 'supportDashboard'])->middleware('auth');

#Terkait laporan berupa Pdf
Route::get('PDF/quotation/{id}', [QuotationController::class, 'toPDF'])->middleware('auth');
Route::get('PDF/purchase-request/{id}', [PurchaseController::class, 'toPDF'])->middleware('auth');
Route::get('PDF/purchase-order/{id}', [PurchaseController::class, 'POtoPDF'])->middleware('auth');
Route::get('PDF/delivery-order/{id}', [DeliveryController::class, 'toPDF'])->middleware('auth');
Route::get('PDF/SPK/{id}', [DeliveryController::class, 'SPKtoPDF'])->middleware('auth');

#Terkait config
Route::get('ACL/database', function () {
    $ConnectionList = [];
    $Configs = Config::get('database');
    foreach ($Configs['connections'] as $key => $value) {
        if (str_contains($key, 'jos')) {
            $ConnectionList[] = [$key => $value];
        }
    }
    return ['data' => $ConnectionList];
});

# Terkait Purchase Request Transaction
Route::get('purchase-request/form', [PurchaseController::class, 'index'])->middleware('auth');
Route::post('purchase-request', [PurchaseController::class, 'save'])->middleware('auth');
Route::get('purchase-request', [PurchaseController::class, 'search'])->middleware('auth');
Route::put('purchase-request/{id}', [PurchaseController::class, 'update'])->middleware('auth');
Route::get('purchase-request/{id}', [PurchaseController::class, 'loadById'])->middleware('auth');
Route::get('purchase-request-approval/{id}', [PurchaseController::class, 'loadByIdApproval'])->middleware('auth');
Route::delete('purchase-request/items/{id}', [PurchaseController::class, 'deleteItemById'])->middleware('auth');

# Terkait Purchase Order Transaction
Route::get('purchase-order/form', [PurchaseController::class, 'formOrder'])->middleware('auth');
Route::post('purchase-order', [PurchaseController::class, 'savePO'])->middleware('auth');
Route::get('purchase-order', [PurchaseController::class, 'searchPO'])->middleware('auth');
Route::get('purchase-order/document/{id}', [PurchaseController::class, 'loadPOById'])->middleware('auth');
Route::get('purchase-order/approval-document/{id}', [PurchaseController::class, 'loadPOByIdApproval'])->middleware('auth');
Route::put('purchase-order/items/{id}', [PurchaseController::class, 'updatePODetail'])->middleware('auth');
Route::delete('purchase-order/items/{id}', [PurchaseController::class, 'updatePODetail'])->middleware('auth');

# Terkait Sales Order Draft Transaction
Route::get('sales-order-draft/document/{id}', [ReceiveOrderController::class, 'loadDraftById'])->middleware('auth');
Route::get('sales-order-draft', [ReceiveOrderController::class, 'searchDraft'])->middleware('auth');

# Terkait Receive
Route::get('receive/form', [ReceiveController::class, 'index'])->middleware('auth');

# Terkait Branch
Route::get('branch/form', [BranchController::class, 'index'])->middleware('auth');
Route::post('branch', [BranchController::class, 'save'])->middleware('auth');
Route::get('branch', [BranchController::class, 'search'])->middleware('auth');

Route::get('confirmation/form/delivery', [DeliveryController::class, 'formDeliveryConfirmation'])->middleware('auth');
Route::get('confirmation/data/delivery', [DeliveryController::class, 'emptyDeliveryDateTime'])->middleware('auth');
Route::put('confirmation/form/delivery/{id}', [DeliveryController::class, 'confirmDelivery'])->middleware('auth');
