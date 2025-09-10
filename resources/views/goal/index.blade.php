
@extends('layouts.master')
@section('content')

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Goals</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Goals</li>
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
                                        <h3 class="page-title">Goals</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="#" class="btn btn-outline-primary me-2"><i class="fas fa-download"></i> Download</a>
                                        <a href="#" data-url="{{ route('goal.create') }}"data-bs-toggle="tooltip" title="{{__('Create')}}" data-ajax-popup="true" data-title="{{__('Create New Goal')}}" class="btn btn-sm btn-primary">
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
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Amount</th>
                                            <th>Is Dashboard Display</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($golas as $gola)
                                        <tr>
                                            <td class="font-style">{{ $gola->name }}</td>
                                            <td class="font-style"> {{ __(\App\Models\Goal::$goalType[$gola->type]) }} </td>
                                            <td class="font-style">{{ $gola->from }}</td>
                                            <td class="font-style">{{ $gola->to }}</td>
                                            <td class="font-style">{{ \App\Models\Utility::priceFormat($gola->amount) }}</td>
                                            <td class="font-style">{{$gola->is_display==1 ? __('Yes') :__('No')}}</td>
                                            <td class="text-center">
                                                <div class="actions">
                                                    <div class="action-btn ms-2">
                                                        <a href="#" class="mx-1 btn btn-sm align-items-center" data-url="{{ route('goal.edit',$gola->id) }}" data-ajax-popup="true" data-title="{{__('Edit Goal')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                    </div>
                                                    <div class="action-btn ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['goal.destroy', $gola->id],'id'=>'delete-form-'.$gola->id]) !!}
                                                            <a class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$gola->id}}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a>

                                                            <div class="modal fade contentmodal" id="{{'deleteModal-'.$gola->id}}" tabindex="-1" aria-hidden="true">
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

