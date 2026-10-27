@extends('layouts.app')

@section('title', 'Verify Email - '.config('app.name'))

@section('content')
<h2 class="text-xl font-semibold text-graphite mb-1">Verify your email</h2>
<p class="text-sm text-muted mb-6">Enter the verification token sent to <strong>{{ $email }}</strong>.</p>

<form method="POST" action="{{ route('auth.register.verify.post') }}">
    @csrf
    <input type="hidden" name="email" value="{{ $email }}">

    <div class="mb-4">
        <label for="token" class="block text-sm font-medium text-graphite mb-1">Verification token</label>
        <input type="text" id="token" name="token" required autofocus
               class="input @error('token') input-error @enderror"
               placeholder="Paste your verification token">
        @error('token')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-full">
        Verify & activate account
    </button>
</form>

<p class="mt-6 text-center text-sm text-muted">
    <a href="{{ route('auth.register') }}" class="text-sky hover:text-sky-dark font-medium">Use a different email</a>
</p>
@endsection
