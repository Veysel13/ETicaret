<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cosmetix Dashboard</title>
    <!-- Plugins CSS -->
    <link href="{{asset('assets/css/plugins/plugins.css')}}" rel="stylesheet">
    <link href="{{asset('assets/js/plugins/data-tables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/js/plugins/data-tables/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/js/plugins/data-tables/responsive.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/smart-form/smart-templates/css/smart-forms.css')}}" rel="stylesheet">
    <link href="{{asset('assets/linearicons/fonts.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('assets/bower_components/sweetalert/dist/sweetalert.css')}}" rel="stylesheet">
    <link href="{{asset('assets/bower_components/toastr/toastr.min.css')}}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>


    <link href="{{asset('assets/css/custom.css?v=2')}}" rel="stylesheet">

    @stack('header')

    <script type="text/javascript">
        const URL = {
            RESTAURANTS: '{{ route('backend.xhr.restaurants') }}',
        }

        const ORDER_STATUS = {
            ORDER_RECEIVED:{{ json_encode(\App\Constants\OrderItemStatus::RECEIVED) }},
            ORDER_BROKEN: {{ json_encode(\App\Constants\OrderItemStatus::BROKEN) }},
            ORDER_MISSING: {{ json_encode(\App\Constants\OrderItemStatus::MISSING) }},
            ORDER_FAULT: {{ json_encode(\App\Constants\OrderItemStatus::FAULT) }},
            ORDER_NOTRECEIVED: {{ json_encode(\App\Constants\OrderItemStatus::NOTRECEIVED) }}
        };
    </script>
</head>

<body class="layout-vertical">

    <div class="page-wrapper">
        <nav id="sidebar" class="sidebar-nav light-sidebar">
            <div class="sidebar-inner content-scroll">

                <div class="logo-header">
                    <a href="{{route('backend.dashboard')}}"><img src="{{asset('assets/images/logo.jpeg')}}" style="width: 120px;height: 75px" alt=""></a>
                </div><!--logo-->
                <ul class="metismenu" id="menu">
                    <li class="nav-heading">
                        <span>Backend Menu</span>
                    </li>

                    @foreach(\App\Helpers\BackendMenu::viewMenu() as $topMenu)
                        @if($topMenu['type']=='single')
                            <li>
                                <a href="{{$topMenu['url']}}">
                                    <i class="{{$topMenu['icon']}}"></i>
                                    <span class="nav-text">{{$topMenu['name']}}</span></a>
                            </li>
                        @else
                            <li>
                                <a class="has-arrow" href="#" aria-expanded="false">
                                    <i class="{{$topMenu['icon']}}"></i>
                                    <span class="nav-text">{{$topMenu['name']}}</span>
                                </a>
                                <ul aria-expanded="false" class="metis-secondary">
                                    @foreach($topMenu['items'] as $item)
                                    <li><a href="{{$item['url']}}">{{$item['name']}}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif

                    @endforeach

                    <li>
                        <a href="#"
                           onclick="document.getElementById('logoutForm').submit()" aria-expanded="false">
                            <i class="icon-power-switch"></i>
                            <span class="nav-text">Logout</span></a>
                    </li>
                </ul>
            </div>
        </nav><!--sidebar nav-->
        <main class="main-content">
            <!--/////////////////////////////// End navbar////////////////////////////-->
            <header class="header">
                <div class="container">
                    <div class="navbar navbar-expand-lg">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a href="javascript:void(0)" class=" btn-circle-round sidebar-toggler">
                                    <i class="icon-menu"></i>
                                </a>
                            </li>
                            <li class="nav-item ml-3 hidden-md-down" style="display: none">
                                <form class="form-inline my-2 my-lg-0">
                                    <input class="form-control mr-sm-2" type="text" placeholder="Find...">
                                    <button class="icon-search" type="submit"><i class="ion-ios-search-strong"></i></button>
                                </form>
                            </li>
                        </ul>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item dropdown hidden-sm-down">

                                <a data-toggle="dropdown" href="javascript:void(0)" class="btn-circle-round dropdown-toggle">
                                    <i class="icon-bubbles"></i>
                                    <span class="badge bg-success">{{0}}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right notification-dropdown">
                                    <li class="notification-header">
                                                <span class="float-right">
                                                    <a href="#">View All</a>
                                                </span>
                                        0 New Messages
                                    </li>
                                    <li class="clearfix notify-item">
                                        <a href="#" class="notify-thumb">
                                            <img src="{{asset('assets/images/user1.jpg')}}" alt="" class="img-fluid rounded-circle">
                                        </a>
                                        <div class="notify-content">
                                            <a href="#">
                                                <span class="float-right">Date</span>
                                                Name
                                            </a>
                                            <p>
                                               Desc
                                            </p>
                                        </div>
                                    </li><!--item-->
                                </ul>
                            </li>
                            <li class="nav-item dropdown hidden-sm-down">

                                <a data-toggle="dropdown" href="javascript:void(0)" class=" btn-circle-round dropdown-toggle">
                                    <i class="icon-bullhorn"></i>
                                    <span class="badge bg-danger">0</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right notification-dropdown">
                                    <li class="notification-header">
                                                <span class="float-right">
                                                    <a href="#">View All</a>
                                                </span>
                                        0 New Notifications
                                    </li>
                                    <li class="clearfix notify-item">
                                        <a href="#" class="notify-thumb bg-danger">
                                            <i class="icon-server text-white"></i>
                                        </a>
                                        <div class="notify-content">
                                            <a href="#">
                                                <span class="float-right"></span>
                                                Title
                                            </a>
                                            <p>
                                                Date
                                            </p>
                                        </div>
                                    </li><!--item-->

                                </ul>
                            </li>
                            <li class="nav-item dropdown user-item">
                                <a data-toggle="dropdown" href="javascript:void(0)" class=" dropdown-toggle">
{{--                                    <img src="{{asset('assets/images/user3.jpg')}}" width="50" alt="" class="img-fluid rounded-circle">--}}
                                    <span class="hidden-md-down"> {{auth('backend')->user()->fullname}}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right profile-dropdown">

