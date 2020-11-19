
@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-7 stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">New Product</h6>
                <form  class="mt-4" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="control-label">Product Logo</label>
                        <input class="form-control mt-2" name="imageUpload" type="file" accept="image/*" required/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Product Name</label>
                        <input name="product_name" type="text" class="form-control" placeholder="Enter product name" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Product Description</label>
                        <textarea name="product_description" type="text" class="form-control" placeholder="Enter product description" rows="7"></textarea>
                    </div>
                    <input type="hidden" value="{{ $service_id }}" name="service_id">
                    <button type="submit" class="btn btn-success">Add New</button>
                </form>

            </div>
        </div>
    </div>
    </div>
@endsection

