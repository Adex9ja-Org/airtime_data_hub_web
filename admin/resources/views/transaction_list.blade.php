@extends('template')
@section('content')


    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
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
                                <th>Full Name</th>
                                <th>E-mail Address</th>
                                <th>Product</th>
                                <th>Channel</th>
                                <th>Status</th>
                                <th>Discount Code</th>
                                <th>Discount Amount</th>
                                <th>Product Amount</th>
                                <th>Total Amount</th>
                                <th>Transaction Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php($count = 0)
                                @foreach($data as $tran)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $tran->fullname }}</td>
                                    <td>{{ $tran->email }}</td>
                                    <td>{{ $tran->sub_name }}</td>
                                    <td>{{ $tran->channel_name }}</td>
                                    <td>
                                        <div class="badge {{ \App\Model\RequestStatus::getPill($tran->approvalStatus) }}">{{ \App\Model\RequestStatus::getReqTitle($tran->approvalStatus) }}</div>
                                    </td>
                                    <td>{{ $tran->discount_code }}</td>
                                    <td>{{ number_format($tran->discount_value, 2) }}</td>
                                    <td>{{ number_format(($tran->amount + $tran->discount_value), 2) }}</td>
                                    <td>{{ number_format($tran->amount, 2) }}</td>
                                    <td>{{ $tran->created_at }}</td>
                                    <td>
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Options
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="/transaction/list/view/{{ $tran->ref }}"><i data-feather="eye" class="icon-sm mr-sm-2"></i>Details</a>

                                                @if($tran->approvalStatus != \App\Model\RequestStatus::Cancelled)
                                                    @if($tran->approvalStatus != \App\Model\RequestStatus::Approved)
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="/transaction/approve/{{ $tran->ref }}"><i data-feather="check" class="icon-sm mr-sm-2"></i>Approve</a>
                                                    @endif
                                                    <div class="dropdown-divider"></div>
                                                    @if($tran->approvalStatus != \App\Model\RequestStatus::Declined)
                                                        <a class="dropdown-item" href="/transaction/decline/{{ $tran->ref }}"><i data-feather="delete" class="icon-sm mr-sm-2"></i>Decline</a>
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
