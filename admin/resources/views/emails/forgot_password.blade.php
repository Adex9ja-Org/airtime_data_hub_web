@extends('emails.email_template')
@section('content')
    <p style="padding:10px;line-height:200%">Hi {{ $fullname }}, You requested a password reset on your account.</p>
    <p><a href="https://www.airtimedatahub.com/mobile/forgot_password.php?email={{ $email }}&otp_code={{ $token }}">Click Here</a> to complete the recovery process. If the link is inaccessible, copy and paste the url below in your browser</p>
    <p>href="https://www.airtimedatahub.com/mobile/forgot_password.php?email={{ $email }}&otp_code={{ $token }}"</p>
@endsection
