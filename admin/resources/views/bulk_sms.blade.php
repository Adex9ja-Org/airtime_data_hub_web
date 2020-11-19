
@extends('template')
@section('content')
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Credit Balance</h6>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="mb-2">{{ number_format($balance, 2, '.', ',') }}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-primary">
                                            <span>Total Credit Balance</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Sent Messages</h6>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="mb-2">{{ $smsStat->success }}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-success">
                                            <span>Total Message Sent</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Unsent Messages</h6>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="mb-2">{{ $smsStat->fail }}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-danger">
                                            <span>Total Messages Unsent</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row inbox-wrapper">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 email-content">
                            <div class="email-head">
                                <div class="email-head-title d-flex align-items-center">
                                    <span data-feather="edit" class="icon-md mr-2"></span>
                                    New message
                                </div>
                            </div>
                            <div class="email-compose-fields">
                                <div class="to">
                                    <form method="post">
                                        @csrf
                                        <div class="form-group row py-0">
                                            <label class="col-md-2 control-label">To:</label>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <textarea name="phone" required rows="12" class="form-control" placeholder="Enter Phone Number Here separated by commas (e.g +234*******, +23490********)"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row py-0">
                                            <label class="col-md-2 control-label">Message:</label>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <textarea name="message" required rows="8" class="form-control" placeholder="Enter your message here (max 250 words)" maxlength="250"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <input class="btn btn-primary btn-space float-right" type="submit" value="Send Message"/>
                                        </div>
                                    </form>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable2" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Phone Number</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 0)
                            @foreach($userList as $item)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $item->fullname }}</td>
                                    <td>{{ $item->phoneno }}</td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
