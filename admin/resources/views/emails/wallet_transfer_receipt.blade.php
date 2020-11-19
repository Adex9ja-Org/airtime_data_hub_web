@extends('emails.email_template')
@section('content')
    <p> Hi!</p>
    <p>Here is a wallet transaction receipt that occurs on your account.</p>
    <h1 class="center">NGN {{ number_format($trans->amount, 2, '.', ',') }}</h1>
    <div class="grey-background single-line"></div>
    <h3 class="center">TRANSFER DETAILS</h3>
    <div class="grey-background container">
        <table width="100%">
            <tr>
                <td>Reference</td>
                <td>{{ $trans->payment_ref }}</td>
            </tr>
            <tr>
                <td>Transaction Status</td>
                <td>{{ \App\Model\RequestStatus::getReqTitle($trans->status) }}</td>
            </tr>
            <tr>
                <td>Transaction Date</td>
                <td>{{ explode(' ', $trans->created_at)[0] }}</td>
            </tr>
            <tr>
                <td>Transaction Time</td>
                <td>{{ explode(' ', $trans->created_at)[1] }}</td>
            </tr>
            <tr>
                <td>Service</td>
                <td>Wallet Transfer</td>
            </tr>
            <tr>
                <td>From</td>
                <td>{{ $sender->fullname. ' - ' .$sender->email }}</td>
            </tr>
            <tr>
                <td>To</td>
                <td>{{ $receiver->fullname. ' - ' .$receiver->email }}</td>
            </tr>
            <tr>
                <td>Transaction Amount</td>
                <td>NGN {{  number_format($trans->amount, 2, '.', ',') }}</td>
            </tr>
        </table>
    </div>

@endsection
