@extends('layouts.app')

@section('title', 'Register - '.config('app.name'))

@section('content')
<h2 class="text-xl font-semibold text-graphite mb-1">Create an account</h2>
<p class="text-sm text-muted mb-6">Register to manage your domains.</p>

<form method="POST" action="{{ route('auth.register.post') }}">
    @csrf
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-graphite mb-1">Full name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
               class="input @error('name') input-error @enderror"
               placeholder="Jeff Wilkes">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-graphite mb-1">Email address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required
               class="input @error('email') input-error @enderror"
               placeholder="you@example.com">
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-6">
        <label for="domain_name" class="block text-sm font-medium text-graphite mb-1">
            Domain name <span class="text-muted font-normal">(optional)</span>
        </label>
        <input type="text" id="domain_name" name="domain_name" value="{{ old('domain_name') }}"
               class="input @error('domain_name') input-error @enderror"
               placeholder="example.com">
        @error('domain_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-muted">Leave blank if you don't have a domain yet — you can register one through the app later.</p>
    </div>

    <button type="submit" class="btn btn-primary w-full">
        Register
    </button>
</form>

<p class="mt-6 text-center text-sm text-muted">
    Already have an account?
    <a href="{{ route('auth.login') }}" class="text-sky hover:text-sky-dark font-medium">Sign in</a>
</p>
@endsection
