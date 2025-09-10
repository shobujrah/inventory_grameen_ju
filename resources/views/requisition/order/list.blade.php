@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Requisitions for Order</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('order.list') }}">Order</a></li>
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
                            <div class="page-header">
                                <div class="row align-items-center">
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>Serial</th>
                                            <th>Branch Name</th>
                                            <th>Project Name</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            @if ($authUserBranch && $authUserBranch->type !== 'Branch')
                                                <th>Document</th>
                                            @endif
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requisitions as $key => $requisitionkey)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ optional($requisitionkey->branch)->name ?? '' }}</td>
                                                <td>{{ $requisitionkey->project->name ?? '' }}</td>
                                                <td>{{ $requisitionkey->date_from }}</td>
                                                <td>
                                                    @if (is_null($requisitionkey->status))
                                                    <span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>

                                                    @elseif ($requisitionkey->status == 1 && $requisitionkey->partial_delivery == 0)
                                                        <span class="badge" style="background-color: #ffc107; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Partial Delivered</span>
                                                    @elseif ($requisitionkey->status == 1 && $requisitionkey->partial_delivery == 1)
                                                        <span class="badge" style="background-color: #28a745; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Delivered</span>

                                                    @elseif ($requisitionkey->status == 2)
                                                    <span style="background-color: #28A745; color: #FFF; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Purchased</span>
                                                    @elseif ($requisitionkey->status == 4)
                                                        <span style="background-color: #ADD8E6; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending Purchase</span>

                                                    @elseif ($requisitionkey->status == 5 && $requisitionkey->partial_reject == 1)
                                                         <span class="badge" style="background-color: #dc3545; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Rejected</span>

                                                    @elseif ($requisitionkey->status == 5 && $requisitionkey->partial_reject == 0)
                                                         <span class="badge" style="background-color: #dc3545; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Partial Rejected</span>

                                                    @elseif ($requisitionkey->status == 6)
                                                         <span class="badge" style="background-color: #dc3545; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Rejected By Purchase Team</span>

                                                    @elseif ($requisitionkey->status == 7)
                                                        <span style="background-color: #ADD8E6; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending Approval for Purchase</span>
                                                    
                                                    @elseif ($requisitionkey->status == 3 && $requisitionkey->partial_stock == 0)
                                                         <span class="badge" style="background-color: #ffc107; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Partial Stocked</span>

                                                    @else
                                                        <span class="badge" style="background-color: #007bff; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Stocked</span>

                                                    @endif
                                                </td>

                                                @if ($authUserBranch && $authUserBranch->type !== 'Branch')
                                                    <td>
                                                        @if($requisitionkey->document)
                                                            @php
                                                                $extension = pathinfo($requisitionkey->document, PATHINFO_EXTENSION);
                                                            @endphp

                                                            @if(in_array($extension, ['pdf']))
                                                                <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                            @elseif(in_array($extension, ['doc', 'docx']))
                                                                <i class="fas fa-file-word fa-2x text-primary"></i>
                                                            @elseif(in_array($extension, ['xls', 'xlsx']))
                                                                <i class="fas fa-file-excel fa-2x text-success"></i>
                                                            @elseif(in_array($extension, ['ppt', 'pptx']))
                                                                <i class="fas fa-file-powerpoint fa-2x text-warning"></i>
                                                            @elseif(in_array($extension, ['png', 'jpg', 'jpeg', 'gif']))
                                                                <img src="{{ asset('storage/document/' . $requisitionkey->document) }}" alt="{{ $requisitionkey->id }}" style="width: 50px; height: 50px;">
                                                            @else
                                                                <i class="fas fa-file-alt fa-2x"></i> {{-- Generic file icon for other file types --}}
                                                            @endif

                                                            {{-- Download button --}}

                                                            <a style="position: relative; top: -10px; padding: 3px 3px; font-size: 8px; line-height: 1;" href="{{ asset('storage/document/' . $requisitionkey->document) }}" download class="btn btn-sm btn-dark ms-2" data-bs-toggle="tooltip" title="Download">
                                                                <i class="fas fa-download"></i>
                                                            </a>

                                                        @else
                                                            No File
                                                        @endif
                                                    </td>
                                                @endif 
 
                                                
                                                <td>
                                                    <a href="{{ route('order.list.view', $requisitionkey->id) }}"
                                                        class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>  


                                                    <!-- New code for Warehouse  Purchase and reject -->  

                                                    @if ($authUserBranch && $authUserBranch->type === 'Warehouse' && $requisitionkey->status == 1 && $requisitionkey->partial_delivery == 0)
                                                        
                                                        @if ($requisitionkey->alldone_status != 1)
                                                    
                                                            <a href="{{ route('requisition.purchasee.check', $requisitionkey->id) }}" 
                                                                    class="btn btn-sm btn-info mx-1" style="margin-left: 8px !important;">Purchase</a>
                                                                <a href="{{ route('requisition.reject.check', $requisitionkey->id) }}" 
                                                                    class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>
                                                            @else
                                                                @if ($requisitionkey->status == 6)
                                                                    <a href="{{ route('requisition.reject.check', $requisitionkey->id) }}" 
                                                                        class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>
                                                                @endif

                                                        @endif  

                                                    @endif   

                                                      
                                                     <!-- New code for Admin purchase and reject -->

                                                    @if (Auth::user()->role_name === 'Admin' && $requisitionkey->status == 1 && $requisitionkey->partial_delivery == 0)
                                                        
                                                        @if ($requisitionkey->alldone_status != 1)
                                                    
                                                            <a href="{{ route('requisition.purchasee.check', $requisitionkey->id) }}" 
                                                                    class="btn btn-sm btn-info mx-1" style="margin-left: 8px !important;">Purchase</a>
                                                                <a href="{{ route('requisition.reject.check', $requisitionkey->id) }}" 
                                                                    class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>
                                                            @else
                                                                @if ($requisitionkey->status == 6)
                                                                    <a href="{{ route('requisition.reject.check', $requisitionkey->id) }}" 
                                                                        class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>
                                                                @endif

                                                        @endif  

                                                    @endif  


                                                    



                                                    @if ($requisitionkey->status == 6)
                                                        <a href="{{ route('requisition.reject.check', $requisitionkey->id) }}" class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>
                                                    @endif


                                                    @if ($requisitionkey->status == 5)
                                                        
                                                                <!-- Reject modal ta  -->

                                                    @endif 



                                                    @if (is_null($requisitionkey->status))
                                                        <a href="{{ route('order.list.edit', $requisitionkey->id) }}"
                                                            class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                            title="{{ __('Edit') }}">
                                                            <i class="feather-edit"></i>
                                                        </a>

                                                        @if(!($authUserBranch->type === 'Warehouse'))
                                                            <a href="{{ route('requisition.delete', $requisitionkey->id) }}"
                                                            class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                            title="{{ __('Delete') }}">
                                                            <i class="fas fa-trash text-danger"></i>
                                                            </a>
                                                        @endif

                                                    @endif

                                                    


                                                    @if ($authUserBranch && $authUserBranch->type === 'Warehouse' && (is_null($requisitionkey->status) || $requisitionkey->status == 2 || ($requisitionkey->status == 4 && $requisitionkey->partial_purchase == 0) || ($requisitionkey->status == 5 && $requisitionkey->partial_reject == 0)))  

                                                        @if ($requisitionkey->alldone_status != 1)
                                                            <a href="{{ route('requisition.deliveryy.check', $requisitionkey->id) }}" class="btn btn-sm btn-success mx-1" style="margin-left: 8px !important;">Delivery</a>
                                                            
                                                            <a href="{{ route('requisition.reject.check', $requisitionkey->id) }}" class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>

                                                            @if (is_null($requisitionkey->status) || ($requisitionkey->status == 4 && $requisitionkey->partial_purchase == 0) || ($requisitionkey->status == 5 && $requisitionkey->partial_reject == 0))
                                                            
                                                                <a href="{{ route('requisition.purchasee.check', $requisitionkey->id) }}" class="btn btn-sm btn-info mx-1" style="margin-left: 8px !important;">Purchase</a>
                                                            
                                                                <button class="btn btn-sm btn-primary upload-btn" data-bs-toggle="modal" data-bs-target="#uploadModal" data-id="{{ $requisitionkey->id }}">
                                                                    <i class="fas fa-upload"></i>
                                                                </button>
                                                            @endif
                                                            
                                                        @endif
                                                        

                                                        @else
                                                        

                                                            @if ($authUserBranch && $authUserBranch->type === 'Warehouse' && $requisitionkey->partial_delivery == 0)
                                                                @if (!($requisitionkey->status == 4 && $requisitionkey->partial_purchase == 1))
                                                                   @if (!($requisitionkey->status == 3 && $requisitionkey->partial_delivery == 0 && $requisitionkey->partial_reject == 0 && $requisitionkey->partial_stock == 0 && $requisitionkey->partial_purchase == 0) && !($requisitionkey->status == 1 && $requisitionkey->partial_delivery == 0 && $requisitionkey->partial_reject == 0 && $requisitionkey->partial_stock == 0 && $requisitionkey->partial_purchase == 0) || (!($requisitionkey->status == 3 && $requisitionkey->partial_delivery == 0 && $requisitionkey->partial_reject == 0 && $requisitionkey->partial_stock == 0 && $requisitionkey->partial_purchase == 0) && !($requisitionkey->status == 1 && $requisitionkey->partial_delivery == 0 && $requisitionkey->partial_reject == 0 && $requisitionkey->partial_stock == 0 && $requisitionkey->partial_purchase == 0)) )
                                                                   

                                                                        <!-- <a href="{{ route('requisition.purchasee.check', $requisitionkey->id) }}" class="btn btn-sm btn-info mx-1" style="margin-left: 8px !important;">Purchase</a> -->
                                                                        @if (!($requisitionkey->status == 5 && $requisitionkey->partial_reject == 1) && !($requisitionkey->status == 1 && $requisitionkey->partial_delivery == 0))
                                                                            <a href="{{ route('requisition.purchasee.check', $requisitionkey->id) }}" class="btn btn-sm btn-info mx-1" style="margin-left: 8px !important;">Purchase</a>
                                                                            <a href="{{ route('requisition.reject.check', $requisitionkey->id) }}" class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>
                                                                            <button class="btn btn-sm btn-primary upload-btn" data-bs-toggle="modal" data-bs-target="#uploadModal" data-id="{{ $requisitionkey->id }}">
                                                                                <i class="fas fa-upload"></i>
                                                                            </button>
                                                                        @endif
                                                                        <!-- <button class="btn btn-sm btn-primary upload-btn" data-bs-toggle="modal" data-bs-target="#uploadModal" data-id="{{ $requisitionkey->id }}">
                                                                            <i class="fas fa-upload"></i>
                                                                        </button> -->
                                                                    @endif
                                                                @endif
                                                            @endif



                                                            <!-- @if ($authUserBranch && $authUserBranch->type === 'Warehouse' && $requisitionkey->partial_delivery == 0)
                                                                @if (!($requisitionkey->status == 4 && $requisitionkey->partial_purchase == 1))
                                                                    @if (!($requisitionkey->status == 3 && $requisitionkey->partial_delivery == 0 && $requisitionkey->partial_reject == 0 && $requisitionkey->partial_stock == 0))
                                                                        @if (!($requisitionkey->status == 5 && $requisitionkey->partial_reject == 1))
                                                                            <a href="{{ route('requisition.purchasee.check', $requisitionkey->id) }}" class="btn btn-sm btn-info mx-1" style="margin-left: 8px !important;">Purchase</a>
                                                                            <a href="{{ route('requisition.reject.check', $requisitionkey->id) }}" class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>
                                                                            <button class="btn btn-sm btn-primary upload-btn" data-bs-toggle="modal" data-bs-target="#uploadModal" data-id="{{ $requisitionkey->id }}">
                                                                                <i class="fas fa-upload"></i>
                                                                            </button>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif -->


                                                    @endif






                                                    @if (Auth::user()->role_name === 'Admin' && (is_null($requisitionkey->status) || $requisitionkey->status == 2 || ($requisitionkey->status == 4 && $requisitionkey->partial_purchase == 0) || ($requisitionkey->status == 5 && $requisitionkey->partial_reject == 0)))  

                                                        @if ($requisitionkey->alldone_status != 1)
                                                            <a href="{{ route('requisition.deliveryy.check', $requisitionkey->id) }}" class="btn btn-sm btn-success mx-1" style="margin-left: 8px !important;">Delivery</a>
                                                            
                                                            <a href="{{ route('requisition.reject.check', $requisitionkey->id) }}" class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>

                                                            @if (is_null($requisitionkey->status) || ($requisitionkey->status == 4 && $requisitionkey->partial_purchase == 0) || ($requisitionkey->status == 5 && $requisitionkey->partial_reject == 0))
                                                            
                                                                <a href="{{ route('requisition.purchasee.check', $requisitionkey->id) }}" class="btn btn-sm btn-info mx-1" style="margin-left: 8px !important;">Purchase</a>
                                                            
                                                                <button class="btn btn-sm btn-primary upload-btn" data-bs-toggle="modal" data-bs-target="#uploadModal" data-id="{{ $requisitionkey->id }}">
                                                                    <i class="fas fa-upload"></i>
                                                                </button>
                                                            @endif
                                                            
                                                        @endif


                                                        @else

                                                            @if (Auth::user()->role_name === 'Admin' && $requisitionkey->partial_delivery == 0)
                                                                @if (!($requisitionkey->status == 4 && $requisitionkey->partial_purchase == 1))
                                                                @if (!($requisitionkey->status == 3 && $requisitionkey->partial_delivery == 0 && $requisitionkey->partial_reject == 0 && $requisitionkey->partial_stock == 0 && $requisitionkey->partial_purchase == 0) && !($requisitionkey->status == 1 && $requisitionkey->partial_delivery == 0 && $requisitionkey->partial_reject == 0 && $requisitionkey->partial_stock == 0 && $requisitionkey->partial_purchase == 0) || (!($requisitionkey->status == 3 && $requisitionkey->partial_delivery == 0 && $requisitionkey->partial_reject == 0 && $requisitionkey->partial_stock == 0 && $requisitionkey->partial_purchase == 0) && !($requisitionkey->status == 1 && $requisitionkey->partial_delivery == 0 && $requisitionkey->partial_reject == 0 && $requisitionkey->partial_stock == 0 && $requisitionkey->partial_purchase == 0)) )
                                                            
                                                                        @if (!($requisitionkey->status == 5 && $requisitionkey->partial_reject == 1) && !($requisitionkey->status == 1 && $requisitionkey->partial_delivery == 0))
                                                                            <a href="{{ route('requisition.purchasee.check', $requisitionkey->id) }}" class="btn btn-sm btn-info mx-1" style="margin-left: 8px !important;">Purchase</a>
                                                                            <a href="{{ route('requisition.reject.check', $requisitionkey->id) }}" class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Reject</a>
                                                                            <button class="btn btn-sm btn-primary upload-btn" data-bs-toggle="modal" data-bs-target="#uploadModal" data-id="{{ $requisitionkey->id }}">
                                                                                <i class="fas fa-upload"></i>
                                                                            </button>
                                                                        @endif

                                                                @endif
                                                            @endif
                                                        @endif


                                                    @endif


                                                    























                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>









                        <!-- Delivery Check modal --> 


                        <!-- @if(session('showModal'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const modal = new bootstrap.Modal(document.getElementById('stockModal'));
                                    modal.show();
                                });
                            </script>
                        @endif

                        <div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="stockModalLabel">Delivery Items Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                        <table class="table table-bordered">
                                            <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                                                <tr>
                                                    <th>Product <br> Name </th>
                                                    <th class="text-center">Demand <br> Quantity</th>
                                                    <th class="text-center">Warehouse <br> Stock</th>
                                                    <th class="text-center"> Details </th>
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                @foreach (session('allDetailsdelivery', []) as $detail)
                                                    <tr class="{{ ($detail['is_insufficient'] || $detail['purchase'] == 1) ? 'table-danger' : '' }}" data-product-id="{{ $detail['product_id'] }}">
                                                        <td>
                                                            <input type="hidden" class="dreq_id" value="{{$detail['dreq_id']}}">
                                                            {{ $detail['product_name'] }}
                                                        </td>
                                                        <td class="text-center">{{ $detail['demand_amount'] }}</td>
                                                        <td class="text-center">
                                                            <strong>{{ $detail['stock'] }}</strong> <br>
                                                        </td>
                                                         <td class="text-center" style="white-space: normal;">
                                                            <strong>
                                                                <span style="font-size: 12px; color: gray; display: inline-block;">
                                                                    {{ $detail['batch_details'] }}
                                                                </span>
                                                            </strong>
                                                        </td>


                                                         <td class="text-center">
                                                            <strong>
                                                                @if(isset($detail['purchase']) && $detail['purchase'] == 1)
                                                                    Progress in Purchase
                                                                @elseif(isset($detail['purchase']) && $detail['purchase'] == 2)
                                                                    Purchased
                                                                @else
                                                                @endif
                                                            </strong>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="modal-footer d-flex flex-column align-items-center">
                                        <div id="confirmationSection" style="display: none; text-align: center; width: 100%;">
                                            <p style="font-size: 18px; font-weight: bold;">Are you sure to deliver without these products?</p>
                                            <div class="mb-3" id="paymentMethodSection" style="display: none;">
                                                <label for="paymentMethod" class="form-label">Payment Method:</label>
                                                <select class="form-select" id="paymentMethod">
                                                    <option value="cash">Cash</option>
                                                    <option value="bank">Bank</option>
                                                    <option value="due">Due</option>
                                                </select>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-success" id="confirmDeliveryButton">Yes</button>
                                                <button type="button" class="btn btn-danger" id="cancelDeliveryButton">No</button>
                                            </div>
                                        </div>
                                        @php
                                            $allInsufficient = collect(session('allDetailsdelivery', []))->every(fn($detail) => $detail['is_insufficient']);
                                        @endphp 
                                        @if (!$allInsufficient && !session('allInProgress'))
                                            <button type="button" class="btn btn-success" id="deliveryButton">Delivery</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const deliveryButton = document.getElementById('deliveryButton');
                                const confirmationSection = document.getElementById('confirmationSection');
                                const confirmDeliveryButton = document.getElementById('confirmDeliveryButton');
                                const cancelDeliveryButton = document.getElementById('cancelDeliveryButton');
                                const paymentMethodSection = document.getElementById('paymentMethodSection');

                                deliveryButton.addEventListener('click', function () {
                                    const tableRows = document.querySelectorAll('.table-danger');
                                    if (tableRows.length === 0) {
                                        paymentMethodSection.style.display = 'block';
                                        confirmationSection.style.display = 'block';
                                        document.querySelector('#confirmationSection p').textContent = 'Select payment method for delivery';
                                        confirmDeliveryButton.textContent = 'Confirm Delivery';
                                        deliveryButton.style.display = 'none';
                                    } else {
                                        confirmationSection.style.display = 'block';
                                        deliveryButton.style.display = 'none';
                                    }
                                });

                                confirmDeliveryButton.addEventListener('click', function () {
                                    if (paymentMethodSection.style.display === 'none') {
                                        paymentMethodSection.style.display = 'block';
                                        document.querySelector('#confirmationSection p').textContent = 'Select payment method for delivery';
                                        confirmDeliveryButton.textContent = 'Confirm Delivery';
                                    } else {
                                        const insufficientStockProducts = [];
                                        const tableRows = document.querySelectorAll('.table-danger');
                                        tableRows.forEach(row => {
                                            const productId = row.getAttribute('data-product-id'); 
                                            insufficientStockProducts.push(productId);
                                        });

                                        const requisitionId = $(".dreq_id").val();
                                        const paymentMethod = document.getElementById('paymentMethod').value;

                                        window.location.href = `/requisition/deliveryy/${requisitionId}?insufficient_products=${JSON.stringify(insufficientStockProducts)}&payment_method=${paymentMethod}`;
                                    }
                                });
                                cancelDeliveryButton.addEventListener('click', function () {
                                    confirmationSection.style.display = 'none';
                                    paymentMethodSection.style.display = 'none';
                                    deliveryButton.style.display = 'block';
                                    document.querySelector('#confirmationSection p').textContent = 'Are you sure to deliver without these products?';
                                    confirmDeliveryButton.textContent = 'Yes';
                                });
                            });
                        </script> -->






                            <!-- Delivery Check modal -->
                            @if(session('showModal'))
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const modal = new bootstrap.Modal(document.getElementById('stockModal'));
                                        modal.show();
                                    });
                                </script>
                            @endif

                            <div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="stockModalLabel">Delivery Items Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                            <table class="table table-bordered">
                                                <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                                                    <tr>
                                                        <th>Product Name</th>
                                                        <th class="text-center">Demand Quantity</th>
                                                        <th class="text-center">Warehouse Stock</th>
                                                        <th class="text-center">Batch Details</th>
                                                        <th class="text-center">Status</th>
                                                    </tr>
                                                </thead> 
                                                <tbody>
                                                    @foreach (session('allDetailsdelivery', []) as $detail)
                                                        <tr class="{{ ($detail['is_insufficient'] || $detail['purchase'] == 1) ? 'table-danger' : '' }}" data-product-id="{{ $detail['product_id'] }}">
                                                            <td>
                                                                <input type="hidden" class="dreq_id" value="{{$detail['dreq_id']}}">
                                                                {{ $detail['product_name'] }}
                                                            </td>
                                                            <td class="text-center">{{ $detail['demand_amount'] }}</td>
                                                            <td class="text-center">
                                                                <strong>{{ $detail['stock'] }}</strong>
                                                            </td>
                                                            <td class="text-center" style="white-space: normal;">
                                                                <strong>
                                                                    <span style="font-size: 12px; color: gray;">
                                                                        {{ $detail['batch_details'] }}
                                                                    </span>
                                                                </strong>
                                                            </td>
                                                            <td class="text-center">
                                                                <strong>
                                                                    @if(isset($detail['purchase']) && $detail['purchase'] == 1)
                                                                        Progress in Purchase
                                                                    @elseif(isset($detail['purchase']) && $detail['purchase'] == 2)
                                                                        Purchased
                                                                    @endif
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="modal-footer d-flex flex-column align-items-center">
                                            <div id="confirmationSection" style="display: none; text-align: center; width: 100%;">
                                                <p style="font-size: 18px; font-weight: bold;">Are you sure to deliver without these products?</p>
                                                <div class="mb-3" id="paymentMethodSection" style="display: none;">
                                                    <label for="paymentMethod" class="form-label">Payment Method:</label>
                                                    <select class="form-select" id="paymentMethod" required>
                                                        <option value="">--Select Payment Method--</option>
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
                                                <div>
                                                    <button type="button" class="btn btn-success" id="confirmDeliveryButton">Yes</button>
                                                    <button type="button" class="btn btn-danger" id="cancelDeliveryButton">No</button>
                                                </div>
                                            </div>
                                            @php
                                                $allInsufficient = collect(session('allDetailsdelivery', []))->every(fn($detail) => $detail['is_insufficient']);
                                            @endphp 
                                            @if (!$allInsufficient && !session('allInProgress'))
                                                <button type="button" class="btn btn-success" id="deliveryButton">Delivery</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const deliveryButton = document.getElementById('deliveryButton');
                                    const confirmationSection = document.getElementById('confirmationSection');
                                    const confirmDeliveryButton = document.getElementById('confirmDeliveryButton');
                                    const cancelDeliveryButton = document.getElementById('cancelDeliveryButton');
                                    const paymentMethodSection = document.getElementById('paymentMethodSection');
                                    const paymentMethodSelect = document.getElementById('paymentMethod');

                                    deliveryButton.addEventListener('click', function () {
                                        const tableRows = document.querySelectorAll('.table-danger');
                                        if (tableRows.length === 0) {
                                            paymentMethodSection.style.display = 'block';
                                            document.querySelector('#confirmationSection p').textContent = 'Select payment method for delivery';
                                            confirmDeliveryButton.textContent = 'Confirm Delivery';
                                            deliveryButton.style.display = 'none';
                                            confirmationSection.style.display = 'block';
                                        } else {
                                            confirmationSection.style.display = 'block';
                                            deliveryButton.style.display = 'none';
                                        }
                                    });

                                    confirmDeliveryButton.addEventListener('click', function () {
                                        if (paymentMethodSection.style.display === 'block' && !paymentMethodSelect.value) {
                                            alert('Please select a payment method');
                                            return;
                                        }

                                        if (paymentMethodSection.style.display === 'none') {
                                            paymentMethodSection.style.display = 'block';
                                            document.querySelector('#confirmationSection p').textContent = 'Select payment method for delivery';
                                            confirmDeliveryButton.textContent = 'Confirm Delivery';
                                        } else {
                                            const insufficientStockProducts = [];
                                            const tableRows = document.querySelectorAll('.table-danger');
                                            tableRows.forEach(row => {
                                                const productId = row.getAttribute('data-product-id'); 
                                                insufficientStockProducts.push(productId);
                                            });

                                            const requisitionId = document.querySelector('.dreq_id').value;
                                            const paymentMethod = paymentMethodSelect.value;

                                            window.location.href = `/requisition/deliveryy/${requisitionId}?insufficient_products=${JSON.stringify(insufficientStockProducts)}&payment_method=${paymentMethod}`;
                                        }
                                    });

                                    cancelDeliveryButton.addEventListener('click', function () {
                                        confirmationSection.style.display = 'none';
                                        paymentMethodSection.style.display = 'none';
                                        deliveryButton.style.display = 'block';
                                        document.querySelector('#confirmationSection p').textContent = 'Are you sure to deliver without these products?';
                                        confirmDeliveryButton.textContent = 'Yes';
                                        paymentMethodSelect.value = '';
                                    });
                                });
                            </script>






















                        <!-- Purchase check modal -->

                        @if(session('showPurchaseModal'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const modal = new bootstrap.Modal(document.getElementById('purchaseModal'));
                                    modal.show();
                                });
                            </script>
                        @endif

                        <div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="purchaseModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="purchaseModalLabel">Purchase Items Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                        <table class="table table-bordered">
                                            <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" id="selectAll"
                                                            {{ collect(session('allDetails', []))->every(fn($detail) => $detail['purchase'] == 1) ? 'disabled' : '' }}>
                                                    </th>
                                                    <th>Product</th>
                                                    <th class="text-center">Demand Quantity</th>
                                                    <th class="text-center">Warehouse Stock</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach (session('allDetails', []) as $detail)
                                                    <tr class="{{ $detail['is_insufficient'] ? 'table-danger' : '' }}">
                                                        <td>
                                                            <input type="hidden" class="req_id" value="{{ $detail['req_id'] }}">
                                                            <input type="checkbox" class="itemCheckbox" value="{{ $detail['product_id'] }}" 
                                                                {{ $detail['purchase'] == 1 ? 'disabled' : '' }}>
                                                        </td>
                                                        <td>{{ $detail['product_name'] }}</td>
                                                        <td class="text-center">{{ $detail['demand_amount'] }}</td>
                                                        <td class="text-center"><strong>{{ $detail['stock'] }}</strong></td>
                                                        <td class="text-center">
                                                            <strong>
                                                                @if($detail['purchase'] == 1)
                                                                    Progress in Purchase
                                                                @else
                                                                   
                                                                @endif
                                                            </strong>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if(!session('allInPurchaseProgress')) 
                                    <div class="modal-footer d-flex justify-content-center">
                                        <button type="button" class="btn btn-info" id="purchaseButton">Purchase</button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>


                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const selectAllCheckbox = document.getElementById('selectAll');
                                const itemCheckboxes = document.querySelectorAll('.itemCheckbox:not(:disabled)');
                                const purchaseButton = document.getElementById('purchaseButton');

                                if (selectAllCheckbox) {
                                    selectAllCheckbox.addEventListener('change', function () {
                                        itemCheckboxes.forEach(checkbox => {
                                            checkbox.checked = selectAllCheckbox.checked;
                                        });
                                    });
                                }

                                if (purchaseButton) {
                                    purchaseButton.addEventListener('click', function () {
                                        const selectedItems = Array.from(document.querySelectorAll('.itemCheckbox:not(:disabled)'))
                                            .filter(checkbox => checkbox.checked)
                                            .map(checkbox => checkbox.value);

                                        if (selectedItems.length > 0) {
                                            console.log('Selected Items:', selectedItems);

                                            const requisitionId = $(".req_id").val();
                                            window.location.href = `/requisition/purchasee/${requisitionId}?items=${selectedItems.join(',')}`;
                                        } else {
                                            toastr.error('Please select at least one item for purchase.');
                                        }
                                    });
                                }
                            });
                        </script>   












                         <!-- Reject Modal -->

                            @if(session('rejectsModal'))
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
                                        rejectModal.show();
                                    });
                                </script>
                            @endif

                            <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModalLabel">Reject Items Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                            <table class="table table-bordered">
                                                <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                                                    <tr>
                                                        <th>
                                                            <input type="checkbox" id="selectAllReject">
                                                        </th>
                                                        <th>Product</th>
                                                        <th class="text-center">Demand Quantity</th>
                                                        <th class="text-center">Warehouse Stock</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach (session('allDetailsreject', []) as $detail)
                                                        <tr class="{{ $detail['is_insufficient'] ? 'table-danger' : '' }}">
                                                            <td>
                                                                <input type="hidden" class="rjreq_id" value="{{ $detail['rjreq_id'] }}">
                                                                <input type="checkbox" class="rejectCheckbox" value="{{ $detail['product_id'] }}">
                                                            </td>
                                                            <td>{{ $detail['product_name'] }}</td>
                                                            <td class="text-center">{{ $detail['demand_amount'] }}</td>
                                                            <td class="text-center"><strong>{{ $detail['stock'] }}</strong></td>
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
                                    const selectAllReject = document.getElementById('selectAllReject');
                                    const rejectCheckboxes = document.querySelectorAll('.rejectCheckbox:not(:disabled)');
                                    const rejectButton = document.getElementById('rejectButton');

                                    if (selectAllReject) {
                                        selectAllReject.addEventListener('change', function () {
                                            rejectCheckboxes.forEach(checkbox => {
                                                checkbox.checked = selectAllReject.checked;
                                            });
                                        });
                                    }

                                    if (rejectButton) {
                                        rejectButton.addEventListener('click', function () {
                                            const selectedRejects = Array.from(document.querySelectorAll('.rejectCheckbox:not(:disabled)'))
                                                .filter(checkbox => checkbox.checked)
                                                .map(checkbox => checkbox.value);

                                            if (selectedRejects.length > 0) {
                                                console.log('Selected Reject Items:', selectedRejects);

                                                const requisitionId = $(".rjreq_id").val();
                                                window.location.href = `//${requisitionId}?items=${selectedRejects.join(',')}`;
                                            } else {
                                                toastr.error('Please select at least one item to reject.');
                                            }
                                        });
                                    }
                                });
                            </script> -->


                            <!-- <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const selectAllReject = document.getElementById('selectAllReject');
                                    const rejectCheckboxes = document.querySelectorAll('.rejectCheckbox:not(:disabled)');
                                    const rejectButton = document.getElementById('rejectButton');
                                    const rejectNoteContainer = document.getElementById('rejectNoteContainer');
                                    const rejectNoteInput = document.getElementById('rejectNoteInput');

                                    if (selectAllReject) {
                                        selectAllReject.addEventListener('change', function () {
                                            rejectCheckboxes.forEach(checkbox => {
                                                checkbox.checked = selectAllReject.checked;
                                            });
                                        });
                                    }

                                    if (rejectButton) {
                                        rejectButton.addEventListener('click', function () {
                                            const selectedRejects = Array.from(document.querySelectorAll('.rejectCheckbox:not(:disabled)'))
                                                .filter(checkbox => checkbox.checked)
                                                .map(checkbox => checkbox.value);

                                            if (selectedRejects.length === 0) {
                                                toastr.error('Please select at least one item to reject.');
                                                return;
                                            }

                                            rejectNoteContainer.style.display = 'block';

                                            if (rejectNoteInput.value.trim() === '') {
                                                toastr.error('Please enter a reject note.');
                                                return;
                                            }

                                            const requisitionId = $(".rjreq_id").val();
                                            console.log('Rejecting items:', selectedRejects, 'with note:', rejectNoteInput.value);
                                            window.location.href = `//${requisitionId}?items=${selectedRejects.join(',')}&note=${encodeURIComponent(rejectNoteInput.value)}`;
                                        });
                                    }
                                });
                            </script> -->


                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                        const selectAllReject = document.getElementById('selectAllReject');
                                        const rejectCheckboxes = document.querySelectorAll('.rejectCheckbox:not(:disabled)');
                                        const rejectButton = document.getElementById('rejectButton');
                                        const rejectNoteContainer = document.getElementById('rejectNoteContainer');
                                        const rejectNoteInput = document.getElementById('rejectNoteInput');

                                        if (selectAllReject) {
                                            selectAllReject.addEventListener('change', function () {
                                                rejectCheckboxes.forEach(checkbox => {
                                                    checkbox.checked = selectAllReject.checked;
                                                });
                                            });
                                        }

                                        if (rejectButton) {
                                            rejectButton.addEventListener('click', function () {
                                                const selectedRejects = Array.from(document.querySelectorAll('.rejectCheckbox:not(:disabled)'))
                                                    .filter(checkbox => checkbox.checked)
                                                    .map(checkbox => checkbox.value);

                                                if (selectedRejects.length === 0) {
                                                    toastr.error('Please select at least one item to reject.');
                                                    return;
                                                }

                                                rejectNoteContainer.style.display = 'block';

                                                if (rejectNoteInput.value.trim() === '') {
                                                    toastr.error('Please enter a reject note.');
                                                    return;
                                                }

                                                const requisitionId = document.querySelector(".rjreq_id").value;

                                                // Send data via AJAX
                                                fetch(`/requisition/reject/${requisitionId}`, {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                    },
                                                    body: JSON.stringify({
                                                        items: selectedRejects,
                                                        note: rejectNoteInput.value
                                                    })
                                                })
                                                .then(response => {
                                                    if (response.ok) {
                                                        toastr.success('Requisition rejected successfully.');
                                                        window.location.href = '/order/list';
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







                            









                             <!-- Upload Document  -->

                            <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="uploadModalLabel">Upload Document</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form id="uploadForm" action="{{ route('requisition.upload') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="requisition_id" id="upload_requisition_id">
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
            </div>
        </div>
    </div>





<script>

    document.querySelectorAll('.upload-btn').forEach(button => {
        button.addEventListener('click', function () {
            const requisitionId = this.getAttribute('data-id');
            document.getElementById('upload_requisition_id').value = requisitionId;
        });
    });

</script>





@endsection
