@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-8 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Promo Code List</h6>
                        <a href="/promo/code/add/new" class="btn btn-outline-primary">Add New</a>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Promo Code</th>
                                <th>Percentage (%)</th>
                                <th>Expiry Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 0)
                            @foreach($promoCodeList as $item)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $item->discount_code }}</td>
                                    <td>{{ $item->percentage }}</td>
                                    <td>{{ $item->expiry_date }}</td>
                                    <td>
                                        <a class="btn @if(request()->route('discount_code') == $item->discount_code) btn-outline-secondary @else btn-secondary @endif  btn-sm" href="/promo/code/list/{{ $item->discount_code }}">Update Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($promoCode))
            <div class="col-md-4 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Promo Code Detail</h6>
                        </div>

                        <form  class="mt-4" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">Promo / Discount Code</label>
                                <input name="discount_code" type="text" class="form-control" placeholder="Enter promo code" value="{{ $promoCode->discount_code }}" required readonly />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Percentage (%) of the actual price</label>
                                <input name="percentage" type="number" class="form-control" placeholder="Enter percentage(%)" value="{{ $promoCode->percentage }}"  required />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Expiry Date</label>
                                <input name="expiry_date" type="date" class="form-control" value="{{ $promoCode->expiry_date }}" required />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Number of Usage</label>
                                <input name="usage_number" type="number" class="form-control" value="{{ $promoCode->usage_number }}" required />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Applicable Product</label>
                                <select class="form-control" name="product_id">
                                    @foreach($products as $product)
                                        <option value="{{ $product->product_id }}" @if($product->product_id == $promoCode->product_id ) selected @endif>{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">Update Information</button>
                            <a class="btn btn-danger btn-sm" href="/promo/code/deactivate/{{ $promoCode->discount_code }}">De-activate</a>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection
