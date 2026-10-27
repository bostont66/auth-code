@extends('layouts.app')

@section('title', 'Verify Code - '.config('app.name'))

@section('content')
<h2 class="text-xl font-semibold text-graphite mb-1">Enter your code</h2>
<p class="text-sm text-muted mb-6">We sent a 6-digit code to <strong>{{ $email }}</strong>.</p>

<form method="POST" action="{{ route('auth.verify.post') }}" onsubmit="var b=this.querySelector('button[type=submit]'); b.disabled=true; b.textContent='Verifying…';">
    @csrf
    <input type="hidden" name="email" value="{{ $email }}">

    <div class="mb-4">
        <label for="code" class="block text-sm font-medium text-graphite mb-1">Login code</label>
        <input type="text" id="code" name="code" required autofocus maxlength="6" pattern="[0-9]{6}"
               class="input text-center text-2xl tracking-[0.5em] @error('code') input-error @enderror"
               placeholder="000000" inputmode="numeric" autocomplete="one-time-code">
        @error('code')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-full">
        Verify & sign in
    </button>
</form>

<p class="mt-6 text-center text-sm text-muted">
    <a href="{{ route('auth.login') }}" class="text-sky hover:text-sky-dark font-medium">Use a different email</a>
</p>
@endsection
