<!DOCTYPE html>
<html>

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>{{ config('app.name') }} - Error </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}">

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
                <div class="col-md-8 col-xl-6 mx-auto d-flex flex-column align-items-center">
                    <img src="{{ asset("images/404.svg") }}" class="img-fluid mb-2" alt="404">
                    <h1 class="font-weight-bold mb-22 mt-2 tx-80 text-muted">{{ $code }}</h1>
                    <h4 class="mb-2"> {{ $msg }}</h4>
                    <h6 class="text-muted mb-3 text-center"> {{ $reason }}</h6>
                    <a href="/dashboard" class="btn btn-primary-muted">Back to home</a>
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
