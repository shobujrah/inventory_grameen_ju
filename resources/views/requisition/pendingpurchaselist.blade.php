@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Pending Purchase Requisitions List</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Pending List</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table comman-shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>Serial</th>
                                            <th>Branch Name</th>
                                            <th>Project Name</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Document</th>
                                            <th>Action</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requisitions as $key => $requisition)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $requisition->branch->name ?? '' }}</td>
                                                <td>{{ $requisition->project->name ?? '' }}</td>
                                                <td>{{ $requisition->date_from }}</td>
                                                <td>

                                                    @if($requisition->status == '4' && $requisition->headoffice_approve != '1' && $requisition->headoffice_reject != '1')
                                                        <span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>

                                                    @elseif($requisition->status == '3' && $requisition->headoffice_approve != '1' && $requisition->headoffice_reject != '1')
                                                        <span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>

                                                    @elseif($requisition->status == '2' && $requisition->pending_purchase_status == '0')
                                                        <span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>
                                                    @elseif($requisition->status == '1' && $requisition->pending_purchase_status == '0')
                                                        <span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>
                                                    @elseif($requisition->status == '3' && $requisition->pending_purchase_status == '0')
                                                        <span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>
                                                    @elseif($requisition->status == '5' && $requisition->pending_purchase_status == '0')
                                                        <span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>

                                                    @elseif($requisition->status == '4' && $requisition->headoffice_approve == '1')
                                                        <span class="badge" style="background-color: #4CAF50; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Approved</span>
                                                    @elseif($requisition->status == '4' && $requisition->headoffice_reject == '1')
                                                        <span class="badge" style="background-color: #FF6347; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Reject</span>
                                                    @else
                                                        <span class="badge" style="background-color: #ADD8E6; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending Approval</span>
                                                    @endif

                                                </td>

                                                <td>
                                                    @if($requisition->document)
                                                        @php
                                                            // Extract file extension
                                                            $extension = pathinfo($requisition->document, PATHINFO_EXTENSION);
                                                        @endphp

                                                        {{-- Display icons based on file type --}}
                                                        @if(in_array($extension, ['pdf']))
                                                            <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                        @elseif(in_array($extension, ['doc', 'docx']))
                                                            <i class="fas fa-file-word fa-2x text-primary"></i>
                                                        @elseif(in_array($extension, ['xls', 'xlsx']))
                                                            <i class="fas fa-file-excel fa-2x text-success"></i>
                                                        @elseif(in_array($extension, ['ppt', 'pptx']))
                                                            <i class="fas fa-file-powerpoint fa-2x text-warning"></i>
                                                        @elseif(in_array($extension, ['png', 'jpg', 'jpeg', 'gif']))
                                                            <img src="{{ asset('storage/document/' . $requisition->document) }}" alt="{{ $requisition->id }}" style="width: 50px; height: 50px;">
                                                        @else
                                                            <i class="fas fa-file-alt fa-2x"></i> {{-- Generic file icon for other file types --}}
                                                        @endif

                                                        {{-- Download button --}}

                                                        <a style="position: relative; top: -10px; padding: 3px 3px; font-size: 8px; line-height: 1;" href="{{ asset('storage/document/' . $requisition->document) }}" download class="btn btn-sm btn-dark ms-2" data-bs-toggle="tooltip" title="Download">
                                                            <i class="fas fa-download"></i>
                                                        </a>

                                                    @else
                                                        No File
                                                    @endif
                                                </td>


                                                <td>
                                                    
                                                    <a href="{{ route('pending.purcahse.requisition.view', $requisition->id) }}" class="btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{ __('View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    

                                                    <!-- @if ($requisition->status == '4' && $requisition->headoffice_reject == '1')
                                                        <a href="{{ route('pending.purchase.requisition.reject.check', $requisition->id) }}" class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>
                                                    @elseif ($requisition->status == '4' || $requisition->status == '3' || $requisition->status == '1' || $requisition->status == '2' || $requisition->status == '5')
                                                        <a href="{{ route('pending.purcahse.requisition.approve.check', $requisition->id) }}" class="btn btn-sm btn-success">In</a>
                                                        <a href="{{ route('pending.purchase.requisition.reject.check', $requisition->id) }}" class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>

                                                        @if ($requisition->headoffice_approve != '1')
                                                            <a href="{{ route('send.to.approve.check', $requisition->id) }}" class="btn btn-sm btn-warning">Send for Approve</a>
                                                        @endif
                                                        <button class="btn btn-sm btn-primary upload-btn" data-bs-toggle="modal" data-bs-target="#uploadModal" data-id="{{ $requisition->id }}">
                                                            <i class="fas fa-upload"></i>
                                                        </button>
                                                    @endif -->





                                                    @if ($requisition->pending_purchase_status == '3')
                                                            <!-- No buttons should be displayed -->
                                                        @else
                                                            @if ($requisition->status == '4' && $requisition->headoffice_reject == '1')
                                                                <!-- Show only the Reject button if headoffice_reject is '1' -->
                                                                <a href="{{ route('pending.purchase.requisition.reject.check', $requisition->id) }}" class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>
                                                            @elseif ($requisition->status == '4' || $requisition->status == '3' || $requisition->status == '1' || $requisition->status == '2' || $requisition->status == '5')
                                                                <!-- Show other buttons if headoffice_reject is not '1' -->
                                                                <a href="{{ route('pending.purcahse.requisition.approve.check', $requisition->id) }}" class="btn btn-sm btn-success">In</a>

                                                                <a href="{{ route('pending.purchase.requisition.reject.check', $requisition->id) }}" class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>

                                                                @if ($requisition->headoffice_approve != '1')
                                                                    <a href="{{ route('send.to.approve.check', $requisition->id) }}" class="btn btn-sm btn-warning">Send for Approve</a>
                                                                @endif

                                                                <button class="btn btn-sm btn-primary upload-btn" data-bs-toggle="modal" data-bs-target="#uploadModal" data-id="{{ $requisition->id }}">
                                                                    <i class="fas fa-upload"></i>
                                                                </button>
                                                            @endif
                                                        @endif



                                                </td>



                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>      














                <!-- Pending Purchase Approve Check Modal -->


                @if(session('showModal'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const modal = new bootstrap.Modal(document.getElementById('pendingPurchaseModal'));
                            modal.show();
                        });
                    </script>
                @endif

                <div class="modal fade" id="pendingPurchaseModal" tabindex="-1" aria-labelledby="pendingPurchaseModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pendingPurchaseModalLabel">Pending Purchase Approval Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-bordered">
                                    <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAll" />
                                            </th>
                                            <th>Product</th>
                                            <th class="text-center">Demand Quantity</th>
                                            <th class="text-center">Warehouse Stock</th>
                                            <th class="text-center">Price</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (session('allDetailsdelivery', []) as $detail)
                                            <tr class="{{ $detail['is_insufficient'] || $detail['headoffice_approval'] ? 'table-danger' : '' }}" data-product-id="{{ $detail['product_id'] }}">
                                                <td>
                                                    <input type="checkbox" 
                                                        class="rowCheckbox" 
                                                        data-product-id="{{ $detail['product_id'] }}" 
                                                        {{ $detail['headoffice_approval'] ? 'disabled' : '' }} />
                                                </td>
                                                <td>
                                                    <input type="hidden" class="ppreq_id" value="{{ $detail['ppreq_id'] }}">
                                                    {{ $detail['product_name'] }}
                                                </td>
                                                <td class="text-center">{{ $detail['demand_amount'] }}</td>
                                                <td class="text-center"><strong>{{ $detail['stock'] }}</strong></td>

                                                <td class="text-center">
                                                    <input type="number" class="form-control price-input" name="price[{{ $detail['product_id'] }}]" min="1" step="0.01" placeholder="Enter price" required>
                                                </td> 

                                                <td class="text-center">
                                                    <strong>

                                                        @if ($detail['headoffice_approval'] == 0)
                                                         
                                                        @elseif ($detail['headoffice_approval'] == NULL)
                                                          
                                                        @else
                                                            {{ $detail['status'] }}
                                                        @endif

                                                    </strong>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                            
                                <div class="modal-footer d-flex flex-column align-items-center">
                                    <div id="paymentMethodWrapper" class="mb-3" style="display: none; width: 100%;">
                                        <label for="paymentMethod" class="form-label">Select Payment Method</label>
                                        <select id="paymentMethod" class="form-select" required>
                                            <option value="">-- Select Payment Method --</option>
                                            @foreach($paymentMethods as $method)
                                                <option value="{{ $method->name }}">
                                                    @if($method->name == 'Cash In Hand')
                                                        Cash
                                                    @elseif($method->name == 'Cash at Bank')
                                                        Bank
                                                    @elseif($method->name == 'Accounts Receivable')
                                                        Due
                                                    @else
                                                        {{ $method->name }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-success" id="approveButton">Approve</button>
                                </div>
                        </div>
                    </div>
                </div> 

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                    const selectAllCheckbox = document.getElementById('selectAll');
                    const rowCheckboxes = document.querySelectorAll('.rowCheckbox:not([disabled])');
                    const approveButton = document.getElementById('approveButton');
                    const paymentMethodWrapper = document.getElementById('paymentMethodWrapper');
                    const paymentMethodSelect = document.getElementById('paymentMethod');

                    selectAllCheckbox.addEventListener('change', function () {
                        rowCheckboxes.forEach(checkbox => {
                            checkbox.checked = selectAllCheckbox.checked;
                        });
                    });

                    approveButton.addEventListener('click', function () {
                        const selectedRows = Array.from(rowCheckboxes).filter(checkbox => checkbox.checked);
                        if (selectedRows.length === 0) {
                            toastr.error('Please select at least one product to approve.');
                            return;
                        }

                        const selectedProductIds = [];
                        let allPricesValid = true;
                        const priceData = {};
                        selectedRows.forEach(checkbox => {
                            const row = checkbox.closest('tr');
                            const priceInput = row.querySelector('.price-input');
                            if (!priceInput || priceInput.value.trim() === '') {
                                allPricesValid = false;
                                priceInput.classList.add('is-invalid');
                            } else {
                                priceInput.classList.remove('is-invalid');
                                priceData[checkbox.getAttribute('data-product-id')] = priceInput.value;
                            }
                            selectedProductIds.push(checkbox.getAttribute('data-product-id'));
                        });

                        if (!allPricesValid) {
                            toastr.error('Please enter a price for all selected products.');
                            return;
                        }

                        if (paymentMethodWrapper.style.display === 'none') {
                            paymentMethodWrapper.style.display = 'block';
                            toastr.info('Please select a payment method to continue.');
                            return;
                        }
                        const selectedPaymentMethod = paymentMethodSelect.value;
                        if (!selectedPaymentMethod) {
                            toastr.error('Please select a payment method.');
                            return;
                        }

                        const requisitionId = document.querySelector('.ppreq_id').value;
                        const params = new URLSearchParams();
                        params.append('selected_products', JSON.stringify(selectedProductIds));
                        params.append('prices', JSON.stringify(priceData));
                        params.append('payment_method', selectedPaymentMethod);

                        window.location.href = `/pending/purcahse/requisition/approve/${requisitionId}?${params.toString()}`;
                    });
                });

                </script>

















                <!-- Pending Purchase Reject Check Modal -->
                @if(session('showRejectModal'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const modal = new bootstrap.Modal(document.getElementById('pendingPurchaseRejectModal'));
                            modal.show();
                        });
                    </script>
                @endif

                <div class="modal fade" id="pendingPurchaseRejectModal" tabindex="-1" aria-labelledby="pendingPurchaseRejectModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pendingPurchaseRejectModalLabel">Pending Purchase Rejection Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-bordered">



                                    <!-- <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="rejectSelectAll" />
                                            </th>
                                            <th>Product</th>
                                            <th class="text-center">Demand Quantity</th>
                                            <th class="text-center">Warehouse Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (session('rejectDetails', []) as $detail)
                                            <tr class="{{ $detail['is_insufficient'] ? 'table-danger' : '' }}" data-product-id="{{ $detail['product_id'] }}">
                                                <td>
                                                    <input type="checkbox" class="rejectRowCheckbox" data-product-id="{{ $detail['product_id'] }}" />
                                                </td>
                                                <td>
                                                    <input type="hidden" class="pprjreq_id" value="{{ $detail['pprjreq_id'] }}">
                                                    {{ $detail['product_name'] }}
                                                </td>
                                                <td class="text-center">{{ $detail['demand_amount'] }}</td>
                                                <td class="text-center"><strong>{{ $detail['stock'] }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody> -->




                                    <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="rejectSelectAll" />
                                            </th>
                                            <th>Product</th>
                                            <th class="text-center">Demand Quantity</th>
                                            <th class="text-center">Warehouse Stock</th>
                                            <!-- <th class="text-center">Status</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (session('rejectDetails', []) as $detail)
                                            @php
                                                $isPendingApproval = $detail['headoffice_approval'] ?? 0; // Assuming `headoffice_approval` is passed in the session
                                            @endphp
                                            <tr class="{{ $isPendingApproval ? 'table-danger' : '' }}" data-product-id="{{ $detail['product_id'] }}">
                                                <td>
                                                    <input type="checkbox" class="rejectRowCheckbox" data-product-id="{{ $detail['product_id'] }}" {{ $isPendingApproval ? 'disabled' : '' }} />
                                                </td>
                                                <td>
                                                    <input type="hidden" class="pprjreq_id" value="{{ $detail['pprjreq_id'] }}">
                                                    {{ $detail['product_name'] }}
                                                </td>
                                                <td class="text-center">{{ $detail['demand_amount'] }}</td>
                                                <td class="text-center"><strong>{{ $detail['stock'] }}</strong></td>
                                                <td class="text-center"><strong>{{ $isPendingApproval ? 'Pending Approval' : '' }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>







                                </table>
                            </div>
                            <div class="modal-footer d-flex flex-column align-items-center">
                                <div id="rejectNoteContainer" class="mb-2" style="display: none; width: 100%;">
                                    <label for="rejectNoteInput" class="form-label">Reject Note <span class="text-danger">*</span></label>
                                    <textarea id="rejectNoteInput" class="form-control" placeholder="Enter reject note..." rows="1" required></textarea>
                                </div>
                                <button type="button" class="btn btn-danger" id="rejectButton">Reject</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <script>
                        document.addEventListener('DOMContentLoaded', function () {
                        const selectAllReject = document.getElementById('rejectSelectAll'); 
                        const rejectCheckboxes = document.querySelectorAll('.rejectRowCheckbox'); 
                        const rejectButton = document.getElementById('rejectButton');
                        const rejectNoteContainer = document.getElementById('rejectNoteContainer');
                        const rejectNoteInput = document.getElementById('rejectNoteInput');

                        if (selectAllReject) {
                            selectAllReject.addEventListener('change', function () {
                                rejectCheckboxes.forEach(checkbox => {
                                    checkbox.checked = selectAllReject.checked;
                                });
                                toggleRejectNoteContainer();
                            });
                        }

                        rejectCheckboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', toggleRejectNoteContainer);
                        });

                        function toggleRejectNoteContainer() {
                            const hasChecked = Array.from(rejectCheckboxes).some(checkbox => checkbox.checked);
                            rejectNoteContainer.style.display = hasChecked ? 'block' : 'none'; 
                        }

                        if (rejectButton) {
                            rejectButton.addEventListener('click', function () {
                                const selectedRejects = Array.from(rejectCheckboxes)
                                    .filter(checkbox => checkbox.checked)
                                    .map(checkbox => checkbox.dataset.productId); 

                                if (selectedRejects.length === 0) {
                                    toastr.error('Please select at least one item to reject.');
                                    return;
                                }

                                if (rejectNoteInput.value.trim() === '') {
                                    toastr.error('Please enter a reject note.');
                                    return;
                                }

                                const requisitionId = document.querySelector(".pprjreq_id").value;

                                fetch(`/pending/purcahse/requisition/reject/${requisitionId}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    },
                                    body: JSON.stringify({
                                        items: selectedRejects,
                                        note: rejectNoteInput.value,
                                    }),
                                })
                                    .then(response => {
                                        if (response.ok) {
                                            toastr.success('Requisition rejected successfully.');
                                            window.location.href = '/pending/purcahse/requisition';
                                        } else {
                                            toastr.error('Failed to reject requisition. Please try again.');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        toastr.error('An error occurred. Please try again.');
                                    });

                            });
                        }
                    });

                </script> -->



                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                    const selectAllReject = document.getElementById('rejectSelectAll'); 
                    const rejectCheckboxes = document.querySelectorAll('.rejectRowCheckbox:not([disabled])'); 
                    const rejectButton = document.getElementById('rejectButton');
                    const rejectNoteContainer = document.getElementById('rejectNoteContainer');
                    const rejectNoteInput = document.getElementById('rejectNoteInput');

                    if (selectAllReject) {
                        selectAllReject.addEventListener('change', function () {
                            rejectCheckboxes.forEach(checkbox => {
                                checkbox.checked = selectAllReject.checked;
                            });
                            toggleRejectNoteContainer();
                        });
                    }

                    rejectCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', toggleRejectNoteContainer);
                    });

                    function toggleRejectNoteContainer() {
                        const hasChecked = Array.from(rejectCheckboxes).some(checkbox => checkbox.checked);
                        rejectNoteContainer.style.display = hasChecked ? 'block' : 'none'; 
                    }

                    if (rejectButton) {
                        rejectButton.addEventListener('click', function () {
                            const selectedRejects = Array.from(rejectCheckboxes)
                                .filter(checkbox => checkbox.checked)
                                .map(checkbox => checkbox.dataset.productId); 

                            if (selectedRejects.length === 0) {
                                toastr.error('Please select at least one item to reject.');
                                return;
                            }

                            if (rejectNoteInput.value.trim() === '') {
                                toastr.error('Please enter a reject note.');
                                return;
                            }

                            const requisitionId = document.querySelector(".pprjreq_id").value;

                            fetch(`/pending/purcahse/requisition/reject/${requisitionId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: JSON.stringify({
                                    items: selectedRejects,
                                    note: rejectNoteInput.value,
                                }),
                            })
                                .then(response => {
                                    if (response.ok) {
                                        toastr.success('Requisition rejected successfully.');
                                        window.location.href = '/pending/purcahse/requisition';
                                    } else {
                                        toastr.error('Failed to reject requisition. Please try again.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    toastr.error('An error occurred. Please try again.');
                                });

                        });
                    }
                });

                </script>
















                








                    <!-- Approval Modal -->

                    <!-- @if(session('approvalModal'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const approvalModal = new bootstrap.Modal(document.getElementById('approvalModal'));
                                approvalModal.show();
                            });
                        </script>
                    @endif

                    <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="approvalModalLabel">Send for Approval Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-bordered">
                                        <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="approvalSelectAll" />
                                                </th>
                                                <th>Product</th>
                                                <th class="text-center">Demand Quantity</th>
                                                <th class="text-center">Warehouse Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (session('allDetailsapprovedfor', []) as $detail)
                                                <tr class="{{ $detail['is_insufficient'] ? 'table-danger' : '' }}" data-product-id="{{ $detail['product_id'] }}">
                                                    <td>
                                                        <input type="checkbox" class="approvalRowCheckbox" data-product-id="{{ $detail['product_id'] }}" />
                                                    </td>
                                                    <td>
                                                        <input type="hidden" class="ppappvreq_id" value="{{ $detail['ppappvreq_id'] }}">
                                                        {{ $detail['product_name'] }}
                                                    </td>
                                                    <td class="text-center">{{ $detail['demand_amount'] }}</td>
                                                    <td class="text-center"><strong>{{ $detail['stock'] }}</strong></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer d-flex flex-column align-items-center">
                                    <button type="button" class="btn btn-primary" id="sendApprovalButton">Send for Approval</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const approvalSelectAll = document.getElementById('approvalSelectAll');
                            const approvalRowCheckboxes = document.querySelectorAll('.approvalRowCheckbox');
                            const sendApprovalButton = document.getElementById('sendApprovalButton');

                            approvalSelectAll.addEventListener('change', function () {
                                approvalRowCheckboxes.forEach(checkbox => {
                                    checkbox.checked = approvalSelectAll.checked;
                                });
                            });

                            sendApprovalButton.addEventListener('click', function () {
                                const selectedRows = Array.from(approvalRowCheckboxes).filter(checkbox => checkbox.checked);
                                if (selectedRows.length === 0) {
                                    toastr.error('Please select at least one product to send for approval.');
                                    return;
                                }

                                const selectedProductIds = selectedRows.map(checkbox => checkbox.getAttribute('data-product-id'));
                                const requisitionId = document.querySelector('.ppappvreq_id').value;

                                window.location.href = `/send/to/approve/${requisitionId}?selected_products=${selectedProductIds.join(',')}`;

                            });
                        });
                    </script> -->




                    @if(session('approvalModal'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const approvalModal = new bootstrap.Modal(document.getElementById('approvalModal'));
                                approvalModal.show();
                            });
                        </script>
                    @endif

                    <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="approvalModalLabel">Send for Approval Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-bordered">
                                        <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="approvalSelectAll" />
                                                </th>
                                                <th>Product</th>
                                                <th class="text-center">Demand Quantity</th>
                                                <th class="text-center">Warehouse Stock</th>
                                                <!-- <th class="text-center">Status</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (session('allDetailsapprovedfor', []) as $detail)
                                                <tr class="{{ $detail['is_insufficient'] ? 'table-danger' : '' }} {{ $detail['headoffice_approval'] == 1 ? 'table-danger' : '' }}" data-product-id="{{ $detail['product_id'] }}">
                                                    <td>
                                                        <input type="checkbox" class="approvalRowCheckbox" data-product-id="{{ $detail['product_id'] }}" {{ $detail['headoffice_approval'] == 1 ? 'disabled' : '' }} />
                                                    </td>
                                                    <td>
                                                        <input type="hidden" class="ppappvreq_id" value="{{ $detail['ppappvreq_id'] }}">
                                                        {{ $detail['product_name'] }}
                                                    </td>
                                                    <td class="text-center">{{ $detail['demand_amount'] }}</td>
                                                    <td class="text-center"><strong>{{ $detail['stock'] }}</strong></td>
                                                    <td class="text-center">
                                                        @if($detail['headoffice_approval'] == 1)
                                                        <span style="font-weight: bold; color: black;">Pending approval</span>
                                                        @else
                                                            <span class="text-success"></span>
                                                        @endif
                                                    </td> <!-- Display status -->
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer d-flex flex-column align-items-center">
                                    <button type="button" class="btn btn-primary" id="sendApprovalButton">Send for Approval</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const approvalSelectAll = document.getElementById('approvalSelectAll');
                            const approvalRowCheckboxes = document.querySelectorAll('.approvalRowCheckbox');
                            const sendApprovalButton = document.getElementById('sendApprovalButton');

                            approvalSelectAll.addEventListener('change', function () {
                                approvalRowCheckboxes.forEach(checkbox => {
                                    if (!checkbox.disabled) {
                                        checkbox.checked = approvalSelectAll.checked;
                                    }
                                });
                            });

                            sendApprovalButton.addEventListener('click', function () {
                                const selectedRows = Array.from(approvalRowCheckboxes).filter(checkbox => checkbox.checked);
                                if (selectedRows.length === 0) {
                                    toastr.error('Please select at least one product to send for approval.');
                                    return;
                                }

                                const selectedProductIds = selectedRows.map(checkbox => checkbox.getAttribute('data-product-id'));
                                const requisitionId = document.querySelector('.ppappvreq_id').value;

                                window.location.href = `/send/to/approve/${requisitionId}?selected_products=${selectedProductIds.join(',')}`;
                            });
                        });
                    </script>


















            <!-- Upload Modal -->
            <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel">Upload Document</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div>
                            <form id="uploadForm" action="{{ route('requisition.upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="requisition_id" id="requisition_id">
                                <div class="mb-3 p-3">
                                    <label for="document" class="form-label">Choose Document</label>
                                    <input type="file" class="form-control" name="document" id="document" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.upload-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const requisitionId = this.getAttribute('data-id');
                    document.getElementById('requisition_id').value = requisitionId;
                });
            });
        });

    </script>


    
@endsection
