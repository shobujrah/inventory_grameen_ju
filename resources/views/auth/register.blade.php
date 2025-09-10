
@extends('layouts.app')
@section('content')
    <div class="login-right">
        <div class="login-right-wrap">
            <h1>Sign Up</h1>
            <p class="account-subtitle">Enter details for sign up</p>
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Name <span class="login-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" required>
                    <span class="profile-views"><i class="fas fa-user-circle"></i></span>
                </div>
                <div class="form-group">
                    <label>Email <span class="login-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" required>
                    <span class="profile-views"><i class="fas fa-envelope"></i></span>
                </div>
                <!-- ~ -->
                <div class="form-group">
                    <label>Phone <span class="login-danger">*</span></label>
                    <input type="number" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" required>
                    <span class="profile-views"><i class="fas fa-mobile-alt"></i></span>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" id="" class="form-control @error('address') is-invalid @enderror" cols="30" rows="10"></textarea>
                    <span class="profile-views"><i class="fas fa-address-card"></i></span>
                </div>

                <!-- <div class="form-group">
                    <label>Blood Group <span class="login-danger">*</span></label>
                    <input type="text" class="form-control @error('blood_group') is-invalid @enderror" name="blood_group">
                    <span class="profile-views"><i class="fas fa-heartbeat"></i></span>
                </div> -->

                <div class="form-group">
                    <label>Designation <span class="login-danger">*</span></label>
                    <input type="text" class="form-control @error('designation') is-invalid @enderror" name="designation" required>
                    <span class="profile-views"><i class="fas fa-clipboard-list"></i></span>
                </div>

                <div class="form-group">
                    <label>Date Of Birth <span class="login-danger">*</span></label>
                    <input type="date" placeholder="DD-MM-YYYY" class="form-control datetimepicker @error('date_of_birth') is-invalid @enderror" name="date_of_birth" required>
                    <span class="profile-views"><i class="fas fa-calender"></i></span>
                </div>

                <!-- <div class="form-group">
                    <label>Designation <span class="login-danger">*</span></label>
                    <input type="text" class="form-control @error('position') is-invalid @enderror" name="position">
                    <span class="profile-views"><i class="fas fa-clipboard-list"></i></span>
                </div> -->

                <!-- ~ -->
                {{-- insert defaults --}}
                <input type="hidden" class="image" name="image" value="photo_defaults.jpg">

                <div class="form-group">
                    <label>Password <span class="login-danger">(min 8)*</span></label>
                    <input type="password" class="form-control pass-input  @error('password') is-invalid @enderror" name="password" required>
                    <span class="profile-views feather-eye toggle-password"></span>
                </div>
                <div class="form-group">
                    <label>Confirm password <span class="login-danger">(min 8)*</span></label>
                    <input type="password" class="form-control pass-confirm @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required>
                    <span class="profile-views feather-eye reg-toggle-password"></span>
                </div>
                {{-- ~ --}}
                <!-- <div class="form-group local-forms">
                    <label>User Type <span class="login-danger">*</span></label>
                    <select class="form-control select @error('navigate_to') is-invalid @enderror" name="navigate_to" id="navigate_to" required>
                        <option selected disabled>Select</option>
                        <option value="Warehouse">Warehouse</option>
                        <option value="Branch">Branch</option>
                    </select>
                    @error('navigate_to')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                </div> -->

                <div class="form-group local-forms">
                    <label for="branch_id">Branch <span class="login-danger">*</span></label>
                    <select class="form-control select @error('branch_id') is-invalid @enderror" name="branch_id" id="branch_id" required>
                        <option selected disabled>--Select Branch--</option>
                        @foreach($branches as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                {{-- ~ --}}
                <div class=" dont-have">Already Registered? <a href="{{ route('login') }}">Login</a></div>
                <div class="form-group mb-0">
                    <button class="btn btn-primary btn-block" type="submit">Register</button>
                </div>
            </form>
            <!-- <div class="login-or">
                <span class="or-line"></span>
                <span class="span-or">or</span>
            </div>
            <div class="social-login">
                <a href="#"><i class="fab fa-google-plus-g"></i></a>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div> -->
        </div>
    </div>



    <script>
        $('#btnSubmit').click(function(event) {
            event.preventDefault();
            let valid = true;

            const phoneRegex = /^01[3-9][0-9]{8}$/;
            const phone = $('#phone_number').val();

            if (!phoneRegex.test(phone)) {
                valid = false;
                alert('Phone number must be valid.');
                $('#phone_number').focus();
            }

            if (valid) {
                $('#registrationForm').submit();
            }
        });
    </script>

    
@endsection
