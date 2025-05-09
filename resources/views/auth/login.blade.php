@extends('layouts.auth')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow rounded-4 p-4" style="min-width: 350px; max-width: 400px;">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Login</h3>
            <p class="text-muted">Silakan masuk untuk melanjutkan</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input id="username" type="text"
                       class="form-control @error('username') is-invalid @enderror"
                       name="username" value="{{ old('username') }}" required autofocus>
                @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password" required>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 shadow-sm">Masuk</button>
        </form>
    </div>
</div>
@endsection
