
@extends('template')
@section('content')

    <div class="row">
        <div class="col-md-7 stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">New Faq</h6>
                <form  class="mt-4" method="post">
                    @csrf
                    <div class="form-group">
                        <select class="form-control" required name="faq_cat">
                            <label class="control-label">Faq Category</label>
                            @foreach($faqCatList as $item)
                                <option value="{{ $item->faq_cat }}"> {{ $item->faq_cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Faq Question</label>
                        <textarea name="faq" type="text" class="form-control" placeholder="Enter faq question" required rows="8"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Faq Answer</label>
                        <textarea name="answer" type="text" class="form-control" placeholder="Enter answer"  required rows="10"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Add New</button>
                </form>

            </div>
        </div>
    </div>
    </div>
@endsection

