@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <h1>Edit User</h1>

    <div class="card">
        <div class="card-header">Edit User and Change Role</div>
        <div class="card-body">
            <!-- Display success or error messages -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                    @error('username')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="">Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role', $user->roles->first()->name) == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update User</button>
            </form>
        </div>
    </div>
</div>
@endsection
