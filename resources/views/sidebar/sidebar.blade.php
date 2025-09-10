@php
    $usr = Auth::guard('user')->user();
@endphp
<div class="sidebar" id="sidebar" style="background-color: #341f97;">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="main-menu">

                <li class="{{ set_active(['home', 'teacher/dashboard', 'student/dashboard']) }}">
                    <a href="{{ route('home') }}">
                    <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li
                    class="submenu {{ set_active(['bank-account', 'bank-transfer', 'customer', 'proposal', 'invoice', 'revenue', 'credit-note']) }} {{ request()->is('bank-account/*') ? 'active' : '' }} {{ request()->is('bank-transfer/*') ? 'active' : '' }} {{ request()->is('customer/*') ? 'active' : '' }} {{ request()->is('proposal/*') ? 'active' : '' }} {{ request()->is('invoice/*') ? 'active' : '' }} {{ request()->is('revenue/*') ? 'active' : '' }} {{ request()->is('credit-note/*') ? 'active' : '' }} ">
                    <a href="#">
                        <i class="fas fa-money-check-alt"></i>
                        <span>Accounting System</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="deepsub-menu">
                        <!-- <li
                            class="submenu {{ set_active(['bank-account', 'bank-transfer']) }} {{ request()->is('bank-account/*') ? 'active' : '' }} {{ request()->is('bank-transfer/*') ? 'active' : '' }}">
                            <a href="#">
                                <span>Banking</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('bank-account.index') }}"
                                        class="{{ set_active(['bank-account']) }} {{ request()->is('bank-account/*') ? 'active' : '' }}">Account</a>
                                </li>
                                <li><a href="{{ route('bank-transfer.index') }}"
                                        class="{{ set_active(['bank-transfer']) }} {{ request()->is('bank-transfer/*') ? 'active' : '' }}">Transfer</a>
                                </li>
                            </ul>
                        </li> -->

                        <!-- <li
                            class="submenu {{ set_active(['customer', 'proposal', 'invoice', 'revenue', 'credit-note']) }} {{ request()->is('customer/*') ? 'active' : '' }} {{ request()->is('proposal/*') ? 'active' : '' }} {{ request()->is('invoice/*') ? 'active' : '' }} {{ request()->is('revenue/*') ? 'active' : '' }} {{ request()->is('credit-note/*') ? 'active' : '' }}">
                            <a href="#">
                                <span>Sales</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('customer.index') }}"
                                        class="{{ set_active(['customer']) }} {{ request()->is('customer/*') ? 'active' : '' }}">Customer</a>
                                </li>
                                <li><a href="{{ route('proposal.index') }}"
                                        class="{{ set_active(['proposal']) }} {{ request()->is('proposal/*') ? 'active' : '' }}">Proposal</a>
                                </li>
                                <li><a href="{{ route('invoice.index') }}"
                                        class="{{ set_active(['invoice']) }} {{ request()->is('invoice/*') ? 'active' : '' }}">Invoice</a>
                                </li>
                                <li><a href="{{ route('revenue.index') }}"
                                        class="{{ set_active(['revenue']) }} {{ request()->is('revenue/*') ? 'active' : '' }}">Revenue</a>
                                </li>
                                <li><a href="{{ route('credit.note') }}"
                                        class="{{ set_active(['credit-note']) }} {{ request()->is('credit-note/*') ? 'active' : '' }}">Credit
                                        Note</a></li>
                            </ul>
                        </li> -->

                        <!-- <li
                            class="submenu {{ set_active(['vender', 'bill', 'expense', 'payment', 'debit-note']) }} {{ request()->is('vender/*') ? 'active' : '' }} {{ request()->is('bill/*') ? 'active' : '' }} {{ request()->is('expense/*') ? 'active' : '' }} {{ request()->is('payment/*') ? 'active' : '' }} {{ request()->is('debit-note/*') ? 'active' : '' }}">
                            <a href="#">
                                <span>Purchases</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('vender.index') }}"
                                        class="{{ set_active(['vender']) }} {{ request()->is('vender/*') ? 'active' : '' }}">Supplier</a>
                                </li>
                                <li><a href="{{ route('bill.index') }}"
                                        class="{{ set_active(['bill']) }} {{ request()->is('bill/*') ? 'active' : '' }}">Bill</a>
                                </li>
                                <li><a href="{{ route('expense.index') }}"
                                        class="{{ set_active(['expense']) }} {{ request()->is('expense/*') ? 'active' : '' }}">Expense</a>
                                </li>
                                <li><a href="{{ route('payment.index') }}"
                                        class="{{ set_active(['payment']) }} {{ request()->is('payment/*') ? 'active' : '' }}">Payment</a>
                                </li>
                                <li><a href="{{ route('debit.note') }}"
                                        class="{{ set_active(['debit-note']) }} {{ request()->is('debit-note/*') ? 'active' : '' }}">Debit
                                        Note</a></li>
                            </ul>
                        </li> -->

                        <li>
                            <a href="{{ route('chart-of-account.index') }}"
                                class="{{ set_active(['chart-of-account']) }} {{ request()->is('chart-of-account/*') ? 'active' : '' }}">Chart
                                of Accounts</a>
                        </li> 

                        <li>
                            <a href="{{ route('product_account_map.index') }}"
                                class="{{ set_active(['product_account_map']) }} {{ request()->is('product_account_map/*') ? 'active' : '' }}">Product
                                Account Map</a>
                        </li>
                        

                        <li
                            class="submenu {{ set_active(['journal-entry']) }} {{ request()->is('journal-entry/*') ? 'active' : '' }}">
                            <a href="#">
                                <span>Double Entry</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('journal-entry.index') }}"
                                        class="{{ set_active(['journal-entry']) }} {{ request()->is('journal-entry/*') ? 'active' : '' }}">Journal
                                        Account</a></li>
                            </ul>
                        </li>

                        <!-- <li>
                            <a href="{{ route('budget.index') }}"
                                class="{{ set_active(['budget']) }} {{ request()->is('budget/*') ? 'active' : '' }}">Budget
                                Planner</a>
                        </li>

                        <li>
                            <a href="{{ route('goal.index') }}"
                                class="{{ set_active(['goal']) }} {{ request()->is('goal/*') ? 'active' : '' }}">Financial
                                Goal</a>
                        </li> -->

                        <li
                            class="submenu {{ set_active(['report']) }} {{ request()->is('report/*') ? 'active' : '' }}">
                            <a href="#">
                                <span>Reports</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <!-- <li><a href="{{ route('report.account.statement') }}"
                                        class="{{ set_active(['report/account-statement-report']) }} {{ request()->is('report/account-statement-report/*') ? 'active' : '' }}">Account
                                        Statement</a></li>
                                <li><a href="{{ route('report.invoice.summary') }}"
                                        class="{{ set_active(['report/invoice-summary']) }} {{ request()->is('report/invoice-summary/*') ? 'active' : '' }}">Invoice
                                        Summary</a></li> -->
                                <li><a href="{{ route('report.ledger') }}"
                                        class="{{ set_active(['report/ledger']) }} {{ request()->is('report/ledger/*') ? 'active' : '' }}">Ledger
                                        Summary</a></li>
                                <li><a href="{{ route('report.balance.sheet') }}"
                                        class="{{ set_active(['report/balance-sheet']) }} {{ request()->is('report/balance-sheet/*') ? 'active' : '' }}">Balance
                                        Sheet</a></li>
                                <li><a href="{{ route('report.profit.loss') }}"
                                        class="{{ set_active(['report/profit-loss']) }} {{ request()->is('report/profit-loss/*') ? 'active' : '' }}">Profit
                                        & Loss</a></li>


                                <li><a href="{{ route('report.store.product.ledger') }}" class="{{ request()->is('report/store-product-ledger') ? 'active' : '' }}">Ledger Report</a></li>
                                <li><a href="{{ route('report.receipt.payment.statement') }}" class="{{ request()->is('report/receipt-payment-statement') ? 'active' : '' }}">Receipt & Payment Statement Report</a></li>


                                <li><a href="{{ route('trial.balance') }}"
                                        class="{{ set_active(['report/trial-balance']) }} {{ request()->is('report/trial-balance/*') ? 'active' : '' }}">Trial
                                        Balance</a></li>
                                <!-- <li><a href="{{ route('report.sales') }}"
                                        class="{{ set_active(['report/sales']) }} {{ request()->is('report/sales/*') ? 'active' : '' }}">
                                        Sales Report</a></li> -->
                                <!-- <li><a href="{{ route('report.receivables') }}"
                                        class="{{ set_active(['report/receivables']) }} {{ request()->is('report/receivables/*') ? 'active' : '' }}">Receivables
                                        Report</a></li>
                                <li><a href="{{ route('report.payables') }}"
                                        class="{{ set_active(['report/payables']) }} {{ request()->is('report/payables/*') ? 'active' : '' }}">Payables</a>
                                </li>
                                <li><a href="{{ route('report.bill.summary') }}"
                                        class="{{ set_active(['report/bill-summary']) }} {{ request()->is('report/bill-summary/*') ? 'active' : '' }}">Bill
                                        Summary</a></li>
                                <li><a href="{{ route('report.product.stock.report') }}"
                                        class="{{ set_active(['report/product-stock-report']) }} {{ request()->is('report/product-stock-report/*') ? 'active' : '' }}">Product
                                        Stock</a></li> -->

                                <!-- <li><a href="{{ route('report.monthly.cashflow') }}"
                                        class="{{ set_active(['report/monthly-cashflow']) }} {{ request()->is('report/monthly-cashflow/*') ? 'active' : '' }}">Cash
                                        Flow</a></li> -->

                                        <!-- <li>
                                            <a href="{{ route('report.monthly.cashflow') }}" 
                                            class="{{ request()->is('reports-monthly-cashflow') ? 'active' : '' }}">Cash Flow</a>
                                        </li> -->

                                <!-- <li><a href="{{ route('transaction.index') }}"
                                        class="{{ set_active(['report/transaction']) }} {{ request()->is('report/transaction/*') ? 'active' : '' }}">Transaction</a>
                                </li> -->
                                <!-- <li><a href="{{ route('report.income.summary') }}"
                                        class="{{ set_active(['report/income-summary']) }} {{ request()->is('report/income-summary/*') ? 'active' : '' }}">Income
                                        Summary</a></li>
                                <li><a href="{{ route('report.expense.summary') }}"
                                        class="{{ set_active(['report/expense-summary']) }} {{ request()->is('report/expense-summary/*') ? 'active' : '' }}">Expense
                                        Summary</a></li>
                                <li><a href="{{ route('report.income.vs.expense.summary') }}"
                                        class="{{ set_active(['report/income-vs-expense-summary']) }} {{ request()->is('report/income-vs-expense-summary/*') ? 'active' : '' }}">Income
                                        VS Expense</a></li> -->

                                <!-- tax rest of work  -->
                                <!-- <li><a href="{{ route('report.tax.summary') }}" class="{{ set_active(['report/tax-summary']) }} {{ request()->is('report/tax-summary/*') ? 'active' : '' }}">Tax Summary</a></li> -->
                            </ul>
                        </li>

                        <!-- <li>
                            <a href="{{ route('taxes.index') }}"
                                class="{{ set_active(['taxes', 'product-category', 'custom-field']) }} {{ request()->is('taxes/*') ? 'active' : '' }} {{ request()->is('product-category/*') ? 'active' : '' }} {{ request()->is('custom-field/*') ? 'active' : '' }}">Accounting
                                Setup</a>
                        </li> -->

                    </ul>
                </li>


               
                @canany(['View Unverify User', 'Edit Unverify User', 'Delete Unverify User', 'View Verify User', 'Edit Verify User'])
                    <li
                        class="submenu {{ set_active(['list/users', 'unverify/user/list']) }} {{ request()->is('view/user/edit/*') ? 'active' : '' }} {{ request()->is('unverify/user/edit/*') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fas fa-shield-alt"></i>
                            <span>User Management</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                        @can(['View Unverify User', 'Edit Unverify User'])
                            <li><a href="{{ route('unverify.user.list') }}"
                                class="{{ set_active(['unverify/user/list']) }} {{ request()->is('unverify/user/list/*') ? 'active' : '' }}">Unverify User</a></li>
                        @endcan

                        @can(['View Verify User', 'Edit Verify User'])
                            <li><a href="{{ route('list/users') }}"
                                class="{{ set_active(['list/users']) }} {{ request()->is('list/users/*') ? 'active' : '' }}">Verified User</a></li>
                        @endcan
                        </ul>
                    </li>
                @endcanany



                @canany(['role.create', 'role.view', 'role.edit', 'role.delete'])
                <li
                    class="submenu {{ set_active(['role/list/page', 'role/add/page']) }} {{ request()->is('role/edit/*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-book-reader"></i>
                        <span>Role</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                    @can(['role.create', 'role.view', 'role.edit', 'role.delete'])
                        <li><a class="{{ set_active(['role/list/page']) }} {{ request()->is('role/edit/*') ? 'active' : '' }}"
                                href="{{ route('role/list/page') }}">List</a></li>
                    @endcan

                    @can('role.create')
                        <li><a class="{{ set_active(['role/add/page']) }}" href="{{ route('role/add/page') }}">Add</a></li>
                    @endcan
                    </ul>
                </li>
                @endcanany

                

                @canany(['Create Requisition', 'View Requisition', 'Edit Requisition', 'Delete Requisition'])
                    <li class="submenu {{ set_active(['requisition/list', 'requisition/create']) }} {{ request()->is('requisition/list/view/*') ? 'active' : '' }} {{ request()->is('requisition/list/edit/*') ? 'active' : '' }}">
                        <a href="#"><i class="fab fa-wpforms" style="font-weight: bold;"></i>
                            <span>Requisition</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>

                        @if(auth()->user()->role_name != 'Admin') 
                            @can('Create Requisition')
                                <li><a href="{{ route('requisition.createreq') }}" class="{{ request()->is('requisition/create') ? 'active' : '' }}">Create</a></li>
                            @endcan
                        @endif

                        @can(['Create Requisition', 'View Requisition', 'Edit Requisition', 'Delete Requisition'])
                         
                        <li>
                            <a href="{{ route('requisition.list') }}" 
                            class="{{ request()->is('requisition/list') || request()->is('requisition/list/view/*') || request()->is('requisition/list/edit/*') ? 'active' : '' }}">
                            List
                            </a>
                        </li>
                        
                        @endcan
                        </ul>
                    </li>
                @endcanany





                @can('List')
                    <li class="submenu {{ set_active(['completed/order/list']) }} {{ request()->is('completed/order/list/view/*') ? 'active' : '' }} ">
                        <a href="#"><i class="fab fa-wpforms" style="font-weight: bold;"></i>
                            <span>Completed Order</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('completed.order.list') }}" class="{{ request()->is('completed/order/list') || request()->is('completed/order/list/view/*') ? 'active' : '' }}">List</a></li>
                        </ul>
                    </li>
                @endcan



                @can(['List Approval', 'Accept List', 'Reject List'])

                    <li class="submenu {{ request()->is('pending/approval/list*') ? 'active' : '' }}">
                        <a href="#"><i class="fab fa-wpforms" style="font-weight: bold;"></i>
                            <span>Approval</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('pending.approval.list') }}" 
                                class="{{ request()->routeIs('pending.approval.list', 'pending.approval.list.view') ? 'active' : '' }}">
                                Pending
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pending.approval.approved.list') }}" 
                                class="{{ request()->routeIs('pending.approval.approved.list', 'pending.approval.approved.list.view') ? 'active' : '' }}">
                                Approved
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pending.approval.reject.list') }}" 
                                class="{{ request()->routeIs('pending.approval.reject.list', 'pending.approval.reject.list.view') ? 'active' : '' }}">
                                Reject
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan 


                
                <!-- @can('List of Pending Purchase')
                    <li class="submenu {{ set_active(['pending/purcahse/requisition']) }} {{ request()->is('pending/purcahse/requisition/*') ? 'active' : '' }}">
                        <a href="#"><i class="fab fa-wpforms" style="font-weight: bold;"></i>
                            <span>Pending Purchase </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('pending.purcahse.requisition') }}" class="{{ request()->is('pending/purcahse/requisition') ? 'active' : '' }}">Pending Purchase</a></li>
                        </ul>
                    </li>
                @endcan -->


                
                @can('Order List')
                    <li class="submenu {{ set_active(['order/list', 'product/demand']) }} {{ request()->is('product/demand/*') ? 'active' : '' }} {{ request()->is('order/list/*') ? 'active' : '' }} {{ request()->is('order/list/view*') ? 'active' : '' }}">
                        <a href="#"><i class="fab fa-wpforms" style="font-weight: bold;"></i>
                            <span>Order Request</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('order.list') }}" class="{{ request()->is('order/list') || request()->is('order/list/view*') || request()->is('order/list/edit*') ? 'active' : '' }}">List</a></li> 
                            <li><a href="{{ route('product.demand') }}" class="{{ request()->is('product/demand') || request()->is('product/demand*') ? 'active' : '' }}">Product Demand List</a></li>
                        </ul>
                    </li>


                    <li class="submenu {{ set_active(['purchase/list']) }} {{ request()->is('purchase/list/*') ? 'active' : '' }}">
                        <a href="#"><i class="fab fa-wpforms" style="font-weight: bold;"></i>
                            <span>Purchase List</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('purchase.list') }}" class="{{ request()->is('purchase/list') || request()->is('purchase/list/view*') ? 'active' : '' }}">List</a></li>
                        </ul>
                    </li>


                    <!-- <li class="submenu {{ set_active(['reject/list']) }} {{ request()->is('reject/list/*') ? 'active' : '' }}">
                        <a href="#"><i class="fab fa-wpforms" style="font-weight: bold;"></i>
                            <span>Reject List</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('reject.list') }}" class="{{ request()->is('reject/list') || request()->is('reject/list/view*') ? 'active' : '' }}">List</a></li>
                        </ul>
                    </li> -->


                    <li class="submenu">
                        <a href="#"><i class="fab fa-wpforms" style="font-weight: bold;"></i>
                            <span>Reject List</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('reject.list') }}" class="{{ request()->is('reject/list') || request()->is('reject/list/view*') ? 'active' : '' }}">List</a></li>
                        </ul>
                    </li>

                @endcan






                

                @can('Stock List and Details')
                    <li class="submenu {{ set_active(['stock']) }} {{ request()->is('stock/*') ? 'active' : '' }} {{ request()->is('stock/view/*') ? 'active' : '' }}" >
                        <a href="#"><i class="fas fa-store" aria-hidden="true"></i>
                            <span>Stock </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('stock.list') }}" class="{{ request()->is('stock') || request()->is('stock/view/*') ? 'active' : '' }}">List</a></li>
                        </ul>
                    </li>
                @endcan 





                @canany(['Pending Purchase', 'Purchase Collection', 'Reject Collection', 'Approved List', 'Reject List'])
                    @can('Pending Purchase')
                    
                    
                    <li class="submenu {{ set_active(['pending/purcahse/requisition']) }} {{ request()->is('pending/purcahse/requisition/*') ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-wallet" aria-hidden="true"></i>
                            <span>Pending Purchase</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('pending.purcahse.requisition') }}" 
                                class="{{ request()->is('pending/purcahse/requisition') || request()->is('pending/purcahse/requisition/view*') ? 'active' : '' }}">
                                    List
                                </a>
                            </li>
                        </ul>
                    </li>


                    @endcan

                    @can('Purchase Collection')
                        <li class="submenu {{ set_active(['purchasee/collection/list']) }} {{ request()->is('purchasee/collection/list/*') ? 'active' : '' }}">
                            <a href="#"><i class="fas fa-wallet" aria-hidden="true"></i>
                                <span>Purchase Collection</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('purchasee.collection.list') }}" class="{{ request()->is('purchasee/collection/list') || request()->is('purchasee/collection/list/view*') ? 'active' : '' }}">List</a></li>
                            </ul>
                        </li>
                    @endcan




                    @can('Reject Collection')

                    <li class="submenu">
                        <a href="#"><i class="fab fa-wpforms" style="font-weight: bold;"></i>
                            <span>Reject Collection</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('reject.list.collection') }}" class="{{ request()->routeIs('reject.list.collection') || request()->routeIs('reject.list.collection.view*') ? 'active' : '' }}">List</a></li>
                        </ul>
                    </li>  


                    <!-- <li class="submenu {{ set_active(['reject/list/collection']) }} {{ request()->is('reject/list/collection/*') ? 'active' : '' }}">
                        <a href="#"><i class="fab fa-wpforms" style="font-weight: bold;"></i>
                            <span>Reject Collection</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('reject.list.collection') }}" class="{{ request()->is('reject/list/collection') ? 'active' : '' }}">List</a></li>
                        </ul>
                    </li> -->
                    @endcan




                   
                    @can(['Approved List', 'Approved List'])   

                    <li class="submenu 
                        {{ set_active(['pending/approval/list', 'pending/approval/approved/list', 'pending/approval/approved/list/view*', 'pending/approval/reject/list/view*']) }} 
                        {{ request()->is('pending/approval/list/*') ? 'active' : '' }}">
                        <a href="#"><i class="fab fa-wpforms" style="font-weight: bold;"></i>
                            <span>Purchase Approvals</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('pending.approval.approved.list') }}" 
                                class="{{ request()->is('pending/approval/approved/list') || request()->is('pending/approval/approved/list/view*') ? 'active' : '' }}">
                                    Approved List
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pending.approval.reject.list') }}" 
                                class="{{ request()->is('pending/approval/reject/list') || request()->is('pending/approval/reject/list/view*') ? 'active' : '' }}">
                                    Reject List
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan

                    <!-- @if(auth()->check() && auth()->user()->role_name == 'PurchaseTeam')
                        <li>
                            <a href="{{ route('warehouse.damage.return.list') }}" class="{{ request()->is('warehouse/damage-return/list') ? 'active' : '' }}">
                                Warehouse Return List
                            </a>
                        </li>
                    @endif -->

                   @if(auth()->check() && auth()->user()->role_name == 'PurchaseTeam')
                        <li class="{{ request()->is('warehouse/damage-return/list') ? 'active' : '' }}">
                            <a href="{{ route('warehouse.damage.return.list') }}" class="{{ request()->is('warehouse/damage-return/list') ? 'active' : '' }}">
                                <i class="fas fa-undo-alt"></i> Warehouse Return List
                            </a>
                        </li>
                    @endif

                    
                @endcanany




                @can(['Expense Entry', 'Expense List'])
                    <li class="submenu {{ set_active(['product.expense.entry', 'product.expense.list']) }} {{ request()->is('expense-list*') ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-wallet" aria-hidden="true"></i>
                            <span>Expense</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @if(auth()->user()->role_name != 'Admin') 
                                <li><a href="{{ route('product.expense.entry') }}" class="{{ request()->routeIs('product.expense.entry') ? 'active' : '' }}">Entry</a></li>
                            @endif
                            <li><a href="{{ route('product.expense.list') }}" class="{{ request()->routeIs('product.expense.list') ? 'active' : '' }}">List</a></li>
                        </ul>
                    </li>
                @endcan 


                    @can('Damage/Return List')
                      <li class="submenu {{ set_active(['damage-return/list', 'damage-return/list/*', 'warehouse/damage-return/list', 'warehouse/damage-return/list/*']) }} {{ request()->is('damage-return/list/*') ? 'active' : '' }} {{ request()->is('warehouse/damage-return/list/*') ? 'active' : '' }}">
                            <a href="#"><i class="fas fa-undo-alt" aria-hidden="true"></i>

                                <span>Damage/Return </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('damage.return.list') }}" class="{{ request()->is('damage-return/list') ? 'active' : '' }}">List</a></li>

                               @if((auth()->check() && auth()->user()->role_name == 'Admin') || 
                                    (auth()->check() && \Illuminate\Support\Str::lower(trim(auth()->user()->branch_type)) == 'warehouse'))
                                    <li>
                                        <a href="{{ route('warehouse.damage.return.list') }}" class="{{ request()->is('warehouse/damage-return/list') ? 'active' : '' }}">
                                           Warehouse Return List
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif




                @can(['Create', 'Branch List'])
                <li
                    class="submenu {{ set_active(['branch/list', 'branch/create']) }} {{ request()->is('branch/edit/*') ? 'active' : '' }} {{ request()->is('branch/show/*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-building"></i>
                        <span> Branch</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('branch.create') }}" class="{{ set_active(['branch/create']) }}">Create</a></li>
                        <li><a href="{{ route('branch.list') }}" class="{{ request()->is('branch/list') || request()->is('branch/show*') || request()->is('branch/edit*') ? 'active' : '' }}"> List</a></li>
                    </ul>
                </li>
                @endcan 



                @can('Project List')
                    <li
                        class="submenu {{ set_active(['project']) }} {{ request()->is('project/*') ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-project-diagram" aria-hidden="true"></i>
                            <span>Project</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('project.index') }}" class="{{ set_active(['project']) }}">List</a></li>
                        </ul>
                    </li>

                    <li
                        class="submenu {{ set_active(['productcategory']) }} {{ request()->is('productcategory/*') ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-th-large" aria-hidden="true"></i>
                            <span>Category</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('productcategory.index') }}" class="{{ set_active(['productcategory']) }}">List</a></li>
                        </ul>
                    </li>

                @endcan 








                <!-- <li
                    class="submenu {{ set_active(['batch/list', 'batch/grid', 'batch/add/page']) }} {{ request()->is('batch/edit/*') ? 'active' : '' }} {{ request()->is('batch/view/*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-building" style="color: #000000;"></i>
                        <span> Batch</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('batch/list') }}"
                                class="{{ set_active(['batch/list', 'batch/grid']) }}">Batch List</a></li>
                        <li><a href="{{ route('batch/add/page') }}"
                                class="{{ set_active(['batch/add/page']) }}">Batch Add</a></li>
                        <li><a class="{{ request()->is('batch/edit/*') ? 'active' : '' }}">Batch Edit</a></li>
                        <li><a href="" class="{{ request()->is('batch/view/*') ? 'active' : '' }}">Batch
                                View</a></li>
                    </ul>
                </li>

                <li
                    class="submenu {{ set_active(['category/list', 'category/grid', 'category/add/page']) }} {{ request()->is('category/edit/*') ? 'active' : '' }} {{ request()->is('category/view/*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-list-alt" style="color: #000000;"></i>
                        <span> Category</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('category/list') }}"
                                class="{{ set_active(['category/list', 'category/grid']) }}">Category List</a></li>
                        <li><a href="{{ route('category/add/page') }}"
                                class="{{ set_active(['category/add/page']) }}">Category Add</a></li>
                        <li><a class="{{ request()->is('category/edit/*') ? 'active' : '' }}">Category Edit</a>
                        </li>
                        <li><a href=""
                                class="{{ request()->is('category/view/*') ? 'active' : '' }}">Category View</a>
                        </li>
                    </ul>
                </li> -->










                @can(['Product Create', 'Product List', 'Barcode'])
                    <li
                        class="submenu {{ set_active(['product/list', 'product/create', 'barcode/list', 'barcode/generate']) }} {{ request()->is('product/view/*') ? 'active' : '' }} {{ request()->is('product/edit/*') ? 'active' : '' }} {{ request()->is('barcode/list/*') ? 'active' : '' }} {{ request()->is('barcode/generate/*') ? 'active' : '' }}">
                        <a href="#"><i class="fab fa-product-hunt" aria-hidden="true"></i>
                            <span>Product</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('product.create') }}" class="{{ set_active(['product/create']) }}">Create</a></li>
                            <li><a href="{{ route('product.list') }}" class="{{ request()->is('product/list') || request()->is('product/view*') || request()->is('product/edit*') ? 'active' : '' }}">List</a></li>
                            <li><a href="{{ route('barcode.list') }}" class="{{ request()->is('barcode/list') || request()->is('barcode/generate*') ? 'active' : '' }}">Barcode</a></li>
                        </ul>
                    </li>
                @endcan 


                <!-- @can('Ledger Report')
                    {{-- <li class="submenu {{ set_active(['expense/report/ledger']) }} {{ request()->is('expense/report/ledger/*') ? 'active' : '' }} " > --}}
                    <li class="submenu {{ request()->is('report/*') ? 'active' : '' }}" >
                        <a href="#"><i class="fas fa-book" aria-hidden="true"></i>
                            <span>Report </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('report.stock.in.out') }}" class="{{ request()->is('report/stock-in-out') ? 'active' : '' }}">Stock In Out</a></li>
                            <li><a href="{{ route('report.product.ledger') }}" class="{{ request()->is('report/product-ledger') ? 'active' : '' }}">Store Report</a></li>
                            <li><a href="{{ route('report.store.product.ledger') }}" class="{{ request()->is('report/store-product-ledger') ? 'active' : '' }}">Ledger Report</a></li>
                            <li><a href="{{ route('report.receipt.payment.statement') }}" class="{{ request()->is('report/receipt-payment-statement') ? 'active' : '' }}">Receipt & Payment Statement Report</a></li>
                        </ul>
                    </li>
                @endcan  --> 



                    <!-- <li class="{{ set_active(['products-ledger']) }}">
                        <a href="{{ route('product.ledger') }}">
                            <i class="fas fa-file-invoice"></i>
                            <span>Product Ledger</span>
                        </a>
                    </li> -->


                    @php
                        $user = auth()->user();
                        $branch = \App\Models\Branch::find($user->branch_id);
                    @endphp

                    @if(
                        $user->role_name !== 'PurchaseTeam' &&
                        $user->role_name !== 'Warehouse' &&
                        (!($branch && $branch->type === 'Warehouse'))
                    )
                        <li class="{{ set_active(['products-ledger']) }}">
                            <a href="{{ route('product.ledger') }}">
                                <i class="fas fa-file-invoice"></i>
                                <span>Product Ledger</span>
                            </a>
                        </li>
                    @endif


                
                @can('Settings')
                    <li class="{{ set_active(['setting/page']) }}">
                        <a href="{{ route('setting.page') }}">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                @endcan 


            </ul>




        </div>
    </div>
</div>