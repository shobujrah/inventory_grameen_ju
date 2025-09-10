<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BankTransferController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\VenderController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DebitNoteController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductServiceCategoryController;
use App\Http\Controllers\ReportController;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware'=>'auth'],function()
{
    Route::get('home',function()
    {
        return view('home');
    });
    Route::get('home',function()
    {
        return view('home');
    });
});

Auth::routes();
Route::group(['namespace' => 'App\Http\Controllers\Auth'],function()
{
    // ----------------------------login ------------------------------//
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
        Route::post('change/password', 'changePassword')->name('change/password');
    });

    // ----------------------------- register -------------------------//
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register','storeUser')->name('register');    
    });
});

Route::group(['namespace' => 'App\Http\Controllers'],function()
{
    // -------------------------- main dashboard ----------------------//
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->middleware('auth')->name('home');
        Route::get('user/profile/page', 'userProfile')->middleware('auth')->name('user/profile/page');
        // Route::get('teacher/dashboard', 'teacherDashboardIndex')->middleware('auth')->name('teacher/dashboard');
        // Route::get('student/dashboard', 'studentDashboardIndex')->middleware('auth')->name('student/dashboard');
    });

    // ----------------------------- user controller ---------------------//
    Route::controller(UserManagementController::class)->group(function () {
        Route::get('list/users', 'index')->middleware('auth')->name('list/users');
        Route::get('list/verify/users', 'unverifiedUsers')->middleware('auth')->name('list/verify/users'); // 1
        Route::post('change/password', 'changePassword')->name('change/password');
        Route::get('view/user/edit/{id}', 'userView')->middleware('auth');
        // Route::match(['get', 'post'], 'userUpdate')->name('user/update');
        Route::post('user/update', 'userUpdate')->name('user/update');
        Route::post('user/delete', 'userDelete')->name('user/delete');
        Route::post('user/verify', 'userVerify')->name('user/verify'); // A
        Route::get('get-users-data', 'getUsersData')->name('get-users-data'); /** get all data users */
        Route::get('get-unverified-users-data', 'getUnverifiedUsersData')->name('get-unverified-users-data'); /** get all data unverified users */ // 1
    });

    // ------------------------ setting -------------------------------//
    Route::controller(Setting::class)->group(function () {
        Route::get('setting/page', 'index')->middleware('auth')->name('setting/page');
    });

    // ------------------------ student -------------------------------// # X #
    // Route::controller(StudentController::class)->group(function () {
    //     Route::get('student/list', 'student')->middleware('auth')->name('student/list'); // list student
    //     Route::get('student/grid', 'studentGrid')->middleware('auth')->name('student/grid'); // grid student
    //     Route::get('student/add/page', 'studentAdd')->middleware('auth')->name('student/add/page'); // page student
    //     Route::post('student/add/save', 'studentSave')->name('student/add/save'); // save record student
    //     Route::get('student/edit/{id}', 'studentEdit'); // view for edit
    //     Route::post('student/update', 'studentUpdate')->name('student/update'); // update record student
    //     Route::post('student/delete', 'studentDelete')->name('student/delete'); // delete record student
    //     Route::get('student/profile/{id}', 'studentProfile')->middleware('auth'); // profile student
    // });

    // ------------------------ warehouse -------------------------------//
    Route::controller(WarehouseController::class)->group(function () {
        Route::get('warehouse/list', 'warehouse')->middleware('auth')->name('warehouse/list'); // list warehouse
        Route::get('warehouse/grid', 'warehouseGrid')->middleware('auth')->name('warehouse/grid'); // grid warehouse
        Route::get('warehouse/add/page', 'warehouseAdd')->middleware('auth')->name('warehouse/add/page'); // page warehouse
        Route::post('warehouse/add/save', 'warehouseSave')->name('warehouse/add/save'); // save record warehouse
        Route::get('warehouse/edit/{id}', 'warehouseEdit'); // view for edit
        Route::post('warehouse/update', 'warehouseUpdate')->name('warehouse/update'); // update record warehouse
        Route::post('warehouse/delete', 'warehouseDelete')->name('warehouse/delete'); // delete record warehouse
        Route::get('warehouse/view/{id}', 'warehouseView')->middleware('auth'); // view warehouse
    });

    // ------------------------ branch -------------------------------//
    Route::controller(BranchController::class)->group(function () {
        Route::get('branch/list', 'branch')->middleware('auth')->name('branch/list'); // list Branch
        Route::get('branch/grid', 'branchGrid')->middleware('auth')->name('branch/grid'); // grid Branch
        Route::get('branch/add/page', 'branchAdd')->middleware('auth')->name('branch/add/page'); // page Branch
        Route::post('branch/add/save', 'branchSave')->name('branch/add/save'); // save record Branch
        Route::get('branch/edit/{id}', 'branchEdit'); // view for edit
        Route::post('branch/update', 'branchUpdate')->name('branch/update'); // update record Branch
        Route::post('branch/delete', 'branchDelete')->name('branch/delete'); // delete record Branch
        Route::get('branch/view/{id}', 'branchView')->middleware('auth'); // view Branch
    });

    // ------------------------ teacher -------------------------------// # X #
    // Route::controller(TeacherController::class)->group(function () {
    //     Route::get('teacher/add/page', 'teacherAdd')->middleware('auth')->name('teacher/add/page'); // page teacher
    //     Route::get('teacher/list/page', 'teacherList')->middleware('auth')->name('teacher/list/page'); // page teacher
    //     Route::get('teacher/grid/page', 'teacherGrid')->middleware('auth')->name('teacher/grid/page'); // page grid teacher
    //     Route::post('teacher/save', 'saveRecord')->middleware('auth')->name('teacher/save'); // save record
    //     Route::get('teacher/edit/{user_id}', 'editRecord'); // view teacher record
    //     Route::post('teacher/update', 'updateRecordTeacher')->middleware('auth')->name('teacher/update'); // update record
    //     Route::post('teacher/delete', 'teacherDelete')->name('teacher/delete'); // delete record teacher
    // });

    // ----------------------- department -----------------------------// # X #
    // Route::controller(DepartmentController::class)->group(function () {
    //     Route::get('department/list/page', 'departmentList')->middleware('auth')->name('department/list/page'); // department/list/page
    //     Route::get('department/add/page', 'indexDepartment')->middleware('auth')->name('department/add/page'); // page add department
    //     Route::get('department/edit/{department_id}', 'editDepartment'); // page add department
    //     Route::post('department/save', 'saveRecord')->middleware('auth')->name('department/save'); // department/save
    //     Route::post('department/update', 'updateRecord')->middleware('auth')->name('department/update'); // department/update
    //     Route::post('department/delete', 'deleteRecord')->middleware('auth')->name('department/delete'); // department/delete
    //     Route::get('get-data-list', 'getDataList')->name('get-data-list'); // get data list

    // });

    // ----------------------- subject -----------------------------// # X #
    // Route::controller(SubjectController::class)->group(function () {
    //     Route::get('subject/list/page', 'subjectList')->middleware('auth')->name('subject/list/page'); // subject/list/page
    //     Route::get('subject/add/page', 'subjectAdd')->middleware('auth')->name('subject/add/page'); // subject/add/page
    //     Route::post('subject/save', 'saveRecord')->name('subject/save'); // subject/save
    //     Route::post('subject/update', 'updateRecord')->name('subject/update'); // subject/update
    //     Route::post('subject/delete', 'deleteRecord')->name('subject/delete'); // subject/delete
    //     Route::get('subject/edit/{subject_id}', 'subjectEdit'); // subject/edit/page
    // });

    // ----------------------- role -----------------------------// # main
    // Route::controller(RoleController::class)->group(function () {
    //     Route::get('role/list/page', 'roleList')->middleware('auth')->name('role/list/page'); // role/list/page
    //     Route::get('role/add/page', 'roleAdd')->middleware('auth')->name('role/add/page'); // role/add/page
    //     Route::post('role/save', 'saveRecord')->name('role/save'); // role/save
    //     Route::post('role/update', 'updateRecord')->name('role/update'); // role/update
    //     Route::post('role/delete', 'deleteRecord')->name('role/delete'); // role/delete
    //     Route::get('role/edit/{role_id}', 'roleEdit'); // role/edit/page
    // });

    // ----------------------- role -----------------------------// # custom
    Route::controller(RoleController::class)->group(function () {
        Route::get('role/list/page', 'roleList')->middleware('auth')->name('role/list/page'); // role/list/page
        Route::get('role/add/page', 'roleAdd')->middleware('auth')->name('role/add/page'); // role/add/page
        Route::post('role/save', 'saveRecord')->name('role/save'); // role/save
        // Route::post('role/update', 'updateRecord')->name('role/update'); // role/update
        Route::get('role/edit/{role_id}', 'roleEdit'); // role/edit/page
        Route::put('role/update/{role_id}', 'updateRecord')->name('role/update'); // role/update
        // Route::post('role/delete', 'deleteRecord')->name('role/delete'); // role/delete #
        Route::get('role/delete/{role_id}', 'deleteRecord'); // role/delete
        
    });
    // Route::match(['get', 'post'], 'user/update', 'UserController@update');
    // ----------------------- page -----------------------------//
    Route::controller(PagePermissionController::class)->group(function () {
        Route::get('page/list/page', 'pageList')->middleware('auth')->name('page/list/page'); // page/list/page
        Route::get('page/add/page', 'pageAdd')->middleware('auth')->name('page/add/page'); // page/add/page
        Route::post('page/save', 'saveRecord')->name('page/save'); // page/save
        Route::post('page/update', 'updateRecord')->name('page/update'); // page/update
        Route::post('page/delete', 'deleteRecord')->name('page/delete'); // page/delete
        Route::get('page/edit/{page_id}', 'pageEdit'); // page/edit/page
        Route::post('page/rolepermission', 'pagepermissionEdit')->middleware('auth')->name('page/rolepermission'); // page/rolepermission (custom added)
        Route::post('page/getPagePermission', 'getPagePermission')->name('page/getPagePermission');
    });

    // ------------------------ batch -------------------------------//
    Route::controller(BatchController::class)->group(function () {
        Route::get('batch/list', 'batch')->middleware('auth')->name('batch/list'); // list batch
        Route::get('batch/grid', 'batchGrid')->middleware('auth')->name('batch/grid'); // grid batch
        Route::get('batch/add/page', 'batchAdd')->middleware('auth')->name('batch/add/page'); // page batch
        Route::post('batch/add/save', 'batchSave')->name('batch/add/save'); // save record batch
        Route::get('batch/edit/{id}', 'batchEdit'); // view for edit
        Route::post('batch/update', 'batchUpdate')->name('batch/update'); // update record batch
        Route::post('batch/delete', 'batchDelete')->name('batch/delete'); // delete record batch
        Route::get('batch/view/{id}', 'batchView')->middleware('auth'); // view batch
    });

    // ------------------------ category -------------------------------//
    Route::controller(CategoryController::class)->group(function () {
        Route::get('category/list', 'category')->middleware('auth')->name('category/list'); // list category
        Route::get('category/grid', 'categoryGrid')->middleware('auth')->name('category/grid'); // grid category
        Route::get('category/add/page', 'categoryAdd')->middleware('auth')->name('category/add/page'); // page category
        Route::post('category/add/save', 'categorySave')->name('category/add/save'); // save record category
        Route::get('category/edit/{id}', 'categoryEdit'); // view for edit
        Route::post('category/update', 'categoryUpdate')->name('category/update'); // update record category
        Route::post('category/delete', 'categoryDelete')->name('category/delete'); // delete record category
        Route::get('category/view/{id}', 'categoryView')->middleware('auth'); // view category
    });

    // ------------------------ product -------------------------------//
    Route::controller(ProductController::class)->group(function () {
        Route::get('product/list', 'product')->middleware('auth')->name('product/list'); // list product
        Route::get('product/grid', 'productGrid')->middleware('auth')->name('product/grid'); // grid product
        Route::get('product/add/page', 'productAdd')->middleware('auth')->name('product/add/page'); // page product
        Route::post('product/add/save', 'productSave')->name('product/add/save'); // save record product
        Route::get('product/edit/{id}', 'productEdit'); // view for edit
        Route::post('product/update', 'productUpdate')->name('product/update'); // update record product
        Route::post('product/delete', 'productDelete')->name('product/delete'); // delete record product
        Route::get('product/view/{id}', 'productView')->middleware('auth'); // view product
    });

    // will test it out 31/01/24 - 12:51 PM
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
    // will test it out

    // ----------------------- invoice -----------------------------// # X #
    // Route::controller(InvoiceController::class)->group(function () {
    //     Route::get('invoice/list/page', 'invoiceList')->middleware('auth')->name('invoice/list/page'); // subjeinvoicect/list/page
    //     Route::get('invoice/paid/page', 'invoicePaid')->middleware('auth')->name('invoice/paid/page'); // invoice/paid/page
    //     Route::get('invoice/overdue/page', 'invoiceOverdue')->middleware('auth')->name('invoice/overdue/page'); // invoice/overdue/page
    //     Route::get('invoice/draft/page', 'invoiceDraft')->middleware('auth')->name('invoice/draft/page'); // invoice/draft/page
    //     Route::get('invoice/recurring/page', 'invoiceRecurring')->middleware('auth')->name('invoice/recurring/page'); // invoice/recurring/page
    //     Route::get('invoice/cancelled/page', 'invoiceCancelled')->middleware('auth')->name('invoice/cancelled/page'); // invoice/cancelled/page
    //     Route::get('invoice/grid/page', 'invoiceGrid')->middleware('auth')->name('invoice/grid/page'); // invoice/grid/page
    //     Route::get('invoice/add/page', 'invoiceAdd')->middleware('auth')->name('invoice/add/page'); // invoice/add/page
    //     Route::get('invoice/edit/page', 'invoiceEdit')->middleware('auth')->name('invoice/edit/page'); // invoice/edit/page
    //     Route::get('invoice/view/page', 'invoiceView')->middleware('auth')->name('invoice/view/page'); // invoice/view/page
    //     Route::get('invoice/settings/page', 'invoiceSettings')->middleware('auth')->name('invoice/settings/page'); // invoice/settings/page
    //     Route::get('invoice/settings/tax/page', 'invoiceSettingsTax')->middleware('auth')->name('invoice/settings/tax/page'); // invoice/settings/tax/page
    //     Route::get('invoice/settings/bank/page', 'invoiceSettingsBank')->middleware('auth')->name('invoice/settings/bank/page'); // invoice/settings/bank/page
    // });

    // ----------------------- accounts ----------------------------//
    // Route::controller(AccountsController::class)->group(function () {
    //     Route::get('account/fees/collections/page', 'index')->middleware('auth')->name('account/fees/collections/page'); // account/fees/collections/page
    // });
});

