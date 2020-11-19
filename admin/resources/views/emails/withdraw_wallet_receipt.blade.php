@extends('emails.email_template')
@section('content')
    <p>Hi!</p>
    <p>Here is a payout request receipt.</p>
    <h1 class="center">NGN {{ number_format($payoutRequest->amount, 2, '.', ',') }}</h1>
    <div class="grey-background single-line"></div>
    <h3 class="center">PAYOUT / WITHDRAWAL REQUEST DETAILS</h3>
    <div class="grey-background container">
        <table width="100%">
            <tr>
                <td>Reference</td>
                <td>{{ $payoutRequest->payout_id }}</td>
            </tr>
            <tr>
                <td>Request Status</td>
                <td>{{ \App\Model\RequestStatus::getReqTitle($payoutRequest->status) }}</td>
            </tr>
            <tr>
                <td>Transaction Date</td>
                <td>{{ explode(' ', $payoutRequest->created_at)[0] }}</td>
            </tr>
            <tr>
                <td>Transaction Time</td>
                <td>{{ explode(' ', $payoutRequest->created_at)[1] }}</td>
            </tr>
            <tr>
                <td>Service</td>
                <td>Payout/Withdrawal Request</td>
            </tr>
            <tr>
                <td>Bank Name</td>
                <td>{{ $payoutRequest->bank_name  }}</td>
            </tr>
            <tr>
                <td>Account Name</td>
                <td>{{ $payoutRequest->acc_name }}</td>
            </tr>
            <tr>
                <td>Account Number</td>
                <td>{{ $payoutRequest->acc_no }}</td>
            </tr>
            <tr>
                <td>Transaction Amount</td>
                <td>NGN {{  number_format($payoutRequest->amount, 2, '.', ',') }}</td>
            </tr>
        </table>
    </div>

@endsection
