
@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-7 stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">New Promo / Discount</h6>
                <form  class="mt-4" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="control-label">Promo / Discount Code</label>
                        <input name="discount_code" type="text" class="form-control" placeholder="Enter promo code" value="{{ $code }}" required readonly />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Percentage (%) of the actual price</label>
                        <input name="percentage" type="number" class="form-control" placeholder="Enter percentage(%)"  required />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Expiry Date</label>
                        <input name="expiry_date" type="date" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Number of Usage</label>
                        <input name="usage_number" type="number" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Applicable Product</label>
                        <select class="form-control" name="product_id">
                            @foreach($products as $product)
                                <option value="{{ $product->product_id }}">{{ $product->product_name }}</option>
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

