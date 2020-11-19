@extends('template')
@section('content')

    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0 d-inline">Available Monify Settlement List</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Transaction Ref</th>
                                <th>Account Number</th>
                                <th>Account Name</th>
                                <th>Amount</th>
                                <th>Transactions</th>
                                <th>Transaction Date</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php($count = 0)
                                @foreach($settlements as $item)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $item->trans_ref }}</td>
                                    <td>{{ $item->acct_num }}</td>
                                    <td>{{ $item->acct_name }}</td>
                                    <td>{{ number_format($item->amount, 2) }}</td>
                                    <td>{{ $item->trans_count }}</td>
                                    <td>{{ $item->created_at }}</td>
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
