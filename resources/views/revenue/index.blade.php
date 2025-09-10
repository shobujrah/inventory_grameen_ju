
@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Revenues</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Revenues</li>
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
                                        <h3 class="page-title">Revenues</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> Download</a>
                                        <a href="#" class="btn btn-primary" data-url="{{ route('revenue.create') }}" data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Revenue')}}"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Account</th>
                                            <th>Customer</th>
                                            <th>Category</th>
                                            <th>Reference</th>
                                            <th>Description</th>
                                            <th>Payment Receipt</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>

                                    @php
                                        $revenuepath=Storage::url('uploads/revenue');
                                    @endphp
                                    
                                    <tbody>
                                        @foreach ($revenues as $index => $revenue)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{  \App\Models\Utility::dateFormat($revenue->date)}}</td>
                                            <td>{{  App\Models\Utility::priceFormat($revenue->amount)}}</td>
                                            <td>{{ !empty($revenue->bankAccount)?$revenue->bankAccount->bank_name.' '.$revenue->bankAccount->holder_name:''}}</td>
                                            <td>{{  (!empty($revenue->customer)?$revenue->customer->name:'-')}}</td>
                                            <td>{{  !empty($revenue->category)?$revenue->category->name:'-'}}</td>
                                            <td>{{  !empty($revenue->reference)?$revenue->reference:'-'}}</td>
                                            <td>{{  !empty($revenue->description)?$revenue->description:'-'}}</td>
                                            <td>
                                                @if(!empty($revenue->add_receipt))
                                                    <a class="action-btn bg-primary ms-2 btn btn-sm align-items-center" href="{{ $revenuepath . '/' . $revenue->add_receipt }}" download="">
                                                        <i class="fas fa-download text-white mx-1"></i>
                                                    </a>
                                                    <a href="{{ $revenuepath . '/' . $revenue->add_receipt }}"  class="action-btn bg-secondary ms-2 mx-1 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Download')}}" target="_blank">
                                                        <span class="btn-inner--icon">
                                                            <i class="far fa-eye text-white mx-1"></i>
                                                        </span>
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-end">
                                                <div class="actions">
                                                     <a href="#" class="mx-1 btn btn-sm align-items-center" data-url="{{ route('revenue.edit',$revenue->id) }}" data-ajax-popup="true" title="{{__('Edit')}}" data-title="{{__('Edit Revenue')}}" data-bs-toggle="tooltip" data-size="lg" data-original-title="{{__('Edit')}}">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['revenue.destroy', $revenue->id],'id'=>'delete-form-'.$revenue->id]) !!}
                                                        <a class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$revenue->id}}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                        <div class="modal fade contentmodal" id="{{'deleteModal-'.$revenue->id}}" tabindex="-1" aria-hidden="true">
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

@endsection

