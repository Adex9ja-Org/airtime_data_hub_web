@extends('template')
@section('content')


    <div class="row mt-3">
        <div class="col-md-4 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Product List</h6>
                        <a href="/product/add/new/{{ collect(request()->segments())[1] }}" class="btn btn-outline-primary">Add New</a>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 0)
                            @foreach($productList as $item)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>
                                        <div class="badge {{ \App\Model\ActiveStatus::getPill($item->active) }}">{{ \App\Model\ActiveStatus::getTitle($item->active) }}</div>
                                    </td>
                                    <td>
                                        @if($item->active == 1)
                                            <a href="/product/delete/{{ $item->product_id }}" type="submit" class="btn btn-sm btn-danger">De-Activate</a>
                                        @else
                                            <a href="/product/activate/{{ $item->product_id }}" type="submit" class="btn btn-sm btn-success">Activate</a>
                                        @endif
                                        <a class="btn @if(request()->route('product_id') == $item->product_id) btn-outline-secondary @else btn-secondary @endif  btn-sm" href="/product/{{ collect(request()->segments())[1] }}/list/{{ $item->product_id }}">Sub Products</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($subProdList))
            <div class="col-md-4 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Sub Product List</h6>
                            <a href="/product/sub_products/add/new/{{ request()->route('product_id') }}" class="btn btn-outline-primary">Add New</a>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable2" class="table table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>Sub-Product</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Reseller's Price</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($subProdList as $data)
                                    <tr>
                                        <td>{{ $data->sub_name }}</td>
                                        <td>
                                            <div class="badge {{ \App\Model\ActiveStatus::getPill($data->active) }}">{{ \App\Model\ActiveStatus::getTitle($data->active) }}</div>
                                        </td>
                                        <td>{{ number_format($data->sub_price, 2) }}</td>
                                        <td>{{ number_format($data->sub_res_price, 2) }}</td>
                                        <td>
                                            @if($data->active == 1)
                                                <a href="/product/sub-prod/delete/{{ $data->sub_prod_id }}" type="submit" class="btn btn-sm btn-danger">De-Activate</a>
                                            @else
                                                <a href="/product/sub-prod/activate/{{ $data->sub_prod_id }}" type="submit" class="btn btn-sm btn-success">Activate</a>
                                            @endif
                                            <a class="btn @if(request()->route('sub_prod_id') == $data->sub_prod_id) btn-outline-info @else btn-info @endif  btn-sm" href="/product/{{ collect(request()->segments())[1] }}/list/{{ request()->route('product_id') . '/' . $data->sub_prod_id  }}">Detail</a>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(isset($subProdDetail))
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Sub Product Detail</h6>
                            <div class="badge {{ \App\Model\ActiveStatus::getPill($subProdDetail->active) }}">{{ \App\Model\ActiveStatus::getTitle($subProdDetail->active) }}</div>
                        </div>

                        <form  class="mt-4" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">Sub Product Name</label>
                                <input name="sub_name" type="text" class="form-control" placeholder="Enter name" value="{{  $subProdDetail->sub_name }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Sub Product Price</label>
                                <input name="sub_price" type="text" class="form-control" placeholder="Enter price" value="{{  $subProdDetail->sub_price }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Reseller's Price</label>
                                <input name="sub_res_price" type="text" class="form-control" placeholder="Enter price" value="{{  $subProdDetail->sub_res_price }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Sub Product Description</label>
                                <textarea name="sub_desc" type="text" class="form-control" placeholder="Enter description" rows="4" >{{  $subProdDetail->sub_desc }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Optional Parameter</label>
                                <input name="optional_param" type="text" class="form-control" placeholder="Enter beneficiary" value="{{  $subProdDetail->optional_param }}">
                            </div>
                            <input type="hidden" value="{{ $subProdDetail->sub_prod_id }}" name="sub_prod_id">
                            <button type="submit" class="btn btn-success">Update Information</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection
