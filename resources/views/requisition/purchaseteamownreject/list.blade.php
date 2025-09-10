@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Reject List </h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"><a >Reject list</a></li>
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
                                            <th>Status</th>
                                            @if ($authUserBranch && $authUserBranch->type !== 'Branch')
                                                <th>Document</th>
                                            @endif
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requisitions as $key => $requisitionkey)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ optional($requisitionkey->branch)->name ?? '' }}</td>
                                                <td>{{ $requisitionkey->project->name ?? '' }}</td>
                                                <td>{{ $requisitionkey->date_from }}</td>
                                                <td>
                                                  
                                                    @if ($requisitionkey->purchase_reject == 1)
                                                   
                                                         <span class="badge" style="background-color: #dc3545; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Rejected </span>


                                                    @else
                                                        <span class="badge" style="background-color: #dc3545; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Rejected</span>

                                                    @endif
                                                </td>

                                                @if ($authUserBranch && $authUserBranch->type !== 'Branch')
                                                    <td>
                                                        @if($requisitionkey->document)
                                                            @php
                                                                $extension = pathinfo($requisitionkey->document, PATHINFO_EXTENSION);
                                                            @endphp

                                                            @if(in_array($extension, ['pdf']))
                                                                <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                            @elseif(in_array($extension, ['doc', 'docx']))
                                                                <i class="fas fa-file-word fa-2x text-primary"></i>
                                                            @elseif(in_array($extension, ['xls', 'xlsx']))
                                                                <i class="fas fa-file-excel fa-2x text-success"></i>
                                                            @elseif(in_array($extension, ['ppt', 'pptx']))
                                                                <i class="fas fa-file-powerpoint fa-2x text-warning"></i>
                                                            @elseif(in_array($extension, ['png', 'jpg', 'jpeg', 'gif']))
                                                                <img src="{{ asset('storage/document/' . $requisitionkey->document) }}" alt="{{ $requisitionkey->id }}" style="width: 50px; height: 50px;">
                                                            @else
                                                                <i class="fas fa-file-alt fa-2x"></i> {{-- Generic file icon for other file types --}}
                                                            @endif

                                                            {{-- Download button --}}

                                                            <a style="position: relative; top: -10px; padding: 3px 3px; font-size: 8px; line-height: 1;" href="{{ asset('storage/document/' . $requisitionkey->document) }}" download class="btn btn-sm btn-dark ms-2" data-bs-toggle="tooltip" title="Download">
                                                                <i class="fas fa-download"></i>
                                                            </a>

                                                        @else
                                                            No File
                                                        @endif
                                                    </td>
                                                @endif 
 
                                                <td>
                                                    <a href="{{ route('reject.list.collection.view', $requisitionkey->id) }}"
                                                        class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                        <a href="#" class="btn btn-sm align-items-center" data-bs-toggle="modal" data-bs-target="#rejectNoteModal{{ $requisitionkey->id }}" title="Reject">
                                                            <i class="fas fa-ban text-danger"></i>
                                                        </a>

                                                        <div class="modal fade" id="rejectNoteModal{{ $requisitionkey->id }}" tabindex="-1" aria-labelledby="rejectNoteLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="rejectNoteLabel">Reject Note</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <!-- <textarea class="form-control" readonly>{{ $requisitionkey->reject_note }}</textarea> -->
                                                                        <textarea class="form-control" readonly style="max-height: 200px; overflow-y: auto;">{{ $requisitionkey->purchaseteam_reject_note }}</textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
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
