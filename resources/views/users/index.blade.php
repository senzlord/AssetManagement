@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <!-- Display success or error alerts using the Alert component -->
    <x-alert type="success" :message="session('success')" />
    <x-alert type="danger" :message="session('error')" />

    <!-- Flex container for heading and button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>User List</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Add New User</a>
    </div>

    @if($users->isEmpty())
        <p>No users found.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @php
                        // Check if this is the authenticated user's own account
                        $isSelf = auth()->id() === $user->id;
                    @endphp
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                @if($user->trashed())
                                    <!-- Restore Account Button -->
                                    @can('delete account')
                                        <form action="{{ route('users.restore', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to restore this user?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" {{ $isSelf ? 'disabled' : '' }}>Restore</button>
                                        </form>
                                    @endcan
                                @else
                                    <!-- Edit Account Button -->
                                    @can('edit account')
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning {{ $isSelf ? 'disabled' : '' }}" {{ $isSelf ? 'tabindex="-1"' : '' }}>Edit</a>
                                    @endcan                                

                                    <!-- Soft Delete Account Button -->
                                    @can('delete account')
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" {{ $isSelf ? 'disabled' : '' }}>Delete</button>
                                        </form>
                                    @endcan

                                    <!-- Change Access Button -->
                                    @can('manage data access')
                                        <a href="{{ route('users.changeAccess', $user->id) }}" class="btn btn-sm btn-secondary {{ $isSelf ? 'disabled' : '' }}" {{ $isSelf ? 'tabindex="-1"' : '' }}>Change Access</a>
                                    @endcan
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection