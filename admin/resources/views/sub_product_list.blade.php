@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-primary text-white" href="/automation/sync"><i data-feather="refresh-ccw" class="icon-sm mr-sm-2"></i> Synchronize Automation</a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Sub Product List</h6>
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
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
