
@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Basic Information</h6>
                <form method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Full Name</label>
                                <input required name="fullname" type="text" class="form-control" placeholder="Enter full name" value="{{ $user->fullname ?? '' }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Address</label>
                                <input required name="address" type="text" class="form-control" placeholder="Enter address" value="{{ $user->address ?? ''  }}">
                            </div>
                        </div><!-- Col -->


                    </div><!-- Row -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Email Address</label>
                                <input required name="email" @php if(isset($user->email)) echo 'readonly' @endphp type="text" class="form-control" placeholder="Enter email address" value="{{ $user->email ?? ''  }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Phone Number</label>
                                <input required name="phoneno" type="text" class="form-control" placeholder="Enter phone number" value="{{ $user->phoneno ?? ''  }}">
                            </div>
                        </div><!-- Col -->


                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">User Role</label>
                                <select class="form-control" required name="userRole">
                                    @foreach($user_roles as $user_role)
                                        <option @php if( isset($user->user_role) && $user_role->user_role == $user->user_role) echo 'selected' @endphp  value="{{ $user_role->user_role }}"> {{ $user_role->user_role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><!-- Col -->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">Password</label>
                                <input name="password" type="password" class="form-control"  value="{{ base64_decode($user->password ?? '')  }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label">4-digit Transaction PIN</label>
                                <input name="pin" type="text" class="form-control"  value="{{ $user->pin ?? "" }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary submit">Save Information</button>
                </form>

            </div>
        </div>
    </div>
    </div>
@endsection

