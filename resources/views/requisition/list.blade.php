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

                        @if(auth()->user()->role_name != 'Admin') 
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
                        @endif



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
                                                <td>{{ $requisitionkey->date_from ?? '' }}</td>
                                                <td>
                                                    @if (is_null($requisitionkey->status))
                                                        <!-- Pending -->
                                                    <span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>

                                                    @elseif ($requisitionkey->status == 4)
                                                        <span style="background-color: #ADD8E6; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending Purchase</span>

                                                   
                                                    
                                                    @elseif ($requisitionkey->status == 4 && $requisitionkey->partial_delivery == 0)
                                                    <span class="badge" style="background-color: #ffc107; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Partial Delivered</span>


                                                    @elseif ($requisitionkey->status == 4 && $requisitionkey->partial_stock == 0)
                                                    <span class="badge" style="background-color: #ffc107; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Partial Stocked</span>

                                                        
                                                        <!-- Delivered & Partial Delivered  -->
                                                    @elseif ($requisitionkey->status == 1 && $requisitionkey->partial_delivery == 0)
                                                        <span class="badge" style="background-color: #ffc107; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Partial Delivered</span>
                                                    @elseif ($requisitionkey->status == 1 && $requisitionkey->partial_delivery == 1)
                                                        <span class="badge" style="background-color: #28a745; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Delivered</span>


                                                    @elseif ($requisitionkey->status == 2)
                                                        @if ($authUserBranch->type === 'Warehouse')
                                                            <span style="background-color: #28A745; color: #FFF; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Purchased</span>
                                                        @else
                                                            <span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>
                                                        @endif
                                                   

                                                    @elseif ($requisitionkey->status == 4)
                                                        @if ($authUserBranch->type === 'Warehouse')
                                                        <span style="background-color: #ADD8E6; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending Purchase</span>
                                                        @else
                                                        <span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>
                                                        @endif



                                                    @elseif ($requisitionkey->status == 5 && $requisitionkey->partial_reject == 1)
                                                     
                                                         <span class="badge" style="background-color: #dc3545; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Rejected</span>
                                                    @elseif ($requisitionkey->status == 5 && $requisitionkey->partial_reject == 0)
                                                      
                                                         <span class="badge" style="background-color: #dc3545; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Partial Rejected</span>

                                                    @elseif ($requisitionkey->status == 6)
                                                        @if ($authUserBranch->type === 'Warehouse')
                                                            <span class="badge" style="background-color: #dc3545; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Rejected</span>
                                                        @else
                                                            <span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>
                                                        @endif


                                                    @elseif ($requisitionkey->status == 7)
                                                        @if ($authUserBranch->type === 'Warehouse')
                                                            <span style="background-color: #ADD8E6; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending Approval for Purchase</span>
                                                        @else
                                                            <span class="badge" style="background-color: #FFD700; color: #000; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Pending</span>
                                                        @endif



                                                        @elseif ($requisitionkey->status == 3 && $requisitionkey->partial_stock == 0)
                                        
                                                         <span class="badge" style="background-color: #ffc107; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Partial Stocked</span>




                                                    @else
                                                        <!-- Stocked -->
                                                        <span class="badge" style="background-color: #007bff; color: #fff; padding: 0.25em 0.5em; border-radius: 0.25rem; font-weight: 600;">Stocked</span>

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
                                                    <a href="{{ route('requisition.view', $requisitionkey->id) }}"
                                                        class="btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>   


                                                    @if ($requisitionkey->status == 6 && $requisitionkey->purchase_reject == 1)
                                                        @if ($authUserBranch->type === 'Warehouse')
                                                            <a href="#" class="btn btn-sm align-items-center" data-bs-toggle="modal" data-bs-target="#rejectNoteModal{{ $requisitionkey->id }}" title="Reject">
                                                                <i class="fas fa-ban text-danger"></i>
                                                            </a>
                                                            <!-- Reject Note Modal -->
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
                                                        @endif
                                                    @else

                                                    @endif


                                                    @if ($requisitionkey->status == 5)
                                                        <a href="#" class="btn btn-sm align-items-center" data-bs-toggle="modal" data-bs-target="#rejectNoteModal{{ $requisitionkey->id }}" title="Reject">
                                                            <i class="fas fa-ban text-danger"></i>
                                                        </a>
                                                   
                                                         <!-- Reject Note Modal -->
                                                        <div class="modal fade" id="rejectNoteModal{{ $requisitionkey->id }}" tabindex="-1" aria-labelledby="rejectNoteLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="rejectNoteLabel">Reject Note</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <!-- <textarea class="form-control" readonly>{{ $requisitionkey->reject_note }}</textarea> -->
                                                                        <textarea class="form-control" readonly style="max-height: 200px; overflow-y: auto;">{{ $requisitionkey->reject_note }}</textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif 

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

                                                    @if ($authUserBranch && $authUserBranch->type === 'Warehouse' && is_null($requisitionkey->status))
                                                        <a href="{{ route('purchasee', $requisitionkey->id) }}" class="btn btn-sm btn-info mx-1" style="margin-left: 8px !important;">Purchase</a>

                                                            <button class="btn btn-sm btn-primary upload-btn" data-bs-toggle="modal" data-bs-target="#uploadModal" data-id="{{ $requisitionkey->id }}">
                                                                <i class="fas fa-upload"></i>
                                                            </button>

                                                        <!-- <a href="{{ route('requisition.deliveryy', $requisitionkey->id) }}" class="btn btn-sm btn-success mx-1" style="margin-left: 8px !important;">Delivery</a>
                                                        <a href="{{ route('requisition.purchasee', $requisitionkey->id) }}" class="btn btn-sm btn-danger mx-1" style="margin-left: 8px !important;">Deny</a> -->
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>




                            <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="uploadModalLabel">Upload Document</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form id="uploadForm" action="{{ route('requisition.upload') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <!-- Unique ID for upload requisition -->
                                            <input type="hidden" name="requisition_id" id="upload_requisition_id">
                                            <div class="mb-3 p-3">
                                                <label for="document" class="form-label">Choose Document</label>
                                                <input type="file" class="form-control" name="document" id="document" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Upload</button>
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
            document.querySelectorAll('.upload-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const requisitionId = this.getAttribute('data-id');
                    document.getElementById('upload_requisition_id').value = requisitionId;
                });
            });
    </script>

@endsection
