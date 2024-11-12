@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <h1>Change Access for {{ $user->name }}</h1>

    <div class="card mb-4">
        <div class="card-header">Manage Permissions</div>
        <div class="card-body">
            <form method="POST" action="{{ route('users.updateAccess', $user->id) }}">
                @csrf

                <!-- User Permissions Section -->
                <h5>User Permissions</h5>
                <div class="form-group mb-3">
                    @foreach ($userPermissions as $permission)
                        <div class="form-check">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                name="permissions[]"
                                value="{{ $permission->name }}"
                                id="permission-{{ $permission->id }}"
                                {{ in_array($permission->name, $userAssignedPermissions) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="permission-{{ $permission->id }}">
                                {{ ucfirst($permission->name) }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <!-- Admin Permissions Section -->
                <h5>Admin Permissions</h5>
                <div class="form-group">
                    @foreach ($adminPermissions as $permission)
                        <div class="form-check">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                name="permissions[]"
                                value="{{ $permission->name }}"
                                id="permission-{{ $permission->id }}"
                                {{ in_array($permission->name, $userAssignedPermissions) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="permission-{{ $permission->id }}">
                                {{ ucfirst($permission->name) }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update Permissions</button>
            </form>
        </div>
    </div>
</div>
@endsection