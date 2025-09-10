<?php

use App\Models\ProductReturn;
use App\Http\Controllers\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\TaxController;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\BillController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VenderController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DebitNoteController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BankTransferController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductReturnController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\PagePermissionController;
use App\Http\Controllers\ProductExpenseController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductAccountMapController;
use App\Http\Controllers\ProductServiceCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** for side bar menu active */


function set_active( $route ) {
    if( is_array( $route ) ){
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}


Route::group(['middleware'=>'auth'],function()
{
    Route::get('home',function()
    {
        return view('home');
    });
});


//Login & Register
Auth::routes();
Route::group(['namespace' => 'App\Http\Controllers\Auth'],function()
{
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
        Route::post('change/password', 'changePassword')->name('change/password');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register','storeUser')->name('register');    
    });
});


//Dashboard
Route::group(['namespace' => 'App\Http\Controllers'],function()
{
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->middleware('auth')->name('dashboard');
        Route::get('/home', 'index')->middleware('auth')->name('home');
        Route::get('user/profile/page', 'userProfile')->middleware('auth')->name('user/profile/page');
        Route::get('user/profile/page/edit', 'userProfileEdit')->middleware('auth')->name('user.profile.page.edit');
        Route::put('user/profile/page/update', 'userProfileUpdate')->middleware('auth')->name('user.profile.page.update');

    });

    //User
    Route::controller(UserManagementController::class)->group(function () {
        Route::get('list/users', 'index')->middleware('auth')->name('list/users');
        Route::get('list/verify/users', 'unverifiedUsers')->middleware('auth')->name('list/verify/users');
        Route::post('change/password', 'changePassword')->name('change/password');
        Route::get('view/user/edit/{id}', 'userView')->middleware('auth');
        Route::post('user/update', 'userUpdate')->name('user/update');
        Route::post('user/delete', 'userDelete')->name('user/delete');
        Route::post('user/destroy/{id}', 'userDestroy')->name('user.destroy');
        Route::post('user/verify', 'userVerify')->name('user/verify'); 
        Route::get('get-users-data', 'getUsersData')->name('get-users-data'); 
        Route::get('get-unverified-users-data', 'getUnverifiedUsersData')->name('get-unverified-users-data');

        Route::post('/users/reset-password', 'resetPassword')->name('users.reset-password');

    });


    Route::middleware('auth')->group(function () {
        Route::get('unverify/user/list', [UserManagementController::class, 'unverifyUserlist'])->name('unverify.user.list'); 
        Route::get('unverify/user/edit/{id}', [UserManagementController::class, 'unverifyUseredit'])->name('unverify.user.edit'); 
        Route::post('unverify/user/update/{id}', [UserManagementController::class, 'unverifyUserupdate'])->name('unverify.user.update'); 
        Route::get('unverify/user/delete/{id}', [UserManagementController::class, 'unverifyUserdelete'])->name('unverify.user.delete'); 
        Route::post('verify/user', [UserManagementController::class, 'verifyUser'])->name('verify.user');

    });


   //Setting
    Route::middleware('auth')->group(function () {
        Route::get('setting/page', [SettingsController::class, 'index'])->name('setting.page');
        Route::post('setting/page/store', [SettingsController::class, 'store'])->name('setting.page.store');

    });

    //Warehouse
    Route::controller(WarehouseController::class)->group(function () {
        Route::get('warehouse/list', 'warehouse')->middleware('auth')->name('warehouse/list');
        Route::get('warehouse/grid', 'warehouseGrid')->middleware('auth')->name('warehouse/grid'); 
        Route::get('warehouse/add/page', 'warehouseAdd')->middleware('auth')->name('warehouse/add/page'); 
        Route::post('warehouse/add/save', 'warehouseSave')->name('warehouse/add/save'); 
        Route::get('warehouse/edit/{id}', 'warehouseEdit'); 
        Route::post('warehouse/update', 'warehouseUpdate')->name('warehouse/update');
        Route::post('warehouse/delete', 'warehouseDelete')->name('warehouse/delete'); 
        Route::get('warehouse/view/{id}', 'warehouseView')->middleware('auth');
    });

    //Branch
    Route::middleware('auth')->group(function () {
        Route::get('branch/create', [BranchController::class, 'create'])->name('branch.create');
        Route::post('branch/store', [BranchController::class, 'store'])->name('branch.store');
        Route::get('branch/list', [BranchController::class, 'index'])->name('branch.list');
        Route::get('branch/show/{id}', [BranchController::class, 'show'])->name('branch.show');
        Route::get('branch/edit/{id}/edit', [BranchController::class, 'edit'])->name('branch.edit');
        Route::put('branch/update/{id}', [BranchController::class, 'update'])->name('branch.update');
        Route::get('branch/delete/{id}', [BranchController::class, 'delete'])->name('branch.delete');
    });

    //Role
    Route::controller(RoleController::class)->group(function () {
        Route::get('role/list/page', 'roleList')->middleware('auth')->name('role/list/page'); 
        Route::get('role/add/page', 'roleAdd')->middleware('auth')->name('role/add/page');
        Route::post('role/save', 'saveRecord')->name('role/save');
        Route::get('role/edit/{role_id}', 'roleEdit'); 
        Route::put('role/update/{role_id}', 'updateRecord')->name('role/update');
        Route::get('role/delete/{role_id}', 'deleteRecord')->name('role.delete'); 
    });
    
    //PagePermission
    Route::controller(PagePermissionController::class)->group(function () {
        Route::get('page/list/page', 'pageList')->middleware('auth')->name('page/list/page'); 
        Route::get('page/add/page', 'pageAdd')->middleware('auth')->name('page/add/page'); 
        Route::post('page/save', 'saveRecord')->name('page/save'); 
        Route::post('page/update', 'updateRecord')->name('page/update'); 
        Route::post('page/delete', 'deleteRecord')->name('page/delete'); 
        Route::get('page/edit/{page_id}', 'pageEdit');
        Route::post('page/rolepermission', 'pagepermissionEdit')->middleware('auth')->name('page/rolepermission'); 
        Route::post('page/getPagePermission', 'getPagePermission')->name('page/getPagePermission');
    });

    //Batch
    Route::controller(BatchController::class)->group(function () {
        Route::get('batch/list', 'batch')->middleware('auth')->name('batch/list'); 
        Route::get('batch/grid', 'batchGrid')->middleware('auth')->name('batch/grid'); 
        Route::get('batch/add/page', 'batchAdd')->middleware('auth')->name('batch/add/page'); 
        Route::post('batch/add/save', 'batchSave')->name('batch/add/save');
        Route::get('batch/edit/{id}', 'batchEdit'); 
        Route::post('batch/update', 'batchUpdate')->name('batch/update'); 
        Route::post('batch/delete', 'batchDelete')->name('batch/delete'); 
        Route::get('batch/view/{id}', 'batchView')->middleware('auth'); 
    });

    //Category
    Route::controller(CategoryController::class)->group(function () {
        Route::get('category/list', 'category')->middleware('auth')->name('category/list'); 
        Route::get('category/grid', 'categoryGrid')->middleware('auth')->name('category/grid'); 
        Route::get('category/add/page', 'categoryAdd')->middleware('auth')->name('category/add/page'); 
        Route::post('category/add/save', 'categorySave')->name('category/add/save'); 
        Route::get('category/edit/{id}', 'categoryEdit'); 
        Route::post('category/update', 'categoryUpdate')->name('category/update');
        Route::post('category/delete', 'categoryDelete')->name('category/delete'); 
        Route::get('category/view/{id}', 'categoryView')->middleware('auth'); 
    });

    //Product
    Route::middleware('auth')->group(function () {
        Route::get('product/list', [ProductController::class, 'productList'])->name('product.list'); 
        Route::get('product/create', [ProductController::class, 'productCreate'])->name('product.create'); 
        Route::post('product/store', [ProductController::class, 'productStore'])->name('product.store');
        Route::get('product/view/{id}', [ProductController::class, 'productView'])->name('product.view');
        Route::get('product/edit/{id}', [ProductController::class, 'productEdit'])->name('product.edit');
        Route::put('product/update/{id}', [ProductController::class, 'productUpdate'])->name('product.update');
        Route::get('product/delete/{id}', [ProductController::class, 'productDelete'])->name('product.delete'); 

        Route::get('/product-details/{id}', [ProductController::class, 'getProductDetails'])->name('product.details');
        Route::post('barcode/generate', [ProductController::class, 'generateBarcode'])->name('barcode.generate');
        Route::get('barcode/list', [ProductController::class, 'ListBarcode'])->name('barcode.list');
        
    });

    // Route::get('/product-details/{id}', [ProductController::class, 'getProductDetails'])->name('product.details');
    // Route::post('barcode/generate', [ProductController::class, 'generateBarcode'])->name('barcode.generate');
    // Route::get('barcode/list', [ProductController::class, 'ListBarcode'])->name('barcode.list');

    //Others
    Route::get('routes', function () {
        $routeCollection = Route::getRoutes();
    
        echo "<table style='width:100%'>";
        echo "<tr>";
        echo "<td width='10%'><h4>HTTP Method</h4></td>";
        echo "<td width='10%'><h4>Route</h4></td>";
        echo "<td width='10%'><h4>Name</h4></td>";
        echo "<td width='70%'><h4>Corresponding Action</h4></td>";
        echo "</tr>";
        foreach ($routeCollection as $value) {
            echo "<tr>";
            echo "<td>" . $value->methods()[0] . "</td>";
            echo "<td>" . $value->uri() . "</td>";
            echo "<td>" . $value->getName() . "</td>";
            echo "<td>" . $value->getActionName() . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    });
  
});



