
@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-8 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Application Settings</h6>
                    </div>

                    <form  class="mt-4" method="post">
                        @csrf
                        <div class="row">
                            @foreach($settings as $item)
                                @if($item->settings_id == 'app_splash')
                                    @php($splash_url = $item->settings_desc ?? '')
                                @else
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ ucfirst(str_replace('_', ' ', $item->settings_id)) }}</label>
                                            <input name="{{ $item->settings_id }}" type="text" class="form-control" placeholder="Enter value" value="{{  $item->settings_desc }}">
                                        </div>
                                    </div>
                                @endif

                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-success">Update Information</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="card-title mb-0">Splash Image Upload</h6>
                            </div>
                            <div class="col-md-12 mt-4">
                                <form method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <img src="{{ asset($splash_url) }}" height="150px">
                                        <input class="form-control mt-2" name="imageUpload" type="file" accept="image/*" required/>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Upload Splash Image</button>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

