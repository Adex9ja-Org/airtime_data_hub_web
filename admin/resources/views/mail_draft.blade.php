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
                                        <li class="active"><a href="/mail/draft"><span class="icon"><i data-feather="file"></i></span>Draft</a></li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 email-content">
                            <div class="email-inbox-header">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <div class="email-title mb-2 mb-md-0"><span class="icon"><i data-feather="file"></i></span> Draft Mails <span class="new-messages">[{{ sizeof($mails) }} draft(s)]</span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="email-list">
                                @foreach($mails as $mail)
                                    <div class="email-list-item email-list-item--unread">

                                        <a href="#" class="email-list-detail">
                                            <div>
                                                <span class="from">{{ $mail->subject }}</span>
                                                <p class="msg">{{ substr($mail->message, 0, 100) }}</p>
                                            </div>
                                            <span class="date">
                                            <span class="icon"><i data-feather="calendar"></i> </span>{{ $mail->created_at }}</span>
                                        </a>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
