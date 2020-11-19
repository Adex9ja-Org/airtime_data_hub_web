@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-primary text-white" href="/wallet/settlement/list/monify"><i data-feather="file-text" class="icon-sm mr-sm-2"></i>View Settlements</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0 d-inline">Available Monify Transfer List</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>E-mail Address</th>
                                <th>Sender Name</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Transaction Ref</th>
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
                                    <td>{{ $item->narration }}</td>
                                    <td>{{ number_format($item->amount, 2) }}</td>
                                    <td><div class="badge badge-success">{{ $item->status }}</div></td>
                                    <td>{{ $item->trans_ref }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Options
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="/users/list/detail/{{ base64_encode($item->email) }}"><i data-feather="eye" class="icon-sm mr-sm-2"></i>User Detail</a>
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
