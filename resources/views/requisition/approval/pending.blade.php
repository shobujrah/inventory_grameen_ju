@extends('layouts.master')
@section('content')


    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Pending Requisitions for Approval</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Pending Aprroval List</li>
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
                                                    @if ($requisition->pending_approval_status_headoffice == '0')
                                                        <span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>
                                                       
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
                                                    <a href="{{ route('pending.approval.list.view', $requisition->id) }}" class="btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{ __('View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                        <a href="{{ route('pending.approval.list.approve.check', $requisition->id) }}" class="btn btn-sm btn-success">Approve</a>
                                                       
                                                        <a href="{{ route('pending.approval.list.reject.check', $requisition->id) }}" class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>
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











            @if(session('showapprovalModal'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const modal = new bootstrap.Modal(document.getElementById('pendingApprovalModal'));
                        modal.show();
                    });
                </script>
            @endif

            <div class="modal fade" id="pendingApprovalModal" tabindex="-1" aria-labelledby="pendingApprovalModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pendingApprovalModalLabel">Pending Approval Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-bordered">
                                <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAllApproval" />
                                        </th>
                                        <th>Product</th>
                                        <th class="text-center">Demand Quantity</th>
                                        <th class="text-center">Warehouse Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (session('allPendingapproval', []) as $detail)
                                        <tr class="{{ $detail['is_insufficient'] ? 'table-danger' : '' }}" data-product-id="{{ $detail['product_id'] }}">
                                            <td>
                                                <input type="checkbox" 
                                                    class="rowApprovalCheckbox" 
                                                    data-product-id="{{ $detail['product_id'] }}" />
                                            </td>
                                            <td>
                                                <input type="hidden" class="ppreq_id" value="{{ $detail['ppreq_id'] }}">
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
                            <button type="button" class="btn btn-success" id="approveApprovalButton">Approve</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const selectAllCheckbox = document.getElementById('selectAllApproval');
                    const rowCheckboxes = document.querySelectorAll('.rowApprovalCheckbox');
                    const approveButton = document.getElementById('approveApprovalButton');

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

                        const selectedProductIds = selectedRows.map(checkbox => checkbox.getAttribute('data-product-id'));
                        const requisitionId = document.querySelector('.ppreq_id').value;

                        window.location.href = `/pending/approval/list/approve/${requisitionId}?selected_products=${JSON.stringify(selectedProductIds)}`;
                    });
                });
            </script>














                @if(session('showrejectModal'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const modal = new bootstrap.Modal(document.getElementById('pendingRejectModal'));
                            modal.show();
                        });
                    </script>
                @endif

                <div class="modal fade" id="pendingRejectModal" tabindex="-1" aria-labelledby="pendingRejectModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pendingRejectModalLabel">Rejected Items Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-bordered">
                                    <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAllRejected" />
                                            </th>
                                            <th>Product</th>
                                            <th class="text-center">Demand Quantity</th>
                                            <th class="text-center">Warehouse Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (session('allRejected', []) as $detail)
                                            <tr class="{{ $detail['is_insufficient'] ? 'table-danger' : '' }}" data-product-id="{{ $detail['product_id'] }}">
                                                <td>
                                                    <input type="checkbox" 
                                                        class="rowRejectCheckbox" 
                                                        data-product-id="{{ $detail['product_id'] }}" />
                                                </td>
                                                <td>
                                                    <input type="hidden" class="hrreq_id" value="{{ $detail['hrreq_id'] }}">
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
                                <div id="rejectNoteContainer" style="display: none; width: 100%; margin-bottom: 10px;">
                                    <label for="reject_note" class="form-label">Reject Note</label>
                                    <textarea id="reject_note" class="form-control" rows="3" placeholder="Enter reject note"></textarea>
                                    <small class="text-danger" id="rejectNoteError" style="display: none;">Reject note is required.</small>
                                </div>
                                <button type="button" class="btn btn-danger" id="rejectApprovalButton">Reject</button>
                            </div>

                        </div>
                    </div>
                </div>



                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const selectAllCheckbox = document.getElementById('selectAllRejected');
                            const rowCheckboxes = document.querySelectorAll('.rowRejectCheckbox');
                            const rejectButton = document.getElementById('rejectApprovalButton');
                            const rejectNoteContainer = document.getElementById('rejectNoteContainer');
                            const rejectNoteField = document.getElementById('reject_note');
                            const rejectNoteError = document.getElementById('rejectNoteError');

                            function toggleRejectNoteField() {
                                const anyChecked = Array.from(rowCheckboxes).some(checkbox => checkbox.checked);
                                rejectNoteContainer.style.display = anyChecked ? 'block' : 'none';
                            }

                            selectAllCheckbox.addEventListener('change', function () {
                                rowCheckboxes.forEach(checkbox => {
                                    checkbox.checked = selectAllCheckbox.checked;
                                });
                                toggleRejectNoteField();
                            });

                            rowCheckboxes.forEach(checkbox => {
                                checkbox.addEventListener('change', toggleRejectNoteField);
                            });

                            rejectButton.addEventListener('click', function () {
                                const selectedRows = Array.from(rowCheckboxes).filter(checkbox => checkbox.checked);

                                if (selectedRows.length === 0) {
                                    toastr.error('Please select at least one product to reject.');
                                    return;
                                }

                                const rejectNote = rejectNoteField.value.trim();
                                if (!rejectNote) {
                                    rejectNoteError.style.display = 'block';
                                    return;
                                } else {
                                    rejectNoteError.style.display = 'none';
                                }

                                const selectedProductIds = selectedRows.map(checkbox => checkbox.getAttribute('data-product-id'));
                                const requisitionId = document.querySelector('.hrreq_id').value;

                                fetch(`/pending/approval/list/reject/${requisitionId}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    },
                                    body: JSON.stringify({
                                        selected_products: selectedProductIds,
                                        reject_note: rejectNote,
                                    }),
                                })
                                .then(response => {
                                    if (response.ok) {
                                        toastr.success('Requisition rejected successfully.');
                                        window.location.reload(); 
                                    } else {
                                        toastr.error('An error occurred. Please try again.');
                                    }
                                })
                                .catch(error => {
                                    console.error(error);
                                    toastr.error('An unexpected error occurred.');
                                });
                            });
                        });
                    </script>








        </div>
    </div>
    





    
@endsection
