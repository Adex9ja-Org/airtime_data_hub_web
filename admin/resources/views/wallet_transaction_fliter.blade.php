@extends('template')
@section('content')


    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0 d-inline">Filter Transaction by Payment Method</h6>
                    </div>
                    <form method="get" class="mt-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="product">Available Payment Methods</label>
                                <select name="id" class="form-control" required>
                                    <option value="">Select Payment Method</option>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->channel_name }}" @if(isset($_GET['c']) && $_GET['c'] == $method->channel_name) selected @endif> {{ $method->channel_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="product">Transaction Status</label>
                                <select name="s" class="form-control" required>
                                    <option value="1" @if(isset($_GET['s']) && $_GET['s'] == 1) selected @endif>Approved</option>
                                    <option value="0" @if(isset($_GET['s']) && $_GET['s'] == 0) selected @endif>Pending</option>
                                    <option value="-1" @if(isset($_GET['s']) && $_GET['s'] == -1) selected @endif>Declined</option>
                                    <option value="2" @if(isset($_GET['s']) && $_GET['s'] == 2) selected @endif>Cancelled</option>
                                    <option value="-2" @if(isset($_GET['s']) && $_GET['s'] == -2) selected @endif>Failed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="date_from">Date From</label>
                                <input name="f" type="date" class="form-control" required value="{{ $_GET['f'] ?? date("Y-m-d") }}">
                            </div>
                            <div class="col-md-2">
                                <label for="date_from">Date To</label>
                                <input name="t" type="date" class="form-control" required value="{{ $_GET['t'] ?? date("Y-m-d") }}">
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <input type="submit" class="btn btn-primary form-control" value="Filter Payment">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($data)
        <div class="row mt-4">
            <div class="col-12 col-xl-12 stretch-card">
                <div class="row flex-grow">
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Transaction Count</h6>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <h5 class="mb-2 text-primary">{{ number_format(sizeof($data)) }}</h5>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Transaction Amount</h6>

                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <h5 class="mb-2 text-info">{{ '₦ ' . number_format(array_sum(array_map(function ($item){ return $item->amount; }, $data)), 0) }}</h5>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
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
                                    <th>Type</th>
                                    <th>Narration</th>
                                    <th>Total Amount</th>
                                    <th>Transaction Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($count = 0)
                                @foreach($data as $tran)
                                    <tr>
                                        <td>{{ ++$count }}</td>
                                        <td>{{ $tran->fullname }}</td>
                                        <td>{{ $tran->email }}</td>
                                        <td><div class="badge {{ \App\Model\TransactionType::getPill($tran->trans_type)  }}">{{ \App\Model\TransactionType::getTitle($tran->trans_type) }}</div></td>
                                        <td>{{ $tran->narration }}</td>
                                        <td>{{ number_format($tran->amount, 2) }}</td>
                                        <td>{{ $tran->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



@endsection
