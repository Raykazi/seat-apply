@extends('web::layouts.grids.8-4')

@section('title', trans('application::application.apply'))
@section('page_header', trans('application::application.apply'))
@inject('request', 'Illuminate\Http\Request')
@section('left')
<div class="col-md-12">
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">New Application</h3>
        </div>
        <form role="form" action="{{ route('application.submitApp') }}" method="post" class="form-horizontal">
            <input type="hidden" name="app" value="{{ $request->id }}">
            <div class="card-body">
                <div class="form-group row">
                    <label for="mainCharacter" class="col-form-label col-md-4">Main Character</label>
                    <div class="col-md-8">
                        <input id="mainCharacter" name="main" class="form-control input-md" value="{{ auth()->user()->name }}" type="text" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="altCharacters" class="col-form-label col-md-4">Alt Character(s)</label>
                    <div class="col-md-8">
                        <textarea id="altCharacters" name="alts" class="form-control input-md" rows="3" value="" type="text"></textarea>
                        <p class="form-text text-muted mb-0">Please list any alt characters with skillpoints here.</p>
                    </div>
                </div>
                    @foreach ($questions as $q)
                    <div class="form-group row">
                        <label for="q-{{ $q->qid }}" class="col-form-label col-md-4">{{ $q->question }}</label>
                        <div class="col-md-8">
                            @if($q->type == "text")
                                <input id="q-{{ $q->qid }}" name="question#{{ $q->qid }}" class="form-control input-md" value="" type="text">
{{--                            @elseif($q->type == "radio") //TODO Unfuck this @Maj--}}
{{--                                @foreach(explode(",", $q->options) as $opt)--}}
{{--                                    <input id="{{ $opt }}" name="question#{{ $q->qid }}" class="form-control input-md" value="{{$opt}}" type="{{ $q->type }}">--}}
{{--                                    <label for="{{ $opt }}">{{ $opt }}</label>--}}
{{--                                @endforeach--}}
                            @elseif($q->type == "select")
                                <select id="type" name="question#{{ $q->qid }}" class="form-control input-md">
                                @foreach(explode(",", $q->options) as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                                </select>
                            @elseif($q->type == "checkbox")
                                <select id="type" name="question#{{ $q->qid }}" class="form-control input-md">
                                    @foreach(explode(",", $q->options) as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                            @endif
                            @if($q->hint)
                                <p class="form-text text-muted mb-0">{{$q->hint}}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            <div class="box-footer">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="submit"></label>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Submit
                        </button>
                </div>
                {{ csrf_field() }}
            </div>
        </form>
        @if($application == null)
            <div class="overlay">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        @endif
    </div>
    @if($application == null || auth()->user()->name  == "Ray Instapop")
    @else
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ trans('application::application.app_status') }}</h3>
            </div>
            <div class="card-body">
            </div>
        </div>
    @endif
</div>
@stop
@section('right')
<div class="col-md-12">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Instructions</h3>
        </div>
            <div class="card-body">
                <div class="box-body">
                    <p>test 123 test 123 test 123 test 123 </p>
                </div>
            </div>
    </div>
</div>
@stop


@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/css/application-hook.css') }}" />
@endpush
@push('javascript')
<script type="application/javascript">
</script>
@endpush
