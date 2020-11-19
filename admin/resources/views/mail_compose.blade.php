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
                                        <li class="active"><a href="/mail/compose"><span class="icon"><i data-feather="edit"></i></span>Compose Mail</a></li>
                                        <li><a href="/mail/sent"><span class="icon"><i data-feather="mail"></i></span>Sent Mail</a></li>
                                        <li><a href="/mail/draft"><span class="icon"><i data-feather="file"></i></span>Draft</a></li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 email-content">
                            <div class="email-head">
                                <div class="email-head-title d-flex align-items-center">
                                    <span data-feather="edit" class="icon-md mr-2"></span>
                                    New message
                                </div>
                            </div>
                            <form method="post" id="mailForm">
                                @csrf
                                <div class="email-compose-fields">
                                    <div class="to">
                                        <div class="form-group row py-0">
                                            <label class="col-md-1 control-label">To:</label>
                                            <div class="col-md-11">
                                                <div class="form-group">
                                                    <select class="compose-multiple-select form-control w-100" multiple="multiple" name="to[]">
                                                        <option value="User" selected="selected">All User</option>
                                                        <option value="Agent">All Merchant</option>
                                                        <option value="Admin">All Admin</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="to cc">
                                        <div class="form-group row pt-1 pb-3">
                                            <label class="col-md-1 control-label">Cc</label>
                                            <div class="col-md-11">
                                                <select class="compose-multiple-select form-control w-100" multiple="multiple" name="cc[]">
                                                    <option value="Admin">All Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="subject">
                                        <div class="form-group row py-0">
                                            <label class="col-md-1 control-label">Subject</label>
                                            <div class="col-md-11">
                                                <input class="form-control" type="text" name="subject" required id="subject">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="email editor">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label sr-only" for="simpleMdeEditor">Descriptions </label>
                                            <textarea class="form-control" name="message" id="simpleMdeEditor" rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="email action-send">
                                        <div class="col-md-12">
                                            <div class="form-group mb-0">
                                                <button class="btn btn-primary btn-space" type="submit"><i data-feather="send" class="icon-sm mr-sm-2"></i> Send Mail</button>
                                                <button class="btn btn-info btn-space" type="button" onclick="saveDraft()"><i data-feather="file" class="icon-sm mr-sm-2"></i> Save Draft</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function saveDraft() {
            let subject = $("#subject").val();
            if(subject == ''){
                alert("Subject is required");
                return;
            }
            $("form#mailForm").attr('action', '/mail/draft/save').submit();
        }
    </script>
@endsection
