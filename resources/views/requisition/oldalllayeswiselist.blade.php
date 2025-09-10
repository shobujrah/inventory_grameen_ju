@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Requisitions</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('requisition.list') }}">Requisition</a></li>
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
                                        <a href="{{ route('requisition.createreq') }}" class="btn btn-sm btn-primary"
                                            data-bs-toggle="tooltip" title="Create">
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
                                            <th>Serial</th>
                                            <th>Branch Name</th>
                                            <th>Project Name</th>
                                            <th>Date</th>
                                            <!-- @if ($isAdmin == 1 || $user_id == Auth::id())
                                                <th>Approve/ Reject <br class="text-center">Status</th>
                                            @endif -->
                                            <!-- <th>Purchase Status</th> -->
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requisitions as $key => $requisitionkey)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <!-- <td>{{ $requisitionkey->branch_name }}</td> -->
                                                <td>{{ optional($requisitionkey->branch)->name }}</td>
                                                <td>{{ $requisitionkey->project_name }}</td>
                                                <td>{{ App\Models\Utility::dateFormat($requisitionkey->date_from) }}</td>

                                               <!-- <td>
                                                    @if ($isAdmin == 1)
                                                        @if ($requisitionkey->statusChecked == 1)
                                                            {{ $requisitionkey->status == 1 ? 'Approved' : 'Rejected' }}
                                                        @else
                                                            Pending
                                                        @endif
                                                    @elseif ($user_id == Auth::id())
                                                        @php
                                                            $approvalStatus = App\Models\ApprovalStatus::where('module', 'requisition')
                                                                ->where('module_id', $requisitionkey->id)
                                                                ->orderBy('id', 'desc') 
                                                                ->first();
                                                        @endphp

                                                        @if ($approvalStatus)
                                                            {{ $approvalStatus->status == 1 ? 'Approved' : 'Rejected' }}
                                                        @else
                                                            Pending
                                                        @endif
                                                    @else
                                                        Pending
                                                    @endif
                                                </td> -->

                                                
                                                <td>
                                                    @if (is_null($requisitionkey->status))
                                                        Pending
                                                    @elseif ($requisitionkey->status == 1)
                                                        Delivered
                                                    @elseif ($requisitionkey->status == 2)
                                                        Purchased
                                                    @elseif ($requisitionkey->status == 4)
                                                         Pending Purchase
                                                    @elseif ($requisitionkey->status == 5)
                                                         Rejected
                                                    @else
                                                        Stocked
                                                    @endif
                                                </td>



                                                <!-- <td>
                                                    @if ($requisitionkey->purchase_status == 1)
                                                        Purchased
                                                    @else
                                                        Pending
                                                    @endif
                                                </td> -->
                                                
                                                <td>
                                                    <a href="{{ route('requisition.view', $requisitionkey->id) }}"
                                                        class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <!-- @if ($requisitionkey->isApprove == 0)
                                                        <a href="{{ route('requisition.edit', $requisitionkey->id) }}"
                                                            class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                            title="{{ __('Edit') }}">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        <a href="{{ route('requisition.delete', $requisitionkey->id) }}"
                                                            class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                            title="{{ __('Delete') }}">
                                                            <i class="fas fa-trash text-danger"></i>
                                                        </a>
                                                    @endif -->

                                                  
                                                    @if (is_null($requisitionkey->status))
                                                        <a href="{{ route('requisition.edit', $requisitionkey->id) }}"
                                                            class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                            title="{{ __('Edit') }}">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        <a href="{{ route('requisition.delete', $requisitionkey->id) }}"
                                                            class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                            title="{{ __('Delete') }}">
                                                            <i class="fas fa-trash text-danger"></i>
                                                        </a>
                                                    @endif







                                                    <!-- @if ($isAdmin == 1)
                                                        @if ($requisitionkey->statusChecked == 0)
                                                            <a href="{{ route('requisition.approveRequisition', $requisitionkey->id) }}"
                                                                class="btn btn-sm btn-primary mx-1"
                                                                style="margin-left: 8px !important;">Approve</a>
                                                            <a href="{{ route('requisition.rejectRequisition', $requisitionkey->id) }}"
                                                                class="btn btn-sm btn-danger">Reject</a>
                                                        @endif
                                                    @endif

                                                    
                                                    @php
                                                        $minOrder = App\Models\Approval::where('module', 'requisition')->min('order');
                                                        $userOrder = App\Models\Approval::where('module', 'requisition')->where('role_id', $roleId)->first()->order ?? null;
                                                    @endphp -->



                                                  





                                                    <!-- @if ($requisitionkey->isHeadoffice && $requisitionkey->user_id == $user_id && is_null($requisitionkey->status))
                                                        <a href="{{ route('requisition.purchasee', $requisitionkey->id) }}" class="btn btn-sm btn-info mx-1" style="margin-left: 8px !important;">Purchase</a>
                                                        <a href="{{ route('requisition.deliveryy', $requisitionkey->id) }}" class="btn btn-sm btn-success mx-1" style="margin-left: 8px !important;">Delivery</a>
                                                    @endif -->

                                                    @if ($authUserBranch && $authUserBranch->type === 'Headoffice' && is_null($requisitionkey->status))
                                                        <a href="{{ route('requisition.purchasee', $requisitionkey->id) }}" class="btn btn-sm btn-info mx-1" style="margin-left: 8px !important;">Purchase</a>
                                                        <a href="{{ route('requisition.deliveryy', $requisitionkey->id) }}" class="btn btn-sm btn-success mx-1" style="margin-left: 8px !important;">Delivery</a>
                                                    @endif



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
