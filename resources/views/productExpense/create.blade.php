@extends('layouts.master')
@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Expense Entry</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="">Expense</a></li>
                            <li class="breadcrumb-item active">Expense Entry</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- message --}}
        {!! Toastr::message() !!}

        <br> </br>

        <div class="content container-fluid">
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <form id="requisitionForm" action="{{ route('product.expense.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="form-group col-md-4">
                                <label for="branch_id">Expense from Branch</label>
                                <select class="form-control select2" name="branch_id" id="branch_id" required>
                                    <option value="" selected disabled>--Select Branch--</option>
                                    @foreach($branches as $id => $name)
                                        <option value="{{ $id }}" 
                                            {{ (auth()->user()->branch_id == $id && $userBranch == 'Branch') ? '' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="consignee_name">Consignee Name</label>
                                <input type="text" class="form-control" id="consignee_name" required name="consignee_name" placeholder="Consignee Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="date_from"> Expense Date</label>
                                <input type="date" class="form-control" id="date_from" required name="date_from" placeholder="Date">
                            </div>
                        </div>

                        <table class="table table-bordered table-hover" id="itemTable">
                            <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Unit Price</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center">Ammount of Expenditure</th>
                                    <th class="text-center"></th> 
                                </tr>
                            </thead>
                            <tbody>
                                
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <select style="width: 350px;" name="items[0][name]" class="form-control product-select select2" required>
                                            <option value="" disabled selected>--Select Product--</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input min="1" type="number" name="items[0][price]" placeholder="Price" class="form-control price" required readonly /></td>
                                    <td><input min="1" type="number" name="items[0][stock]" placeholder="Stock" class="form-control stock" min="1" required readonly /></td>
                                    <td><input min="1" type="number" name="items[0][amount]" placeholder="Ammount of Expenditure" class="form-control amount" required /></td>
                                    <td></td> 
                                </tr>
                            </tbody>
                        </table>
                        <div class="my-3 text-center">
                            <button type="button" id="add_row" class="btn btn-primary">Add Item</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript">
    $(document).ready(function() {
        let branchProducts = {}; 
        let i = 0; 

        $('#branch_id').on('change', function() {
            const branchId = $(this).val();

            if (branchId) {
                $.ajax({
                    url: '/branch-products/' + branchId,
                    type: 'GET',
                    success: function(data) {
                        branchProducts = {}; 
                        $('.product-select').empty().append('<option value="" disabled selected>--Select Product--</option>');

                        data.forEach(function(item) {
                            branchProducts[item.product_id] = item.stock; 
                            $('.product-select').append(
                                `<option value="${item.product_id}" data-stock="${item.stock}">${item.product.name}</option>`
                            );
                        });

                        updateProductSelection();
                    },
                    error: function() {
                        alert('Could not retrieve branch products.');
                    }
                });
            }
        });

        $(document).on('change', '.product-select', function() {
            const row = $(this).closest('tr');
            const selectedProductId = $(this).val();
            const stock = branchProducts[selectedProductId] || 0; 

            row.find('.stock').val(stock);
            updateProductSelection();
        });

        function updateSerialNumbers() {
            $('#itemTable tbody tr').each(function(index, row) {
                $(row).find('td:first').text(index + 1);
            });
        }

        function updateProductSelection() {
            const selectedProducts = [];
            $('.product-select').each(function() {
                if ($(this).val()) {
                    selectedProducts.push($(this).val());
                }
            });

            $('.product-select').each(function() {
                $(this).find('option').each(function() {
                    if (selectedProducts.includes($(this).val())) {
                        if (!$(this).is(':selected')) {
                            $(this).prop('disabled', true);
                        }
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            });
        }


        $(document).on('change', '.product-select', function() {
            var row = $(this).closest('tr');
            var productId = $(this).val();

            if (productId) {
                $.ajax({
                    url: '/product-details/' + productId,
                    type: 'GET',
                    success: function(data) {
                        row.find('.description').val(data.description);
                        row.find('.price').val(data.price);
                        calculateTotalPrice(row);
                    },
                    error: function() {
                        alert('Product details could not be retrieved.');
                    }
                });
            }
            updateProductSelection();
        });


        $(document).on('change', '.product-select', function() {
            const row = $(this).closest('tr');
            const selectedProductId = $(this).val();
            const stock = branchProducts[selectedProductId] || 0;

            row.find('.stock').val(stock);
            row.find('.amount').attr('max', stock); 
            updateProductSelection();
        });

        



        // $(document).on('input', '.amount', function() {
        //     const maxStock = parseInt($(this).attr('max'), 10);
        //     const amountValue = parseInt($(this).val(), 10);

        //     if (amountValue > maxStock) {
        //         $(this).addClass('is-invalid'); 
        //         toastr.error(`Amount of Expenditure cannot exceed stock value (${maxStock}).`); 
        //         $(this).val(maxStock); 
        //     } else {
        //         $(this).removeClass('is-invalid');
        //     }
        // });



        //old logic after before reset input filed 

    // $(document).on('input', '.amount', function() {
    //     const row = $(this).closest('tr');
    //     const branchId = $('#branch_id').val();
    //     const productId = row.find('.product-select').val();
    //     const inputAmount = parseInt($(this).val(), 10);

    //     if (branchId && productId) {
       
    //         $.ajax({
    //             url: '/fetch-product-data',
    //             type: 'GET',
    //             data: {
    //                 branch_id: branchId,
    //                 product_id: productId
    //             },
    //             success: function(data) {
    //                 const allPendingReturnValue = data.pendingReturnQuantity; 
    //                 const restOfStock = data.stock; 
    //                 const checkAllStock = restOfStock - allPendingReturnValue;

    //                 if (inputAmount > checkAllStock) {
                 
    //                     $(this).val(checkAllStock);
    //                     $(this).addClass('is-invalid');
    //                     toastr.error(`Amount cannot exceed available stock of ${checkAllStock}, So check some pending product in damage/return list`);
    //                 } else {
    //                     $(this).removeClass('is-invalid');
    //                 }
    //             },
    //             error: function() {
    //                 alert('Could not retrieve product data.');
    //             }
    //         });
    //     }
    // });



    $(document).on('input', '.amount', function() {
    const $inputField = $(this); 
    const row = $inputField.closest('tr');
    const branchId = $('#branch_id').val();
    const productId = row.find('.product-select').val();
    const inputAmount = parseInt($inputField.val(), 10);

            if (branchId && productId) {
                $.ajax({
                    url: '/fetch-product-data',
                    type: 'GET',
                    data: {
                        branch_id: branchId,
                        product_id: productId
                    },
                    success: function(data) {
                        const allPendingReturnValue = data.pendingReturnQuantity; 
                        const restOfStock = data.stock; 
                        const checkAllStock = restOfStock - allPendingReturnValue;

                        if (inputAmount > checkAllStock) {
                            $inputField.val(''); 
                            $inputField.addClass('is-invalid');
                            toastr.error(`Amount cannot exceed available stock of ${checkAllStock}, so check some pending product in damage/return list.`);
                        } else {
                            $inputField.removeClass('is-invalid');
                        }
                    },
                    error: function() {
                        alert('Could not retrieve product data.');
                    }
                });
            }
        });







        $('#add_row').click(function() {
            i++;  
            
            const newRow = `
                <tr>
                    <td>${$('#itemTable tbody tr').length + 1}</td>
                    <td>
                        <select style="width: 350px;" name="items[${i}][name]" class="form-control product-select select2" required>
                            <option value="" disabled selected>--Select Product--</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="items[${i}][price]" placeholder="Price" class="form-control price" required readonly /></td>
                    <td><input type="number" name="items[${i}][stock]" placeholder="Stock" class="form-control stock" required readonly /></td>
                    <td><input type="number" name="items[${i}][amount]" placeholder="Amount of Expenditure" class="form-control amount" min="1" required /></td>
                    <td><a class="delete-row" style="cursor: pointer;"><i class="fas fa-trash text-danger"></i></a></td>
                </tr>`;

            $('#itemTable tbody').append(newRow);
            $('.select2').last().select2(); 
            updateSerialNumbers();
            updateDeleteButtons();
            updateProductSelection();
        });


        $(document).on('click', '.delete-row', function() {
            $(this).closest('tr').remove();
            updateSerialNumbers();
            updateDeleteButtons();
            updateProductSelection();
        });

        function updateDeleteButtons() {
            const rowCount = $('#itemTable tbody tr').length;
            if (rowCount === 1) {
                $('#itemTable tbody tr td:last-child').empty();
            } else {
                $('#itemTable tbody tr td:last-child').html('<a class="delete-row" style="cursor: pointer;"><i class="fas fa-trash text-danger"></i></a>');
            }
        }
    });
</script>




<style>
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    #requisitionForm .table tbody tr:last-child {
        border: #dee2e6;
    }
</style>
@endsection



@section('script')
<script>
    $('.select2').select2();
</script>
<style>
    .select2-container .select2-selection--single {
        height: 42px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px;
    }
</style>
@endsection
