@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create a New Thread</div>

                    <div class="card-body">
                        <form method="post" action="/threads">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" name = "title" id="title" aria-describedby="emailHelp">
                            </div>


                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea class="form-control" id="body" rows="5" name="body"></textarea>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10">
                              <button type="submit" class="btn btn-primary">publish</button>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
