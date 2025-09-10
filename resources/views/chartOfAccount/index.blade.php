
@extends('layouts.master')
@section('content')

<script>
    $(document).on('change', '#type', function() {
        var type = $(this).val();
        $.ajax({
            url: '{{ route('charofAccount.subType') }}',
            type: 'POST',
            data: {
                "type": type,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#sub_type').empty();
                $.each(data, function(key, value) {
                    $('#sub_type').append('<option value="' + key + '">' + value +
                        '</option>');
                });
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        callback();
        function callback() {
            var start_date = $(".startDate").val();
            var end_date = $(".endDate").val();

            $('.start_date').val(start_date);
            $('.end_date').val(end_date);

        }
        });

</script>

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Chart of Accounts</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Chart of Accounts</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}

            <div class="row pb-2">
                <div class="col-auto text-end float-end ms-auto download-grp">
                    <a href="#" data-url="{{ route('chart-of-account.create') }}" data-bs-toggle="tooltip" title="{{ __('Create') }}" data-size="lg" data-ajax-popup="true" data-title="{{ __('Create New Account') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>

            <div class="row">
                @foreach ($chartAccounts as $type => $accounts)
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>{{ $type }}</h6>
                            </div>
                            <div class="card-body table-border-style">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="10%">Code</th>
                                                <th width="30%">Name</th>
                                                <th width="20%">Type</th>
                                                <th width="20%">Balance</th>
                                                <th width="10%">Status</th>
                                                <th width="10%" class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($accounts as $account)
                                                @php
                                                    $balance = 0;
                                                    $totalDebit = 0;
                                                    $totalCredit = 0;
                                                    $totalBalance = \App\Models\Utility::getAccountBalance($account->id,$filter['startDateRange'],$filter['endDateRange']);
                                                @endphp
        
                                                <tr>
                                                    <td>{{ $account->code }}</td>
                                                    <td><a
                                                            href="{{ route('report.ledger', $account->id) }}?account={{ $account->id }}">{{ $account->name }}</a>
                                                    </td>
                                                    <td>{{ !empty($account->subType) ? $account->subType->name : '-' }}</td>
        
                                                    <td>
                                                        @if (!empty($totalBalance))
                                                            {{ \App\Models\Utility::priceFormat($totalBalance) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($account->is_enabled == 1)
                                                            <span
                                                                class="badge bg-primary p-2 px-3 rounded">{{ __('Enabled') }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-danger p-2 px-3 rounded">{{ __('Disabled') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="actions">
                                                            <div class="action-btn ms-2">
                                                                <a href="{{ route('report.ledger', $account->id) }}?account={{ $account->id }}"
                                                                    class="mx-1 btn btn-sm align-items-center " data-bs-toggle="tooltip"
                                                                    title="{{ __('Transaction Summary') }}"
                                                                    data-original-title="{{ __('Detail') }}">
                                                                    <i class="fas fa-wave-square"></i>
                                                                </a>
                                                            </div>
            
                                                            <div class="action-btn">
                                                                <a href="#" class="mx-1 btn btn-sm align-items-center"
                                                                    data-url="{{ route('chart-of-account.edit', $account->id) }}"
                                                                    data-ajax-popup="true" data-title="{{ __('Edit Account') }}"
                                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                    data-original-title="{{ __('Edit') }}">
                                                                    <i class="feather-edit"></i>
                                                                </a>
                                                            </div>
    
                                                            <div class="action-btn ms-2">
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['chart-of-account.destroy', $account->id],
                                                                    'id' => 'delete-form-' . $account->id,
                                                                ]) !!}
                                                                <a class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$account->id}}">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </a>
    
                                                                <div class="modal fade contentmodal" id="{{'deleteModal-'.$account->id}}" tabindex="-1" aria-hidden="true">
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
                @endforeach
            </div>

        </div>
    </div>

@endsection

