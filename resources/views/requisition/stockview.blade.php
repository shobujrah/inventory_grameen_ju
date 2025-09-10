@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Stock Details</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('stock.list') }}">Stock List</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('stock.list') }}" class="btn btn-sm btn-info text-white">Back</a></li>
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
                                    <div class="col">
                                        <h5>Stock for Branch: <span class="badge bg-primary">{{ $branch->name }}</span></h5>
                                    </div>
                                </div>
                            </div>
                            </br>
                            <div class="table-responsive">
                                <table class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">


                                    <thead class="warehouse-thread">

                                        @php
                                            $user = auth()->user();
                                            $canSeeUnitPrice = false;
                                            $canSeeBatch = false;
                                            $canSeeBreakdown = false;

                                            if ($user->branch->type === 'Warehouse' && $user->branch->id === $branch->id && $branch->type === 'Warehouse') {
                                                $canSeeUnitPrice = true;
                                                $canSeeBatch = true;
                                            } elseif (($user->role_name === 'Admin' || $user->branch->type === 'Headoffice') && $branch->type === 'Warehouse') {
                                                $canSeeUnitPrice = true;
                                                $canSeeBatch = true;
                                            }

                                            if ($user->branch->type === 'Branch' && $user->branch->id === $branch->id) {
                                                $canSeeBreakdown = true;
                                            } elseif ($user->role_name === 'Admin' && $branch->type !== 'Warehouse') {
                                                $canSeeBreakdown = true;
                                            } elseif ($user->branch->type === 'Headoffice' && ($branch->type === 'Branch' || $user->branch->id === $branch->id)) {
                                                $canSeeBreakdown = true;
                                            }
                                        @endphp


                                        <tr>
                                            <th>Serial</th>
                                            <th>Product Name</th>

                                            @if($canSeeUnitPrice)
                                                <th>Unit Price</th>
                                            @endif

                                            @if($canSeeBreakdown)
                                                <th>Price Quantity Breakdown</th>
                                            @endif
                                            
                                            <th>Stock</th>

                                            @if($canSeeBatch)
                                                <th>Batch</th>
                                            @endif

                                            @if(!(auth()->user()->branch->type === "Headoffice" || auth()->user()->role_name === 'Admin'))
                                                <th>Action</th>
                                            @endif

                                        </tr>
                                    </thead>

                                    <tbody> 

                                        @php
                                            $serial = 1;
                                            $totalStock = 0;
                                        @endphp

                                        @foreach ($products as $product)
                                            <tr>
                                                <td>{{ $serial++ }}</td>
                                                <td>{{ $product->product ? $product->product->name : 'N/A' }}</td>

                                                @if($canSeeUnitPrice)
                                                    <td>{{ $product->product ? $product->price : 'N/A' }}</td> 
                                                @endif

                                                @if($canSeeBreakdown)
                                                    <td>
                                                        @php
                                                            $details = json_decode($product->remain_details, true);
                                                        @endphp

                                                        @if($details)
                                                            @foreach ($details as $detail)
                                                                Price: {{ $detail['prc'] }} for Quantity: {{ $detail['qty'] }}<br>
                                                            @endforeach
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                @endif


                                                <td>{{ $product->stock }}</td>

                                                @if($canSeeBatch)
                                                    <td>{{ $product->batch }}</td>
                                                @endif

                                                <td>
                                                    @if( auth()->user()->branch->type === "Warehouse" )
                                                        <button type="button" class="btn btn-sm btn-success" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#addStockModal" 
                                                            data-product-id="{{ $product->product_id }}"
                                                            data-product-name="{{ $product->product->name }}"
                                                            data-batch="{{ $product->batch }}">
                                                            Add Stock
                                                        </button>  

                                                        <button type="button" class="btn btn-sm btn-warning" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#damagereturnModal"
                                                            data-product-id="{{ $product->product_id }}"
                                                            data-product-name="{{ $product->product->name }}"
                                                            data-stock="{{ $product->stock }}"
                                                            data-remain-details='@json($product->remain_details)'>
                                                            Return
                                                        </button> 

                                                            @php
                                                                $key = $product->product_id . '_' . $product->price;
                                                            @endphp

                                                            @if ($productReturnCounts->has($key))
                                                                <i class="fas fa-info-circle ms-2 text-primary"
                                                                    role="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#infoModal"
                                                                    data-product-name="{{ $product->product->name }}"
                                                                    data-pending-count="{{ $productReturnCounts[$key] }}"
                                                                    title="Click to view more info.">
                                                                </i>
                                                            @endif


                                                        @else
                                                    @endif



                                                    @if(auth()->user()->branch->type === 'Headoffice')
                                                        @if(auth()->user()->role_name !== 'Admin' && $product->stock > 0 && $product->branch_id === auth()->user()->branch_id)
                                                            <button type="button" class="btn btn-sm btn-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#expenseModal" 
                                                                data-product-id="{{ $product->product_id }}"
                                                                data-product-name="{{ $product->product->name }}"
                                                                data-stock="{{ $product->stock }}">
                                                                Expense
                                                            </button>
                                                        @endif
                                                    @else
                                                        @if(auth()->user()->role_name !== 'Admin' && $product->stock > 0 && auth()->user()->role_name !== 'Warehouse')
                                                            <button type="button" class="btn btn-sm btn-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#expenseModal" 
                                                                data-product-id="{{ $product->product_id }}"
                                                                data-product-name="{{ $product->product->name }}"
                                                                data-stock="{{ $product->stock }}">
                                                                Expense
                                                            </button>
                                                        @endif
                                                    @endif




                                                    @if(auth()->user()->branch->type === 'Headoffice')
                                                        @if(auth()->user()->role_name !== 'Admin' && $product->stock > 0 && $product->branch_id === auth()->user()->branch_id)


                                                           <!-- friday last new add this button all 3 -->

                                                            <!-- <button type="button" class="btn btn-sm btn-secondary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#damagereturnModal" 
                                                                data-product-id="{{ $product->product_id }}"
                                                                data-product-name="{{ $product->product->name }}"
                                                                data-stock="{{ $product->stock }}">
                                                                Damage/Return
                                                            </button> -->

                                                            <button type="button" class="btn btn-sm btn-warning" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#damagereturnModal"
                                                                data-product-id="{{ $product->product_id }}"
                                                                data-product-name="{{ $product->product->name }}"
                                                                data-stock="{{ $product->stock }}"
                                                                data-remain-details='@json($product->remain_details)'>
                                                                Return
                                                            </button>

                                                        @endif
                                                    @else
                                                        @if(auth()->user()->branch->type !== 'Warehouse')
                                                            @if(auth()->user()->role_name !== 'Admin' && $product->stock > 0)

                                                                <!-- <button type="button" class="btn btn-sm btn-secondary" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#damagereturnModal" 
                                                                    data-product-id="{{ $product->product_id }}"
                                                                    data-product-name="{{ $product->product->name }}"
                                                                    data-stock="{{ $product->stock }}">
                                                                    Damage/Return
                                                                </button> --> 

                                                                <button type="button" class="btn btn-sm btn-warning" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#damagereturnModal"
                                                                    data-product-id="{{ $product->product_id }}"
                                                                    data-product-name="{{ $product->product->name }}"
                                                                    data-stock="{{ $product->stock }}"
                                                                    data-remain-details='@json($product->remain_details)'>
                                                                    Return
                                                                </button>


                                                            @endif
                                                        @elseif(auth()->user()->role_name === "Super Admin" && auth()->user()->role_name !== 'Admin' && $product->stock > 0) 

                                                            <!-- <button type="button" class="btn btn-sm btn-secondary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#damagereturnModal" 
                                                                data-product-id="{{ $product->product_id }}"
                                                                data-product-name="{{ $product->product->name }}"
                                                                data-stock="{{ $product->stock }}">
                                                                Damage/Return
                                                            </button> -->


                                                            <button type="button" class="btn btn-sm btn-warning" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#damagereturnModal"
                                                                data-product-id="{{ $product->product_id }}"
                                                                data-product-name="{{ $product->product->name }}"
                                                                data-stock="{{ $product->stock }}"
                                                                data-remain-details='@json($product->remain_details)'>
                                                                Return
                                                            </button>

                                                        @endif
                                                    @endif 




                                                    <!-- @php
                                                        $user = Auth::user();
                                                        $userBranch = $user->branch;
                                                    @endphp

                                                    @if (
                                                        ($user->role_name === 'Admin' && $productReturnCounts->has($product->product_id)) ||
                                                        (
                                                            in_array($userBranch->type, ['Branch', 'Headoffice']) &&
                                                            $productReturnCounts->has($product->product_id)
                                                        )
                                                    )

                                                        <i class="fas fa-info-circle ms-2 text-primary"
                                                        role="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#infoModal"
                                                        data-product-name="{{ $product->product->name }}"
                                                        data-pending-count="{{ $productReturnCounts[$product->product_id] ?? 0 }}"
                                                        title="Click to view more info.">
                                                        </i>

                                                    @endif   -->



                                                        <!-- @php
                                                            $user = Auth::user();
                                                            $isAdminInWarehouse = ($user->role_name === 'Admin' && $branch->type === 'Warehouse');
                                                            $isWarehouseUser = ($user->branch->type === 'Warehouse');
                                                            
                                                            if ($isAdminInWarehouse || $isWarehouseUser) {
                                                                $key = $product->product_id . '_' . $product->price;
                                                                $hasPendingReturns = $productReturnCounts->has($key);
                                                                $pendingCount = $hasPendingReturns ? $productReturnCounts[$key] : 0;
                                                            } else {
                                                                $hasPendingReturns = $productReturnCounts->has($product->product_id);
                                                                $pendingCount = $hasPendingReturns ? $productReturnCounts[$product->product_id] : 0;
                                                            }
                                                        @endphp

                                                        @if ($hasPendingReturns)
                                                            <i class="fas fa-info-circle ms-2 text-primary"
                                                                role="button"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#infoModal"
                                                                data-product-name="{{ $product->product->name }}"
                                                                data-pending-count="{{ $pendingCount }}"
                                                                title="Click to view more info.">
                                                            </i>
                                                        @endif -->


                                                        @php
                                                            $user = Auth::user();
                                                            $isWarehouseContext = ($branch->type === 'Warehouse');
                                                            $isAdminOrHeadoffice = ($user->role_name === 'Admin' || 
                                                                                ($user->role_name === 'Headoffice' && $user->branch->type === 'Headoffice'));
                                                            
                                                            if ($isWarehouseContext && $isAdminOrHeadoffice) {
                                                                $key = $product->product_id . '_' . $product->price;
                                                                $hasPendingReturns = $productReturnCounts->has($key);
                                                                $pendingCount = $hasPendingReturns ? $productReturnCounts[$key] : 0;
                                                            } else {
                                                                $hasPendingReturns = $productReturnCounts->has($product->product_id);
                                                                $pendingCount = $hasPendingReturns ? $productReturnCounts[$product->product_id] : 0;
                                                            }
                                                        @endphp

                                                        @if ($hasPendingReturns)
                                                            <i class="fas fa-info-circle ms-2 text-primary"
                                                                role="button"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#infoModal"
                                                                data-product-name="{{ $product->product->name }}"
                                                                data-pending-count="{{ $pendingCount }}"
                                                                title="Click to view more info.">
                                                            </i>
                                                        @endif

                                                
                                                </td>
                                            </tr>

                                            @php
                                                $totalStock += $product->stock;
                                            @endphp
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

 
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">
      <div class="modal-header bg-light">
        <h5 class="modal-title" id="infoModalLabel">Information</h5>
        <button type="button" class="btn fs-4 text-danger border-0 bg-transparent" data-bs-dismiss="modal" aria-label="Close">
          &times;
        </button>
      </div>
      <div class="modal-body text-center py-4">
        <p class="mb-0 fs-5 text-secondary"></p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="damagereturnModal" tabindex="-1" aria-labelledby="damagereturnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="damagereturnModalLabel">Damage/Return Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="damagereturnForm" method="POST" action="{{ route('damage.return.store.single') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                    <input type="hidden" id="damagereturnProductId" name="product_id">

                    <div class="mb-3">
                        <label for="damagereturnProductName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="damagereturnProductName" name="product_name" readonly>
                    </div>


                     <!-- friday last new add this price -->
                    <div class="form-group">
                        <label for="damagereturnPrice">Price</label>
                        <!-- <input type="number" step="0.01" name="price" id="damagereturnPrice" class="form-control" required>  -->
                        <input type="number" id="damagereturnPrice" name="price" step="0.01" class="form-control" required>

                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <label for="damagereturnQuantity" class="form-label">Damage/Return Quantity</label>
                            <input type="number" class="form-control" id="damagereturnQuantity" name="quantity" min="1" required placeholder="Quantity">
                        </div>
                        <div class="col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required placeholder="Enter Date">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required placeholder="Reason for damage/return"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- //main -->
<script>
    $(document).ready(function () {
      
        $('#damagereturnModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var productId = button.data('product-id');
            var productName = button.data('product-name');
            var stock = button.data('stock');
            
            $('#damagereturnProductId').val(productId);
            $('#damagereturnProductName').val(productName);
            $('#damagereturnQuantity').val(1); 
        });

        // $('#damagereturnForm').on('submit', function (e) {
        //     e.preventDefault(); 

        //     var branchId = $("input[name='branch_id']").val();
        //     var productId = $('#damagereturnProductId').val();
        //     var quantity = $('#damagereturnQuantity').val();

        //     $.ajax({
        //         url: "{{ route('damage.return.check') }}",
        //         type: "POST",
        //         data: {
        //             _token: "{{ csrf_token() }}",
        //             branch_id: branchId,
        //             product_id: productId,
        //             quantity: quantity
        //         },
        //         success: function (response) {
        //             if (response.success) {
        //                 $('#damagereturnForm')[0].submit();
        //             } else {
        //                 toastr.error(response.message);
        //             }
        //         }
        //     });
        // });



        $('#damagereturnForm').on('submit', function (e) {
            e.preventDefault(); 

            var branchId = $("input[name='branch_id']").val();
            var productId = $('#damagereturnProductId').val();
            var quantity = $('#damagereturnQuantity').val();
            var price = $('#damagereturnPrice').val(); // Get the price from the form

            $.ajax({
                url: "{{ route('damage.return.check') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    branch_id: branchId,
                    product_id: productId,
                    quantity: quantity,
                    price: price // Include price in the request
                },
                success: function (response) {
                    if (response.success) {
                        $('#damagereturnForm')[0].submit();
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        });


    });
</script> 



<!-- correction for main today -->
<!-- <script>
    $(document).ready(function () {
        let productBreakdowns = @json($products->pluck('remain_details', 'product_id'));

        $('#damagereturnModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var productId = button.data('product-id');
            var productName = button.data('product-name');
            var stock = button.data('stock');

            $('#damagereturnProductId').val(productId);
            $('#damagereturnProductName').val(productName);
            $('#damagereturnQuantity').val(1); 
            $('#damagereturnPrice').val(''); // clear price on open
        });

        $('#damagereturnForm').on('submit', function (e) {
            e.preventDefault(); 

            var branchId = $("input[name='branch_id']").val();
            var productId = $('#damagereturnProductId').val();
            var quantity = parseInt($('#damagereturnQuantity').val());
            var price = parseFloat($('#damagereturnPrice').val());

            // 🔸 Price breakdown validation start
            let rawDetails = productBreakdowns[productId];
            let priceFound = false;
            let matchingQty = 0;

            if (rawDetails) {
                let parsed = JSON.parse(rawDetails);
                parsed.forEach(detail => {
                    let detailPrice = parseFloat(detail.prc);
                    let detailQty = parseInt(detail.qty);

                    if (detailPrice === price) {
                        priceFound = true;
                        matchingQty = detailQty;
                    }
                });
            }

            if (!priceFound) {
                toastr.error('This price does not match any available product price.');
                return;
            }

            if (matchingQty === 0) {
                toastr.error('This price has no available quantity for return.');
                return;
            }

            if (quantity > matchingQty) {
                toastr.error(`Only ${matchingQty} items are available at price ${price}.`);
                return;
            }
            // 🔸 Price breakdown validation end

            // Continue with your existing AJAX call
            $.ajax({
                url: "{{ route('damage.return.check') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    branch_id: branchId,
                    product_id: productId,
                    quantity: quantity,
                    price: price
                },
                success: function (response) {
                    if (response.success) {
                        $('#damagereturnForm')[0].submit();
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        });
    });
</script> -->


























<!-- old last -->

<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        var damagereturnModal = document.getElementById('damagereturnModal');

        damagereturnModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; 
            var productId = button.getAttribute('data-product-id');
            var productName = button.getAttribute('data-product-name');
            var stock = button.getAttribute('data-stock'); 

            var modalProductName = damagereturnModal.querySelector('#damagereturnProductName');
            var modalProductId = damagereturnModal.querySelector('#damagereturnProductId');
            var modalQuantity = damagereturnModal.querySelector('#damagereturnQuantity');

            modalProductName.value = productName;
            modalProductId.value = productId;
            modalQuantity.value = ''; 

            damagereturnModal.setAttribute('data-stock', stock);
        });
    });
</script> -->



<!-- 
new add 1st code friday -->
<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        var damagereturnModal = document.getElementById('damagereturnModal');

        damagereturnModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            var productId = button.getAttribute('data-product-id');
            var productName = button.getAttribute('data-product-name');
            var stock = button.getAttribute('data-stock');
            var remainDetails = button.getAttribute('data-remain-details');

            damagereturnModal.querySelector('#damagereturnProductName').value = productName;
            damagereturnModal.querySelector('#damagereturnProductId').value = productId;
            damagereturnModal.querySelector('#damagereturnQuantity').value = 1;
            damagereturnModal.querySelector('#damagereturnPrice').value = '';

            damagereturnModal.setAttribute('data-stock', stock);
            damagereturnModal.setAttribute('data-remain-details', remainDetails);
        });

        $('#damagereturnForm').on('submit', function (e) {
            e.preventDefault();

            var branchId = $("input[name='branch_id']").val();
            var productId = $('#damagereturnProductId').val();
            var price = parseFloat($('#damagereturnPrice').val());
            var quantity = parseInt($('#damagereturnQuantity').val());
            var remainDetailsJson = $('#damagereturnModal').attr('data-remain-details');

            try {
                var remainDetails = JSON.parse(remainDetailsJson);
            } catch (err) {
                toastr.error("Invalid price breakdown data.");
                return;
            }

            // 1. Check if price exists and has > 0 quantity
            var detail = remainDetails.find(d => parseFloat(d.prc) === price);
            if (!detail) {
                toastr.error("This price does not exist in the price breakdown.");
                return;
            }

            if (parseInt(detail.qty) === 0) {
                toastr.error("This price has no available quantity.");
                return;
            }

            // 2. Check with backend if this user already has a pending return at this price
            $.ajax({
                url: "{{ route('damage.return.check.stock') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    branch_id: branchId,
                    product_id: productId,
                    quantity: quantity,
                    price: price
                },
                success: function (response) {
                    if (response.success) {
                        $('#damagereturnForm')[0].submit();
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        });
    });
</script> -->




 <!-- friday new code 2 -->
<script>
    $(document).ready(function () {
        let productBreakdowns = @json($products->pluck('remain_details', 'product_id'));

        $('#damagereturnModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var productId = button.data('product-id');
            var productName = button.data('product-name');
            var stock = button.data('stock');

            $('#damagereturnProductId').val(productId);
            $('#damagereturnProductName').val(productName);
            $('#damagereturnQuantity').val(1); 
            $('#damagereturnPrice').val(''); // clear price on open
        });

        $('#damagereturnForm').on('submit', function (e) {
            e.preventDefault(); 

            var branchId = $("input[name='branch_id']").val();
            var productId = $('#damagereturnProductId').val();
            var quantity = parseInt($('#damagereturnQuantity').val());
            var price = parseFloat($('#damagereturnPrice').val());

            // Parse price breakdown for current product
            let rawDetails = productBreakdowns[productId];
            let priceFound = false;
            let matchingQty = 0;

            if (rawDetails) {
                let parsed = JSON.parse(rawDetails);
                parsed.forEach(detail => {
                    let detailPrice = parseFloat(detail.prc);
                    let detailQty = parseInt(detail.qty);

                    if (detailPrice === price) {
                        priceFound = true;
                        matchingQty = detailQty;
                    }
                });
            }

            // 1️⃣: If price does not exist in breakdown
            if (!priceFound) {
                // toastr.error('This price does not match any available product price.');
                return;
            }

            // 2️⃣: If quantity is zero for that price
            if (matchingQty === 0) {
                toastr.error('This price has no available quantity for return.');
                return;
            }

            // 3️⃣: Check pending return for same price, user, product, and branch
            // $.ajax({
            //     url: "{{ route('damage.return.check') }}",
            //     type: "POST",
            //     data: {
            //         _token: "{{ csrf_token() }}",
            //         branch_id: branchId,
            //         product_id: productId,
            //         quantity: quantity,
            //         price: price
            //     },
            //     success: function (response) {
            //         if (response.success) {
            //             $('#damagereturnForm')[0].submit();
            //         } else {
            //             toastr.error(response.message);
            //         }
            //     }
            // });
        });
    });
