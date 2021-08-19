@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <i class="fas fa-signal"></i> Waiting Webhook Processes
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Scope</th>
                        <th>Type</th>
                        <th>Bigcommerce ID</th>
                        <th>Create Time</th>
                        <th style="width: 100px">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td>{{ $record->scope }}</td>
                            <td>{{ $record->type }}</td>
                            <td>{{ $record->bigcommerce_id }}</td>
                            <td>{{ $record->created_at->format('m/d/Y H:i:s') }}</td>
                            <td>
                                <a href="{{ route('webhook-process.destroy', $record->id) }}"
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
            $('a[data-method="delete"]').click(function (e) {
                e.preventDefault();

                $.post({
                    type: "delete",
                    url: $(this).attr('href')
                }).done(function () {
                    window.location.reload();
                })
            })
        })
    </script>
@endsection
