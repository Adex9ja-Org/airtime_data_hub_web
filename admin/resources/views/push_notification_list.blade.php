@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Notification List</h6>
                        <a href="/push/notification/send/new" class="btn btn-outline-primary">Add New</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Published Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 0)
                            @foreach($pushList as $data)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ substr($data->title, 0, 50) }}</td>
                                    <td>{{ substr($data->message, 0, 50) }}</td>
                                    <td>{{ $data->created_at }}</td>
                                    <td>
                                        <a class="btn btn-secondary  btn-sm" href="/push/notification/re-send/{{ $data->id }}">Re-Send</a>
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