</script> 




 <!-- friday other code  -->

 <script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('damagereturnForm');
    const priceInput = document.getElementById('damagereturnPrice');

    form.addEventListener('submit', function (e) {
        const inputPrice = parseFloat(priceInput.value);
        let priceFound = false;

        // Loop over all elements that store price breakdowns
        document.querySelectorAll('.price-breakdown').forEach(function (el) {
            const breakdownPrice = parseFloat(el.dataset.price);
            if (inputPrice === breakdownPrice) {
                priceFound = true;
            }
        });

        // If no matching price, prevent form submission and show error
        if (!priceFound) {
            e.preventDefault(); 
            // toastr.error('This price does not match any available product price.');
        }
    });
});
</script>





    
    <div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStockModalLabel">Add Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStockForm" method="POST" action="{{ route('stock.add') }}">
                        @csrf
                        <input type="hidden" name="branch_id" value="{{ $branch->id }}">

                        <div class="mb-3">
                            <label for="batch" class="form-label">Batch</label>
                            <input type="text" class="form-control" id="batch" name="batch" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="product_name" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="productStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" min="1" id="productStock" name="stock" required>
                        </div>

                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Payment Method:</label>
                            <select class="form-select" id="paymentMethod" name="payment_method" required>
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

                        <input type="hidden" id="productId" name="product_id">
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Stock</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




