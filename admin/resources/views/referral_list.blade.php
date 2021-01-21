@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-7 stretch-card">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Referral Codes</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable2" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Referral Code</th>
                                <th>Full Name</th>
                                <th>Referrals</th>
                                <th>Earned</th>
                                <th>Payment Reference</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 0)
                            @foreach($referralList as $item)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $item->ref_code }}</td>
                                    <td>{{ $item->fullname }}</td>
                                    <td>{{ $item->referred }}</td>
                                    <td>{{ $item->earned ?? 0 }}</td>
                                    <td>{{ $item->reference }}</td>
                                    <td>{{ number_format($item->amount, 2) }}</td>
                                    <td>
                                        <a class="btn @if(request()->route('ref_code') == $item->ref_code) btn-outline-secondary @else btn-secondary @endif  btn-sm" href="/referral/earnings/list/{{ $item->ref_code }}">See Commissions</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($userDetail))
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Eanings</h6>
                        </div>
                        <div class="mt-2 mb-2">
                            <input class="form-control" type="text" value="{{ $userDetail->fullname. ' - ' . $userDetail->ref_code }}" readonly>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Referred Name</th>
                                    <th>Payment Reference</th>
                                    <th>Amount</th>
                                    <th>Transaction Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($count = 0)
                                @foreach($earningList as $item)
                                    <tr>
                                        <td>{{ ++$count }}</td>
                                        <td>{{ $item->fullname }}</td>
                                        <td>{{ $item->payment_ref }}</td>
                                        <td>{{ number_format(1000, 2) }}</td>
                                        <td>{{ $item->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection
