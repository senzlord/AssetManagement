@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <h1>Profile</h1>

    <div class="card mb-4">
        <div class="card-header">
            User Information
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Username:</strong> {{ $user->username }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Permissions
        </div>
        <div class="card-body">
            @foreach ($groupedPermissions as $group => $permissions)
                <h5>{{ $group }}</h5>
                @if($permissions->isEmpty())
                    <p>No {{ strtolower($group) }} assigned.</p>
                @else
                    <ul style="list-style-type: none; padding-left: 0;">
                        @foreach($permissions as $permission)
                            <li>
                                <!-- Checkbox that is disabled, checked if the user has permission -->
                                <input type="checkbox" disabled {{ $user->hasPermissionTo($permission) ? 'checked' : '' }}>
                                <label class="form-check-label" style="color: inherit;">{{ $permission }}</label>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endsection