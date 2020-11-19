@extends('template')
@section('content')

    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0 d-inline">Transaction List</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Channel</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Transaction Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php($count = 0)
                                @foreach($transList as $tran)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $tran->sub_name }}</td>
                                    <td>{{ $tran->channel_name }}</td>
                                    <td>
                                        <div class="badge {{ \App\Model\RequestStatus::getPill($tran->approvalStatus) }}">{{ \App\Model\RequestStatus::getReqTitle($tran->approvalStatus) }}</div>
                                    </td>
                                    <td>{{ $tran->amount }}</td>
                                    <td>{{ $tran->transDate }}</td>
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
    </div>

@endsection