{{--                                    <li class="dropdown-item">--}}
{{--                                        <a href="#"><i class="icon-user"></i> Profile</a>--}}
{{--                                    </li><!--item-->--}}
{{--                                    <li class="dropdown-item">--}}
{{--                                        <a href="#"><i class="icon-envelope"></i> Mailbox</a>--}}
{{--                                    </li><!--item-->--}}
                                    <li class="dropdown-item">
                                        <a href="{{route('backend.user.password')}}"><i class="icon-user"></i> Password Change</a>
                                    </li><!--item-->
                                    <li class="dropdown-item">
                                        <a href="#" onclick="document.getElementById('logoutForm').submit()"><i class="icon-power-switch"></i> Logout</a>
                                    </li><!--item-->
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </header><!--header-->
            <!--/////////////////////////////// End Header////////////////////////////-->

            <div class="content">
                <div class="container-fluid">
                    <div class="page-title pt30 pb30">
                        <div class="row">
                            <div class="col-sm-12">
{{--                                <ol class="breadcrumb float-right">--}}
{{--                                    <li class="breadcrumb-item"><a href="#">{{$pageTitle??'Home'}}</a></li>--}}
{{--                                </ol>--}}
                                <h4 class="mb10">{{$pageTitle??'Home'}}</h4>
                            </div>
                        </div>
                    </div><!--page title-->

                    @yield('content')

                </div><!--container-->
            </div><!--content-->
        </main>



        <footer id="footer" class="page-footer">
            <div class="container">
                &copy; Copyright 2018. Veysel Akpınar
            </div>
        </footer>
    </div><!--page wrapper-->

    <div class="modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="body">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Uploads...</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<x-success/>
<x-error/>

<form action="{{ route('backend.auth.logout') }}" method="post" id="logoutForm">@csrf</form>

<script type="text/javascript" src="{{asset('assets/js/plugins/plugins.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/assan.custom.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script type="text/javascript" src="{{ asset('assets/js/plugins/lodash/lodash.min.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/data-tables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/data-tables/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/data-tables/dataTables.fixedHeader.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/data-tables/dataTables.responsive.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/data-tables/responsive.bootstrap4.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/data-table.init.js?v=2')}}"></script>
<script type="text/javascript" src="{{asset('assets/bower_components/sweetalert/dist/sweetalert.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/sweetAlert.init.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/bower_components/toastr/toastr.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/toastr.init.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/select2.js?v=').time()}}"></script>
<script type="text/javascript" src="{{asset('assets/js/searchData.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom.js?v=').time()}}"></script>

@stack('footer')
</body>
</html>
