@extends('template')
@section('content')


    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0 d-inline">Available Bank Transfer List</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>E-mail Address</th>
                                <th>Status</th>
                                <th>Sender Name</th>
                                <th>Sender Acct.</th>
                                <th>Amount</th>
                                <th>Account Number</th>
                                <th>Transaction Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php($count = 0)
                                @foreach($bankTransfers as $item)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        <div class="badge {{ \App\Model\RequestStatus::getPill($item->status) }}">{{ \App\Model\RequestStatus::getReqTitle($item->status) }}</div>
                                    </td>
                                    <td>{{ $item->sender_name }}</td>
                                    <td>{{ $item->sender_number }}</td>
                                    <td>
                                        {{ number_format($item->amount, 2) }}
                                    </td>
                                    <td>{{ $item->acc_no }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Options
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="/users/list/detail/{{ base64_encode($item->email) }}"><i data-feather="eye" class="icon-sm mr-sm-2"></i>User Detail</a>


                                                @if($item->status != \App\Model\RequestStatus::Cancelled)
                                                    @if($item->status != \App\Model\RequestStatus::Approved)
                                                        <a class="dropdown-item" href="/wallet/bank/approve/{{ $item->payment_ref }}"><i data-feather="check" class="icon-sm mr-sm-2"></i>Approve</a>
                                                    @endif
                                                    @if($item->status != \App\Model\RequestStatus::Declined)
                                                        <a class="dropdown-item" href="/wallet/bank/decline/{{ $item->payment_ref }}"><i data-feather="delete" class="icon-sm mr-sm-2"></i>Decline</a>
                                                    @endif
                                                @endif

                                            </div>
                                        </div>


                                    </td>

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
