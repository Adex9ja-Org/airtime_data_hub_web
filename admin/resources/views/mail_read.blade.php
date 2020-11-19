@extends('template')
@section('content')

    <div class="row inbox-wrapper">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 email-aside border-lg-right">
                            <div class="aside-content">
                                <div class="aside-header">
                                    <button class="navbar-toggle" data-target=".aside-nav" data-toggle="collapse" type="button">
                                        <span class="icon"><i data-feather="chevron-down"></i></span>
                                    </button>
                                    <span class="title text-muted font-weight-bold">Mail Service</span>
                                </div>
                                <div class="aside-nav collapse">
                                    <ul class="nav">
                                        <li><a href="/mail/compose"><span class="icon"><i data-feather="edit"></i></span>Compose Mail</a></li>
                                        <li><a href="/mail/sent"><span class="icon"><i data-feather="mail"></i></span>Sent Mail</a></li>
                                        <li><a href="/mail/draft"><span class="icon"><i data-feather="file"></i></span>Draft</a></li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 email-content">
                            <div class="email-head">
                                <div class="email-head-subject">
                                    <div class="title d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span>{{ $mail->subject }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="email-head-sender d-flex align-items-center justify-content-between flex-wrap">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar">
                                            <img src="{{ asset($mail->image_url) }}" alt="Avatar" class="rounded-circle user-avatar-md">
                                        </div>
                                    </div>
                                    <div class="date">{{ $mail->created_at }}</div>
                                </div>
                            </div>
                            <div class="email-body">
                                {{ $mail->message }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
