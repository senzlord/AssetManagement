@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <h1>Log History</h1>

    @if ($logs->isEmpty())
        <p>No logs available.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Log Level</th>
                    <th>Description</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->user->name }}</td>
                        <td>{{ ucfirst($log->log_level) }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $logs->links('vendor.pagination.bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
