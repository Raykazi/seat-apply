@extends('web::layouts.grids.6-6')

@section('title', trans('application::application.apply'))
@section('page_header', trans('application::application.apply'))
@inject('request', 'Illuminate\Http\Request')
@section('left')
<div class="col-md-12">
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">New Application</h3>
        </div>
        <form role="form" action="{{ route('application.submitApp') }}" method="post" class="form-horizontal" >
            <input type="hidden" name="app" value="{{ auth()->user()->name }}">
            <div class="card-body">
            <legend>Your Characters</legend>
                <div class="form-group row">
                    <label for="mainCharacter" class="col-form-label col-md-4">Main Character</label>
                    <div class="col-md-8">
                        <input id="mainCharacter" name="main" class="form-control input-md" value="{{ auth()->user()->name }}" type="text" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="altCharacters" class="col-form-label col-md-4">Alt Character(s)</label>
                    <div class="col-md-8">
                        <textarea id="altCharacters" name="alts" class="form-control input-md" rows="3" value="" type="text" style="margin-top: 8px;"></textarea>
                        <p class="form-text text-muted mb-0">Please list any alt characters with skillpoints here.</p>
                    </div>
                </div>
                    @foreach ($questions as $q)
                    <div class="form-group row">
                        <label for="q-{{ $q->qid }}" class="col-form-label col-md-4">{{ $q->question }}</label>
                        @if($q->type != "checkbox")
                        @endif
                        <div class="col-md-8">
                            @if($q->type == "text")
                                <input id="q-{{ $q->qid }}" name="question#{{ $q->qid }}" class="form-control input-md" style="margin-top: 8px;" value="" type="text">
                            @elseif($q->type == "radio")
                                @foreach(explode(",", $q->options) as $opt)
                                    <input id="{{ $opt }}" name="question#{{ $q->qid }}" class="form-control input-md" value="{{$opt}}" type="{{ $q->type }}">
                                    <label for="{{ $opt }}">{{ $opt }}</label>
                                @endforeach
                            @elseif($q->type == "select")
                                <select id="type" name="question#{{ $q->qid }}" style="margin-top: 10px;" class="form-control input-md">
                                @foreach(explode(",", $q->options) as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                                </select>
                            @elseif($q->type == "checkbox")
                                <fieldset>
                                    @foreach(explode(",", $q->options) as $opt)
                                        <input type="checkbox" style="margin-left: 10px; margin-top: 10px;" name="question#{{ $q->qid }}" value="{{ $opt }}"> {{ $opt }}
                                    @endforeach
                                </fieldset>
                            @elseif($q->type == "multiline")
                                <textarea id="q-{{ $q->qid }}" name="question#{{ $q->qid }}" class="form-control input-md" rows="3" value="" type="text" style="margin-top: 8px;"></textarea>
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
        @if(count($application)>0)
            <div class="overlay">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        @endif
{{--        @if($application == null)--}}
{{--            <div class="overlay">--}}
{{--                <i class="fa fa-refresh fa-spin"></i>--}}
{{--            </div>--}}
{{--        @endif--}}
    </div>
    @if($application == null)
    @else
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
                    <p>Fill out the application form with as little or as much detail as you think each question requires.</p>
                    <h4>Process</h4>
                        <ul>
                            <li>Submit this application form</li>
                            <li>Join our discord here: <a href="http://discord.com/invite/VV4Y38kur5">http://discord.com/invite/VV4Y38kur5</a></li>
                            <li>We'll go over your application and if we like what we see, invite you to a voice chat on Discord</li>
                            <li>After your chat on Discord, provided we're a good fit for each other we'll send you an invite to corp</li>
                        </ul>
                        <p>The whole process shouldn't take more than a couple of days, feel free to keep shopping around for corporations while you wait.</p>
                    <h4>Tips</h4>
                        <ul>
                            <li>There are no wrong answers</li>
                            <li>During the chat on Discord, you're welcome to ask lots of questions too!</li>
                            <li>Killboard history isn't as important as you might think, don't stress about stats</li>
                        </ul>   
                </div>
            </div>
    </div>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ trans('application::application.app_status') }}</h3>
        </div>
        <div class="card-body">
            @if(count($application) == 0)
                <p> Nothing to see here.</p>
            @else
                @switch($application[0]->status)
                    @case(-1)
                        <img src="{{ asset('web/img/sad-pepe.png') }}" width="128" height="128">
                        <p>Sorry bud</p>
                    @break;
                    @case(0)
                        <p> Currently pending</p>
                    @break;
                    @case(1)
                        <p> Currently reviewing your application</p>
                    @break;
                    @case(2)
                        <p> Join discord for your group interview.</p>
                    @break;
                    @case(3)
                        <p> Welcome to the Windrammers</p>
                    @break;
                @endswitch
            @endif

        </div>
        @if(count($application) == 0)
            <div class="overlay">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        @endif
    </div>
</div>
@stop


@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/css/application-hook.css') }}" />
@endpush
@push('javascript')
<script type="application/javascript">
    $('.overlay').show();
</script>
@endpush
