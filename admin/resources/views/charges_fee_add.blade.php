
@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-7 stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">New Charges Information</h6>
                <form  class="mt-4" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="control-label">Charges Name</label>
                        <input name="conversion_name" type="text" class="form-control" placeholder="Enter name" >
                    </div>
                    <div class="form-group">
                        <label class="control-label">Charges Amount</label>
                        <input name="per_charges" type="text" class="form-control" placeholder="Enter price" >
                    </div>

                    <button type="submit" class="btn btn-success">Add New</button>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

