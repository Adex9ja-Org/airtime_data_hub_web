@extends('template')
@section('content')


    <div class="row">
        <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0 d-inline">Available User List</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>E-mail Address</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php($count = 0)
                                @foreach($data as $farmer)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ substr($farmer->fullname, 0, 50) }}</td>
                                    <td>{{ substr($farmer->email, 0, 50) }}</td>
                                    <td>{{ substr($farmer->phoneno, 0, 15) }}</td>
                                    <td>{{ substr($farmer->address, 0, 50) }}</td>
                                    <td>
                                        @if($farmer->active == 1)
                                            <div class="badge badge-secondary">Active</div>
                                        @else
                                            <div class="badge badge-danger">Banned</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-light dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Options
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="/users/list/detail/{{ base64_encode($farmer->email) }}"><i data-feather="eye" class="icon-sm mr-sm-2"></i>Detail</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="/users/update/{{ base64_encode($farmer->email) }}"><i data-feather="edit" class="icon-sm mr-sm-2"></i>Update</a>
                                                <div class="dropdown-divider"></div>
                                                @if($farmer->active == 1)
                                                    <a class="dropdown-item" href="/users/deactivate/{{ base64_encode($farmer->email) }}"><i data-feather="delete" class="icon-sm mr-sm-2"></i>Ban User</a>
                                                @else
                                                    <a class="dropdown-item" href="/users/activate/{{ base64_encode($farmer->email) }}"><i data-feather="check" class="icon-sm mr-sm-2"></i>Activate</a>
                                                @endif
                                            </div>
                                        </div>
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
