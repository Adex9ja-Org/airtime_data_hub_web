<!DOCTYPE html>
<html>

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{asset('images/favicon.webp')}}">

    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('fonts/feather-font/css/iconfont.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.css') }}">
    <!-- end plugin css -->


    <!-- common css -->
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- end common css -->

</head>
<body >

<script src="{{ asset('js/spinner.js') }}"></script>

<div class="main-wrapper" id="app">
    <div class="page-wrapper full-page">
        <div class="page-content d-flex align-items-center justify-content-center">

            <div class="row w-100 mx-0 auth-page">
                <div class="col-md-8 col-xl-6 mx-auto">
                    <div class="card">
                        <div class="row">
                            <div class="col-md-4 pr-md-0">
                                <div class="auth-left-wrapper" style="background-image: url({{asset('images/bg.jpg')}})">

                                </div>
                            </div>
                            <div class="col-md-8 pl-md-0">
                                <div class="auth-form-wrapper px-4 py-5">
                                    <a href="#" class="noble-ui-logo d-block mb-2">
                                        <center>
                                            <img src="{{asset('images/logo.png')}}" alt="{{ config('app.name') }}" height="120px">
                                        </center>
                                    </a>
                                    <h5 class="text-muted font-weight-normal mb-4">Welcome back! Log in to your account.</h5>
                                    <form class="forms-sample" method="post">
                                        @csrf
                                        {!! session()->get('msg')!!}
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email address</label>
                                            <input type="email" class="form-control" name="email" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Password</label>
                                            <input type="password" class="form-control" name="password" autocomplete="current-password" placeholder="Password">
                                        </div>

                                        <div class="mt-3">
                                            <input type="submit" class="btn btn-primary mr-2 mb-2 mb-md-0" value="Login"/>

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

<!-- base js -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('plugins/feather-icons/feather.min.js') }}"></script>
<!-- end base js -->

<!-- plugin js -->
<!-- end plugin js -->

<!-- common js -->
<script src="{{ asset('js/template.js') }}"></script>
<!-- end common js -->

</body>

</html>
