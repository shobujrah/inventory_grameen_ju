
@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Product & Income-Expense Category</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Product Category</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- message --}}
            {!! Toastr::message() !!}

            <div class="row">
                <div class="col-3">
                    @include('component.account_setup')
                </div>
                <div class="col-9">
                    <div class="row mb-3">
                        <div class="col-auto text-end float-end ms-auto">
                            <a href="#" class="btn btn-primary btn-sm" data-url="{{ route('product-category.create') }}" data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Tax Rate')}}"><i class="fas fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table datatable">
                                    <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Type</th>
                                        <th>Account</th>
                                        <th width="10%"> Action</th>
                                    </tr>
                                    </thead>
        
                                    <tbody>
                                    @foreach ($categories as $category)
                                        <tr class="font-style">
                                            <td class="font-style">{{ $category->name }}</td>
                                        <td class="font-style">
                                            @if (array_key_exists($category->type, \App\Models\ProductServiceCategory::$catTypes))
                                                {{ __(\App\Models\ProductServiceCategory::$catTypes[$category->type]) }}
                                            @else
                                                {{ __('Undefined category type') }}
                                            @endif
                                        </td>
                                        <td>{{ (!empty($category->chartAccount)?$category->chartAccount->name :'-') }}</td>
                                        <td class="text-end">
                                            <div class="actions">
                                                <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('product-category.edit',$category->id) }}" data-ajax-popup="true" data-title="{{__('Edit Product Category')}}" data-bs-toggle="tooltip" title="{{__('Create')}}" data-original-title="{{__('Edit')}}">
                                                    <i class="feather-edit"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['product-category.destroy', $category->id],'id'=>'delete-form-'.$category->id]) !!}
                                                    <a class="mx-1 btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="{{'#deleteModal-'.$category->id}}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <div class="modal fade contentmodal" id="{{'deleteModal-'.$category->id}}" tabindex="-1" aria-hidden="true">
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

