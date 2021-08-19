@extends('layouts.app')

@section('content')
  <div class="card">
    <div class="card-header">
      <i class="fas fa-list-alt"></i> Webhook Process Errors
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Process ID</th>
            <th>File</th>
            <th>Line</th>
            <th>Message</th>
            <th>Code</th>
            <th>Create Time</th>
            <th style="width: 100px">&nbsp;</th>
          </tr>
          </thead>
          <tbody>
          @forelse($records as $record)
            <tr>
              <td>{{ $record->process_id }}</td>
              <td>{{ $record->file }}</td>
              <td>{{ $record->line }}</td>
              <td>{{ $record->message }}</td>
              <td>{{ $record->code }}</td>
              <td>{{ $record->created_at->format('m/d/Y H:i:s') }}</td>
              <td>
                <a href="{{ route('webhook-error-retry', $record->id) }}"
                   role="button" class="btn btn-info btn-sm" data-method="post">
                  <i class="fas fa-sync"></i>
                </a>
                <a href="{{ route('webhook-process-errors.destroy', $record->id) }}"
                   role="button" class="btn btn-danger btn-sm" data-method="delete">
                  <i class="fas fa-trash"></i>
                </a>
              </td>
            </tr>
          @empty
          @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer">
      {!! $records->links() !!}
    </div>
  </div>


@endsection

@section('footer_javascript_yield')
  <script type="text/javascript">
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $(function () {
          $('a[data-method]').click(function (e) {
              e.preventDefault();

              $.post({
                  type: $(this).data('method'),
                  url: $(this).attr('href')
              }).done(function () {
                  window.location.reload();
              })
          })
      })
  </script>
@endsection
