@extends('layouts.master')
@section('content')

<script type="text/javascript" src="{{ asset('assets/js/plugins/html2pdf.bundle.min.js') }}"></script>



<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Approve Requisition</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Requisition</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- message --}}
        {!! Toastr::message() !!}

        @push('script-page')
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

        <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
        <script>
            var filename = $('#filename').val();

            function saveAsPDF() {
                var element = document.getElementById('printableArea');
                var opt = {
                    margin: 0.3,
                    filename: filename,
                    image: {
                        type: 'jpeg',
                        quality: 1
                    },
                    html2canvas: {
                        scale: 4,
                        dpi: 72,
                        letterRendering: true
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'A2'
                    }
                };
                html2pdf().set(opt).from(element).save();
            }
        </script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#filter").click(function() {
                    $("#show_filter").toggle();
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                callback();

                function callback() {
                    var start_date = $(".startDate").val();
                    var end_date = $(".endDate").val();

                    $('.start_date').val(start_date);
                    $('.end_date').val(end_date);

                }
            });
        </script>
        @endpush




        <div class="row">
            <div class="col-12" id="invoice-container">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between w-100">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="profile-tab3" data-bs-toggle="pill" href="#item" role="tab" aria-controls="pills-item" aria-selected="true">{{__('Requisition')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab4" data-bs-toggle="pill" href="#customer" role="tab" aria-controls="pills-customer" aria-selected="false">{{__('Others')}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>


                    <style>
                        .timeline:before {
                            width: 0px;
                        }

                        .timeline-item {
                            display: flex;
                            align-items: center;
                            margin-bottom: 20px;
                            position: relative;
                        }
                        .timeline-item .actions {
                            position: absolute;
                            right: -50px;
                        }

                        .timeline-content {
                            background-color: #f9f9f9;
                            padding: 12px 20px;
                            border-radius: 5px;
                            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                        }

                        .timeline-content h3 {
                            margin-top: 0;
                            font-size: 18px;
                        }

                        .timeline-item h6 {
                            font-size: 28px;
                        }

                        .timeline-arrow {
                            font-size: 24px;
                            margin: 0;
                            padding: 0;
                            line-height: 1;
                        }

                        .timeline-item:nth-child(even) .timeline-arrow {
                            transform: rotate(180deg);
                        }
                    </style>



        <div class="card-body">
            <div class="tab-content" id="myTabContent2">
                 <div class="tab-pane fade fade show active" id="item" role="tabpanel" aria-labelledby="profile-tab3">

                    <div class="timeline d-flex align-items-center flex-column">
                        
                  
                    <!-- @foreach($approvals as $approval)
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <h3 class="mb-0">{{ $approval->role->name }}</h3>
                            </div>
                            <div class="actions">
                                <a href="{{route('requisition.approve.delete',$approval->id)}}" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <h6 class="mb-0">↓</h6>
                        </div>
                    @endforeach -->


                    @php
                        $levelCounter = 1;
                    @endphp

                    @foreach($approvals as $approval)
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <h3 class="mb-0">Layer {{$levelCounter}} - {{ $approval->role->name }}</h3>
                            </div>
                            <div class="actions">
                                <a href="{{route('requisition.approve.delete',$approval->id)}}" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <h6 class="mb-0">↓</h6>
                        </div>
                        @php
                            $levelCounter++;
                        @endphp
                    @endforeach




                    <!-- Role_id name disable -->

                    <!-- <div class="timeline-item">
                        <button class="btn btn-primary" id="addStepBtn" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-plus"></i></button>
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Select Role</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('requisition.approve.store')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3 d-flex align-items-center">
                                                <select class="form-select" name="role_id" required>
                                                    <option value="" selected disabled>Select a role</option>
                                                    @foreach ($roles as $role)
                                                        @php
                                                            $approvalIds = $approvals->pluck('role_id')->toArray();
                                                            $disabled = in_array($role->id, $approvalIds) ? 'disabled' : '';
                                                        @endphp
                                                        <option value="{{$role->id}}" {{$disabled}}>{{$role->name}}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-primary ms-3" id="submitStepBtn">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->


                    <!-- role_id name not show if once insert -->

                    <div class="timeline-item">
                        <button class="btn btn-primary" id="addStepBtn" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-plus"></i></button>
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Select Role</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('requisition.approve.store')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3 d-flex align-items-center">
                                                <select class="form-select" name="role_id" required>
                                                    <option value="" selected disabled>Select a role</option>
                                                    @foreach ($roles as $role)
                                                        @php
                                                            $addedRole = $approvals->where('role_id', $role->id)->first();
                                                        @endphp
                                                        @if (!$addedRole)
                                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-primary ms-3" id="submitStepBtn">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


            </div>
        </div>

            <div class="tab-pane fade fade" id="customer" role="tabpanel" aria-labelledby="profile-tab3">
              <h5>This is temporary empty now!</h5>                   
            </div>

            </div>
            </div>

         </div>
       </div>
      </div>
     </div>
   </div>
    
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#myForm').submit(function(event) {
                    event.preventDefault();
                    this.submit();
                });
            });
        </script>



@endsection