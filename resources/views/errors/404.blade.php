@extends('layouts.app')

@section('title', 'Page not found')

@section('content')
    <div class="error-page">
        <div class="card error-card">
            <h1 class="error-code">404</h1>
            <p class="error-message">{{ $message ?? "We couldn't find the page you were looking for." }}</p>
            <a href="{{ route('login') }}" class="btn btn--primary">Back to sign in</a>
        </div>
    </div>
@endsection
