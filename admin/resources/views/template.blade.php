<!DOCTYPE html>
<html>

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>{{ config('app.name') . ' - '. $pageInfo->title }} </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{asset('images/favicon.webp')}}">

    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('fonts/feather-font/css/iconfont.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('datatable/dataTables.bootstrap4.min.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('plugins/simplemde/simplemde.min.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">


    <!-- common css -->
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- end common css -->

</head>
<body>

<script src="{{ asset('js/spinner.js') }}"></script>

<div class="main-wrapper" id="app">
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="/dashboard" class="sidebar-brand">
                 <span>
                    <img style="margin-bottom: 8px" src="{{asset('images/logo.png')}}" alt="{{ config('app.name') }}"height="30px">
                </span>
                AirtimeDH
            </a>
            <div class="sidebar-toggler not-active">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="sidebar-body">
            <ul class="nav">
                <li class="nav-item nav-category">Main</li>
                <li class="nav-item ">
                    <a href="/dashboard" class="nav-link">
                        <i class="link-icon" data-feather="home"></i>
                        <span class="link-title">Dashboard</span>
                    </a>
                </li>


                <li class="nav-item nav-category">Components</li>

                @php
                    $current_cat = ''; $count = 0;
                   foreach ($sideMenuDataList as $side) {
                   switch($side->menu_cat){
                           case $current_cat:
                               echo '<li class="nav-item">
                                        <a href="'. $side->link .'" class="nav-link ">'. $side->title .'</a>
                                    </li>';
                               break;
                           default:
                               if($count > 0)
                                    echo ' </ul></div></li>';
                               $current_cat =  $side->menu_cat;
                               echo '<li class="nav-item ">
                                        <a class="nav-link" data-toggle="collapse" href="#'. $side->cat_link .'" role="button" aria-expanded="false" aria-controls="'. $side->cat_link .'">
                                            <i class="link-icon" data-feather="'. $side->cat_icon .'"></i>
                                            <span class="link-title">'. $side->menu_cat .'</span>
                                            <i class="link-arrow" data-feather="chevron-down"></i>
                                        </a>
                                        <div class="collapse " id="'. $side->cat_link .'">
                                            <ul class="nav sub-menu">
                                                <li class="nav-item">
                                                    <a href="'. $side->link .'" class="nav-link ">'. $side->title .'</a>
                                                </li>';
                               break;
                       }
                       ++$count;


               }
                echo ' </ul></div></li>';


                @endphp

            </ul>
        </div>
    </nav>
    <div class="page-wrapper">
        <nav class="navbar">
            <a href="#" class="sidebar-toggler">
                <i data-feather="menu"></i>
            </a>
            <div class="navbar-content">
                <form class="search-form" method="get" action="/search">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i data-feather="search"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control" id="navbarForm" placeholder="Search transaction and user here..." name="term">
                    </div>
                </form>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown nav-apps">
                        <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i data-feather="grid"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="appsDropdown">
                            <div class="dropdown-header d-flex align-items-center justify-content-between">
                                <p class="mb-0 font-weight-medium">My Social Media Page</p>
                            </div>
                            <div class="dropdown-body">
                                <div class="d-flex align-items-center apps">
                                    <a href="https://twitter.com/airtimedatahub" target="_blank"><i data-feather="twitter" class="icon-lg"></i><p>Twitter</p></a>
                                    <a href="https://www.facebook.com/Airtimedatahub/" target="_blank"><i data-feather="facebook" class="icon-lg"></i><p>Facebook</p></a>
                                    <a href="https://www.instagram.com/airtimedatahub/" target="_blank"><i data-feather="instagram" class="icon-lg"></i><p>Instagram</p></a>
                                </div>
                            </div>
                            <div class="dropdown-footer d-flex align-items-center justify-content-center">
                                <a href="market://details?id=com.airtimedatahub.app">Open Mobile App</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown nav-messages">
                        <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i data-feather="mail"></i>
                            @if( isset($pendingMessages) && sizeof($pendingMessages) > 0)
                                <div class="indicator">
                                    <div class="circle"></div>
                                </div>
                            @endif
                        </a>
                        <div class="dropdown-menu" aria-labelledby="messageDropdown">
                            <div class="dropdown-body">
                                @foreach($pendingMessages as $message)
                                    <a href="/messages/list/{{ $message->support_id }}" class="dropdown-item">
                                        <div class="content">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p>{{ substr($message->message, 0, 20) }}</p>
                                                <sub class="sub-text text-muted"> {{ explode(' ', $message->created_at)[0] }}</sub>
                                            </div>
                                            <p class="sub-text text-muted">{{ $message->fullname }}</p>
                                        </div>
                                    </a>
                                @endforeach

                            </div>
                            <div class="dropdown-footer d-flex align-items-center justify-content-center">
                                <a href="/messages/list">View All Messages</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown nav-notifications">
                        <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i data-feather="bell"></i>
                            @if(isset($pendingTrans) && sizeof($pendingTrans) > 0)
                                <div class="indicator">
                                    <div class="circle"></div>
                                </div>
                            @endif
                        </a>
                        <div class="dropdown-menu" aria-labelledby="notificationDropdown">

                            <div class="dropdown-body">
                                @foreach($pendingTrans as $tran)
                                    <a href="/transaction/list/view/{{ $tran->ref }}" class="dropdown-item">
                                        <div class="content">
                                            <p>{{ $tran->sub_name. ' -  N' . number_format($tran->amount, 2) }}</p>
                                            <p class="sub-text text-muted">{{ $tran->created_at }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <div class="dropdown-footer d-flex align-items-center justify-content-center">
                                <a href="/transaction/list">View All Transactions</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown nav-profile">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('images/default_profile.png') }}" alt="profile">
                        </a>
                        <div class="dropdown-menu" aria-labelledby="profileDropdown">
                            <div class="dropdown-header d-flex flex-column align-items-center">
                                <div class="info text-center">
                                    <p class="name  mb-0"> {{ \Illuminate\Support\Facades\Auth::user()->fullname }} </p>
                                    <p class="email text-muted mb-3"> {{ \Illuminate\Support\Facades\Auth::user()->email }} </p>
                                </div>
                            </div>
                            <div class="dropdown-body">
                                <ul class="profile-nav p-0 pt-3">
                                    <li class="nav-item">
                                        <a href="{{ url()->to('profile') }}" class="nav-link">
                                            <i data-feather="user"></i>
                                            <span>Profile</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ url()->route('logout') }}" class="nav-link">
                                            <i data-feather="log-out"></i>
                                            <span>Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="page-content">
            <nav class="page-breadcrumb">
                <ol class="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item">{{ $pageInfo->menu_cat }}</li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $pageInfo->title }}</li>
                    </ol>
                </ol>
            </nav>

            {!! session('msg')  !!}

            @yield('content')
        </div>
        <footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between">
            <p class="text-muted text-center text-md-left">Copyright © 2021 . All rights reserved</p>
            <p class="text-muted text-center text-md-left mb-0 d-none d-md-block">Powered by: <a target="_blank" href="http://codefixbug.com">CodeFixBug</a></p>
        </footer>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/email.js') }}"></script>
<script src="{{ asset('js/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/simplemde/simplemde.min.js') }}"></script>
<script src="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('plugins/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('js/chat.js') }}"></script>
<script src="{{ asset('js/template.js') }}"></script>
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script src="{{ asset('datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('datatable/dataTables.bootstrap4.min.js') }}"></script>

<script type="text/javascript"  src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.bootstrap4.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.colVis.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable( {
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        } );

        $('#datatable2').DataTable( {
            dom: 'Bfrtip',
            responsive: true,
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        } );
    } );
</script>
@yield('script')
</body>

</html>
