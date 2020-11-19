@extends('template')
@section('content')


    <div class="row">
        <div class="col-md-10 chat-aside border-lg-right ">
            <div class="aside-content">
                <div class="aside-body">
                    <ul class="nav nav-tabs mt-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="chats-tab" data-toggle="tab" href="#chats" role="tab" aria-controls="chats" aria-selected="true">
                                <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                                    <i data-feather="feather" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                                    <p class="d-none d-sm-block">Transaction Info</p>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="calls-tab" data-toggle="tab" href="#calls" role="tab" aria-controls="calls" aria-selected="false">
                                <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                                    <i data-feather="user" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                                    <p class="d-none d-sm-block">User Info</p>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contacts-tab" data-toggle="tab" href="#contacts" role="tab" aria-controls="contacts" aria-selected="false">
                                <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                                    <i data-feather="lock" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                                    <p class="d-none d-sm-block">Security Info</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="chats" role="tabpanel" aria-labelledby="chats-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                                        <h6 class="card-title mb-0 d-inline">Transaction Detail</h6>
                                        <div class="badge {{ \App\Model\RequestStatus::getPill($transaction->approvalStatus) }}">{{ \App\Model\RequestStatus::getReqTitle($transaction->approvalStatus) }}</div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="control-label">Transaction ID</label>
                                            <input class="form-control" type="text" readonly value="{{ $transaction->ref }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Transaction Date</label>
                                            <input class="form-control" type="text" readonly value="{{ $transaction->created_at }}">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="control-label">Product Name</label>
                                            <input class="form-control" type="text" readonly value="{{ $transaction->product_name }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Sub Product Name</label>
                                            <input class="form-control" type="text" readonly value="{{ $transaction->sub_name }}">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="control-label">Beneficiary/Receiver</label>
                                            <input class="form-control" type="text" readonly value="{{ $transaction->cr_acc }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Payment Channel</label>
                                            <input class="form-control" type="text" readonly value="{{ $transaction->channel_name }}">
                                        </div>

                                    </div>




                                    @if($transaction->per_charges > 0)
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <label class="control-label">Sender Number</label>
                                                <input class="form-control" type="text" readonly value="{{ $transaction->dr_acc }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label">Amount To Receive </label>
                                                <input class="form-control" type="text" readonly value="{{ number_format(($transaction->amount - ($transaction->amount * ($transaction->per_charges / 100))), 2) }}">
                                            </div>
                                        </div>
                                    @endif
                                    @if($transaction->discount_code)
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <label class="control-label">Discount Code</label>
                                                <input class="form-control" type="text" readonly value="{{ $transaction->discount_code }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label">Discount Amount </label>
                                                <input class="form-control" type="text" readonly value="{{ number_format($transaction->discount_value, 2) }}">
                                            </div>
                                        </div>
                                    @endif

                                    @if($transaction->cardPin)
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <label class="control-label">PIN / Token</label>
                                                <input class="form-control" type="text" readonly value="{{ $transaction->cardPin }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label">Customer Name / Narration</label>
                                                <input class="form-control" type="text" readonly value="{{ $transaction->narration }}">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="control-label">Total Amount</label>
                                            <input class="form-control" type="text" readonly value="{{ $transaction->amount }}">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="float-right">
                                                <label class="control-label d-block">&nbsp;</label>
                                                @if($transaction->approvalStatus != \App\Model\RequestStatus::Cancelled)
                                                    @if($transaction->approvalStatus != \App\Model\RequestStatus::Approved)
                                                        <a class="btn btn-success btn-sm " href="/transaction/approve/{{ $transaction->ref }}">Approve</a>
                                                    @endif
                                                    @if($transaction->approvalStatus != \App\Model\RequestStatus::Declined)
                                                        <a class="btn btn-danger btn-sm" href="/transaction/decline/{{ $transaction->ref }}">Decline</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>


                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="calls" role="tabpanel" aria-labelledby="calls-tab">
                            <div class="card">
                                <div class="card-body">

                                    <div class="form-group">
                                        <center><img alt="Profile Image" style="border-radius: 50%" class="profile-pic" src="{{ asset( $userDetail->image_url) }}" height="200px" width="200px"></center>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Full Name</label>
                                                <input readonly type="text" class="form-control" placeholder="Enter name" value="{{  $userDetail->fullname }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Email Address</label>
                                                <input readonly type="text" class="form-control" placeholder="Enter description" value="{{  $userDetail->email }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Phone Number</label>
                                                <input readonly type="text" class="form-control" placeholder="Enter description" value="{{  $userDetail->phoneno }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Address</label>
                                                <input readonly type="text" class="form-control" placeholder="Enter description" value="{{  $userDetail->address }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">BVN Number</label>
                                                <input readonly type="text" class="form-control" placeholder="Enter BVN Number" value="{{  $userDetail->bvn_number }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Date of Birth</label>
                                                <input readonly type="text" class="form-control" placeholder="Enter Date of Birth" value="{{  $userDetail->dob }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Gender</label>
                                                <input readonly type="text" class="form-control" placeholder="Enter Gender" value="{{  $userDetail->gender }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">User Role</label>
                                                <input readonly type="text" class="form-control" placeholder="Enter User Role" value="{{  $userDetail->userRole }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                            <div class="card">
                                <div class="card-body">
                                   <div class="row">
                                       <div class="col-md-6">
                                           <div class="form-group">
                                               <label class="control-label">Mac Address</label>
                                               <input readonly type="text" class="form-control" placeholder="Mac Address" value="{{  $transaction->mac_address }}">
                                           </div>
                                           <div class="form-group">
                                               <label class="control-label">IP Address</label>
                                               <input readonly type="text" class="form-control" placeholder="IP Address" value="{{  $transaction->ip_address }}">
                                           </div>
                                           <div class="form-group">
                                               <label class="control-label">Longitude</label>
                                               <input readonly type="text" class="form-control" placeholder="Longitude" value="{{  $transaction->longitude }}">
                                           </div>
                                           <div class="form-group">
                                               <label class="control-label">Latitude</label>
                                               <input readonly type="text" class="form-control" placeholder="Longitude" value="{{  $transaction->latitude }}">
                                           </div>
                                       </div>
                                       <div class="col-md-6">
                                           <div id='map' style='height: 400px; width: 100%;'></div>
                                       </div>
                                   </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
@section('script')
    <script type="text/javascript">
        let locations = {lat: {{ $transaction->latitude }}, lng: {{ $transaction->longitude }} };

        function initMap() {

            let map = new google.maps.Map(document.getElementById('map'), {
                zoom: 16,
                center: locations
            });


            new google.maps.Marker({
                position: locations,
                map: map,
                title: 'Location'
            });

        }
    </script>
    <script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAfF7GVrAscqvP-AnrPU3KK-RryWPXGc3g&callback=initMap"> </script>
@endsection
