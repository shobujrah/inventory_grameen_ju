@extends('layouts.master')

@section('content')

<script>

    function copyToClipboard(element) {

        var copyText = element.id;
        var tempTextarea = $('<textarea>');
        $('body').append(tempTextarea);
        tempTextarea.val(copyText).select();
        document.execCommand('copy');
        tempTextarea.remove();
        show_toastr('success', 'Url copied to clipboard', 'success');
    }
</script>

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Customer Detail</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer</a></li>
                                <li class="breadcrumb-item active">{{ $customer->name }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}

            <div class="row mb-3 text-end">
                <div class="col-md-12">
                    <a href="{{ route('invoice.create',$customer->id) }}" class="btn btn-primary btn-sm">Create Invoice</a>
                    <a href="{{ route('proposal.create',$customer->id) }}" class="btn btn-primary btn-sm">Create Proposal</a>
                    <a href="#" class="btn btn-primary btn-sm align-items-center" data-url="{{ route('customer.edit',$customer->id) }}" data-ajax-popup="true" title="{{__('Edit')}}" data-title="{{__('Edit Customer')}}" data-bs-toggle="tooltip" data-size="lg" data-original-title="{{__('Edit')}}">
                        <i class="feather-edit"></i>
                    </a>
                    <a class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$customer->id}}">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                    {!! Form::open(['method' => 'DELETE','class' => 'delete-form-btn', 'route' => ['customer.destroy', $customer['id']]]) !!}
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
            </div>

            <div class="row">
                <div class="col-md-4 col-lg-4 col-xl-4 mb-4">
                    <div class="card customer-detail-box customer_card">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Customer Info')}}</h5>
                            <p class="card-text mb-0">{{$customer['name']}}</p>
                            <p class="card-text mb-0">{{$customer['email']}}</p>
                            <p class="card-text mb-0">{{$customer['contact']}}</p>
                        </div>
                    </div>
                </div>
        
                <div class="col-md-4 col-lg-4 col-xl-4 mb-4">
                    <div class="card customer-detail-box customer_card">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Billing Info')}}</h5>
                            <p class="card-text mb-0">{{$customer['billing_name']}}</p>
                            <p class="card-text mb-0">{{$customer['billing_address']}}</p>
                            <p class="card-text mb-0">{{$customer['billing_city'].', '. $customer['billing_state'] .', '.$customer['billing_zip']}}</p>
                            <p class="card-text mb-0">{{$customer['billing_country']}}</p>
                            <p class="card-text mb-0">{{$customer['billing_phone']}}</p>
                        </div>
                    </div>
        
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4 mb-4">
                    <div class="card customer-detail-box customer_card">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Shipping Info')}}</h5>
                            <p class="card-text mb-0">{{$customer['shipping_name']}}</p>
                            <p class="card-text mb-0">{{$customer['shipping_address']}}</p>
                            <p class="card-text mb-0">{{$customer['shipping_city'].', '. $customer['shipping_state'] .', '.$customer['shipping_zip']}}</p>
                            <p class="card-text mb-0">{{$customer['shipping_country']}}</p>
                            <p class="card-text mb-0">{{$customer['shipping_phone']}}</p>
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
                                    $totalInvoiceSum=$customer->customerTotalInvoiceSum($customer['id']);
                                    $totalInvoice=$customer->customerTotalInvoice($customer['id']);
                                    $averageSale=($totalInvoiceSum!=0)?$totalInvoiceSum/$totalInvoice:0;
                                @endphp
                                
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                        <p class="card-text mb-0">{{__('Customer Id')}}</p>
                                        <h6 class="report-text mb-3">{{\App\Models\Utility::customerNumberFormat($customer['customer_id'])}}</h6>
                                        <p class="card-text mb-0">{{__('Total Sum of Invoices')}}</p>
                                        <h6 class="report-text mb-0">{{\App\Models\Utility::priceFormat($totalInvoiceSum)}}</h6>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                        <p class="card-text mb-0">{{__('Date of Creation')}}</p>
                                        <h6 class="report-text mb-3">{{\App\Models\Utility::dateFormat($customer['created_at'])}}</h6>
                                        <p class="card-text mb-0">{{__('Quantity of Invoice')}}</p>
                                        <h6 class="report-text mb-0">{{$totalInvoice}}</h6>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                        <p class="card-text mb-0">{{__('Balance')}}</p>
                                        <h6 class="report-text mb-3">{{\App\Models\Utility::priceFormat($customer['balance'])}}</h6>
                                        <p class="card-text mb-0">{{__('Average Sales')}}</p>
                                        <h6 class="report-text mb-0">{{\App\Models\Utility::priceFormat($averageSale)}}</h6>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                        <p class="card-text mb-0">{{__('Overdue')}}</p>
                                        <h6 class="report-text mb-3">{{\App\Models\Utility::priceFormat($customer->customerOverdue($customer['id']))}}</h6>
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
                        <div class="card-body table-border-style table-border-style">
                            <h5 class="d-inline-block mb-5">{{__('Proposal')}}</h5>
        
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead>
                                    <tr>
                                        <th>{{__('Proposal')}}</th>
                                        <th>{{__('Issue Date')}}</th>
                                        <th>{{__('Amount')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th width="10%"> {{__('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($customer->customerProposal($customer->id) as $proposal)
                                        <tr>
                                            <td class="Id">
                                                <a href="{{ route('proposal.show',\Crypt::encrypt($proposal->id)) }}" class="btn btn-outline-primary">{{ \App\Models\Utility::proposalNumberFormat($proposal->proposal_id) }}
                                                </a>
                                            </td>
                                            <td>{{ \App\Models\Utility::dateFormat($proposal->issue_date) }}</td>
                                            <td>{{ App\Models\Utility::priceFormat($proposal->getTotal()) }}</td>
                                            <td>
                                                @if($proposal->status == 0)
                                                    <span class="badge bg-primary p-2 px-3 rounded status_badge">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                                @elseif($proposal->status == 1)
                                                    <span class="badge bg-warning p-2 px-3 rounded status_badge">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                                @elseif($proposal->status == 2)
                                                    <span class="badge bg-danger p-2 px-3 rounded status_badge">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                                @elseif($proposal->status == 3)
                                                    <span class="badge bg-info p-2 px-3 rounded status_badge">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                                @elseif($proposal->status == 4)
                                                    <span class="badge bg-success p-2 px-3 rounded status_badge">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    @if($proposal->is_convert==0)
                                                        <div class="action-btn ms-2">
                                                            {!! Form::open(['method' => 'get', 'route' => ['proposal.convert', $proposal->id],'id'=>'proposal-form-'.$proposal->id]) !!}
                                                            <a href="#" class="mx-1 btn btn-sm align-items-center bs-pass-para"  data-bs-toggle="modal" data-bs-target="{{'#convert-'.$proposal->id}}">
                                                                <i class="fas fa-sync-alt"></i>
                                                            </a>

                                                            <div class="modal fade contentmodal" id="{{'convert-'.$proposal->id}}" tabindex="-1" aria-hidden="true">
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
                                                                                    <i class="fas fa-sync-alt"></i>
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
                                                    @else
                                                        <div class="action-btn ms-2">
                                                            <a href="{{ route('invoice.show',\Crypt::encrypt($proposal->converted_invoice_id)) }}"
                                                            class="mx-1 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Already convert to Invoice')}}" data-original-title="{{__('Already convert to Invoice')}}" >
                                                                <i class="far fa-file-alt"></i>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    <div class="action-btn">
                                                        {!! Form::open(['method' => 'get', 'route' => ['proposal.duplicate', $proposal->id],'id'=>'duplicate-form-'.$proposal->id]) !!}
                                                        <a href="#" class="mx-1 btn btn-sm  align-items-center bs-pass-para"  data-bs-toggle="modal" data-bs-target="{{'#duplicate-'.$proposal->id}}">
                                                            <i class="far fa-copy"></i>
                                                        </a>

                                                        <div class="modal fade contentmodal" id="{{'duplicate-'.$proposal->id}}" tabindex="-1" aria-hidden="true">
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

                                                    <a href="{{ route('proposal.show',\Crypt::encrypt($proposal['id'])) }}" class="mx-1 btn btn-sm align-items-center"
                                                        data-bs-toggle="tooltip" title="{{__('View')}}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <a href="{{ route('proposal.edit',\Crypt::encrypt($proposal->id)) }}" class="mx-1 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                        <i class="feather-edit"></i>
                                                    </a>

                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['proposal.destroy', $proposal->id],'id'=>'delete-form-'.$proposal->id]) !!}
                                                        <a class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$proposal->id}}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                        <div class="modal fade contentmodal" id="{{'deleteModal-'.$proposal->id}}" tabindex="-1" aria-hidden="true">
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
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-border-style table-border-style">
                            <h5 class="d-inline-block mb-5">{{__('Invoice')}}</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{__('Invoice')}}</th>
                                        <th>{{__('Issue Date')}}</th>
                                        <th>{{__('Due Date')}}</th>
                                        <th>{{__('Due Amount')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th width="10%"> {{__('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($customer->customerInvoice($customer->id) as $invoice)
                                        <tr>
                                            <td class="Id">
                                                <a href="{{ route('invoice.show',\Crypt::encrypt($invoice->id)) }}" class="btn btn-outline-primary">{{ \App\Models\Utility::invoiceNumberFormat($invoice->invoice_id) }}
                                                </a>
                                            </td>
                                            <td>{{ \App\Models\Utility::dateFormat($invoice->issue_date) }}</td>
                                            <td>
                                                @if(($invoice->due_date < date('Y-m-d')))
                                                    <p class="text-danger"> {{ \App\Models\Utility::dateFormat($invoice->due_date) }}</p>
                                                @else
                                                    {{ \App\Models\Utility::dateFormat($invoice->due_date) }}
                                                @endif
                                            </td>
                                            <td>{{\App\Models\Utility::priceFormat($invoice->getDue())  }}</td>
                                            <td>
                                                @if($invoice->status == 0)
                                                    <span class="badge bg-primary p-2 px-3 rounded status_badge">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 1)
                                                    <span class="badge bg-warning p-2 px-3 rounded status_badge">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 2)
                                                    <span class="badge bg-danger p-2 px-3 rounded status_badge">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 3)
                                                    <span class="badge bg-info p-2 px-3 rounded status_badge">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 4)
                                                    <span class="badge bg-success p-2 px-3 rounded status_badge">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    @php $invoiceID= Crypt::encrypt($invoice->id); @endphp

                                                    <div class="action-btn ms-2">
                                                        <a href="#" id="{{ route('invoice.link.copy',[$invoiceID]) }}"
                                                           class="mx-1 btn btn-sm align-items-center"  onclick="copyToClipboard(this)"
                                                           data-bs-toggle="tooltip" title="{{__('Copy Invoice')}}" data-original-title="Copy Invoice"><i class="fas fa-link"></i></a>
                                                    </div>

                                                    <div class="action-btn">
                                                        {!! Form::open(['method' => 'get', 'route' => ['invoice.duplicate', $invoice->id],'id'=>'duplicate-form-'.$invoice->id]) !!}
                                                        <a href="#" class="mx-1 btn btn-sm  align-items-center bs-pass-para"  data-bs-toggle="modal" data-bs-target="{{'#duplicate-'.$invoice->id}}">
                                                            <i class="far fa-copy"></i>
                                                        </a>

                                                        <div class="modal fade contentmodal" id="{{'duplicate-'.$invoice->id}}" tabindex="-1" aria-hidden="true">
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

                                                    <a href="{{ route('invoice.show', \Crypt::encrypt($invoice->id)) }}"
                                                        class="mx-1 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Show "
                                                        data-original-title="{{ __('Detail') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <a href="{{ route('invoice.edit', \Crypt::encrypt($invoice->id)) }}"
                                                        class="mx-1 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Edit "
                                                        data-original-title="{{ __('Edit') }}">
                                                        <i class="feather-edit"></i>
                                                    </a>

                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id],'id'=>'delete-form-'.$invoice->id]) !!}
                                                        <a class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$invoice->id}}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                        <div class="modal fade contentmodal" id="{{'deleteModal-'.$invoice->id}}" tabindex="-1" aria-hidden="true">
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

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
        <div id="liveToast" class="toast text-white  fade" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"> </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

@endsection
