@extends('web::layouts.grids.8-4')

@section('title', trans('application::application.questions'))
@section('page_header', trans('application::application.questions'))
@inject('request', 'Illuminate\Http\Request')
@section('left')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Questions</h3>
        </div>
        <div class="card-body">
            <table id="questions" class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Questions</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($questions as $q)
                    <tr>
                        <td>{{ $q->order }} </td>
                        <td><span class='id-to-name' data-id="{{ $q->qid }}">{{ $q->question }}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm float-right">
                                <button type="button" class="btn btn-warning app-status" id="app-status" name="{{ $q->qid }}">Edit</button>
                                <button type="button" class="btn btn-danger app-status" id="app-status" name="{{ $q->qid }}">Delete</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('right')
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">{{ trans('application::application.add_question') }}</h3>
        </div>
        <form role="form" action="{{ route('application.submitQuestion') }}" method="post">
            <input type="hidden" name="id" value="{{ $request->id }}">
            <div class="card-body">
                <div class="form-group row">
                    <label for="questionNumber" class="col-form-label col-md-4">Question Number</label>
                    <div class="col-md-8">
                        <input id="questionNumber" name="questionNumber" class="form-control" value="{{ count($questions)+1 }}" type="number">
                        <p class="form-text text-muted mb-0">Questions are sorted by their number.</p>
                    </div>
                    <label for="questionInput" class="col-form-label col-md-4">Question</label>
                    <div class="col-md-8">
                        <input id="questionInput" name="questionInput" class="form-control" value="" type="text">
                        <p class="form-text text-muted mb-0"></p>
                    </div>
                    <label for="type" class="col-form-label col-md-4">Answer Type</label>
                    <div class="col-md-8">
                        <select id="type" name="type" class="form-control">
                            <option value="select">Dropdown</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="text" selected>Textbox</option>
                            <option value="multiline">Multiline Textbox</option>
                        </select>
                        <p class="form-text text-muted mb-0"></p>
                    </div>
                    <label for="options" class="col-form-label col-md-4">Choices</label>
                    <div class="col-md-8">
                        <input id="options" name="options" value="" type="text" class="form-control">
                        <p class="form-text text-muted mb-0">Use comma-separated values here.</p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                        <button type="submit" class="btn btn-success float-right">
                            <i class="fa fa-plus"></i> Add Question
                        </button>
                {{ csrf_field() }}
            </div>
        </form>
    </div>
<div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">{{ trans('application::application.add_header') }}</h3>
        </div>
        <form role="form" action="" method="post">
            <input type="hidden" name="id" value="{{ $request->id }}">
            <div class="card-body">
                <div class="form-group row">
                    <label for="questionNumber2" class="col-form-label col-md-4">Question Number</label>
                    <div class="col-md-8">
                        <input id="questionNumber2" name="questionNumber2" class="form-control" value="{{ count($questions)+1 }}" type="number">
                        <p class="form-text text-muted mb-0">Headers are sorted by their number.</p>
                    </div>
                    <label for="headerInput" class="col-form-label col-md-4">Header</label>
                    <div class="col-md-8">
                        <input id="headerInput" name="headerInput" class="form-control" value="" type="text">
                        <p class="form-text text-muted mb-0">Use this to split your questions into groups, if needed.</p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
		<button type="submit" class="btn btn-success float-right">
                            <i class="fa fa-plus"></i> Add Header
                        </button>
                {{ csrf_field() }}
            </div>
        </form>
    </div>
<div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Instructions</h3>
        </div>
        <form role="form" action="" method="post">
            <input type="hidden" name="id" value="{{ $request->id }}">
            <div class="card-body">
                <div class="form-group row">
                    <label for="editInstructions" class="col-form-label col-md-4">Instructions</label>
                    <div class="col-md-8">
                        <textarea id="editInstructions" name="editInstructions" class="form-control input-md" rows="6" value="" type="text" style="margin-top: 8px;"></textarea>
                        <p class="form-text text-muted mb-0">HTML can be used here.</p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
		<button type="submit" class="btn btn-info float-right">
                            <i class="far fa-edit""></i> Edit Instructions
                        </button>
                {{ csrf_field() }}
            </div>
        </form>
    </div>
@stop

@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/css/application-hook.css') }}" />
@endpush
@push('javascript')
    @include('web::includes.javascript.id-to-name')

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <script type="application/javascript">

        $(function () {
            $('#questions').DataTable();

            {{--$('#apps tbody').on('click', 'button', function(btn) {--}}
            {{--    $.ajax({--}}
            {{--        headers: function() {},--}}
            {{--        url: "{{ route('application.list') }}/" + btn.target.name + "/" + $(btn.target).text(),--}}
            {{--        dataType: 'json',--}}
            {{--        timeout: 5000--}}
            {{--    }).done(function (data) {--}}
            {{--        if (data.name === "Accept") {--}}
            {{--            $("#id-"+data.value).html('<span class="badge badge-success">Accepted</span>');--}}
            {{--        } else if (data.name === "Reject") {--}}
            {{--            $("#id-"+data.value).html('<span class="badge badge-danger">Rejected</span>');--}}
            {{--        } else if (data.name === "Interview") {--}}
            {{--            $("#id-"+data.value).html('<span class="badge badge-primary">Ready For Interview</span>');--}}
            {{--        } else if (data.name === "Review") {--}}
            {{--            $("#id-"+data.value).html('<span class="badge badge-warning">Reviewing</span>');--}}
            {{--        }--}}
            {{--        $("#approver-"+data.value).html(data.approver);--}}
            {{--    });--}}
            {{--});--}}

        });
    </script>
@endpush
