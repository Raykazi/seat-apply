
@extends('web::layouts.grids.12')

@section('title', trans('application::application.apps'))
@section('page_header', trans('application::application.apps'))

@section('full')
    <div class="card card-primary card-solid">
        <div class="card-header">
            <h3 class="card-title">Applications</h3>
        </div>
        <div class="card-body">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Pending</a></li>
              <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Completed</a></li>
            </ul>

          <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
          <table id="apps" class="table table-bordered">
            <thead>
                <tr>
                  <th>ID</th>
                  <th>{{ trans('application::application.characterName') }}</th>
                  <th>{{ trans('application::application.app_status') }}</th>
                  <th>{{ trans('application::application.app_action') }}</th>
                  <th>{{ trans('application::application.app_notes') }}</th>
                  <th>{{ trans('application::application.app_approver') }}</th>
                </tr>
            </thead>
              <tbody>
              @foreach ($applications as $app)
                  @if(($app->status))
                      <tr>
                          <td>{{ $app->application_id }} </td>
                          <td><span class='id-to-name' data-id="{{ $app->character_name }}">{{ $app->character_name }}</span></td>
                          @if ($app->status == 0)
                              <td id="id-{{ $app->application_id }}"><span class="badge badge-secondary">Pending</span></td>
                          @elseif ($app->status == -1)
                              <td id="id-{{ $app->application_id }}"><span class="badge badge-danger">Rejected</span></td>
                          @elseif ($app->status == 1)
                              <td id="id-{{ $app->application_id }}"><span class="badge badge-warning">Reviewing</span></td>
                          @elseif ($app->status == 2)
                              <td id="id-{{ $app->application_id }}"><span class="badge badge-primary">Interviewing</span></td>
                          @endif
                          <td>
                              <button type="button" class="btn btn-xs btn-danger app-status" id="app-status" name="{{ $app->application_id }}">Reject</button>
                              <button type="button" class="btn btn-xs btn-warning app-status" id="app-status" name="{{ $app->application_id }}">Review</button>
                              <button type="button" class="btn btn-xs btn-primary app-status" id="app-status" name="{{ $app->application_id }}">Interview</button>
                              <button type="button" class="btn btn-xs btn-success app-status" id="app-status" name="{{ $app->application_id }}">Accept</button>
                          </td>
                          <td data-order="{{ strtotime($app->created_at) }}>
                      <span data-toggle="tooltip" data-placement="top" title="{{ $app->created_at }}">{{ human_diff($app->created_at) }}</span>
                          </td>


                          <td id="approver-{{ $app->application_id }}">{{ $app->approver }}</td>
                      </tr>
                  @endif
              @endforeach
              </tbody>
          </table>
        </div>
          <div class="tab-pane" id="tab_2">
          <table id="srps-arch" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ trans('application::application.characterName') }}</th>
                    <th>{{ trans('application::application.app_status') }}</th>
                    <th>{{ trans('application::application.app_action') }}</th>
                    <th>{{ trans('application::application.app_notes') }}</th>
                    <th>{{ trans('application::application.app_approver') }}</th>
                </tr>
            </thead>
          </table>
        </div>
        </div>
          </div>
    </div>

    <div class="card-footer text-muted">
        Plugin maintained by <a href="{{ route('application.about') }}"> {!! img('characters', 'portrait', 94819809, 64, ['class' => 'img-circle eve-icon small-icon']) !!} Raykaze Jenkins</a>. <span class="float-right snoopy" style="color: #fa3333;"><i class="fas fa-signal"></i></span>
    </div>
</div>
@stop

@push('javascript')
@include('web::includes.javascript.id-to-name')

<script>
    $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<script type="application/javascript">

  $(function () {
    $('#apps').DataTable();
    $('#apps-arch').DataTable();

    $('#apps tbody').on('click', 'button', function(btn) {
        $.ajax({
          headers: function() {},
          url: "{{ route('application.list') }}/" + btn.target.name + "/" + $(btn.target).text(),
          dataType: 'json',
          timeout: 5000
        }).done(function (data) {
          if (data.name === "Accept") {
              $("#id-"+data.value).html('<span class="badge badge-success">Accepted</span>');
          } else if (data.name === "Reject") {
              $("#id-"+data.value).html('<span class="badge badge-danger">Rejected</span>');
          } else if (data.name === "Interview") {
              $("#id-"+data.value).html('<span class="badge badge-primary">Ready For Interview</span>');
          } else if (data.name === "Review") {
              $("#id-"+data.value).html('<span class="badge badge-warning">Reviewing</span>');
          }
          $("#approver-"+data.value).html(data.approver);
        });
    });

});
</script>
@endpush
