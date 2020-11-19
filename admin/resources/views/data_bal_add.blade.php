
@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-7 stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Data Balance Code Information</h6>
                <form  class="mt-4" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="control-label">Network Name</label>
                        <select name="net_name"  class="form-control" required >
                            @foreach($networkList as $data)
                                <option value="{{ $data->net_name }}">{{ $data->net_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Network Code</label>
                        <input name="net_code" type="text" class="form-control" placeholder="Enter network code" required>
                    </div>
                    <button type="submit" class="btn btn-success">Add New</button>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

