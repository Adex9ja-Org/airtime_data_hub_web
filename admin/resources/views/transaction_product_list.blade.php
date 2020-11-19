@extends('template')
@section('content')


    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0 d-inline">Query Search Parameters</h6>
                    </div>
                    <form method="get" class="mt-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="product">Available Products</label>
                                <select name="id" class="form-control" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->product_id }}" @if(isset($_GET['id']) && $_GET['id'] == $product->product_id) selected @endif> {{ $product->product_name }}</option>
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
                                    <option value="3" @if(isset($_GET['s']) && $_GET['s'] == 3) selected @endif>Insufficient</option>
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
                                <input type="submit" class="btn btn-primary form-control" value="Search Transaction">
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
                                    <th>Channel</th>
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
                                        <td>{{ $tran->channel_name }}</td>
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
    @endif



@endsection
