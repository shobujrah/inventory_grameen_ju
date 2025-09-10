
@extends('layouts.master')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Customers</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Customers</li>
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
                                        <h3 class="page-title">Customers</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> Download</a>
                                        <a href="#" class="btn btn-primary" data-url="{{ route('customer.create') }}" data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Customer')}}"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th>Email</th>
                                            <th>Balance</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($customers as $index => $customer)
                                        <tr>
                                            <td class="Id">
                                                <a href="{{ route('customer.show',\Crypt::encrypt($customer['id'])) }}" class="btn btn-outline-primary">
                                                    {{ \App\Models\Utility::customerNumberFormat($customer['customer_id']) }}
                                                </a>
                                            </td>
                                            <td class="font-style">{{$customer['name']}}</td>
                                            <td>{{$customer['contact']}}</td>
                                            <td>{{$customer['email']}}</td>
                                            <td>{{\App\Models\Utility::priceFormat($customer['balance'])}}</td>

                                            <td class="text-end">
                                                <div class="actions">

                                                    <a href="{{ route('customer.show',\Crypt::encrypt($customer['id'])) }}" class="mx-1 btn btn-sm align-items-center"
                                                        data-bs-toggle="tooltip" title="{{__('View')}}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                     <a href="#" class="mx-1 btn btn-sm align-items-center" data-url="{{ route('customer.edit',$customer->id) }}" data-ajax-popup="true" title="{{__('Edit')}}" data-title="{{__('Edit Customer')}}" data-bs-toggle="tooltip" data-size="lg" data-original-title="{{__('Edit')}}">
                                                        <i class="feather-edit"></i>
                                                    </a>

                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['customer.destroy', $customer->id],'id'=>'delete-form-'.$customer->id]) !!}
                                                        <a class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$customer->id}}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                        <div class="modal fade contentmodal" id="{{'deleteModal-'.$customer->id}}" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content doctor-profile">
                                                                    <div class="modal-header pb-0 border-bottom-0  justify-content-end">
                                                                        <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="delete-wrap text-center">
                                                                            <div class="del-icon">
                                                                                <i class="fas fa-trash-alt"></i>
                                                                            </div>
                                                                            <input type="hidden" name="id" class="e_id" value="">
                                                                            <input type="hidden" name="avatar" class="e_avatar" value="">
                                                                            <h2>Sure you want to delete?</h2>
                                                                            <div class="submit-section d-flex justify-content-center">
                                                                                <button type="submit" class="btn btn-success me-2">Yes</button>
                                                                                <a class="btn btn-danger me-2 d-flex justify-content-center" style="height: unset;" data-bs-dismiss="modal">No</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    {!! Form::close() !!}

                                                </div>
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
        </div>
    </div>

    <script>
        $(document).on('click', '#billing_data', function () {
            $("[name='shipping_name']").val($("[name='billing_name']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_phone']").val($("[name='billing_phone']").val());
            $("[name='shipping_zip']").val($("[name='billing_zip']").val());
            $("[name='shipping_address']").val($("[name='billing_address']").val());
        })

    </script>

@endsection

