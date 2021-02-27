@extends('web::layouts.grids.8-4')

@section('title', trans('application::application.questions'))
@section('page_header', trans('application::application.questions'))
@inject('request', 'Illuminate\Http\Request')
@section('left')
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">Questions</h3>
        </div>
        <div class="card-body">
            <table id="apps" class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($questions as $q)
                    <tr>
                        <td>{{ $q->order }} </td>
                        <td><span class='id-to-name' data-id="{{ $q->qid }}">{{ $q->question }}</span></td>
                        <td>
                            <button type="button" class="btn btn-xs btn-warning app-status" id="app-status" name="{{ $q->qid }}">Edit</button>
                            <button type="button" class="btn btn-xs btn-danger app-status" id="app-status" name="{{ $q->qid }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('right')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ trans('application::application.add_question') }}</h3>
        </div>
        <form role="form" action="{{ route('application.submitQuestion') }}" method="post">
            <input type="hidden" name="id" value="{{ $request->id }}">
            <div class="card-body">
                <div class="form-group row">
                    <label for="questionNumber" class="col-form-label col-md-4">Question #</label>
                    <div class="col-md-8">
                        <input id="questionNumber" name="questionNumber"  style="width: 100%;" value="{{ count($questions)+1 }}" type="number">
                    </div>
                    <label for="questionInput" class="col-form-label col-md-4">Question</label>
                    <div class="col-md-8">
                        <input id="questionInput" name="questionInput"  style="width: 100%;" value="Would you drown your kid?" type="text">
                    </div>
                    <label for="questionNumber" class="col-form-label col-md-4">Answer Type</label>
                    <div class="col-md-8">
                        <select id="type" name="type" style="width: 100%;">
                            <option value="select">Drop Down</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="text" selected>Text</option>
                        </select>
                    </div>
                    <label for="options" class="col-form-label col-md-4">Choices</label>
                    <div class="col-md-8">
                        <input id="options" name="options"  style="width: 100%;" value="Separate via ," type="text">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="btn-group pull-right" role="group">
                    <input type="submit" class="btn btn-primary" id="saveQuestion" value="{{ trans('application::application.submit') }}"/>
                </div>
                {{ csrf_field() }}
            </div>
        </form>
    </div>
@stop

@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/css/application-hook.css') }}" />
@endpush
