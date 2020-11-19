
@extends('template')
@section('title', 'Profile')

@section('content')

    <div class="row">
        <div class="col-md-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Profile Update</h6>
                <form method="post">
                    @csrf

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Full Name</label>
                                <input name="fullname" type="text" class="form-control" placeholder="Enter full name" value="{{ \Illuminate\Support\Facades\Auth::user()->fullname }}">
                            </div>
                        </div><!-- Col -->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Email Address</label>
                                <input name="email" readonly type="text" class="form-control" placeholder="Enter email address" value="{{ \Illuminate\Support\Facades\Auth::user()->email }}">
                            </div>
                        </div><!-- Col -->
                    </div><!-- Row -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Phone Number</label>
                                <input name="phoneno" type="text" class="form-control" placeholder="Enter phone number" value="{{ \Illuminate\Support\Facades\Auth::user()->phoneno }}">
                            </div>
                        </div><!-- Col -->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Address</label>
                                <input name="address" type="text" class="form-control" placeholder="Enter address" value="{{ \Illuminate\Support\Facades\Auth::user()->address }}">
                            </div>
                        </div><!-- Col -->

                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">User Role</label>
                                <input readonly type="text" class="form-control" placeholder="Enter user role" value="{{ \Illuminate\Support\Facades\Auth::user()->userRole }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Password</label>
                                <input name="password" type="password" class="form-control"  value="{{ base64_decode(\Illuminate\Support\Facades\Auth::user()->password )  }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary submit">Update Profile</button>
                </form>

            </div>
        </div>
    </div>
    </div>
@endsection

