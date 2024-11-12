@extends('layouts.sidebar-layout')

@section('content')
<div class="container">
    <h1>Change Password</h1>

    <div class="card mb-4">
        <div class="card-header">
            Change Password
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <div class="mb-3">
                    <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                    <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
                    @error('current_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">{{ __('New Password') }}</label>
                    <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required>
                    @error('new_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">{{ __('Confirm New Password') }}</label>
                    <input id="new_password_confirmation" type="password" class="form-control" name="new_password_confirmation" required>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Update Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection