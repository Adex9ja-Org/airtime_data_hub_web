
@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-7 stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">New Banner Information</h6>
                <form  class="mt-4" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="control-label">Account Name</label>
                        <input name="acc_no" type="text" class="form-control" placeholder="Enter name" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Account Name</label>
                        <input name="acc_name" type="text" class="form-control" placeholder="Enter price" required>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Bank</label>
                        <select class="form-control" name="bank_code" required>
                            @foreach($bankList as $item)
                                <option value="{{ $item->bank_code }}" >{{ $item->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Add New</button>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