Route::middleware(['auth'])->group(function () {
    //Bank, Transffer & Customer
    Route::resource('bank-account', BankAccountController::class);
    Route::resource('bank-transfer', BankTransferController::class);
    Route::resource('customer', CustomerController::class);

    //Customer & Proposal 
    Route::get('/customer/invoice/{id}/', [InvoiceController::class, 'invoiceLink'])->name('invoice.link.copy');
    Route::get('/customer/proposal/{id}/', [ProposalController::class, 'invoiceLink'])->name('proposal.link.copy');
    Route::get('proposal/pdf/{id}', [ProposalController::class, 'proposal'])->name('proposal.pdf');
    Route::get('proposal/{id}/status/change', [ProposalController::class, 'statusChange'])->name('proposal.status.change');
    Route::get('proposal/{id}/convert', [ProposalController::class, 'convert'])->name('proposal.convert');
    Route::get('proposal/{id}/duplicate', [ProposalController::class, 'duplicate'])->name('proposal.duplicate');
    Route::post('proposal/product/destroy', [ProposalController::class, 'productDestroy'])->name('proposal.product.destroy');
    Route::post('proposal/customer', [ProposalController::class, 'customer'])->name('proposal.customer');
    Route::post('proposal/product', [ProposalController::class, 'product'])->name('proposal.product');
    Route::get('proposal/items', [ProposalController::class, 'items'])->name('proposal.items');
    Route::get('proposal/{id}/sent', [ProposalController::class, 'sent'])->name('proposal.sent');
    Route::get('proposal/{id}/resent', [ProposalController::class, 'resent'])->name('proposal.resent');
    Route::resource('proposal', ProposalController::class);
    Route::get('proposal/create/{cid}', [ProposalController::class, 'create'])->name('proposal.create');

    //Requisition
    Route::get('requisition/index', [RequisitionController::class, 'index'])->name('requisition.index');
    Route::get('requisition/create', [RequisitionController::class, 'createreq'])->name('requisition.createreq');
    Route::get('requisition/list', [RequisitionController::class, 'list'])->name('requisition.list'); 
    Route::get('requisition/list/view/{id}', [RequisitionController::class, 'view'])->name('requisition.view');
    Route::get('requisition/list/edit/{id}', [RequisitionController::class, 'edit'])->name('requisition.edit');
    Route::put('requisition/list/update/{id}', [RequisitionController::class, 'update'])->name('requisition.update');
    Route::get('requisition/list/delete/{id}', [RequisitionController::class, 'delete'])->name('requisition.delete');
    Route::post('requisition/store', [RequisitionController::class, 'store'])->name('requisition.store');
    Route::get('requisition/download-pdf', [PDFController::class, 'downloadPDF'])->name('download.pdf'); 

    //Project
    Route::resource('project', ProjectController::class); 

    //Product Category
    Route::resource('productcategory', ProductCategoryController::class);

    //Order
    Route::get('order/list', [RequisitionController::class, 'orderlist'])->name('order.list'); 
    Route::get('order/list/edit{id}', [RequisitionController::class, 'orderlistEdit'])->name('order.list.edit'); 
    Route::put('order/list/update/{id}', [RequisitionController::class, 'orderlistUpdate'])->name('order.list.update');
    Route::get('order/list/view{id}', [RequisitionController::class, 'orderlistView'])->name('order.list.view');  

    //Warehouse see demand all list of product with quantity
    Route::get('product/demand', [RequisitionController::class, 'pDemandindex'])->name('product.demand');

    //reject list & purchase list for warehouse 
    Route::get('reject/list', [RequisitionController::class, 'rejectlist'])->name('reject.list'); 
    Route::get('reject/list/view{id}', [RequisitionController::class, 'rejectlistView'])->name('reject.list.View'); 
    Route::get('purchase/list', [RequisitionController::class, 'purchaselist'])->name('purchase.list'); 
    Route::get('purchase/list/view{id}', [RequisitionController::class, 'purchaselistView'])->name('purchase.list.view'); 

    //reject list dor purchase team 
    Route::get('reject/list/collection', [RequisitionController::class, 'rejectlistCollection'])->name('reject.list.collection'); 
    Route::get('reject/list/collection/view{id}', [RequisitionController::class, 'rejectlistCollectionView'])->name('reject.list.collection.view'); 

    //completed order
    Route::get('completed/order/list', [RequisitionController::class, 'completedorderlist'])->name('completed.order.list'); 
    Route::get('completed/order/list/view/{id}', [RequisitionController::class, 'completedorderlistView'])->name('completed.order.list.view');
    Route::get('completed/order/to/instock/{id}', [RequisitionController::class, 'completedorderlistInstock'])->name('completed.order.to.instock');

    Route::post('/requisition/reject/{id}', [RequisitionController::class, 'reject'])->name('requisition.reject');
    Route::get('/requisition/reject/check/{id}', [RequisitionController::class, 'rejectCheck'])->name('requisition.reject.check');
    Route::post('/pending/purcahse/requisition/reject/{id}', [RequisitionController::class, 'pendingpurchaseReject'])->name('pending.purchase.requisition.reject');
    Route::get('/pending/purcahse/requisition/reject/check/{id}', [RequisitionController::class, 'pendingpurchaseRejectCheck'])->name('pending.purchase.requisition.reject.check');

    //Requisition Approve
    Route::get('requisition/approve', [RequisitionController::class, 'approve'])->name('requisition.approve');
    Route::get('requisition/approve/delete/{id}', [RequisitionController::class, 'Approvedelete'])->name('requisition.approve.delete');
    Route::post('requisition/approve/store', [RequisitionController::class, 'Approvestore'])->name('requisition.approve.store');
    Route::get('requisition/approval/{id}', [RequisitionController::class, 'approveRequisition'])->name('requisition.approveRequisition');
    Route::get('requisition/reject/{id}', [RequisitionController::class, 'rejectRequisition'])->name('requisition.rejectRequisition');


    //Purchase for others by warehouse 
    Route::get('/requisition/purchasee/{id}', [RequisitionController::class, 'purchase'])->name('requisition.purchasee');

    //Purchase checked 
    Route::get('/requisition/purchasee/check/{id}', [RequisitionController::class, 'purchaseCheck'])->name('requisition.purchasee.check'); 

    //Purchase for own by warehouse himself
    Route::get('purchasee/{id}', [RequisitionController::class, 'purchaseownWarehouse'])->name('purchasee');

    //Purchase Collection 
    Route::get('purchasee/collection/list', [RequisitionController::class, 'purchaseCollectionlist'])->name('purchasee.collection.list');
    Route::get('purchasee/collection/list/view/{id}', [RequisitionController::class, 'purchaseCollectionView'])->name('purchasee.collection.list.view');

    //Pending Purchase list & View
    Route::get('/pending/purcahse/requisition', [RequisitionController::class, 'pendingPurchase'])->name('pending.purcahse.requisition');
    Route::get('/pending/purcahse/requisition/view/{id}', [RequisitionController::class, 'pendingPurchaseView'])->name('pending.purcahse.requisition.view');

    Route::get('/pending/purcahse/requisition/pdf/{id}', [RequisitionController::class, 'purchaseRequisitionPdf'])->name('pending.purcahse.requisition.pdf'); 
    //file upload
    Route::post('requisition/upload', [RequisitionController::class, 'uploadDocument'])->name('requisition.upload');

    //Pending Purchase list  TO Apoprove
    Route::get('/pending/purcahse/requisition/approve/{id}', [RequisitionController::class, 'pendingPurchaseApprove'])->name('pending.purcahse.requisition.approve'); 
    Route::get('/pending/purcahse/requisition/approve/check/{id}', [RequisitionController::class, 'pendingPurchaseApproveCheck'])->name('pending.purcahse.requisition.approve.check'); 

    //Pending Purchase list  TO headoffice approve
    // Route::get('send/to/approve/{id}', [RequisitionController::class, 'sendToapprove'])->name('send.to.approve'); 
    Route::get('send/to/approve/{id}', [RequisitionController::class, 'sendToApprove'])->name('send.to.approve');
    Route::get('send/to/approve/check/{id}', [RequisitionController::class, 'sendToapproveCheck'])->name('send.to.approve.check');

    //Headoffice pending approve list, approved & reject all
    Route::get('pending/approval/list', [RequisitionController::class, 'pendingApprovallist'])->name('pending.approval.list');
    Route::get('pending/approval/list/view{id}', [RequisitionController::class, 'pendingApprovallistView'])->name('pending.approval.list.view');
    Route::get('pending/approval/list/approve/{id}', [RequisitionController::class, 'pendingApprovallistapprove'])->name('pending.approval.list.approve');
    Route::get('pending/approval/list/approve/check/{id}', [RequisitionController::class, 'pendingApprovallistapproveCheck'])->name('pending.approval.list.approve.check');
    // Route::post('pending/approval/list/reject/{id}', [RequisitionController::class, 'pendingApprovallistreject'])->name('pending.approval.list.reject'); 
    Route::post('pending/approval/list/reject/{id}', [RequisitionController::class, 'pendingApprovallistreject'])->name('pending.approval.list.reject');
    Route::get('pending/approval/list/reject/check/{id}', [RequisitionController::class, 'pendingApprovallistrejectCheck'])->name('pending.approval.list.reject.check');
    Route::get('pending/approval/approved/list', [RequisitionController::class, 'pendingApprovalApproveList'])->name('pending.approval.approved.list');
    Route::get('pending/approval/approved/list/view{id}', [RequisitionController::class, 'pendingApprovalApproveListView'])->name('pending.approval.approved.list.view');
    Route::get('pending/approval/reject/list', [RequisitionController::class, 'pendingApprovalRejectlist'])->name('pending.approval.reject.list');
    Route::get('pending/approval/reject/list/view{id}', [RequisitionController::class, 'pendingApprovalRejectlistView'])->name('pending.approval.reject.list.view');
    Route::post('/pending/purcahse/requisition/reject/{id}', [RequisitionController::class, 'pendingPurchaseReject'])->name('pending.purcahse.requisition.reject');

    //Delivery  & modal open view 
    Route::get('/requisition/deliveryy/{id}', [RequisitionController::class, 'delivery'])->name('requisition.deliveryy');

    //modal open view
    Route::get('/requisition/deliveryy/check/{id}', [RequisitionController::class, 'deliveryCheck'])->name('requisition.deliveryy.check');

    //Pending List Requisition & Stock
    Route::get('/requisition/pending-list', [RequisitionController::class, 'pendingList'])->name('requisition.pending-list');
    Route::get('/requisition/pending-list/view/{id}', [RequisitionController::class, 'pendingListView'])->name('requisition.pending-list.view');
    Route::get('/requisition/pending-list/instock/{id}', [RequisitionController::class, 'pendingListInstock'])->name('requisition.pending-list.instock');

    //Purchase List Requisition
    Route::get('/purcahse/requisition', [RequisitionController::class, 'purchaseRequisition'])->name('purcahse.requisition');
    Route::get('/purcahse/requisition/view/{id}', [RequisitionController::class, 'purchaseRequisitionView'])->name('purcahse.requisition.view'); 
    Route::get('/purcahse/requisition/pdf/{id}', [RequisitionController::class, 'purchaseRequisitionPdf'])->name('purcahse.requisition.pdf'); 

    //Accept Delivery
    Route::get('/purcahse/requisition/acceptdelivery/{id}', [RequisitionController::class, 'updateAcceptdelivery'])->name('purcahse.requisition.acceptdelivery');

    //Stock
    Route::get('/stock', [RequisitionController::class, 'stockList'])->name('stock.list');
    Route::get('/stock/view/{branch_id}', [RequisitionController::class, 'stockView'])->name('stock.view');

    //Add Stock
    Route::post('/stock/add', [RequisitionController::class, 'addStock'])->name('stock.add');

    Route::get('expense/product/list', [ProductExpenseController::class, 'expenseList'])->name('product.expense.list');
    Route::get('expense/product/entry', [ProductExpenseController::class, 'expenseEntry'])->name('product.expense.entry');
    Route::post('expense/product/store', [ProductExpenseController::class, 'expenseStore'])->name('product.expense.store');

    //friday last new add this route
    Route::post('damage/return/check/stock', [ProductExpenseController::class, 'damageReturnCheckStock'])->name('damage.return.check.stock');

    Route::post('/expenses/store/single', [ProductExpenseController::class, 'expenseStoresingle'])->name('expenses.store.single');
    Route::post('/damage-reurn/store/single', [ProductReturnController::class, 'damageReturnStore'])->name('damage.return.store.single');

    //Headoffice & Branch
    Route::get('returnproduct/cancel/{id}', [ProductReturnController::class, 'cancel'])->name('cancel.returnproduct');
    //Warehouse
    Route::get('warehouse/returnproduct/cancel/{id}', [ProductReturnController::class, 'cancelWarehouse'])->name('cancel.returnproduct.warehouse');

    //Headoffice & Branch
    Route::post('returnproduct/accept/{id}', [ProductReturnController::class, 'accept'])->name('accept.returnproduct');
    //Warehouse
    Route::post('warehouse/returnproduct/accept/{id}', [ProductReturnController::class, 'acceptWarehouse'])->name('accept.returnproduct.warehouse');

    //Branch & Headoffice
    Route::post('/returnproduct/deny', [ProductReturnController::class, 'denyReturnProduct'])->name('deny.returnproduct');
    //Warehouse
    Route::post('/warehouse/returnproduct/deny', [ProductReturnController::class, 'denyReturnProductWarehouse'])->name('deny.returnproduct.warehouse');

    //Return notification update
    Route::get('/return-product-page', [ProductReturnController::class, 'handleReturnProductPage'])->name('return.product.page');

    //before damage/return check stock with branch product table and Product return table
    Route::post('/damage-return/check', [ProductReturnController::class, 'checkDamageReturnQuantity'])->name('damage.return.check');

    //before expense check stock with branch product table and Product return table
    Route::post('/check-expense-stock', [ProductExpenseController::class, 'checkExpenseStock'])->name('check.expense.stock');

    //multiple expense 
    Route::get('/fetch-product-data', [ProductExpenseController::class, 'fetchProductData']);

    //Brnach & Headoffice
    Route::get('/damage-return/list', [ProductReturnController::class, 'damageReturnList'])->name('damage.return.list'); 

    //Warehouse 
    Route::get('/warehouse/damage-return/list', [ProductReturnController::class, 'damageReturnListWarehouse'])->name('warehouse.damage.return.list');

    Route::get('report/stock-in-out', [ProductExpenseController::class, 'stockInOutReport'])->name('report.stock.in.out');
    Route::get('report/product-ledger', [ProductExpenseController::class, 'productLedger'])->name('report.product.ledger');
    Route::get('report/store-product-ledger', [ProductExpenseController::class, 'storeproductLedger'])->name('report.store.product.ledger');
    Route::get('/products-ledger', [ProductExpenseController::class, 'BranchHeadofcproductLedger'])->name('product.ledger');
    Route::get('report/receipt-payment-statement', [ReportController::class, 'receiptPaymentStatement'])->name('report.receipt.payment.statement');
    Route::get('/branch-products/{branchId}', [ProductExpenseController::class, 'getBranchProducts']);

    Route::get('invoice/{id}/duplicate', [InvoiceController::class, 'duplicate'])->name('invoice.duplicate');
    Route::get('invoice/{id}/shipping/print', [InvoiceController::class, 'shippingDisplay'])->name('invoice.shipping.print');
    Route::get('invoice/{id}/payment/reminder', [InvoiceController::class, 'paymentReminder'])->name('invoice.payment.reminder');
    Route::get('invoice/index', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::post('invoice/product/destroy', [InvoiceController::class, 'productDestroy'])->name('invoice.product.destroy');
    Route::post('invoice/product', [InvoiceController::class, 'product'])->name('invoice.product');
    Route::post('invoice/customer', [InvoiceController::class, 'customer'])->name('invoice.customer');
    Route::get('invoice/{id}/sent', [InvoiceController::class, 'sent'])->name('invoice.sent');
    Route::get('invoice/{id}/resent', [InvoiceController::class, 'resent'])->name('invoice.resent');
    Route::get('invoice/{id}/payment', [InvoiceController::class, 'payment'])->name('invoice.payment');
    Route::post('invoice/{id}/payment', [InvoiceController::class, 'createPayment'])->name('invoice.payment');
    Route::post('invoice/{id}/payment/{pid}/destroy', [InvoiceController::class, 'paymentDestroy'])->name('invoice.payment.destroy');
    Route::get('invoice/items', [InvoiceController::class, 'items'])->name('invoice.items');
    Route::resource('invoice', InvoiceController::class);
    Route::get('invoice/create/{cid}', [InvoiceController::class, 'create'])->name('invoice.create');
    Route::get('invoice/pdf/{id}', [InvoiceController::class, 'invoice'])->name('invoice.pdf');
    Route::get('revenue/index', [RevenueController::class, 'index'])->name('revenue.index');
    Route::resource('revenue', RevenueController::class);
    Route::get('credit-note', [CreditNoteController::class, 'index'])->name('credit.note');
    Route::get('custom-credit-note', [CreditNoteController::class, 'customCreate'])->name('invoice.custom.credit.note');
    Route::post('custom-credit-note', [CreditNoteController::class, 'customStore'])->name('invoice.custom.credit.note');
    Route::get('credit-note/invoice', [CreditNoteController::class, 'getinvoice'])->name('invoice.get');
    Route::get('invoice/{id}/credit-note', [CreditNoteController::class, 'create'])->name('invoice.credit.note');
    Route::post('invoice/{id}/credit-note', [CreditNoteController::class, 'store'])->name('invoice.credit.note');
    Route::get('invoice/{id}/credit-note/edit/{cn_id}', [CreditNoteController::class, 'edit'])->name('invoice.edit.credit.note');
    Route::post('invoice/{id}/credit-note/edit/{cn_id}', [CreditNoteController::class, 'update'])->name('invoice.edit.credit.note');
    Route::delete('invoice/{id}/credit-note/delete/{cn_id}', [CreditNoteController::class, 'destroy'])->name('invoice.delete.credit.note');
    Route::get('vender/{id}/show', [VenderController::class, 'show'])->name('vender.show');
    Route::resource('vender', VenderController::class);
    Route::get('bill/{id}/duplicate', [BillController::class, 'duplicate'])->name('bill.duplicate');
    Route::get('bill/{id}/shipping/print', [BillController::class, 'shippingDisplay'])->name('bill.shipping.print');
    Route::get('bill/index', [BillController::class, 'index'])->name('bill.index');
    Route::post('bill/product/destroy', [BillController::class, 'productDestroy'])->name('bill.product.destroy');
    Route::post('bill/product', [BillController::class, 'product'])->name('bill.product');
    Route::post('bill/vender', [BillController::class, 'vender'])->name('bill.vender');
    Route::get('bill/{id}/sent', [BillController::class, 'sent'])->name('bill.sent');
    Route::get('bill/{id}/resent', [BillController::class, 'resent'])->name('bill.resent');
    Route::get('bill/{id}/payment', [BillController::class, 'payment'])->name('bill.payment');
    Route::post('bill/{id}/payment', [BillController::class, 'createPayment'])->name('bill.payment');
    Route::post('bill/{id}/payment/{pid}/destroy', [BillController::class, 'paymentDestroy'])->name('bill.payment.destroy');
    Route::get('bill/items', [BillController::class, 'items'])->name('bill.items');
    Route::resource('bill', BillController::class);
    Route::get('bill/create/{cid}', [BillController::class, 'create'])->name('bill.create');
    Route::get('/vender/bill/{id}/', [BillController::class, 'invoiceLink'])->name('bill.link.copy');

    Route::get('expense/index', [ExpenseController::class, 'index'])->name('expense.index');
    Route::any('expense/customer', [ExpenseController::class, 'customer'])->name('expense.customer');
    Route::post('expense/vender', [ExpenseController::class, 'vender'])->name('expense.vender');
    Route::post('expense/employee', [ExpenseController::class, 'employee'])->name('expense.employee');
    Route::post('expense/product/destroy', [ExpenseController::class, 'productDestroy'])->name('expense.product.destroy');
    Route::post('expense/product', [ExpenseController::class, 'product'])->name('expense.product');
    Route::get('expense/{id}/payment', [ExpenseController::class, 'payment'])->name('expense.payment');
    Route::get('expense/items', [ExpenseController::class, 'items'])->name('expense.items');
    Route::resource('expense', ExpenseController::class);
    Route::get('expense/create/{cid}', [ExpenseController::class, 'create'])->name('expense.create');
    Route::get('payment/index', [PaymentController::class, 'index'])->name('payment.index');
    Route::resource('payment', PaymentController::class);
    Route::get('debit-note', [DebitNoteController::class, 'index'])->name('debit.note');
    Route::get('custom-debit-note', [DebitNoteController::class, 'customCreate'])->name('bill.custom.debit.note');
    Route::post('custom-debit-note', [DebitNoteController::class, 'customStore'])->name('bill.custom.debit.note');
    Route::get('debit-note/bill', [DebitNoteController::class, 'getbill'])->name('bill.get');
    Route::get('bill/{id}/debit-note', [DebitNoteController::class, 'create'])->name('bill.debit.note');
    Route::post('bill/{id}/debit-note', [DebitNoteController::class, 'store'])->name('bill.debit.note');
    Route::get('bill/{id}/debit-note/edit/{cn_id}', [DebitNoteController::class, 'edit'])->name('bill.edit.debit.note');
    Route::post('bill/{id}/debit-note/edit/{cn_id}', [DebitNoteController::class, 'update'])->name('bill.edit.debit.note');
    Route::delete('bill/{id}/debit-note/delete/{cn_id}', [DebitNoteController::class, 'destroy'])->name('bill.delete.debit.note');
    Route::resource('chart-of-account', ChartOfAccountController::class);
    Route::resource('product_account_map', ProductAccountMapController ::class);
    Route::post('chart-of-account/subtype', [ChartOfAccountController::class, 'getSubType'])->name('charofAccount.subType');
    Route::post('journal-entry/account/destroy', [JournalEntryController::class, 'accountDestroy'])->name('journal.account.destroy');
    Route::delete('journal-entry/journal/destroy/{item_id}', [JournalEntryController::class, 'journalDestroy'])->name('journal.destroy');
    Route::resource('journal-entry', JournalEntryController::class);
    Route::get('journal-entry', [JournalEntryController::class,'index'])->name('journal-entry.index');
    // Route::get('journal-entry/crete', [JournalEntryController::class,'create'])->name('journal-entry.create');
    Route::get('journal-entry/create', [JournalEntryController::class,'create'])->name('journal-entry.create');

    Route::resource('budget', BudgetController::class);
    Route::resource('post', PostController::class);
    Route::resource('goal', GoalController::class);
    Route::resource('taxes', TaxController::class);
    Route::resource('custom-field', CustomFieldController::class);
    Route::resource('product-category', ProductServiceCategoryController::class);
    Route::post('product-category/getaccount', [ProductServiceCategoryController::class, 'getAccount'])->name('productServiceCategory.getaccount');

    // Reports
    Route::get('report/transaction', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('export/transaction', [TransactionController::class, 'export'])->name('transaction.export');

    Route::get('report/income-summary', [ReportController::class, 'incomeSummary'])->name('report.income.summary');
    Route::get('report/expense-summary', [ReportController::class, 'expenseSummary'])->name('report.expense.summary');
    Route::get('report/income-vs-expense-summary', [ReportController::class, 'incomeVsExpenseSummary'])->name('report.income.vs.expense.summary');
    Route::get('report/tax-summary', [ReportController::class, 'taxSummary'])->name('report.tax.summary');

    //Route::get('report/profit-loss-summary', [ReportController::class, 'profitLossSummary'])->name('report.profit.loss.summary');

    Route::get('report/invoice-summary', [ReportController::class, 'invoiceSummary'])->name('report.invoice.summary');
    Route::get('report/bill-summary', [ReportController::class, 'billSummary'])->name('report.bill.summary');
    Route::get('report/product-stock-report', [ReportController::class, 'productStock'])->name('report.product.stock.report');
    Route::post('export/productstock', [ReportController::class, 'stock_export'])->name('productstock.export');
    Route::get('report/invoice-report', [ReportController::class, 'invoiceReport'])->name('report.invoice');
    Route::get('report/account-statement-report', [ReportController::class, 'accountStatement'])->name('report.account.statement');
    // Route::get('report/balance-sheet/{view?}', [ReportController::class, 'balanceSheet'])->name('report.balance.sheet');
    Route::get('report/balance-sheet/{view?}', [ReportController::class, 'balanceSheet'])->name('report.balance.sheet');
    Route::get('report/profit-loss/{view?}', [ReportController::class, 'profitLoss'])->name('report.profit.loss');
    Route::get('report/ledger/{account?}', [ReportController::class, 'ledgerSummary'])->name('report.ledger');
    Route::get('report/trial-balance', [ReportController::class, 'trialBalanceSummary'])->name('trial.balance');
    Route::get('reports-monthly-cashflow', [ReportController::class, 'monthlyCashflow'])->name('report.monthly.cashflow');
    Route::get('reports-quarterly-cashflow', [ReportController::class, 'quarterlyCashflow'])->name('report.quarterly.cashflow');
    Route::post('export/trial-balance', [ReportController::class, 'trialBalanceExport'])->name('trial.balance.export');
    Route::post('export/balance-sheet', [ReportController::class, 'balanceSheetExport'])->name('balance.sheet.export');
    Route::post('print/balance-sheet/{view?}', [ReportController::class, 'balanceSheetPrint'])->name('balance.sheet.print');
    Route::post('print/trial-balance', [ReportController::class, 'trialBalancePrint'])->name('trial.balance.print');
    Route::post('export/profit-loss', [ReportController::class, 'profitLossExport'])->name('profit.loss.export');
    Route::post('print/profit-loss/{view?}', [ReportController::class, 'profitLossPrint'])->name('profit.loss.print');
    Route::get('report/sales', [ReportController::class, 'salesReport'])->name('report.sales');
    Route::post('export/sales', [ReportController::class, 'salesReportExport'])->name('sales.export');
    Route::post('print/sales-report', [ReportController::class, 'salesReportPrint'])->name('sales.report.print');
    Route::get('report/receivables', [ReportController::class, 'ReceivablesReport'])->name('report.receivables');
    Route::post('export/receivables', [ReportController::class, 'ReceivablesExport'])->name('receivables.export');
    Route::post('print/receivables', [ReportController::class, 'ReceivablesPrint'])->name('receivables.print');
    Route::get('report/payables', [ReportController::class, 'PayablesReport'])->name('report.payables');
    Route::post('print/payables', [ReportController::class, 'PayablesPrint'])->name('payables.print');
    // Export
    Route::get('export/accountstatement', [ReportController::class, 'export'])->name('accountstatement.export');

});