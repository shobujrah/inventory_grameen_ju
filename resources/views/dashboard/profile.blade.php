
@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Profile</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- message --}}
        {!! Toastr::message() !!}



        <div class="row">
            <div class="col-md-12">
                <div class="profile-header">
                    <div class="row align-items-center">
                        <div class="col-auto profile-image">
    
                            <a href="#">
                                <img class="rounded-circle" alt="{{ $user->name }}" src="{{ asset('images/' . $user->avatar) }}">
                            </a>

                        </div>
                        <div class="col ms-md-n2 profile-user-info">
                            <h4 class="user-name mb-0"> {{ $user->name }} </h4>
                            <h6 class="text-muted"></h6>
                            <div class="user-Location"><i class="fas fa-map-marker-alt"></i> Address: {{ $user->address ?? 'No address available' }}</div>
                            <div class="user-Branch"><i class="fas fa-building"></i> Branch: <span class="badge badge-success">{{ $user->branch_name ?? 'No Branch Found' }}</span></div>
                        </div>
                        <div class="col-auto profile-btn">
                            <!-- <a href="{{ route('user.profile.page.edit') }}" class="btn btn-primary">Edit</a> -->
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit</a>

                        </div>
                    </div>
                </div>
                <div class="profile-menu">
                    <ul class="nav nav-tabs nav-tabs-solid">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#password_tab">Password</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content profile-tab-cont">

                    <div class="tab-pane fade show active" id="per_details_tab">
                        <div class="row">
                            <div class="col-lg-9">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title d-flex justify-content-between">
                                            <span>Personal Details:</span>
                                                <br> </br>
                                            <!-- <a href="{{ route('user.profile.page.edit') }}"><i class="far fa-edit sm"></i>Edit</a> -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal"><i class="far fa-edit sm"></i>Edit</a>

                                        </h5>
                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Name:</p>
                                            <p class="col-sm-9">{{ $user->name }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Date of Birth:</p>
                                            <p class="col-sm-9">{{ $user->date_of_birth ?? 'No Date of Birth Available' }}</p>
                                        </div>

                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Email:</p>
                                            <p class="col-sm-9">{{ $user->email }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Mobile:</p>
                                            <p class="col-sm-9">{{ $user->phone_number }}</p>
                                        </div>

                                    </div>
                                </div>
                            </div>


                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title d-flex justify-content-center">
                                                <span>Account Status:</span>
                                            </h5>
                                            <div class="d-flex justify-content-center">
                                                <div class="status-circle">
                                                    <i class="fe fe-check-verified"></i>
                                                    <span>{{ $user->status }}  </span>
                                                </div>
                                            </div>
                                           <br> </br>
                                            <div class="d-flex justify-content-center">
                                                <div class="row">
                                                    <span class="text-align: center;"><h6 class="text-bold"> Join Date: </h6> {{ \Carbon\Carbon::parse($user->join_date)->format('d-m-Y') }} </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <style>
                                    .status-circle {
                                        width: 80px;
                                        height: 80px;
                                        background-color: #28a745; 
                                        border-radius: 50%;
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        color: white;
                                        font-size: 18px;
                                        text-align: center;
                                        flex-direction: column;
                                    }

                                    .status-circle i {
                                        font-size: 24px;
                                        margin-bottom: 10px; 
                                    }

                                    .status-circle span {
                                        display: block;
                                        text-align: center;
                                        font-size: 16px;
                                    }
                                </style>


                        </div>
                    </div>



                    <!-- <div id="password_tab" class="tab-pane fade">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Change Password:</h5>
                                <br> </br>
                                <div class="row">
                                    <div class="col-md-10 col-lg-6">
                                        <form action="{{ route('change/password') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Old Password:</label>
                                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" value="{{ old('current_password') }}">
                                                @error('current_password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                           
                                            <div class="form-group">
                                                <label>New Password:</label>
                                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" value="{{ old('new_password') }}">
                                                @error('new_password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label>Confirm Password:</label>
                                                <input type="password" class="form-control @error('new_confirm_password') is-invalid @enderror" name="new_confirm_password" value="{{ old('new_confirm_password') }}">
                                                @error('new_confirm_password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->




                    <div id="password_tab" class="tab-pane fade">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Change Password:</h5>
                                <br> </br>
                                <div class="row">
                                    <div class="col-md-10 col-lg-6">
                                        <form action="{{ route('change/password') }}" method="POST">
                                            @csrf

                                            <div class="form-group position-relative">
                                                <label>Old Password:<span class="text-danger">(min 8)*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" id="current_password" value="{{ old('current_password') }}" required>
                                                    <span class="input-group-text password-toggle" onclick="togglePasswordVisibility('current_password', this)">
                                                        <i class="fa fa-eye"></i>
                                                    </span>
                                                </div>
                                                @error('current_password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>


                                            
                                            <div class="form-group position-relative">
                                                <label>New Password:<span class="text-danger">(min 8)*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" id="new_password" value="{{ old('new_password') }}" required>
                                                        <span class="input-group-text password-toggle" onclick="togglePasswordVisibility('new_password', this)">
                                                            <i class="fa fa-eye"></i>
                                                        </span>
                                                </div>
                                                @error('new_password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group position-relative">
                                                <label>Confirm Password:<span class="text-danger">(min 8)*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control @error('new_confirm_password') is-invalid @enderror" name="new_confirm_password" id="new_confirm_password" value="{{ old('new_confirm_password') }}" required>
                                                        <span class="input-group-text password-toggle" onclick="togglePasswordVisibility('new_confirm_password', this)">
                                                            <i class="fa fa-eye"></i>
                                                        </span>
                                                </div>
                                                @error('new_confirm_password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function togglePasswordVisibility(fieldId, icon) {
                            var passwordField = document.getElementById(fieldId);
                            var iconElement = icon.querySelector('i');
                            
                            if (passwordField.type === "password") {
                                passwordField.type = "text";
                                iconElement.classList.remove('fa-eye');
                                iconElement.classList.add('fa-eye-slash');
                            } else {
                                passwordField.type = "password";
                                iconElement.classList.remove('fa-eye-slash');
                                iconElement.classList.add('fa-eye');
                            }
                        }
                    </script>

                    <style>
                            .form-group {
                            position: relative;
                        }

                        .password-toggle {
                            position: absolute;
                            right: 10px; /* Adjust as per your requirement */
                            top: 50%;
                            transform: translateY(-50%);
                            cursor: pointer;
                            z-index: 10;
                            background-color: transparent;
                            border: none;
                        }

                    </style>



                </div>
            </div>
        </div>
    </div>
</div>


<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Include your edit form here -->
                <form action="{{ route('user.profile.page.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="name">Name<span class="login-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth<span class="login-danger">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Phone Number<span class="login-danger">*</span></label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                    </div>

                    <div class="form-group">
                        <label for="avatar">Avatar<span class="login-danger">(.jpeg,.png,.jpg & max 2 mb)</label>
                        <!-- <input type="file" id="avatar" name="avatar" class="form-control"> -->
                        <input type="file" id="avatar" name="avatar" class="form-control" accept=".jpeg,.png,.jpg" max-file-size="2048">

                        @if($user->avatar)
                            <img src="{{ asset('images/' . $user->avatar) }}" alt="Avatar" class="img-thumbnail mt-2" width="100">
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
