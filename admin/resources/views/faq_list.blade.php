@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-6 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">FAQ List</h6>
                        <a href="/others/faq/add/new" class="btn btn-outline-primary">Add New</a>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $count = 0;
                            @endphp

                            @foreach($faqList as $item)
                                <tr>
                                    <td>{{ ++$count }}</td>
                                    <td>{{ substr($item->faq, 0, 100) }}</td>
                                    <td>{{ substr($item->answer, 0, 100) }}</td>
                                    <td>
                                        <a class="btn @if(request()->route('id') == $item->id) btn-outline-secondary @else btn-secondary @endif  btn-sm" href="/others/faq/list/{{ $item->id }}">Update Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($faq))
            <div class="col-md-5 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Faq Detail</h6>
                        </div>

                        <form  class="mt-4" method="post">
                            @csrf
                            <div class="form-group">
                                <select class="form-control" required name="faq_cat">
                                    <label class="control-label">Faq Category</label>
                                    @foreach($faqCatList as $item)
                                        <option @php if( isset($faq->faq_cat) && $item->faq_cat == $faq->faq_cat) echo 'selected' @endphp  value="{{ $item->faq_cat }}"> {{ $item->faq_cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Faq Question</label>
                                <textarea name="faq" type="text" class="form-control" placeholder="Enter faq question" required rows="8">{{ $faq->faq }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Faq Answer</label>
                                <textarea name="answer" type="text" class="form-control" placeholder="Enter answer"  required rows="10">{{  $faq->answer }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Update Information</button>
                            <a class="btn btn-danger btn-sm" href="/others/faq/deactivate/{{ $faq->id }}">De-activate</a>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection
