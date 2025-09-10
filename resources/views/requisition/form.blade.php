@extends('layouts.master')
@section('content')

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Create Requisition</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="">Requisition</a></li>
                            <li class="breadcrumb-item active">Create Requisition</li>
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
                    <form id="requisitionForm" action="{{route('requisition.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="form-group col-md-4">
                                <label for="branch_id">Branch</label>
                                <select class="form-control select2" name="branch_id" id="branch_id" required>
                                    <option selected value="">--Select Branch--</option>
                                    @foreach($branches as $id => $name)
                                        <option value="{{ $id }}" 
                                            {{ (auth()->user()->branch_id == $id && ($userBranch == 'Branch' || $userBranch == 'Warehouse' || $userBranch == 'Headoffice')) ? 'selected' : '' }}>
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

                            <!-- <div class="form-group col-md-4">
                                <label for="project_name">Project Name</label>
                                <input type="text" class="form-control" id="project_name" required name="project_name" placeholder="Project Name">
                            </div> -->


                            <div class="form-group col-md-4">
                                <label for="project_id">Project Name</label>
                                <select class="form-control select2" id="project_id" name="project_id" required>
                                    <option value="">-- Select Project --</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group col-md-4">
                                <label for="date_from">Date</label>
                                <input type="text" class="form-control" id="date_from" name="date_from"
                                    value="{{ \Carbon\Carbon::today()->format('d/m/Y') }}" required readonly>
                            </div>



                        </div>

                        <table class="table table-bordered table-hover" id="itemTable">
                            <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Qunatity</th>
                                    <th class="text-center">Total Price</th>
                                    <!-- <th class="text-center">Stock</th>
                                    <th class="text-center">Authorization Amount</th> -->
                                    <th class="text-center">Comment</th>
                                    <th class="text-center"></th> 
                                </tr>
                            </thead>
                            <tbody>
                                
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <select style="width: 200px;" name="items[0][name]" class="form-control product-select select2" required>
                                            <option value="" disabled selected>--Select Product--</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="items[0][description]" placeholder="Description" class="form-control description" required /></td>
                                    <td><input min="1" type="number" name="items[0][price]" placeholder="Price" class="form-control price" required readonly /></td>
                                    <td><input min="1" type="number" name="items[0][amount]" placeholder="Qunatity" class="form-control amount" required /></td>
                                    <td><input min="1" type="number" name="items[0][total_price]" placeholder="0" class="form-control total_price" required disabled /></td>

                                    <!-- <td><input min="1" type="number" name="items[0][stock]" placeholder="Stock" class="form-control stock" required /></td>
                                    <td><input min="1" type="number" name="items[0][authorization_amount]" placeholder="Authorization Amount" class="form-control" required /></td> -->

                                    <td><input type="text" name="items[0][comment]" placeholder="Comment" class="form-control" required /></td>
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
    var i = 1;

    function updateSerialNumbers() {
        $('#itemTable tbody tr').each(function(index, row) {
            $(row).find('td:first').text(index + 1);
        });
    }

    function calculateTotalPrice(row) {
        var price = parseFloat(row.find('.price').val()) || 0;
        var amount = parseFloat(row.find('.amount').val()) || 0;
        var totalPrice = price * amount;
        row.find('.total_price').val(totalPrice.toFixed(2));
    }

    function updateProductSelection() {
        var selectedProducts = [];
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

    $("#add_row").click(function() {
        var productOptions = '@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }}</option>@endforeach';
        var newRow = '<tr>' +
            '<td>' + ($('#itemTable tbody tr').length + 1) + '</td>' +
            '<td><select name="items[' + i + '][name]" class="form-control product-select select2" required>' +
                '<option value="" disabled selected>Select Product</option>' +
                productOptions +
            '</select></td>' +
            '<td><input type="text" name="items[' + i + '][description]" placeholder="Description" class="form-control description" required /></td>' +
            '<td><input min="1" type="number" name="items[' + i + '][price]" placeholder="Price" class="form-control price" required readonly /></td>' +
            '<td><input min="1" type="number" name="items[' + i + '][amount]" placeholder="Quantity" class="form-control amount" required /></td>' +
            '<td><input min="1" type="number" name="items[' + i + '][total_price]" placeholder="0" class="form-control total_price" required readonly /></td>' +


            // '<td><input min="1" type="number" name="items[' + i + '][stock]" placeholder="Stock" class="form-control stock" required /></td>' +
            // '<td><input min="1" type="number" name="items[' + i + '][authorization_amount]" placeholder="Authorization Amount" class="form-control" required /></td>' +


            '<td><input type="text" name="items[' + i + '][comment]" placeholder="Comment" class="form-control" required /></td>' +
            '<td><a class="delete-row" style="cursor: pointer;"><i class="fas fa-trash text-danger"></i></a></td>' +
            '</tr>';

        $('#itemTable').append(newRow);
        $('#itemTable .select2').last().select2();

        i++;
        updateSerialNumbers();
        updateDeleteButtons();
        updateProductSelection();
    });



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
                    row.find('.stock').val(1); 
                    calculateTotalPrice(row); 
                },
                error: function() {
                    alert('Product details could not be retrieved.');
                }
            });
        }
        updateProductSelection();
    });

    $(document).on('click', '.delete-row', function() {
        $(this).closest('tr').remove();
        updateSerialNumbers();
        updateDeleteButtons();
        updateProductSelection();
    });

    function updateDeleteButtons() {
        var rowCount = $('#itemTable tbody tr').length;
        if (rowCount == 1) {
            $('#itemTable tbody tr td:last-child').empty();
        } else {
            $('#itemTable tbody tr td:last-child').html('<a class="delete-row" style="cursor: pointer;"><i class="fas fa-trash text-danger"></i></a>');
        }
    }

    $('#itemTable').on('input', '.price, .amount', function() {
        var row = $(this).closest('tr');
        calculateTotalPrice(row);
    });

    updateDeleteButtons();
    updateProductSelection();
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
