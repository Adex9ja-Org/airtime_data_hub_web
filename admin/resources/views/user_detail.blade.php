
@extends('template')
@section('content')
<div class="row">
    <div class="col-md-4 chat-aside border-lg-right ">
        <div class="aside-content">
            <div class="aside-body">
                <ul class="nav nav-tabs mt-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="chats-tab" data-toggle="tab" href="#chats" role="tab" aria-controls="chats" aria-selected="true">
                            <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                                <i data-feather="user" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                                <p class="d-none d-sm-block">Basic Info</p>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="calls-tab" data-toggle="tab" href="#calls" role="tab" aria-controls="calls" aria-selected="false">
                            <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                                <i data-feather="edit" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                                <p class="d-none d-sm-block">Bank Info</p>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contacts-tab" data-toggle="tab" href="#contacts" role="tab" aria-controls="contacts" aria-selected="false">
                            <div class="d-flex flex-row flex-lg-column flex-xl-row align-items-center">
                                <i data-feather="paperclip" class="icon-sm mr-sm-2 mr-lg-0 mr-xl-2 mb-md-1 mb-xl-0"></i>
                                <p class="d-none d-sm-block">Identity Info</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="chats" role="tabpanel" aria-labelledby="chats-tab">
                        <div class="card">
                            <div class="card-body">

                                <div class="form-group">
                                    <center><img alt="Profile Image" style="border-radius: 50%" class="profile-pic" src="{{ asset( $userDetail->image_url) }}" height="100px" width="100px"></center>
                                </div>
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
                                <div class="form-group">
                                    <label class="control-label">User Role</label>
                                    <input readonly type="text" class="form-control" placeholder="Enter description" value="{{  $userDetail->userRole }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="calls" role="tabpanel" aria-labelledby="calls-tab">
                        <div class="card">
                            <div class="card-body">
                                <ul>
                                    @foreach($banks as $item)
                                        <li class="mt-1">
                                            <div>{{ $item->acc_name }}</div>
                                            <code>{{ $item->acc_no . ' - '. \App\Model\Repository::getAccountName($item->bank_code) }}</code>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                        <div class="card">
                            <div class="card-body">

                                <div class="form-group">
                                    <label class="control-label">BVN Number</label>
                                    <input readonly type="text" class="form-control" placeholder="BVN number" value="{{  $userDetail->bvn_number }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Date of Birth</label>
                                    <input readonly type="text" class="form-control" placeholder="Enter name" value="{{  $userDetail->dob }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Gender</label>
                                    <input readonly type="text" class="form-control" placeholder="Enter description" value="{{  $userDetail->gender }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Document Type</label>
                                    <input readonly type="text" class="form-control" placeholder="Enter description" value="{{  $userDetail->doc_type }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Document</label>
                                    <img alt="Document Img" class="d-block" src="{{ asset($userDetail->doc_url) }}" height="100px">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                    <h6 class="card-title mb-0 d-inline">Available Transaction List</h6>
                </div>
                <div class="table-responsive">
                    <table id="datatable" class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Transaction Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($count = 0)
                        @foreach($transaction as $tran)
                            <tr>
                                <td>{{ ++$count }}</td>
                                <td>{{ $tran->sub_name }}</td>
                                <td>
                                    <div class="badge {{ \App\Model\RequestStatus::getPill($tran->approvalStatus) }}">{{ \App\Model\RequestStatus::getReqTitle($tran->approvalStatus) }}</div>
                                </td>
                                <td>
                                    {{ number_format($tran->amount, 2) }}
                                </td>
                                <td>{{ $tran->created_at }}</td>
                                <td>
                                    <a class="btn btn-outline-secondary btn-sm " href="/transaction/list/view/{{ $tran->ref }}">Details</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-center text-uppercase mt-3 mb-4">Wallet Balance</h5>
                        <h3 class="text-center font-weight-light">NGN {{ number_format($balance, 2) }}</h3>

                        <form method="post" id="walletOp">
                            @csrf
                            <div class="form-group">
                                <input type="number" class="form-control" name="amount" id="amount">
                                <input type="hidden" class="form-control" name="email" value="{{ base64_encode($userDetail->email)  }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-success text-white btn-block" onclick="walletOperation('/wallet/fund/add')" ><i data-feather="plus" class="icon-sm mr-sm-2"></i> Fund Wallet</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-danger text-white btn-block" onclick="walletOperation('/wallet/fund/remove')"><i data-feather="minus" class="icon-sm mr-sm-2"></i>Remove Fund</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-2">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable2" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Transaction Date</th>
                                <th>Narration</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 0)
                            @foreach($walletList as $tran)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ number_format($tran['amount'], 2) }}</td>
                                    <td><div class="badge {{ \App\Model\TransactionType::getPill($tran['trans_type'])  }}">{{ \App\Model\TransactionType::getTitle($tran['trans_type']) }}</div></td>
                                    <td>{{ $tran['created_at'] }}</td>
                                    <td>{{ $tran['narration'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')
    <script type="text/javascript">
        function  walletOperation($url) {
            $amount = $("#amount").val();
            if($amount == ''){
                alert("Invalid Amount");
                return;
            }
            $("form#walletOp").attr('action', $url).submit();
        }
    </script>
@endsection


