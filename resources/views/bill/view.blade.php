
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
                            <h3 class="page-title">Bill Detail</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('bill.index') }}">Bill</a></li>
                                <li class="breadcrumb-item active">Bill Details</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}

            @if($bill->status!=4)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row timeline-wrapper">

                                <div class="col-md-6 col-lg-4 col-xl-4 d-flex flex-column align-items-center">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="fas fa-plus text-primary"></i>
                                    </div>
                                    <h6 class="text-primary my-3">{{__('Create Bill')}}</h6>
                                    <p class="text-muted text-sm mb-3"><i class="far fa-clock mx-1"></i>{{__('Created on ')}}{{\App\Models\Utility::dateFormat($bill->bill_date)}}</p>
                                    
                                        <a href="{{ route('bill.edit',\Crypt::encrypt($bill->id)) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil mr-2"></i>{{__('Edit')}}</a>

                                    
                                </div>

                                <div class="col-md-6 col-lg-4 col-xl-4 d-flex flex-column align-items-center">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="far fa-envelope text-warning"></i>
                                    </div>
                                    <h6 class="text-warning my-3">{{__('Send Bill')}}</h6>
                                    <p class="text-muted text-sm mb-3">
                                        @if($bill->status!=0)
                                            <i class="far fa-clock mx-1"></i>{{__('Sent on')}} {{\App\Models\Utility::dateFormat($bill->send_date)}}
                                        @else
                                            <small>{{__('Status')}} : {{__('Not Sent')}}</small>
                                        @endif
                                    </p>

                                    @if($bill->status==0)
                                        <a href="{{ route('bill.sent',$bill->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-original-title="{{__('Mark Sent')}}"><i class="ti ti-send mr-2"></i>{{__('Send')}}</a>
                                        
                                    @endif
                                </div>

                                <div class="col-md-6 col-lg-4 col-xl-4 d-flex flex-column align-items-center">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="fas fa-money-check-alt text-info"></i>
                                    </div>
                                    <h6 class="text-info my-3">{{__('Get Paid')}}</h6>
                                    <p class="text-muted text-sm mb-3">{{__('Status')}} : {{__('Awaiting payment')}} </p>
                                    @if($bill->status!=0)
                                        <a href="#" data-url="{{ route('bill.payment',$bill->id) }}" data-ajax-popup="true" data-title="{{__('Add Payment')}}" class="btn btn-sm btn-info text-light" data-original-title="{{__('Add Payment')}}"><i class="ti ti-report-money mr-2"></i>{{__('Add Payment')}}</a> <br>
                                        
                                    @endif

                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            
        @if($bill->status!=0)

    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                    <h4>{{__('Bill')}}</h4>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                                    <h4 class="invoice-number">{{ \App\Models\Utility::billNumberFormat($bill->bill_id) }}</h4>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col text-end">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="me-4">
                                            <small>
                                                <strong>{{__('Issue Date')}} :</strong><br>
                                                {{\App\Models\Utility::dateFormat($bill->bill_date)}}<br><br>
                                            </small>
                                        </div>
                                        <div>
                                            <small>
                                                <strong>{{__('Due Date')}} :</strong><br>
                                                {{\App\Models\Utility::dateFormat($bill->due_date)}}<br><br>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <small class="font-style">
                                        <strong>{{__('Billed To')}} :</strong><br>
                                        @if(!empty($vendor->billing_name))
                                            {{!empty($vendor->billing_name)?$vendor->billing_name:''}}<br>
                                            {{!empty($vendor->billing_address)?$vendor->billing_address:''}}<br>
                                            {{!empty($vendor->billing_city)?$vendor->billing_city:'' .', '}}<br>
                                            {{!empty($vendor->billing_state)?$vendor->billing_state:'',', '}},
                                            {{!empty($vendor->billing_zip)?$vendor->billing_zip:''}}<br>
                                            {{!empty($vendor->billing_country)?$vendor->billing_country:''}}<br>
                                            {{!empty($vendor->billing_phone)?$vendor->billing_phone:''}}<br>
                                            <strong>{{__('Tax Number ')}} : </strong>{{!empty($vendor->tax_number)?$vendor->tax_number:''}}
                                        @else
                                            -
                                        @endif
                                    </small>
                                </div>

                                
                                    <div class="col">
                                        <small>
                                            <strong>{{__('Shipped To')}} :</strong><br>
                                            @if(!empty($vendor->shipping_name))
                                                {{!empty($vendor->shipping_name)?$vendor->shipping_name:''}}<br>
                                                {{!empty($vendor->shipping_address)?$vendor->shipping_address:''}}<br>
                                                {{!empty($vendor->shipping_city)?$vendor->shipping_city:'' . ', '}}<br>
                                                {{!empty($vendor->shipping_state)?$vendor->shipping_state:'' .', '}},
                                                {{!empty($vendor->shipping_zip)?$vendor->shipping_zip:''}}<br>
                                                {{!empty($vendor->shipping_country)?$vendor->shipping_country:''}}<br>
                                                {{!empty($vendor->shipping_phone)?$vendor->shipping_phone:''}}<br>
                                            @else
                                                -
                                            @endif
                                        </small>
                                    </div>
                                

                                <div class="col">
                                    <div class="float-end mt-3">
                                        {!! DNS2D::getBarcodeHTML(route('bill.link.copy',\Illuminate\Support\Facades\Crypt::encrypt($bill->id)), "QRCODE",2,2) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <small>
                                        <strong>{{__('Status')}} :</strong><br>
                                        @if($bill->status == 0)
                                            <span class="badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 1)
                                            <span class="badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 2)
                                            <span class="badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 3)
                                            <span class="badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 4)
                                            <span class="badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Bill::$statues[$bill->status]) }}</span>
                                        @endif
                                    </small>
                                </div>


                                @if(!empty($customFields) && count($bill->customField)>0)
                                    @foreach($customFields as $field)
                                        <div class="col text-md-end">
                                            <small>
                                                <strong>{{$field->name}} :</strong><br>
                                                {{!empty($bill->customField)?$bill->customField[$field->id]:'-'}}
                                                <br><br>
                                            </small>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="font-bold mb-2">{{__('Product Summary')}}</div>
                                    <small class="mb-2">{{__('All items here cannot be deleted.')}}</small>
                                    <div class="table-responsive mt-3">
                                        <table class="table mb-0 table-striped">
                                            <tr>
                                                <th class="text-dark" data-width="40">#</th>
                                                <th class="text-dark">{{__('Product')}}</th>
                                                <th class="text-dark">{{__('Quantity')}}</th>
                                                <th class="text-dark">{{__('Rate')}}</th>
                                                <th class="text-dark">{{__('Discount')}}</th>
                                                <th class="text-dark">{{__('Tax')}}</th>
                                                <th class="text-dark">{{__('Chart Of Account')}}</th>
                                                <th class="text-dark">{{__('Account Amount')}}</th>
                                                <th class="text-dark">{{__('Description')}}</th>
                                                <th class="text-end text-dark" width="12%">{{__('Price')}}<br>
                                                    <small class="text-danger font-weight-bold">{{__('after tax & discount')}}</small>
                                                </th>
                                                <th></th>
                                            </tr>
                                            @php
                                                $totalQuantity=0;
                                               $totalRate=0;
                                               $totalTaxPrice=0;
                                               $totalDiscount=0;
                                               $taxesData=[];
                                            @endphp

                                            @foreach($items as $key =>$item)
                                                @php
                                                    $totalQuantity+=$item->quantity;
                                                    $totalRate+=$item->price;
                                                    $totalDiscount+=$item->discount;
                                                @endphp

                                                @if(!empty($item->tax))
                                                    @php
                                                        $taxes=App\Models\Utility::tax($item->tax);
                                                        foreach($taxes as $taxe){
                                                            $taxDataPrice=App\Models\Utility::taxRate($taxe->rate,$item->price,$item->quantity,$item->discount);
                                                            if (array_key_exists($taxe->name,$taxesData))
                                                            {
                                                                $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                                            }
                                                            else
                                                            {
                                                                $taxesData[$taxe->name] = $taxDataPrice;
                                                            }
                                                        }
                                                    @endphp
                                                @endif

                                                @if(!empty($item->product_id))
                                                    <tr>
                                                        <td>{{$key+1}}</td>

                                                        <td>{{!empty($item->product)?$item->product->name:'-'}}</td>
                                                        @php
                                                        $unit = !empty($item->product)?$item->product->unit_id:'-';
                                                        $unitName = App\Models\ProductServiceUnit::find($unit);
                                                    @endphp
                                                    <td>{{$item->quantity . ' (' . $unitName->name . ')'}}</td>
                                                        <td>{{\App\Models\Utility::priceFormat($item->price)}}</td>
                                                        <td>{{\App\Models\Utility::priceFormat($item->discount)}}</td>
                                                        <td>
                                                            @if(!empty($item->tax))
                                                                <table>
                                                                    @php
                                                                        $totalTaxRate = 0;
                                                                    @endphp
                                                                    @foreach($taxes as $tax)

                                                                        @php
                                                                            $taxPrice=App\Models\Utility::taxRate($tax->rate,$item->price,$item->quantity,$item->discount) ;
                                                                            $totalTaxPrice+=$taxPrice;
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{$tax->name .' ('.$tax->rate .'%)'}}</td>
                                                                            <td>{{\App\Models\Utility::priceFormat($taxPrice)}}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </table>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>

                                                        @php
                                                            $chartAccount = \App\Models\ChartOfAccount::find($item->chart_account_id);
                                                        @endphp

                                                        <td>{{!empty($chartAccount) ? $chartAccount->name : '-'}}</td>
                                                        <td>{{\App\Models\Utility::priceFormat($item->amount)}}</td>

                                                        <td>{{!empty($item->description)?$item->description:'-'}}</td>

                                                        <td class="text-end">{{\App\Models\Utility::priceFormat(($item->price * $item->quantity - $item->discount) + $totalTaxPrice)}}</td>
                                                        <td></td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        @php
                                                            $chartAccount = \App\Models\ChartOfAccount::find($item['chart_account_id']);
                                                        @endphp
                                                        <td>{{!empty($chartAccount) ? $chartAccount->name : '-'}}</td>
                                                        <td>{{\App\Models\Utility::priceFormat($item['amount'])}}</td>
                                                        <td>-</td>
                                                        <td class="text-end">{{\App\Models\Utility::priceFormat($item['amount'])}}</td>
                                                        <td></td>


                                                    </tr>

                                                @endif


                                            @endforeach
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                <td><b>{{__('Total')}}</b></td>
                                                <td><b>{{$totalQuantity}}</b></td>
                                                <td><b>{{\App\Models\Utility::priceFormat($totalRate)}}</b></td>
                                                <td><b>{{\App\Models\Utility::priceFormat($totalDiscount)}}</b></td>
                                                <td><b>{{\App\Models\Utility::priceFormat($totalTaxPrice)}}</b></td>
                                                <td></td>
                                                <td><b>{{\App\Models\Utility::priceFormat($bill->getAccountTotal())}}</b></td>

                                            </tr>
                                            <tr>
                                                <td colspan="8"></td>
                                                <td class="text-end"><b>{{__('Sub Total')}}</b></td>
                                                <td class="text-end">{{\App\Models\Utility::priceFormat($bill->getSubTotal())}}</td>
                                            </tr>

                                            <tr>
                                                <td colspan="8"></td>
                                                <td class="text-end"><b>{{__('Discount')}}</b></td>
                                                <td class="text-end">{{\App\Models\Utility::priceFormat($bill->getTotalDiscount())}}</td>
                                            </tr>

                                            @if(!empty($taxesData))
                                                @foreach($taxesData as $taxName => $taxPrice)
                                                    <tr>
                                                        <td colspan="8"></td>
                                                        <td class="text-end"><b>{{$taxName}}</b></td>
                                                        <td class="text-end">{{ \App\Models\Utility::priceFormat($taxPrice) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="8"></td>
                                                <td class="blue-text text-end"><b>{{__('Total')}}</b></td>
                                                <td class="blue-text text-end">{{\App\Models\Utility::priceFormat($bill->getTotal())}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="8"></td>
                                                <td class="text-end"><b>{{__('Paid')}}</b></td>
                                                <td class="text-end">{{\App\Models\Utility::priceFormat(($bill->getTotal()-$bill->getDue())-($bill->billTotalDebitNote()))}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="8"></td>
                                                <td class="text-end"><b>{{__('Debit Note')}}</b></td>
                                                <td class="text-end">{{\App\Models\Utility::priceFormat(($bill->billTotalDebitNote()))}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="8"></td>
                                                <td class="text-end"><b>{{__('Due')}}</b></td>
                                                <td class="text-end">{{\App\Models\Utility::priceFormat($bill->getDue())}}</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
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
                    <h5 class=" d-inline-block mb-5">{{__('Payment Summary')}}</h5>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-dark">{{__('Payment Receipt')}}</th>
                                <th class="text-dark">{{__('Date')}}</th>
                                <th class="text-dark">{{__('Amount')}}</th>
                                <th class="text-dark">{{__('Account')}}</th>
                                <th class="text-dark">{{__('Reference')}}</th>
                                <th class="text-dark">{{__('Description')}}</th>
                                <th class="text-dark">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            @forelse($bill->payments as $key =>$payment)
                                <tr>
                                    <td>
                                        @if(!empty($payment->add_receipt))
                                            <a href="{{asset(Storage::url('uploads/payment')).'/'.$payment->add_receipt}}" download="" class="btn btn-sm btn-secondary btn-icon rounded-pill" target="_blank"><span class="btn-inner--icon"><i class="ti ti-download"></i></span></a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{\App\Models\Utility::dateFormat($payment->date)}}</td>
                                    <td>{{\App\Models\Utility::priceFormat($payment->amount)}}</td>
                                    <td>{{!empty($payment->bankAccount)?$payment->bankAccount->bank_name.' '.$payment->bankAccount->holder_name:''}}</td>
                                    <td>{{$payment->reference}}</td>
                                    <td>{{$payment->description}}</td>
                                    <td class="text-dark">
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'post', 'route' => ['bill.payment.destroy',$bill->id,$payment->id],'id'=>'delete-form-'.$payment->id]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip"  title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$payment->id}}').submit();">
                                                    <i class="ti ti-trash text-white text-white text-white"></i>
                                                </a>
                                                {!! Form::close() !!}
                                            </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5 class="d-inline-block mb-5">{{__('Debit Note Summary')}}</h5>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-dark">#</th>
                                <th class="text-dark">{{__('Date')}}</th>
                                <th class="text-dark">{{__('Amount')}}</th>
                                <th class="text-dark">{{__('Description')}}</th>
                                <th class="text-dark">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            @forelse($bill->debitNote as $key =>$debitNote)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{\App\Models\Utility::dateFormat($debitNote->date)}}</td>
                                    <td>{{\App\Models\Utility::priceFormat($debitNote->amount)}}</td>
                                    <td>{{$debitNote->description}}</td>
                                    <td class="text-center">
                                        <div class="actions">
                                            <div class="action-btn ms-2">
                                                <a data-url="{{ route('bill.edit.debit.note',[$debitNote->bill,$debitNote->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Debit Note')}}" href="#" class="mx-1 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                    <i class="feather-edit"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => array('bill.delete.debit.note', $debitNote->bill,$debitNote->id),'id'=>'delete-form-'.$debitNote->id]) !!}
                                                    <a class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$debitNote->id}}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>

                                                    <div class="modal fade contentmodal" id="{{'deleteModal-'.$debitNote->id}}" tabindex="-1" aria-hidden="true">
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
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


        </div>
    </div>

@endsection

