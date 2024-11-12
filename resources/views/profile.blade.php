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
                    <ul>
                        @foreach($permissions as $permission)
                            <li>{{ $permission }}</li>
                        @endforeach
                    </ul>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endsection