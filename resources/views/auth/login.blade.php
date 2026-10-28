@extends('layouts.app')

@section('title', 'Login - '.config('app.name'))

@section('content')
<h2 class="text-xl font-semibold text-graphite mb-1">Sign in</h2>
<p class="text-sm text-muted mb-6">Enter your email to receive a login code.</p>

@if (request('expired'))
    <div class="mb-4 p-3 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm">
        Your session expired. Please sign in again.
    </div>
@endif

<form method="POST" action="{{ route('auth.login.send') }}" onsubmit="var b=this.querySelector('button[type=submit]'); b.disabled=true; b.textContent='Sending…';">
    @csrf
    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-graphite mb-1">Email address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
               class="input @error('email') input-error @enderror"
               placeholder="you@example.com">
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-full">
        Send login code
    </button>
</form>

<p class="mt-6 text-center text-sm text-muted">
    Don't have an account?
    <a href="{{ route('auth.register') }}" class="text-sky hover:text-sky-dark font-medium">Register</a>
</p>
@endsection
