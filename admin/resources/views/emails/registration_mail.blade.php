@extends('emails.email_template')
@section('content')
    <p>Hello <b>{{ $fullname }}</b>!</p>
    <p>We are happy to have you on-board. To get started, <a href="{{ config('app.url') }}/user/verify/email/{{ base64_encode($email) }}">click here</a> to verify your email account</p>
    <p>We have a few resources you might like to checkout to broaden your insight on how our services work and how our product operates</p>
    <div class="grey-background single-line"></div>

    <div class="">
        <ol>
            <li><a href="https://airtimedatahub.com/">Airtime to Cash</a> </li>
            <li><a href="https://airtimedatahub.com/">Data Top up</a> </li>
            <li><a href="https://airtimedatahub.com/">Airtime topup</a> </li>
            <li><a href="https://airtimedatahub.com/">Bill payment</a> </li>
            <li><a href="https://airtimedatahub.com/">Earn Commission</a> </li>
        </ol>
    </div>

@endsection
@section('style')
    <style type="text/css">
        li{
            margin-top: 20px;
        }
    </style>
@endsection
