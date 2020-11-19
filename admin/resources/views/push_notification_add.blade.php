
@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-7 stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">New Push Notification</h6>
                <form  class="mt-4" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="control-label">Published Date</label>
                        <input class="form-control mt-2"  type="date" readonly value="{{ date("Y-m-d") }}"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Notification Title</label>
                        <input name="title" type="text" class="form-control" placeholder="Enter message title" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Notification Message</label>
                        <textarea name="message" type="text" class="form-control" placeholder="Enter message" rows="5" ></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Add New</button>
                </form>

            </div>
        </div>
    </div>
    </div>
@endsection

