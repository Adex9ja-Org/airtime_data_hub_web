@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-8 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Account List</h6>
                        <a href="/setup/payment/account/add/new" class="btn btn-outline-primary">Add New</a>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Account No</th>
                                <th>Account Name</th>
                                <th>Bank Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 0)
                            @foreach($accountList as $data)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $data->acc_no }}</td>
                                    <td>{{ $data->acc_name }}</td>
                                    <td>{{ $data->bank_name }}</td>
                                    <td>
                                        <a class="btn @if(request()->route('acc_no') == $data->acc_no) btn-outline-secondary @else btn-secondary @endif  btn-sm" href="/setup/payment/account/{{ $data->acc_no }}">Update Account</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($accountInfo))
            <div class="col-md-4 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Account Detail</h6>
                        </div>

                        <form  class="mt-4" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">Account Name</label>
                                <input name="acc_no" type="text" class="form-control" placeholder="Enter name" value="{{  $accountInfo->acc_no }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Account Name</label>
                                <input name="acc_name" type="text" class="form-control" placeholder="Enter price" value="{{  $accountInfo->acc_name }}">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Bank</label>
                                <select class="form-control" name="bank_code">
                                    @foreach($bankList as $item)
                                        <option value="{{ $item->bank_code }}"  @if($item->bank_code == $accountInfo->bank_code) selected @endif>{{ $item->bank_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Update Information</button>
                            <a class="btn btn-danger btn-sm" href="/setup/payment/account/deactivate/{{ $accountInfo->acc_no }}">De-activate</a>

                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div> <!-- row -->

@endsection
