
@extends('layouts.master')
@section('content')



    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Category List</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Category</li>
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
                                        <h3 class="page-title">Category Information</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="#" class="btn btn-sm btn-primary" data-url="{{ route('productcategory.create') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Category')}}"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped" id="printableArea">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>Serial</th>
                                            <th>Category Name</th>
                                            <th>Created Date</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($productcategory as $index => $productcategories)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{  $productcategories->name}}</td>
                                            <td>{{  ($productcategories->created_at)? date('d/m/Y', strtotime($productcategories->created_at)):'' }}</td>

                                            <td class="text-end">
                                                <div class="actions">
                                                     <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('productcategory.edit',$productcategories->id) }}" data-ajax-popup="true" title="{{__('Edit')}}" data-title="{{__('Edit productcategories')}}" data-bs-toggle="tooltip" data-size="md" data-original-title="{{__('Edit')}}">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['productcategory.destroy', $productcategories->id],'id'=>'delete-form-'.$productcategories->id]) !!}
                                                        <a class="btn btn-sm align-items-center" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$productcategories->id}}">
                                                            <i class="fas fa-trash text-danger"></i>
                                                        </a>

                                                        <div class="modal fade contentmodal" id="{{'deleteModal-'.$productcategories->id}}" tabindex="-1" aria-hidden="true">
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

