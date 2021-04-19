
@extends('web::layouts.grids.12')

@section('title', trans('application::application.apps'))
@section('page_header', trans('application::application.apps'))

@section('full')
    <div class="card card-solid">
        <div class="card-header">
            <h3 class="card-title">Applications</h3>
        </div>
        <div class="card-body">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Pending</a></li>
              <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Completed</a></li>
            </ul><br>

          <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
          <table id="apps" class="table table-striped">
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
                  @if(($app->status >= 0 && $app->status <3))
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
                          <td>
                              <button type="button" class="btn btn-xs btn-success app-view" id="app-view" data-toggle="modal" data-target="#apply-view-app"  data-app-id="{{ $app->application_id }}" name="{{ $app->application_id }}">View</button>
                          </td>
                          <td id="approver-{{ $app->application_id }}">{{ $app->approver }}</td>
                      </tr>
                  @endif
              @endforeach
              </tbody>
          </table>
        </div>
          <div class="tab-pane" id="tab_2">
          <table id="apps-arch" class="table table-striped">
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
                  @if(($app->status == -1 || $app->status == 3))
                      <tr>
                          <td>{{ $app->application_id }} </td>
                          <td><span class='id-to-name' data-id="{{ $app->character_name }}">{{ $app->character_name }}</span></td>
                          @if ($app->status == -1)
                              <td id="id-{{ $app->application_id }}"><span class="badge badge-danger">Rejected</span></td>
                          @elseif ($app->status == 3)
                              <td id="id-{{ $app->application_id }}"><span class="badge badge-success">Accepted</span></td>
                          @endif
                          <td>
                              <button type="button" class="btn btn-xs btn-success app-status" id="app-status" name="{{ $app->application_id }}">Accept</button>
                              <button type="button" class="btn btn-xs btn-warning app-status" id="app-status" name="{{ $app->application_id }}">Review</button>
                              <button type="button" class="btn btn-xs btn-danger app-status" id="app-status" name="{{ $app->application_id }}">Reject</button>
                              <button type="button" class="btn btn-xs btn-primary app-status" id="app-status" name="{{ $app->application_id }}">Delete</button>
                          </td>
                          <td>
                              <button type="button" class="btn btn-xs btn-success app-view" id="app-view" data-toggle="modal" data-target="#apply-view-app"  data-app-id="{{ $app->application_id }}" name="{{ $app->application_id }}">View</button>
                          </td>
                          <td id="approver-{{ $app->application_id }}">{{ $app->approver }}</td>
                      </tr>
                  @endif
              @endforeach
              </tbody>
          </table>
        </div>
        </div>
          </div>
    </div>
</div>
    @include('application::includes.view-app-modal')
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
              location.reload();
          } else if (data.name === "Reject") {
              location.reload();
          } else if (data.name === "Interview") {
              $("#id-"+data.value).html('<span class="badge badge-primary">Ready For Interview</span>');
          } else if (data.name === "Review") {
              $("#id-"+data.value).html('<span class="badge badge-warning">Reviewing</span>');
          }
          $("#approver-"+data.value).html(data.approver);
        });
    });
    $('#apps-arch tbody').on('click', 'button', function(btn) {
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
          } else if (data.name === "Delete") {
              location.reload();
          } else if (data.name === "Review") {
              location.reload();
          }
          $("#approver-"+data.value).html(data.approver);
      });
    });
  });
  $(function () {
      $('#apply-view-app').on('show.bs.modal', function(e){
          var link = '{{ route('application.application', 0) }}';
          var rlink = link.replace('/0', '/' + $(e.relatedTarget).attr('data-app-id'));
          $(this).find('.overlay').show();
          $.ajax({
              url: rlink,
              dataType: 'json',
              method: 'GET'
          }).done(function(response){
              $('#apply-view-app').find('#mainCharacter').val(response.character_name);
              $('#apply-view-app').find('#altCharacters').val(response.alt_characters);
              $('#apply-view-app').find('#responses').val(response.responses);
          }).fail(function(jqXHR, status){
              alert(jqXHR);
          });

          $(this).find('.overlay').hide();
      });

  });
</script>
@endpush
