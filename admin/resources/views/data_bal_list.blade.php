@extends('template')
@section('content')


    <div class="row">
        <div class="col-md-7 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Data Balance Code List</h6>
                        <a href="/setup/data/balance/add/new" class="btn btn-outline-primary">Add New</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Network Name</th>
                                <th>Network Code</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 0)
                            @foreach($dataCodeList as $data)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $data->net_name }}</td>
                                    <td>{{ $data->net_code }}</td>
                                    <td>
                                        <a class="btn @if(request()->route('net_code') == base64_encode($data->net_code)) btn-outline-secondary @else btn-secondary @endif  btn-sm" href="/setup/data/balance/{{ base64_encode($data->net_code) }}">Update Data Code</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($dataCode))
            <div class="col-md-4 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Account Detail</h6>
                        </div>

                        <form  class="mt-4" method="post">
                            @csrf
                            <select name="net_name"  class="form-control" required >
                                @foreach($networkList as $data)
                                    <option @if($dataCode->net_name == $data->net_name  ) selected @endif value="{{ $data->net_name }}">{{ $data->net_name }}</option>
                                @endforeach
                            </select>
                            <div class="form-group">
                                <label class="control-label">Network Code</label>
                                <input name="net_code" type="text" class="form-control" placeholder="Enter network code" value="{{  $dataCode->net_code }}">
                            </div>
                            <button type="submit" class="btn btn-success">Update Information</button>
                            <a class="btn btn-danger" href="/setup/data/balance/deactivate/{{ base64_encode($dataCode->net_code) }}">De-Activate</a>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection
