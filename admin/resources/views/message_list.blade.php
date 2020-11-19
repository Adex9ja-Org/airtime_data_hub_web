@extends('template')
@section('content')

    <div class="row chat-wrapper">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row position-relative">
                        <div class="col-lg-4 chat-aside border-lg-right">
                            <div class="aside-content">
                                <div class="aside-header">
                                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2">
                                        <div class="d-flex align-items-center">
                                            <figure class="mr-2 mb-0">
                                                <img src="{{ \App\Model\ImageHelper::getProfileImage(\Illuminate\Support\Facades\Auth::user()->image_url) }}" class="img-sm rounded-circle" alt="profile">
                                                <div class="status online"></div>
                                            </figure>
                                            <div>
                                                <h6>{{ \Illuminate\Support\Facades\Auth::user()->fullname }}</h6>
                                                <p class="text-muted tx-13">{{ \Illuminate\Support\Facades\Auth::user()->userRole }}</p>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="icon-lg text-muted pb-3px" data-feather="settings" data-toggle="tooltip" title="Settings"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item d-flex align-items-center" href="{{ url()->to('profile') }}"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit Profile</span></a>
                                                <a class="dropdown-item d-flex align-items-center" href="{{ url()->route('logout') }}"><i data-feather="log-out" class="icon-sm mr-2"></i> <span class="">Switch User</span></a>
                                            </div>
                                        </div>
                                    </div>
                                    <form class="search-form">
                                        <div class="input-group border rounded-sm">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text border-0 rounded-sm">
                                                    <i data-feather="search" class="icon-md cursor-pointer"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control  border-0 rounded-sm" id="searchForm" placeholder="Search here...">
                                        </div>
                                    </form>
                                </div>
                                <div class="aside-body">
                                    <ul class="nav nav-tabs mt-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="chats-tab" data-toggle="tab" href="#chats" role="tab" aria-controls="chats" aria-selected="true">
                                                <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                                                    <i data-feather="message-square" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                                                    <p class="d-none d-sm-block">Contact-Us Messages</p>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-3">
                                        <div class="tab-pane fade show active" id="chats" role="tabpanel" aria-labelledby="chats-tab">
                                            <div>
                                                <ul class="list-unstyled chat-list px-1">
                                                    @foreach($messages as $message)
                                                        <li class="chat-item @if($message->read_status == 0) bg-gradient-light @endif pr-1">
                                                            <a href="/messages/list/{{ $message->support_id }}" class="d-flex align-items-center">
                                                                <figure class="mb-0 mr-2">
                                                                    <img src="{{ \App\Model\ImageHelper::getProfileImage($message->image_url) }}" class="img-xs rounded-circle" alt="user">
                                                                </figure>
                                                                <div class="d-flex justify-content-between flex-grow border-bottom">
                                                                    <div>
                                                                        <p class="text-body font-weight-bold">{{ $message->fullname }}</p>
                                                                        <p class="text-muted tx-13">{{ strlen($message->message) > 35 ? substr($message->message, 0, 35). '...' : $message->message }}</p>
                                                                    </div>
                                                                    <div class="d-flex flex-column align-items-end">
                                                                        <p class="text-muted text-small">{{ explode(' ', $message->created_at)[0]}}</p>
                                                                        <div>
                                                                            <span title="Priority" class="badge {{ \App\Model\ThreadPriority::getBadge($message->priority) }}">{{ $message->priority }}</span>
                                                                            @if($message->un_read > 0)
                                                                                <span class="badge badge-pill badge-primary ml-auto"> {{ '+' . $message->un_read }}</span>
                                                                            @endif
                                                                            @if(\App\Model\TicketStatus::closed == $message->ticket_status)
                                                                                <i data-feather="slash" class="icon-sm text-muted" data-toggle="tooltip" title="Thread Closed"></i>
                                                                            @endif
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($messageDetail))
                            <div class="col-lg-8 chat-content">
                                <div class="chat-header border-bottom pb-2">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i data-feather="corner-up-left" id="backToChatList" class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                                            <figure class="mb-0 mr-2">
                                                <img src="{{ \App\Model\ImageHelper::getProfileImage($messageDetail->image_url) }}" class="img-sm rounded-circle" alt="image">
                                            </figure>
                                            <div>
                                                <p>{{ $messageDetail->fullname }}</p>
                                                <p class="text-muted tx-13">{{ $messageDetail->userRole }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mr-n1">
                                            @if(\App\Model\TicketStatus::closed == $messageDetail->ticket_status)
                                                <a href="/message/thread/open/{{ $messageDetail->support_id }}">
                                                    <i data-feather="unlock" class="icon-lg text-muted mr-3" data-toggle="tooltip" title="Open Thread"></i>
                                                </a>
                                            @endif
                                            <a href="mailto://{{ $messageDetail->email }}">
                                                <i data-feather="mail" class="icon-lg text-muted mr-3" data-toggle="tooltip" title="Send Mail"></i>
                                            </a>
                                            <a href="tel://{{ $messageDetail->phoneno }}">
                                                <i data-feather="phone-call" class="icon-lg text-muted mr-0 mr-sm-3" data-toggle="tooltip" title="Start voice call"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="divider mt-2"></div>
                                    <div>
                                        <p>{{ $messageDetail->message }}</p>
                                        <div title="Priority" class="text-small badge {{ \App\Model\ThreadPriority::getBadge($messageDetail->priority) }}">{{ $messageDetail->priority }}</div>
                                        <sub>{{ $messageDetail->created_at }}</sub>
                                    </div>
                                </div>
                                <div class="chat-body">
                                    <ul class="messages" style="min-height: 300px">
                                        @if(isset($replyList))
                                            @foreach($replyList as $item)
                                                <li class="message-item {{ \Illuminate\Support\Facades\Auth::user()->email == $item->email ? 'me' : 'friend' }}">
                                                    <img src="{{ \App\Model\ImageHelper::getProfileImage($item->image_url) }}" class="img-xs rounded-circle" alt="avatar">
                                                    <div class="content">
                                                        <div class="message">
                                                            <div class="bubble">
                                                                @if($item->file_link != '')
                                                                    <img src="{{ \App\Model\ImageHelper::getProfileImage($item->file_link) }}" width="100px"/>
                                                                @endif
                                                                <p>{{ $item->reply_message }}</p>
                                                            </div>
                                                            <span>{{ $item->created_at }}</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                @if(\App\Model\TicketStatus::opened == $messageDetail->ticket_status)
                                    <div class="chat-footer d-flex">
                                        <div>
                                            <button onclick="location.href='/message/thread/close/{{ $messageDetail->support_id }}'" type="button" class="btn border btn-icon rounded-circle mr-2" data-toggle="tooltip" title="Close Thread">
                                                <i data-feather="slash" class="text-muted"></i>
                                            </button>
                                        </div>
                                        <form class="search-form flex-grow mr-2" method="post">
                                            <div class="input-group">
                                                @csrf
                                                <input type="hidden" name="support_id" value="{{ $messageDetail->support_id }}">
                                                <input type="text" class="form-control rounded-pill" id="chatForm" placeholder="Type a message" name="reply_message" minlength="2" required>
                                                <button type="submit" class="btn btn-primary btn-icon rounded-circle ml-2">
                                                    <i data-feather="send"></i>
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
