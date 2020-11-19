@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-6 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">User Roles</h6>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>User Role</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php($count = 0)
                                @foreach($data as $farmer)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ $farmer->user_role }}</td>
                                    <td>
                                        <a class="btn @if(base64_decode(collect(request()->segments())->last()) == $farmer->user_role) btn-outline-secondary @else btn-secondary @endif  btn-sm" href="/users/privileges/{{ base64_encode($farmer->user_role) }}">Set Privileges</a>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($privileges))
            <div class="col-md-4 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <input class="form-control" type="text" value="{{ base64_decode(collect(request()->segments())->last())  }}" readonly/>
                    </div>
                    <form method="post">
                        @csrf
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover mb-0">
                                <tbody>
                                @foreach($privileges as $farmer)
                                    <tr>
                                        <td><input name="privileges[]"  @if($farmer->privilege != null) checked @endif type="checkbox" value="{{ $farmer->link }}"> {{ $farmer->title }} </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                        <input type="submit" class="btn btn-success float-right" value="Update Privilege"/>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </div>

@endsection
