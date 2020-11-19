@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-7 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Charges List</h6>
                        <a href="/setup/charges/rate/add/new" class="btn btn-outline-primary">Add New</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Charges Name</th>
                                <th>Charges Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 0)
                            @foreach($chargesList as $data)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $data->conversion_name }}</td>
                                    <td>{{ $data->per_charges }}</td>
                                    <td>
                                        <a class="btn @if(request()->route('conversion_id') == $data->conversion_id) btn-outline-secondary @else btn-secondary @endif  btn-sm" href="/setup/charges/rate/{{ $data->conversion_id }}">Update Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($charges))
            <div class="col-md-4 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Charges Detail</h6>
                        </div>

                        <form  class="mt-4" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">Charges Name</label>
                                <input name="conversion_name" type="text" class="form-control" placeholder="Enter name" value="{{  $charges->conversion_name }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Charges Amount</label>
                                <input name="per_charges" type="text" class="form-control" placeholder="Enter price" value="{{  $charges->per_charges }}">
                            </div>

                            <button type="submit" class="btn btn-success">Update Information</button>
                            <a class="btn btn-danger btn-sm" href="/setup/charges/rate/deactivate/{{ $charges->conversion_id }}">De-activate</a>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection
