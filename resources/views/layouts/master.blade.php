@php $settings = \App\Models\Setting::first(); @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- A -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>{{$settings->website_name}}</title>
    <link rel="shortcut icon" href="{{ asset('logo/' . $settings->logo) }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/icons/flags/flags.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/css/bootstrap-datetimepicker.min.cs') }}s">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/icons/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/simple-calendar/simple-calendar.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/css/style.css') }}">

    <link href="{{ asset('vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />

	{{-- message toastr --}}
	<link rel="stylesheet" href="{{ URL::to('assets/css/toastr.min.css') }}">
	<script src="{{ URL::to('assets/js/toastr_jquery.min.js') }}"></script>
	<script src="{{ URL::to('assets/js/toastr.min.js') }}"></script>
</head>
<body>
    <div class="main-wrapper">
        <!-- <div class="header" style="background-color: #c8d6e5;">
            <div class="header-left" style="background-color: #c8d6e5;"> -->
        <div class="header">
            <div class="header-left">
                <!-- <a href="{{ route('home') }}" class="logo">
                    <img src="{{ URL::to('assets/img/logo.png') }}" alt="Logo">
                </a>
                <a href="{{ route('home') }}" class="logo logo-small">
                    <img src="{{ URL::to('assets/img/logo-small.png') }}" alt="Logo" width="30" height="30">
                </a> -->

                <a href="{{ route('home') }}" class="logo">
                    <img style="margin-left: 90px; height: 50px; width: 50px; object-fit: contain;" src="{{ asset('logo/' . $settings->logo) }}" alt="Logo">
                </a>

                <a href="{{ route('home') }}" class="logo logo-small">
                    <img src="{{ asset('logo/' . $settings->logo) }}" alt="Logo" width="30" height="30">
                </a>

            </div>
            <div class="menu-toggle">
                <a href="javascript:void(0);" id="toggle_btn">
                    <i class="fas fa-bars" ></i>
                </a>
            </div>

            <!-- <div class="top-nav-search">
                <form>
                    <input type="text" class="form-control" placeholder="Search here">
                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div> -->
            <a class="mobile_btn" id="mobile_btn" >
                <i class="fas fa-bars" style="background-color: #fff;"></i>
            </a>
            <ul class="nav user-menu">
                <!-- <li class="nav-item dropdown noti-dropdown language-drop me-2">
                    <a href="#" class="dropdown-toggle nav-link header-nav-list" data-bs-toggle="dropdown">
                        <img src="{{ URL::to('assets/img/icons/header-icon-01.svg') }}" alt="">
                    </a>
                    <div class="dropdown-menu ">
                        <div class="noti-content">
                            <div>
                                <a class="dropdown-item" href="javascript:;"><i class="flag flag-lr me-2"></i>English</a>
                                <a class="dropdown-item" href="javascript:;"><i class="flag flag-kh me-2"></i>Khmer</a>
                            </div>
                        </div>
                    </div>
                </li> -->

                <li class="nav-item dropdown noti-dropdown me-2">
                    <!-- <a href="#" class="dropdown-toggle nav-link header-nav-list" data-bs-toggle="dropdown">
                        <img src="{{ URL::to('assets/img/icons/header-icon-05.svg') }}" alt="">
                    </a> -->
                    <div class="dropdown-menu notifications">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title">Notifications</span>
                            <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media d-flex">
                                            <span class="avatar avatar-sm flex-shrink-0">
                                                <img class="avatar-img rounded-circle" alt="User Image" src="{{ URL::to('assets/img/profiles/avatar-13.png') }}">
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">Carlson Tech</span> has
                                                    approved <span class="noti-title">your estimate</span></p>
                                                <p class="noti-time"><span class="notification-time">4 mins ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media d-flex">
                                            <span class="avatar avatar-sm flex-shrink-0">
                                                <img class="avatar-img rounded-circle" alt="User Image" src="{{ URL::to('assets/img/profiles/avatar-13.png') }}">
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details">
                                                    <span class="noti-title">International Software Inc</span> has sent you a invoice in the amount of
                                                    <span class="noti-title">$218</span>
                                                </p>
                                                <p class="noti-time">
                                                    <span class="notification-time">6 mins ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media d-flex">
                                            <span class="avatar avatar-sm flex-shrink-0">
                                                <img class="avatar-img rounded-circle" alt="User Image" src="{{ URL::to('assets/img/profiles/avatar-13.png') }}"php>
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">John Hendry</span> sent a cancellation request <span class="noti-title">Apple iPhone XR</span></p>
                                                <p class="noti-time"><span class="notification-time">8 mins ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media d-flex">
                                            <span class="avatar avatar-sm flex-shrink-0">
                                                <img class="avatar-img rounded-circle" alt="User Image" src="{{ URL::to('assets/img/profiles/avatar-13.png') }}">
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">Mercury Software Inc</span> added a new product <span class="noti-title">Apple MacBook Pro</span></p>
                                                <p class="noti-time"><span class="notification-time">12 mins ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="topnav-dropdown-footer">
                            <a href="#">View all Notifications</a>
                        </div>
                    </div>
                </li>

                {{-- <li class="nav-item zoom-screen me-2">
                    <a href="#" class="nav-link header-nav-list win-maximize">
                        <img src="{{ URL::to('assets/img/icons/header-icon-04.svg') }}" alt="">
                    </a>
                </li> --}}

                <li class="nav-item zoom-screen me-2 date-style">
                    {{ date('l, d M Y') }}
                </li>

                <li class="nav-item dropdown has-arrow new-user-menus">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <span class="user-img">
                            <!-- <img class="rounded-circle" src="/images/{{ Session::get('avatar') }}" width="31"alt="{{ Session::get('name') }}"> -->
                            <div class="user-text">
                                <h6>{{ Session::get('name') }}</h6>
                                <p class="text-muted mb-0">{{ Session::get('role_name') }}</p>
                            </div>
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <!-- <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="/images/{{ Session::get('avatar') }}" alt="{{ Session::get('name') }}" class="avatar-img rounded-circle">
                            </div>
                            <div class="user-text">
                                <h6>{{ Session::get('name') }}</h6>
                                <p class="text-muted mb-0">{{ Session::get('role_name') }}</p>
                            </div>
                        </div> -->
                        <a class="dropdown-item" href="{{ route('user/profile/page') }}">My Profile</a>
                        <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
		{{-- side bar --}}
		@include('sidebar.sidebar')
		{{-- content page --}}
        @yield('content')
        <footer>
            <p>Copyright © <?php echo date('Y'); ?> 
                <a href="http://techeureka.com">TECH EUREKA</a>
            </p>
        </footer>
    
    </div>

    <div class="modal fade" id="commonModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="body">
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::to('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/feather.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/apexchart/chart-data.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/simple-calendar/jquery.simple-calendar.js') }}"></script>
    <script src="{{ URL::to('assets/js/calander.js') }}"></script>
    <script src="{{ URL::to('assets/js/circle-progress.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/script.js') }}"></script>
    
    <script src="{{ asset('vendors/select2/dist/js/select2.min.js') }}"></script>
    

    @yield('script')
    <script>
        function show_toastr(type, message) {

        var f = document.getElementById('liveToast');
        var a = new bootstrap.Toast(f).show();
        if (type == 'success') {
            $('#liveToast').addClass('bg-primary');
        } else {
            $('#liveToast').addClass('bg-danger');
        }
        $('#liveToast .toast-body').html(message);

        setTimeout(function () {
            var toastElement = new bootstrap.Toast(f);
            toastElement.hide();
        }, 2500);
        
        }

        $(document).ready(function() {
            $('.select2s-hidden-accessible').select2({
                closeOnSelect: false
            });
        });

        $(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function () {

        var data = {};
        var title1 = $(this).data("title");

        var title2 = $(this).data("bs-original-title");
        var title3 = $(this).data("original-title");
        var title = (title1 != undefined) ? title1 : title2;
        var title=(title != undefined) ? title : title3;

        $('.modal-dialog').removeClass('modal-xl');
        var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');

        var url = $(this).data('url');
        $("#commonModal .modal-title").html(title);
        $("#commonModal .modal-dialog").addClass('modal-' + size);

        if ($('#vc_name_hidden').length > 0) {
            data['vc_name'] = $('#vc_name_hidden').val();
        }
        if ($('#warehouse_name_hidden').length > 0) {
            data['warehouse_name'] = $('#warehouse_name_hidden').val();
        }
        if ($('#discount_hidden').length > 0) {
            data['discount'] = $('#discount_hidden').val();
        }
        $.ajax({
            url: url,
            data: data,
            success: function (data) {
                $('#commonModal .body').html(data);
                $("#commonModal").modal('show');
                // daterange_set();
                taskCheckbox();
                common_bind("#commonModal");
                commonLoader();

            },
            error: function (data) {
                data = data.responseJSON;
                
            }
        });

        });

    </script> 


    <script>
        $('.select2').select2();
    </script>

    <style>
        .select2-container .select2-selection--single {
            height: 42px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }
    </style>
    
</body>
</html>