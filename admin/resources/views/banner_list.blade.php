@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-4 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Banner List</h6>
                        <a href="/others/banner/add/new" class="btn btn-outline-primary">Add New</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Banner Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 0)
                            @foreach($bannerList as $data)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $data->banner_name }}</td>
                                    <td>
                                        <a class="btn @if(request()->route('banner_id') == $data->banner_id) btn-outline-secondary @else btn-secondary @endif  btn-sm" href="/others/banner/{{ $data->banner_id }}">Update Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($banner))
            <div class="col-md-4 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Banner Detail</h6>
                        </div>

                        <form  class="mt-4" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <img src="{{ asset( $banner->image_url) }}" height="100px">
                                <input class="form-control mt-2" name="imageUpload" type="file" accept="image/*"/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Service</label>
                                <select class="form-control" name="service_id">
                                    @foreach($services as $item):
                                        <option value="{{ $item->service_id }}"  @if($item->service_id == $banner->service_id): selected @endif>{{ $item->service_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Banner Description</label>
                                <input name="description" type="text" class="form-control" placeholder="Enter description" value="{{  $banner->description }}">
                            </div>
                            <button type="submit" class="btn btn-success">Update Information</button>
                            <a class="btn btn-danger btn-sm" href="/others/banner/deactivate/{{ $banner->banner_id }}">De-activate</a>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection
