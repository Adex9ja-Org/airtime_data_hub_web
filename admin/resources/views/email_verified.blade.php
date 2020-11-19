@extends('emails.email_template')
@section('content')
    <p>Hi <b>{{ $user->fullname }}</b>!</p>
    <p>Your email {{ $user->email }} verification is successful</p>
    <p>You can now login to the app to start transacting</p>
@endsection

