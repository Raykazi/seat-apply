@extends('web::layouts.grids.4-4-4')

@section('title', trans('application::application.about'))
@section('page_header', trans('application::application.name'))
@section('page_description', trans('application::application.about'))


@section('left')

  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Functionality</h3>
    </div>
    <div class="card-body">

     <p>This plugin provides a very simple yet powerful functionality to have nerds apply to your corporation.</p>

    </div>
  </div>
@stop

@section('center')

  <div class="card card-success">
    <div class="card-header">
      <h3 class="card-title">THANK YOU!</h3>
    </div>
    <div class="card-body">
      <div class="box-body">

        <p><strong>SeAT Apply</strong> is a negro-coded community creations designed to benefit Windrammers! I sincerely hope you enjoy using it. If you are feeling generous then please feel free to make my wallet go blinky blinky.</p>

        <p>
            <table class="table table-borderless">
                <tr> <td>SeAT Apply</td> <td> <a href="https://evewho.com/character/94819809"> {!! img('characters', 'portrait', 94819809, 64, ['class' => 'img-circle eve-icon small-icon']) !!} Raykaze Jenkins</a></td></tr>
            </table>
        </p>

        </div>
    </div>
    <div class="card-footer text-muted">
        Plugin maintained by <a href="{{ route('application.about') }}"> {!! img('characters', 'portrait', 94819809, 64, ['class' => 'img-circle eve-icon small-icon']) !!} Raykaze Jenkins</a>. <span class="float-right snoopy" style="color: #fa3333;"><i class="fas fa-signal"></i></span>
    </div>
  </div>

@stop
@section('right')

  <div class="card card-warning">
    <div class="card-header">
      <h3 class="card-title">Info</h3>
    </div>
    <div class="card-body">

      <legend>Bugs and Feature Requests</legend>

      <p>If you encounter a bug or have a suggestion, either contact Crypta-Eve on <a href="https://eveseat.github.io/docs/about/contact/">SeAT-Slack</a> or submit an <a href="https://github.com/Raykazi/seat-apply/issues/new">issue on Github</a></p>

    </div>
  </div>

@stop