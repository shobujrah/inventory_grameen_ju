
@extends('layouts.master')
@section('content')

<script>
    $(document).on('change', '#bill', function () {

        var id = $(this).val();
        var url = "{{route('bill.get')}}";

        $.ajax({
            url: url,
            type: 'get',
            cache: false,
            data: {
                'bill_id': id,

            },
            success: function (data) {
                $('#amount').val(data)
            },

        });

    })
</script>

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Debit Notes</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Debit Notes</li>
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
                                        <h3 class="page-title">Debit Notes</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> Download</a>
                                        <a href="#" class="btn btn-primary" data-url="{{ route('bill.custom.debit.note') }}" data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Payment')}}"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>Bill</th>
                                            <th>Vendor</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Description</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @foreach ($bills as $bill)
                                            @if(!empty($bill->debitNote))
                                                @foreach ($bill->debitNote as $debitNote)

                                                    <tr class="font-style">
                                                        <td class="Id">
                                                            <a href="{{ route('bill.show',\Crypt::encrypt($debitNote->bill)) }}" class="btn btn-outline-primary">{{ \App\Models\Utility::billNumberFormat($bill->bill_id) }}
                                                            </a>
                                                        </td>
                                                        <td>{{ (!empty($bill->vender)?$bill->vender->name:'-') }}</td>
                                                        <td>{{ \App\Models\Utility::dateFormat($debitNote->date) }}</td>
                                                        <td>{{ \App\Models\Utility::priceFormat($debitNote->amount) }}</td>
                                                        <td>{{!empty($debitNote->description)?$debitNote->description:'-'}}</td>
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
                                                @endforeach
                                            @endif
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

