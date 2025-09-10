@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Damage/Return List</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"><a>List</a></li>
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
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>Serial</th>
                                            <th>Damage/Return BY</th>
                                            <th>Branch Name</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Damage/Return <br>Quantity</th>
                                            <th>Reason</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($returnproducts as $key => $returnproduct)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td><span class="badge md bg-success">{{ $returnproduct->user->name }}</span></td>
                                                <td>{{ $returnproduct->branch->name ?? ''}}</td>
                                                <td>{{ $returnproduct->product->name ?? '' }}</td>
                                                <td>{{ $returnproduct->price ?? '' }}</td>
                                                <td class="text-center">{{ $returnproduct->return_quantity }}</td>
                                        
                                                @php
                                                    if (!function_exists('splitIntoLines')) {
                                                        function splitIntoLines($text, $wordsPerLine = 7) {
                                                            $words = explode(' ', $text);
                                                            $lines = [];
                                                            
                                                            for ($i = 0; $i < count($words); $i += $wordsPerLine) {
                                                                $lines[] = implode(' ', array_slice($words, $i, $wordsPerLine));
                                                            }
                                                            
                                                            return implode('<br>', $lines);
                                                        }
                                                    }
                                                @endphp

                                                <td>
                                                    @if (str_word_count($returnproduct->reason) >= 7)
                                                        {!! splitIntoLines($returnproduct->reason) !!}
                                                    @else
                                                        {{ $returnproduct->reason }}
                                                    @endif
                                                </td>

                                                <td>{{ \Carbon\Carbon::parse($returnproduct->date)->format('d/m/Y') }}</td> 
                              
                                                <td>
                                                    {!! 
                                                        $returnproduct->status == 0 && $returnproduct->deny_status == null
                                                        ? '<span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>' 
                                                        : ($returnproduct->status == 1 && $returnproduct->deny_status == null
                                                            ? '<span class="badge" style="background-color: #28a745; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Accept</span>' 
                                                            : ($returnproduct->status == null && $returnproduct->deny_status == 1 
                                                                ? '<span class="badge" style="background-color: #dc3545; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Reject</span>' 
                                                                : 'Unknown')
                                                        )
                                                    !!}
                                                </td>

                                                <td>

                                                    @if ($returnproduct->status == null && $returnproduct->deny_status == 1)
                                                        <a href="#" class="btn btn-sm align-items-center" data-bs-toggle="modal" data-bs-target="#rejectNoteModal{{ $returnproduct->id }}" title="Reject">
                                                            <i class="fas fa-ban text-danger"></i>
                                                        </a>
                                                    @endif

                                                    <div class="modal fade" id="rejectNoteModal{{ $returnproduct->id }}" tabindex="-1" aria-labelledby="rejectNoteLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="rejectNoteLabel">Deny Note</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <textarea class="form-control" readonly style="max-height: 200px; overflow-y: auto;">{{ $returnproduct->deny_reason_note }}</textarea>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- @if ((auth()->user()->branch->type === 'Warehouse' || $returnproduct->status == 0 && $returnproduct->deny_status == null && $showButton && auth()->user()->role_name !== 'Admin' && auth()->user()->role_name !== 'PurchaseTeam'))
                                                        <a href="{{ route('cancel.returnproduct.warehouse', $returnproduct->id) }}" class="btn btn-danger btn-sm" style="margin-top: 5px;">Cancel</a>
                                                    @endif --> 


                                                    @if (((auth()->user()->branch->type === 'Warehouse' && $returnproduct->status == 0 && $returnproduct->deny_status == null) || ($returnproduct->status == 0 && $returnproduct->deny_status == null && $showButton)) && auth()->user()->role_name !== 'Admin' && auth()->user()->role_name !== 'PurchaseTeam')
                                                        <a href="{{ route('cancel.returnproduct.warehouse', $returnproduct->id) }}" class="btn btn-danger btn-sm" style="margin-top: 5px;">Cancel</a>
                                                    @endif


                                                    @if((in_array(auth()->user()->role_name, ['Admin', 'PurchaseTeam'])) && $returnproduct->status != 1 && $returnproduct->status != null)
                                                        <button type="button" class="btn btn-success btn-sm" style="margin-top: 5px;" data-bs-toggle="modal" data-bs-target="#acceptModal{{ $returnproduct->id }}">
                                                            Accept
                                                        </button>

                                                        <div class="modal fade" id="acceptModal{{ $returnproduct->id }}" tabindex="-1" aria-labelledby="acceptModalLabel{{ $returnproduct->id }}" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <form action="{{ route('accept.returnproduct.warehouse', $returnproduct->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="acceptModalLabel{{ $returnproduct->id }}">Select Payment Type</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <label for="payment_type_{{ $returnproduct->id }}" class="form-label">Payment Type <span class="text-danger">*</span></label>
                                                                            <select class="form-select" name="payment_type" id="payment_type_{{ $returnproduct->id }}" required>
                                                                                <option value="" disabled selected>-- Select Payment Method --</option>
                                                                                @foreach($paymentMethods as $method)
                                                                                    <option value="{{ $method->name }}">
                                                                                        @if($method->name == 'Cash In Hand')
                                                                                            Cash
                                                                                        @elseif($method->name == 'Cash at Bank')
                                                                                            Bank
                                                                                        @elseif($method->name == 'Accounts Receivable')
                                                                                            Due
                                                                                        @else
                                                                                            {{ $method->name }}
                                                                                        @endif
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <a href="javascript:void(0);" onclick="openDenyModal({{ $returnproduct->id }})" class="btn btn-danger btn-sm" style="margin-top: 5px;">Deny</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form id="denyForm" method="POST" action="{{ route('deny.returnproduct.warehouse') }}">
                                            @csrf
                                            <input type="hidden" name="returnproduct_id" id="returnproduct_id">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="denyModalLabel">Deny Return Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="deny_note">Deny Note</label>
                                                    <textarea class="form-control" id="deny_note" name="deny_note" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    function openDenyModal(returnProductId) {
        document.getElementById('returnproduct_id').value = returnProductId;
        new bootstrap.Modal(document.getElementById('denyModal')).show();
    }
</script>

@endsection
