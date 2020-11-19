
@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-7 stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">New Pay Bill Sub Product</h6>
                <form  class="mt-4" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="control-label">Product Name</label>
                        <input type="text" class="form-control" value="{{ $product->product_name }}" readonly/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Sub-Product Name</label>
                        <input name="sub_name" type="text" class="form-control" placeholder="Enter sub-product name" required/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Sub-Product Price</label>
                        <input name="sub_price" type="number" class="form-control" placeholder="Enter price" required/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Reseller's Price</label>
                        <input name="sub_res_price" type="number" class="form-control" placeholder="Enter reseller's price" required/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Optional Parameter</label>
                        <input name="optional_param" type="text" class="form-control" placeholder="Enter optional parameter"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Sub-Product Description</label>
                        <textarea name="sub_desc" type="text" class="form-control" placeholder="Enter sub-product description" rows="7"></textarea>
                    </div>
                    <input type="hidden" value="{{ $product->product_id }}" name="product_id"/>
                    <button type="submit" class="btn btn-success">Add New</button>
                </form>

            </div>
        </div>
    </div>
    </div>
@endsection

