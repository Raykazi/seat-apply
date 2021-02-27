@extends('web::layouts.grids.12')

@section('title', trans('application::application.apply'))
@section('page_header', trans('application::application.apply'))
@inject('request', 'Illuminate\Http\Request')
@section('left')
<div class="col-md-6">
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
                        <input id="mainCharacter" name="main"  style="width: 100%;" value="{{ auth()->user()->name }}" type="text" disabled>
                    </div>
                    <label for="altCharacters" class="col-form-label col-md-4">Alt Character(s)</label>
                    <div class="col-md-8">
                        <input id="altCharacters" name="alts"  style="width: 100%;" value="" type="text">
                    </div>
                    @foreach ($questions as $q)
                        <label for="q-{{ $q->qid }}" class="col-form-label col-md-4">{{ $q->question }}</label>
                        <div class="col-md-8">
                            @if($q->type == "text")
                                <input id="q-{{ $q->qid }}" name="question#{{ $q->qid }}"  style="width: 100%;" value="" type="text">
{{--                            @elseif($q->type == "radio") //TODO Unfuck this @Maj--}}
{{--                                @foreach(explode(",", $q->options) as $opt)--}}
{{--                                    <input id="{{ $opt }}" name="question#{{ $q->qid }}"  style="width: 100%;" value="{{$opt}}" type="{{ $q->type }}">--}}
{{--                                    <label for="{{ $opt }}">{{ $opt }}</label>--}}
{{--                                @endforeach--}}
                            @elseif($q->type == "select")
                                <select id="type" name="question#{{ $q->qid }}" style="width: 100%;">
                                @foreach(explode(",", $q->options) as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                                </select>
                            @elseif($q->type == "checkbox")
                                <select id="type" name="question#{{ $q->qid }}" style="width: 100%;">
                                    @foreach(explode(",", $q->options) as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                <div class="btn-group pull-right" role="group">
                    <input type="submit" class="btn btn-primary" id="saveApp" value="{{ trans('application::application.submit') }}"/>
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
<div class="col-md-6">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Instructions</h3>
        </div>
            <div class="card-body">
                <div class="box-body">
                    <p>test 123 test 123 test 123 test 123 test 123</p>
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
