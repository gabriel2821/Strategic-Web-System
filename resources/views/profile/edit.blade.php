@extends('layouts.app')
@section('title')
@section('content')
<div class="container">
    <h1 class="mb-4">Profil</h1>

    <!-- Profile Information Form -->
    <div class="card mb-4">
        <div class="card-header">Kemaskini Maklumat Profil</div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Emel</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-muted">
                                Your email address is unverified.
                                <form id="send-verification" method="POST" action="{{ route('verification.send') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-sm">Click here to re-send the verification email.</button>
                                </form>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="text-sm text-success">A new verification link has been sent to your email address.</p>
                            @endif
                        </div>
                    @endif
                </div>
                @if (auth()->user()->userType === 'admin')
                    <div class="mb-3">
                        <label for="userType" class="form-label">User Type</label>
                        <select class="form-control @error('userType') is-invalid @enderror" id="userType" name="userType">
                            <option value="admin" {{ old('userType', $user->userType) === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ old('userType', $user->userType) === 'user' ? 'selected' : '' }}>User</option>
                        </select>
                        @error('userType')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                <div class="d-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                    @if (session('status') === 'profile-updated')
                        <p class="text-sm text-success mb-0">Disimpan.</p>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Password Update Form -->
    <div class="card mb-4">
        <div class="card-header">Update Password</div>
        <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="current_password" class="form-label">Kata Laluan Semasa</label>
                    <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Kata Laluan Baru</label>
                    <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Sahkan Kata Laluan</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                    @error('password_confirmation', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-primary">Kemas kini Kata Laluan</button>
                    @if (session('status') === 'password-updated')
                        <p class="text-sm text-success mb-0">Saved.</p>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account Form -->
    <!-- <div class="card">
        <div class="card-header">Delete Account</div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <div class="mb-3">
                    <label for="delete_password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" id="delete_password" name="password" required>
                    @error('password', 'userDeletion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-danger">Delete Account</button>
            </form>
        </div>
    </div>
</div> -->
@endsection