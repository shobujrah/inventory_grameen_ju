@extends('layouts.master')

@section('content')

<script>
    $(document).on('click', '#shipping', function () {
        var url = $(this).data('url');
        var is_display = $("#shipping").is(":checked");
        $.ajax({
            url: url,
            type: 'get',
            data: {
                'is_display': is_display,
            },
            success: function (data) {
                // console.log(data);
            }
        });
    })



</script>
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Vendor Detail</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('vender.index') }}">Vendor</a></li>
                                <li class="breadcrumb-item active">{{ $vendor->name }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}

            <div class="row mb-3 text-end">
                <div class="col-md-12">
                    <a href="{{ route('bill.create',$vendor->id) }}" class="btn btn-primary btn-sm">Create Bill</a>

                    <a href="#" class="btn btn-primary btn-sm align-items-center" data-url="{{ route('vender.edit',$vendor->id) }}" data-ajax-popup="true" title="{{__('Edit')}}" data-title="{{__('Edit Vendor')}}" data-bs-toggle="tooltip" data-size="lg" data-original-title="{{__('Edit')}}">
                        <i class="feather-edit"></i>
                    </a>
                    <a class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$vendor->id}}">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                    {!! Form::open(['method' => 'DELETE','class' => 'delete-form-btn', 'route' => ['vender.destroy', $vendor['id']]]) !!}
                    <div class="modal fade contentmodal" id="{{'deleteModal-'.$vendor->id}}" tabindex="-1" aria-hidden="true">
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
            </div>

            <div class="row">
                <div class="col-md-4 col-lg-4 col-xl-4">
                    <div class="card pb-0 customer-detail-box vendor_card">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Vendor Info')}}</h5>
                            <p class="card-text mb-0">{{$vendor->name}}</p>
                            <p class="card-text mb-0">{{$vendor->email}}</p>
                            <p class="card-text mb-0">{{$vendor->contact}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                    <div class="card pb-0 customer-detail-box vendor_card">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Billing Info')}}</h5>
                            <p class="card-text mb-0">{{$vendor->billing_name}}</p>
                            <p class="card-text mb-0">{{$vendor->billing_address}}</p>
                            <p class="card-text mb-0">{{$vendor->billing_city.', '. $vendor->billing_state .', '.$vendor->billing_zip}}</p>
                            <p class="card-text mb-0">{{$vendor->billing_country}}</p>
                            <p class="card-text mb-0">{{$vendor->billing_phone}}</p>
        
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                    <div class="card pb-0 customer-detail-box vendor_card">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Shipping Info')}}</h5>
                            <p class="card-text mb-0">{{$vendor->shipping_name}}</p>
                            <p class="card-text mb-0">{{$vendor->shipping_address}}</p>
                            <p class="card-text mb-0">{{$vendor->shipping_city.', '. $vendor->shipping_state .', '.$vendor->shipping_zip}}</p>
                            <p class="card-text mb-0">{{$vendor->shipping_country}}</p>
                            <p class="card-text mb-0">{{$vendor->shipping_phone}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card pb-0">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Company Info')}}</h5>
                            <div class="row">
                                @php
                                    $totalBillSum=$vendor->vendorTotalBillSum($vendor['id']);
                                    $totalBill=$vendor->vendorTotalBill($vendor['id']);
                                    $averageSale=($totalBillSum!=0)?$totalBillSum/$totalBill:0;
                                @endphp
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                        <p class="card-text mb-0">{{__('Vendor Id')}}</p>
                                        <h6 class="report-text mb-3">{{\App\Models\Utility::venderNumberFormat($vendor->vender_id)}}</h6>
                                        <p class="card-text mb-0">{{__('Total Sum of Bills')}}</p>
                                        <h6 class="report-text mb-0">{{\App\Models\Utility::priceFormat($totalBillSum)}}</h6>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                        <p class="card-text mb-0">{{__('Date of Creation')}}</p>
                                        <h6 class="report-text mb-3">{{\App\Models\Utility::dateFormat($vendor->created_at)}}</h6>
                                        <p class="card-text mb-0">{{__('Quantity of Bills')}}</p>
                                        <h6 class="report-text mb-0">{{$totalBill}}</h6>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                        <p class="card-text mb-0">{{__('Balance')}}</p>
                                        <h6 class="report-text mb-3">{{\App\Models\Utility::priceFormat($vendor->balance)}}</h6>
                                        <p class="card-text mb-0">{{__('Average Sales')}}</p>
                                        <h6 class="report-text mb-0">{{\App\Models\Utility::priceFormat($averageSale)}}</h6>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                        <p class="card-text mb-0">{{__('Overdue')}}</p>
                                        <h6 class="report-text mb-3">{{\App\Models\Utility::priceFormat($vendor->vendorOverdue($vendor->id))}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-border-style">
                            <h5 class=" d-inline-block mb-5">{{__('Bills')}}</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{__('Bill')}}</th>
                                        <th>{{__('Bill Date')}}</th>
                                        <th>{{__('Due Date')}}</th>
                                        <th>{{__('Due Amount')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th width="10%"> {{__('Action')}}</th>
                                    </tr>
                                    </thead>
        
                                    <tbody>
                                    @foreach ($vendor->vendorBill($vendor->id) as $bill)
                                        <tr class="font-style">
                                            <td class="Id">
                                                <a href="{{ route('bill.show',\Crypt::encrypt($bill->id)) }}" class="btn btn-outline-primary">{{ \App\Models\Utility::billNumberFormat($bill->bill_id) }}
                                                </a>
                                            </td>
                                            <td>{{ \App\Models\Utility::dateFormat($bill->bill_date) }}</td>
                                            <td>
                                                @if(($bill->due_date < date('Y-m-d')))
                                                    <p class="text-danger"> {{ \App\Models\Utility::dateFormat($bill->due_date) }}</p>
                                                @else
                                                    {{ \App\Models\Utility::dateFormat($bill->due_date) }}
                                                @endif
                                            </td>
                                            <td>{{\App\Models\Utility::priceFormat($bill->getDue())  }}</td>
                                            <td>
                                                @if($bill->status == 0)
                                                    <span class="badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                @elseif($bill->status == 1)
                                                    <span class="badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                @elseif($bill->status == 2)
                                                    <span class="badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                @elseif($bill->status == 3)
                                                    <span class="badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                @elseif($bill->status == 4)
                                                    <span class="badge bg-success p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                @endif
                                            </td>
                                            
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <div class="actions">

                                                            <div class="action-btn">
                                                                {!! Form::open(['method' => 'get', 'route' => ['bill.duplicate', $bill->id],'id'=>'duplicate-form-'.$bill->id]) !!}
                                                                <a href="#" class="mx-1 btn btn-sm  align-items-center bs-pass-para"  data-bs-toggle="modal" data-bs-target="{{'#duplicate-'.$bill->id}}">
                                                                    <i class="far fa-copy"></i>
                                                                </a>
        
                                                                <div class="modal fade contentmodal" id="{{'duplicate-'.$bill->id}}" tabindex="-1" aria-hidden="true">
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
                                                                                        <i class="far fa-copy"></i>
                                                                                    </div>
                                                                                    <input type="hidden" name="id" class="e_id" value="">
                                                                                    <input type="hidden" name="avatar" class="e_avatar" value="">
                                                                                    <h2>Are you sure?</h2>
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
        
                                                            <a href="{{ route('bill.show',\Crypt::encrypt($bill->id)) }}" class="mx-1 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Show')}}" data-original-title="{{__('Detail')}}">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
        
                                                            <a href="{{ route('bill.edit',\Crypt::encrypt($bill->id)) }}" class="mx-1 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Edit" data-original-title="{{__('Edit')}}">
                                                                <i class="feather-edit"></i>
                                                            </a>
        
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['bill.destroy', $bill->id],'class'=>'delete-form-btn','id'=>'delete-form-'.$bill->id]) !!}
                                                                <a class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$bill->id}}">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </a>
                                                                <div class="modal fade contentmodal" id="{{'deleteModal-'.$bill->id}}" tabindex="-1" aria-hidden="true">
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
@endsection
