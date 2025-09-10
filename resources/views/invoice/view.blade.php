@extends('layouts.master')
@section('content')

<style>
    #card-element {
        border: 1px solid #a3afbb !important;
        border-radius: 10px !important;
        padding: 10px !important;
    }
</style>

<script src="https://js.stripe.com/v3/"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="text/javascript">


    //here goes payment settings


    $('.cp_link').on('click', function() {
        var value = $(this).attr('data-link');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(value).select();
        document.execCommand("copy");
        $temp.remove();
        show_toastr('success', '{{ __('Link Copy on Clipboard') }}', 'success')
    });
</script>

<script>
    $(document).on('click', '#shipping', function() {
        var url = $(this).data('url');
        var is_display = $("#shipping").is(":checked");
        $.ajax({
            url: url,
            type: 'get',
            data: {
                'is_display': is_display,
            },
            success: function(data) {
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
                        <h3 class="page-title">Invoice Detail</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('invoice.index') }}">Invoice</a></li>
                            <li class="breadcrumb-item active">Invoice Details</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- message --}}
        {!! Toastr::message() !!}

        @if ($invoice->status != 4)
            <div class="row">
                <div class="col-12">
                    <div class="card ">
                        <div class="card-body">
                            <div class="row timeline-wrapper">

                                <div class="col-md-6 col-lg-4 col-xl-4 d-flex flex-column align-items-center">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="fas fa-plus text-primary"></i>
                                    </div>
                                    <h6 class="text-primary my-3">Create Invoice</h6>
                                    <p class="text-muted text-sm mb-3"><i class="far fa-clock mx-1"></i>{{__('Created on ')}}{{\App\Models\Utility::dateFormat($invoice->issue_date)}}</p>
                                    
                                    <a href="{{ route('invoice.edit',\Crypt::encrypt($invoice->id)) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="feather-edit mx-1"></i>{{__('Edit')}}</a>
                                    
                                </div>

                                <div class="col-md-6 col-lg-4 col-xl-4 d-flex flex-column align-items-center">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="far fa-envelope text-warning"></i>
                                    </div>
                                    <h6 class="text-warning my-3">{{__('Send Invoice')}}</h6>
                                    <p class="text-muted text-sm mb-3">
                                        @if($invoice->status!=0)
                                            <i class="far fa-clock mx-1"></i>{{__('Sent on')}} {{\App\Models\Utility::dateFormat($invoice->send_date)}}
                                        @else
                                            <small>{{__('Status')}} : {{__('Not Sent')}}</small>
                                        @endif
                                    </p>

                                    @if($invoice->status==0)
                                        
                                        <a href="{{ route('invoice.sent',$invoice->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-original-title="{{__('Mark Sent')}}"><i class="fas fa-paper-plane mx-1"></i>{{__('Send')}}</a>
                                       
                                    @endif
                                </div>

                                <div class="col-md-6 col-lg-4 col-xl-4 d-flex flex-column align-items-center">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="fas fa-money-check-alt text-info"></i>
                                    </div>
                                    <h6 class="text-info my-3">{{__('Get Paid')}}</h6>
                                    <p class="text-muted text-sm mb-3">{{__('Status')}} : {{__('Awaiting payment')}} </p>
                                    @if($invoice->status!=0)
                                        <a href="#" data-url="{{ route('invoice.payment',$invoice->id) }}" data-ajax-popup="true" data-title="{{__('Add Payment')}}" class="btn btn-sm btn-info text-light" data-original-title="{{__('Add Payment')}}"><i class="ti ti-report-money mr-2"></i>{{__('Add Payment')}}</a> <br>
                                    @endif
    
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($invoice->status!=0)
        <div class="row justify-content-between align-items-center mb-3">
            <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                @if(!empty($invoicePayment))
                    <div class="all-button-box mx-2 mr-2">
                        <a href="#" class="btn btn-sm btn-primary" data-url="{{ route('invoice.credit.note',$invoice->id) }}" data-ajax-popup="true" data-title="{{__('Add Credit Note')}}">
                            {{__('Add Credit Note')}}
                        </a>
                    </div>
                @endif
                @if($invoice->status!=4)
                    <div class="all-button-box mr-2">
                        <a href="{{ route('invoice.payment.reminder',$invoice->id)}}" class="btn btn-sm btn-primary me-2">{{__('Receipt Reminder')}}</a>
                    </div>
                @endif
                <div class="all-button-box mr-2">
                    <a href="{{ route('invoice.resent',$invoice->id)}}" class="btn btn-sm btn-primary me-2">{{__('Resend Invoice')}}</a>
                </div>
                <div class="all-button-box">
                    <a href="{{ route('invoice.pdf', Crypt::encrypt($invoice->id))}}" target="_blank" class="btn btn-sm btn-primary">{{__('Download')}}</a>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="invoice">
                            <div class="invoice-print">
                                <div class="row invoice-title mt-2">
                                    <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                        <h4>{{__('Invoice')}}</h4>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                                        <h4 class="invoice-number">{{ \App\Models\Utility::invoiceNumberFormat($invoice->invoice_id) }}</h4>
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
                                                    {{\App\Models\Utility::dateFormat($invoice->issue_date)}}<br><br>
                                                </small>
                                            </div>
                                            <div>
                                                <small>
                                                    <strong>{{__('Due Date')}} :</strong><br>
                                                    {{\App\Models\Utility::dateFormat($invoice->due_date)}}<br><br>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @if(!empty($customer->billing_name))
                                        <div class="col">
                                            <small class="font-style">
                                                <strong>{{__('Billed To')}} :</strong><br>
                                                @if(!empty($customer->billing_name))
                                                    {{!empty($customer->billing_name)?$customer->billing_name:''}}<br>
                                                    {{!empty($customer->billing_address)?$customer->billing_address:''}}<br>
                                                    {{!empty($customer->billing_city)?$customer->billing_city:'' .', '}}<br>
                                                    {{!empty($customer->billing_state)?$customer->billing_state:'',', '}},
                                                    {{!empty($customer->billing_zip)?$customer->billing_zip:''}}<br>
                                                    {{!empty($customer->billing_country)?$customer->billing_country:''}}<br>
                                                    {{!empty($customer->billing_phone)?$customer->billing_phone:''}}<br>
                                                    @if(!empty($customer->tax_number))
                                                        <strong>{{__('Tax Number ')}} : </strong>{{!empty($customer->tax_number)?$customer->tax_number:''}}
                                                    @endif
                                                @else
                                                    -
                                                @endif
    
                                            </small>
                                        </div>
                                    @endif

                                        <div class="col ">
                                            <small>
                                                <strong>{{__('Shipped To')}} :</strong><br>
                                                @if(!empty($customer->shipping_name))
                                                    {{!empty($customer->shipping_name)?$customer->shipping_name:''}}<br>
                                                    {{!empty($customer->shipping_address)?$customer->shipping_address:''}}<br>
                                                    {{!empty($customer->shipping_city)?$customer->shipping_city:'' . ', '}}<br>
                                                    {{!empty($customer->shipping_state)?$customer->shipping_state:'' .', '}},
                                                    {{!empty($customer->shipping_zip)?$customer->shipping_zip:''}}<br>
                                                    {{!empty($customer->shipping_country)?$customer->shipping_country:''}}<br>
                                                    {{!empty($customer->shipping_phone)?$customer->shipping_phone:''}}<br>
                                                @else
                                                    -
                                                @endif
                                            </small>
                                        </div>
                                    <div class="col">
                                        <div class="float-end mt-3">
                                            {!! DNS2D::getBarcodeHTML(route('invoice.link.copy',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)), "QRCODE",2,2) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <small>
                                            <strong>{{__('Status')}} :</strong><br>
                                            @if($invoice->status == 0)
                                                <span class="badge bg-primary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                            @elseif($invoice->status == 1)
                                                <span class="badge bg-warning">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                            @elseif($invoice->status == 2)
                                                <span class="badge bg-danger">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                            @elseif($invoice->status == 3)
                                                <span class="badge bg-info">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                            @elseif($invoice->status == 4)
                                                <span class="badge bg-success">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                            @endif
                                        </small>
                                    </div>
    
                                    @if(!empty($customFields) && count($invoice->customField)>0)
                                        @foreach($customFields as $field)
                                            <div class="col text-md-right">
                                                <small>
                                                    <strong>{{$field->name}} :</strong><br>
                                                    {{!empty($invoice->customField)?$invoice->customField[$field->id]:'-'}}
                                                    <br><br>
                                                </small>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="font-weight-bold">{{__('Product Summary')}}</div>
                                        <small>{{__('All items here cannot be deleted.')}}</small>
                                        <div class="table-responsive mt-2">
                                            <table class="table mb-0 table-striped">
                                                <tr>
                                                    <th data-width="40" class="text-dark">#</th>
                                                    <th class="text-dark">{{__('Product')}}</th>
                                                    <th class="text-dark">{{__('Quantity')}}</th>
                                                    <th class="text-dark">{{__('Rate')}}</th>
                                                    <th class="text-dark">{{__('Discount')}}</th>
                                                    <th class="text-dark">{{__('Tax')}}</th>
                                                    <th class="text-dark">{{__('Description')}}</th>
                                                    <th class="text-end text-dark" width="12%">{{__('Price')}}<br>
                                                        <small class="text-danger font-weight-bold">{{__('after tax & discount')}}</small>
                                                    </th>
                                                </tr>
                                                @php
                                                    $totalQuantity=0;
                                                    $totalRate=0;
                                                    $totalTaxPrice=0;
                                                    $totalDiscount=0;
                                                    $taxesData=[];
                                                @endphp
                                                @foreach($iteams as $key =>$iteam)
                                                    @php
                                                        $totalQuantity+=$iteam->quantity;
                                                        $totalRate+=$iteam->price;
                                                        $totalDiscount+=$iteam->discount;
                                                    @endphp
                                                    
                                                    @if(!empty($iteam->tax))
                                                        @php
                                                            $taxes=App\Models\Invoice::invoiceTax($iteam->tax);
                                                            foreach($taxes as $taxe){
                                                                $taxDataPrice=App\Models\Invoice::taxRate($taxe->rate,$iteam->price,$iteam->quantity,$iteam->discount);
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
                                                    <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td>{{!empty($iteam->product)?$iteam->product->name:''}}</td>
                                                        @php
                                                        $unit = $iteam->product->unit_id;
                                                        $unitName = App\Models\ProductServiceUnit::find($unit);
                                                    @endphp
                                                    <td>{{$iteam->quantity . ' (' . $unitName->name . ')'}}</td>
                                                        <td>{{\App\Models\Utility::priceFormat($iteam->price)}}</td>
                                                        <td>{{\App\Models\Utility::priceFormat($iteam->discount)}}</td>
    
                                                        <td>
                                                            @if(!empty($iteam->tax))
                                                                <table>
                                                                    @php
                                                                        $totalTaxRate = 0;
                                                                    @endphp
                                                                    @foreach($taxes as $tax)
                                                                        @php
                                                                            $taxPrice=App\Models\Invoice::taxRate($tax->rate,$iteam->price,$iteam->quantity,$iteam->discount) ;
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
    
                                                        <td>{{!empty($iteam->description)?$iteam->description:'-'}}</td>
                                                        <td class="text-end">{{\App\Models\Utility::priceFormat(($iteam->price * $iteam->quantity - $iteam->discount) + $totalTaxPrice)}}</td>
                                                    </tr>
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
                                                </tr>
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td class="text-end"><b>{{__('Sub Total')}}</b></td>
                                                    <td class="text-end">{{\App\Models\Utility::priceFormat($invoice->getSubTotal())}}</td>
                                                </tr>
    
                                                    <tr>
                                                        <td colspan="6"></td>
                                                        <td class="text-end"><b>{{__('Discount')}}</b></td>
                                                        <td class="text-end">{{\App\Models\Utility::priceFormat($invoice->getTotalDiscount())}}</td>
                                                    </tr>
    
                                                @if(!empty($taxesData))
                                                    @foreach($taxesData as $taxName => $taxPrice)
                                                        <tr>
                                                            <td colspan="6"></td>
                                                            <td class="text-end"><b>{{$taxName}}</b></td>
                                                            <td class="text-end">{{ \App\Models\Utility::priceFormat($taxPrice) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td class="blue-text text-end"><b>{{__('Total')}}</b></td>
                                                    <td class="blue-text text-end">{{\App\Models\Utility::priceFormat($invoice->getTotal())}}</td>
                                                </tr>
                                                @php
                                                    $creditNote = $invoice->creditNote->sum('amount');
                                                    $getDue = $invoice->getDue();
                                                @endphp
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td class="text-end"><b>{{__('Paid')}}</b></td>
                                                    <td class="text-end">{{\App\Models\Utility::priceFormat(($invoice->getTotal()-$getDue)-($creditNote))}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td class="text-end"><b>{{__('Credit Note')}}</b></td>
                                                    <td class="text-end">{{\App\Models\Utility::priceFormat(($creditNote))}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td class="text-end"><b>{{__('Due')}}</b></td>
                                                    <td class="text-end">{{\App\Models\Utility::priceFormat($getDue)}}</td>
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
                        <h5 class=" d-inline-block  mb-5">{{__('Receipt Summary')}}</h5>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead>
                                <tr>
                                    <th class="text-dark">{{__('Payment Receipt')}}</th>
                                    <th class="text-dark">{{__('Date')}}</th>
                                    <th class="text-dark">{{__('Amount')}}</th>
                                    <th class="text-dark">{{__('Payment Type')}}</th>
                                    <th class="text-dark">{{__('Account')}}</th>
                                    <th class="text-dark">{{__('Reference')}}</th>
                                    <th class="text-dark">{{__('Description')}}</th>
                                    <th class="text-dark">{{__('Receipt')}}</th>
                                    <th class="text-dark">{{__('OrderId')}}</th>
                                    <th class="text-dark">{{__('Action')}}</th>
                                </tr>
                                </thead>
                                @if(!empty($invoice->payments) && $invoice->bankPayments)
                                    @php
                                        $path =Storage::url('uploads/payment');
                                    @endphp
    
                                    @foreach($invoice->payments as $key =>$payment)
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
                                            <td>{{$payment->payment_type}}</td>
                                            <td>{{!empty($payment->bankAccount)?$payment->bankAccount->bank_name.' '.$payment->bankAccount->holder_name:'--'}}</td>
                                            <td>{{!empty($payment->reference)?$payment->reference:'--'}}</td>
                                            <td>{{!empty($payment->description)?$payment->description:'--'}}</td>
                                            <td>
                                                @if(!empty($payment->receipt))
                                                    <a href="{{ $path . '/' . $payment->receipt }}" target="_blank">
                                                        <i class="ti ti-file"></i>{{__('Receipt')}}</a>
                                                @elseif(!empty($payment->add_receipt))
                                                    <a href="{{asset(Storage::url('uploads/payment')).'/'.$payment->add_receipt}}" target="_blank">
                                                        <i class="ti ti-file"></i>{{__('Receipt')}}</a>
                                                @else --
                                                @endif
                                            </td>
                                            <td>{{!empty($payment->order_id)?$payment->order_id:'--'}}</td>
                                            
                                                <td>
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'post', 'route' => ['invoice.payment.destroy',$invoice->id,$payment->id],'id'=>'delete-form-'.$payment->id]) !!}
    
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="Delete" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$payment->id}}').submit();">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                    {!! Form::close() !!}
                                                </td>
                                            
                                        </tr>
                                    @endforeach
    
                                    {{--  start for bank transfer--}}
    
                                    @foreach($invoice->bankPayments as $key =>$bankPayment)
    
                                        <tr>
                                            <td>-</td>
                                            <td>{{\App\Models\Utility::dateFormat($bankPayment->date)}}</td>
                                            <td>{{\App\Models\Utility::priceFormat($bankPayment->amount)}}</td>
                                            <td>{{__('Bank Transfer')}}<br>
                                                {{--                                            @if($bankPayment->status == 'Pending')--}}
                                                {{--                                                <span class="badge bg-warning p-2 px-3 rounded">{{$bankPayment->status}}</span>--}}
                                                {{--                                            @elseif($bankPayment->status == 'Approved')--}}
                                                {{--                                                <span class="badge bg-primary p-2 px-3 rounded">{{$bankPayment->status}}</span>--}}
                                                {{--                                            @else--}}
                                                {{--                                                <span class="badge bg-danger p-2 px-3 rounded">{{$bankPayment->status}}</span>--}}
                                                {{--                                            @endif--}}
    
                                            </td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>
                                                @if(!empty($bankPayment->receipt))
                                                    <a href="{{ $path . '/' . $bankPayment->receipt }}" target="_blank">
                                                        <i class="ti ti-file"></i> {{__('Receipt')}}
                                                    </a>
                                                @endif
    
                                            </td>
                                            <td>{{!empty($bankPayment->order_id)?$bankPayment->order_id:'--'}}</td>
                                            
    
                                                <td>
                                                    @if($bankPayment->status == 'Pending')
                                                        <div class="action-btn bg-warning">
                                                            <a href="#" data-url="{{ URL::to('invoice/'.$bankPayment->id.'/action') }}" data-size="lg"
                                                                data-ajax-popup="true" data-title="{{__('Payment Status')}}" class="mx-3 btn btn-sm align-items-center"
                                                                data-bs-toggle="tooltip" title="{{__('Payment Status')}}" data-original-title="{{__('Payment Status')}}">
                                                                <i class="ti ti-caret-right text-white"></i>
                                                            </a>
                                                        </div>
    
                                                    @endif
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'post', 'route' => ['invoice.payment.destroy',$invoice->id,$bankPayment->id],'id'=>'delete-form-'.$bankPayment->id]) !!}
    
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="Delete" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$bankPayment->id}}').submit();">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                    {!! Form::close() !!}
                                                </td>
                                            
                                        </tr>
                                    @endforeach
    
                                    {{--  end for bank transfer--}}
    
                                @else
                                    <tr>
                                        <td colspan="10" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                                    </tr>
                                @endif
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
                        <h5 class="d-inline-block mb-5">{{__('Credit Note Summary')}}</h5>
    
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-dark">{{__('Date')}}</th>
                                    <th class="text-dark" class="">{{__('Amount')}}</th>
                                    <th class="text-dark" class="">{{__('Description')}}</th>
                                    <th class="text-dark">{{__('Action')}}</th>
                                </tr>
                                </thead>
                                @forelse($invoice->creditNote as $key =>$creditNote)
                                    <tr>
                                        <td>{{\App\Models\Utility::dateFormat($creditNote->date)}}</td>
                                        <td class="">{{\App\Models\Utility::priceFormat($creditNote->amount)}}</td>
                                        <td class="">{{$creditNote->description}}</td>
                                        <td class="text-center">
                                            <div class="actions">
                                                <div class="action-btn ms-2">
                                                    <a data-url="{{ route('invoice.edit.credit.note',[$creditNote->invoice,$creditNote->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Credit Note')}}" href="#" class="mx-1 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                </div>
                                                <div class="action-btn ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => array('invoice.delete.credit.note', $creditNote->invoice,$creditNote->id),'class'=>'delete-form-btn','id'=>'delete-form-'.$creditNote->id]) !!}
                                                        <a class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$creditNote->id}}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>

                                                        <div class="modal fade contentmodal" id="{{'deleteModal-'.$creditNote->id}}" tabindex="-1" aria-hidden="true">
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
                                        <td colspan="4" class="text-center">
                                            <p class="text-dark">{{__('No Data Found')}}</p>
                                        </td>
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
