
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
                            <h3 class="page-title">Invoices</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Invoices</li>
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
                                        <h3 class="page-title">Invoices</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> Download</a>
                                        <a href="{{ route('invoice.create',0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Create">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Issue Date</th>
                                            <th>Due Date</th>
                                            <th>Due Amount</th>
                                            <th>Status</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($invoices as $index => $invoice)
                                        <tr>
                                            <td class="Id">
                                                <a href="{{ route('invoice.show', \Crypt::encrypt($invoice->id)) }}" class="btn btn-outline-primary">{{\App\Models\Utility::invoiceNumberFormat($invoice->invoice_id) }}</a>
                                            </td>
                                            <td>{{ \App\Models\Utility::dateFormat($invoice->issue_date) }}</td>
                                            <td>
                                                @if ($invoice->due_date < date('Y-m-d'))
                                                    <p class="text-danger mt-3">
                                                        {{ \App\Models\Utility::dateFormat($invoice->due_date) }}</p>
                                                @else
                                                    {{ \App\Models\Utility::dateFormat($invoice->due_date) }}
                                                @endif
                                            </td>
                                            <td>{{ \App\Models\Utility::priceFormat($invoice->getDue()) }}</td>
                                            <td>
                                                @if ($invoice->status == 0)
                                                    <span
                                                        class="status_badge badge bg-secondary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 1)
                                                    <span
                                                        class="status_badge badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 2)
                                                    <span
                                                        class="status_badge badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 3)
                                                    <span
                                                        class="status_badge badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 4)
                                                    <span
                                                        class="status_badge badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @endif
                                            </td>

                                            <td class="text-end">
                                                <div class="actions">
                                                    @php $invoiceID= Crypt::encrypt($invoice->id); @endphp

                                                    <div class="action-btn ms-2">
                                                        <a href="#" id="{{ route('invoice.link.copy',[$invoiceID]) }}"
                                                           class="mx-1 btn btn-sm align-items-center"   onclick="copyToClipboard(this)"
                                                           data-bs-toggle="tooltip" title="{{__('Copy Invoice')}}" data-original-title="Copy Invoice"><i class="fas fa-link"></i></a>
                                                    </div>

                                                    <div class="action-btn">
                                                        {!! Form::open(['method' => 'get', 'route' => ['invoice.duplicate', $invoice->id],'id'=>'duplicate-form-invoice-'.$invoice->id]) !!}
                                                        <a href="#" class="mx-1 btn btn-sm  align-items-center bs-pass-para"  data-bs-toggle="modal" data-bs-target="{{'#duplicate-invoice-'.$invoice->id}}">
                                                            <i class="far fa-copy"></i>
                                                        </a>

                                                        <div class="modal fade contentmodal" id="{{'duplicate-invoice-'.$invoice->id}}" tabindex="-1" aria-hidden="true">
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

                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id],'id'=>'delete-form-invoice-'.$invoice->id]) !!}
                                                        <a class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-invoice-'.$invoice->id}}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                        <div class="modal fade contentmodal" id="{{'deleteModal-invoice-'.$invoice->id}}" tabindex="-1" aria-hidden="true">
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

