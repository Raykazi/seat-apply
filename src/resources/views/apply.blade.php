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
        <form role="form" action="{{ route('application.submitApp') }}" method="post" class="form-horizontal">
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
					<div class="form-check" style="margin-top: 10px;">
						<input class="form-check-input" type="checkbox" id="inlineCheckbox{{ $opt }}" value="{{ $opt }}">
						<label class="form-check-label" for="inlineCheckbox{{ $opt }}">{{ $opt }}</label>
					</div>
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
            <div class="card-footer">
                        <button type="submit" class="btn btn-success float-right">
                            <i class="fas fa-check"></i> Apply
                        </button>
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
                <h3><span class="badge badge-danger">Nothing</span></h3>
		<p>Ray what is dis</p>
            @else
                @switch($application[0]->status)
                    @case(-1)
                        <p><span class="badge badge-warning">Denied</span></p>
			<p>Your application to Windrammers was denied. We've most likely sent you the reasoning for this via Discord - Feel free to contact us if you have any questions.</p>
                    @break;
                    @case(0)
                        <p><span class="badge badge-default">Pending</span></p>
			<p>We've got your application and we're ready to take a look. This process shouldn't take more than 24 hours. Please make sure you're on our Discord!</p>
                    @break;
                    @case(1)
                        <p><span class="badge badge-info">Reviewing</span></p>
			<p>We're currently going over your application, if we have any questions we'll message you via Discord.</p>
                    @break;
                    @case(2)
                        <p><span class="badge badge-warning">Awaiting Interview</span></p>
			<p>We would love a voice chat with you on Discord, just a casual chat to make sure we're both looking for the same things.</p>
                    @break;
                    @case(3)
                        <p><span class="badge badge-success">Successful</span></p>
			<p>Welcome to Windrammers! Please check the Windrammers Discord for instructions.</p>
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
