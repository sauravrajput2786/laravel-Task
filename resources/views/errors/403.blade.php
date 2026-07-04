@extends('layouts.app')

@section('title', 'Access denied')

@section('content')
    <div class="error-page">
        <div class="card error-card">
            <h1 class="error-code">403</h1>
            <p class="error-message">You don't have permission to access this resource.</p>
            <a href="{{ route('login') }}" class="btn btn--primary">Back to sign in</a>
        </div>
    </div>
@endsection
