@extends('layouts.master')
@section('content')

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title"> Approved list</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">List</li>
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
                            <div class="table-responsive">
                                <table class="table border-0 star-warehouse table-hover table-center mb-0 datatable table-striped">
                                    <thead class="warehouse-thread">
                                        <tr>
                                            <th>Serial</th>
                                            <th>Branch Name</th>
                                            <th>Project Name</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Document</th>
                                            <th>Action</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requisitions as $key => $requisition)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $requisition->branch->name ?? '' }}</td>
                                                <td>{{ $requisition->project->name ?? '' }}</td>
                                                <td>{{ $requisition->date_from }}</td>
                                                <td>
                                                    @if ($requisition->headoffice_approve == '1')
                                                        <span class="badge" style="background-color: #4CAF50; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Approved</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($requisition->document)
                                                        @php
                                                            // Extract file extension
                                                            $extension = pathinfo($requisition->document, PATHINFO_EXTENSION);
                                                        @endphp

                                                        {{-- Display icons based on file type --}}
                                                        @if(in_array($extension, ['pdf']))
                                                            <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                        @elseif(in_array($extension, ['doc', 'docx']))
                                                            <i class="fas fa-file-word fa-2x text-primary"></i>
                                                        @elseif(in_array($extension, ['xls', 'xlsx']))
                                                            <i class="fas fa-file-excel fa-2x text-success"></i>
                                                        @elseif(in_array($extension, ['ppt', 'pptx']))
                                                            <i class="fas fa-file-powerpoint fa-2x text-warning"></i>
                                                        @elseif(in_array($extension, ['png', 'jpg', 'jpeg', 'gif']))
                                                            <img src="{{ asset('storage/document/' . $requisition->document) }}" alt="{{ $requisition->id }}" style="width: 50px; height: 50px;">
                                                        @else
                                                            <i class="fas fa-file-alt fa-2x"></i> {{-- Generic file icon for other file types --}}
                                                        @endif

                                                        {{-- Download button --}}

                                                        <a style="position: relative; top: -10px; padding: 3px 3px; font-size: 8px; line-height: 1;" href="{{ asset('storage/document/' . $requisition->document) }}" download class="btn btn-sm btn-dark ms-2" data-bs-toggle="tooltip" title="Download">
                                                            <i class="fas fa-download"></i>
                                                        </a>

                                                    @else
                                                        No File
                                                    @endif
                                                </td>
 
                                                <td>
                                                    <a href="{{ route('pending.approval.approved.list.view', $requisition->id) }}" class="btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{ __('View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

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