Route::resource('bank-account', BankAccountController::class);
Route::resource('bank-transfer', BankTransferController::class);
Route::resource('customer', CustomerController::class);

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
Route::post('chart-of-account/subtype', [ChartOfAccountController::class, 'getSubType'])->name('charofAccount.subType');

Route::post('journal-entry/account/destroy', [JournalEntryController::class, 'accountDestroy'])->name('journal.account.destroy');
Route::delete('journal-entry/journal/destroy/{item_id}', [JournalEntryController::class, 'journalDestroy'])->name('journal.destroy');
Route::resource('journal-entry', JournalEntryController::class);

// Route::get('journal-entry', [JournalEntryController::class,'index'])->name('journal-entry.index');
// Route::get('journal-entry/crete', [JournalEntryController::class,'create'])->name('journal-entry.create');

Route::resource('budget', BudgetController::class);

Route::resource('post', PostController::class);

Route::resource('goal', GoalController::class);

Route::resource('taxes', TaxController::class);

Route::resource('custom-field', CustomFieldController::class);

Route::resource('product-category', ProductServiceCategoryController::class);
Route::post('product-category/getaccount', [ProductServiceCategoryController::class, 'getAccount'])->name('productServiceCategory.getaccount');


// Reports


Route::get('report/income-summary', [ReportController::class, 'incomeSummary'])->name('report.income.summary');
Route::get('report/expense-summary', [ReportController::class, 'expenseSummary'])->name('report.expense.summary');
Route::get('report/income-vs-expense-summary', [ReportController::class, 'incomeVsExpenseSummary'])->name('report.income.vs.expense.summary');
Route::get('report/tax-summary', [ReportController::class, 'taxSummary'])->name('report.tax.summary');
//    Route::get('report/profit-loss-summary', [ReportController::class, 'profitLossSummary'])->name('report.profit.loss.summary');
Route::get('report/invoice-summary', [ReportController::class, 'invoiceSummary'])->name('report.invoice.summary');
Route::get('report/bill-summary', [ReportController::class, 'billSummary'])->name('report.bill.summary');
Route::get('report/product-stock-report', [ReportController::class, 'productStock'])->name('report.product.stock.report');
Route::get('report/invoice-report', [ReportController::class, 'invoiceReport'])->name('report.invoice');
Route::get('report/account-statement-report', [ReportController::class, 'accountStatement'])->name('report.account.statement');
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