<div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expenseModalLabel">Expense Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="expenseForm" method="POST" action="{{ route('expenses.store.single') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                    <input type="hidden" id="expenseProductId" name="product_id">

                    <div class="mb-3">
                        <label for="expenseProductName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="expenseProductName" name="product_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="consigneeName" class="form-label">Consignee Name</label>
                        <input type="text" class="form-control" id="consigneeName" name="consignee_name" required placeholder="Consignee Name">
                    </div>
                    <div class="mb-3">
                        <label for="expenseDate" class="form-label">Expense Date</label>
                        <input type="date" class="form-control" id="expenseDate" name="expense_date" required placeholder="Date">
                    </div>
                    <div class="mb-3">
                        <label for="amountExpenditure" class="form-label">Amount of Expenditure</label>
                        <input type="number" class="form-control" id="amountExpenditure" name="amount_expenditure" min="1" required placeholder="Amount of Expenditure">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $('#expenseForm').on('submit', function (e) {
    e.preventDefault(); 

    var branchId = $("input[name='branch_id']").val();
    var productId = $('#expenseProductId').val();
    var amount_expenditure = $('#amountExpenditure').val();

    $.ajax({
        url: "{{ route('check.expense.stock') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            branch_id: branchId,
            product_id: productId,
            amount_expenditure: amount_expenditure
        },
        success: function (response) {
            if (response.success) {
                $('#expenseForm')[0].submit();
            } else {
                console.error("Error:", response.message);
                toastr.error(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
            toastr.error("An error occurred while checking the stock. Please try again.");
        }
    });
});

</script>




    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var expenseModal = document.getElementById('expenseModal');

            expenseModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget; 
                var productId = button.getAttribute('data-product-id');
                var productName = button.getAttribute('data-product-name');
                var stock = button.getAttribute('data-stock'); 

                var modalProductName = expenseModal.querySelector('#expenseProductName');
                var modalProductId = expenseModal.querySelector('#expenseProductId');



                var modalQuantity = expenseModal.querySelector('#amountExpenditure');


                modalProductName.value = productName;
                modalProductId.value = productId;



                modalQuantity.value = ''; 

                expenseModal.setAttribute('data-stock', stock);
            });
        });
    </script>


    <script>
        $(document).on('click', '.open-expense-modal', function() {
            var productId = $(this).data('product-id');
            var productName = $(this).data('product-name'); 

            $('#expenseProductId').val(productId);
            $('#expenseProductName').val(productName);
            
            $('#expenseModal').modal('show');
        });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var addStockModal = document.getElementById('addStockModal');

            addStockModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var productId = button.dataset.productId;  
                var productName = button.getAttribute('data-product-name');
                var batch = button.getAttribute('data-batch');

                var modalProductName = addStockModal.querySelector('#productName');
                var modalProductId = addStockModal.querySelector('#productId');
                var modalBatch = addStockModal.querySelector('#batch');

                modalProductName.value = productName;
                modalProductId.value = productId;
                modalBatch.value = batch;
            });
        });

    </script>



<script>
    const infoModal = document.getElementById('infoModal');
    infoModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const count = button.getAttribute('data-pending-count');
        const productName = button.getAttribute('data-product-name');
        const modalBody = infoModal.querySelector('.modal-body p');
        modalBody.textContent = `${count} product return request pending for "${productName}".`;
    });
</script>



@endsection
