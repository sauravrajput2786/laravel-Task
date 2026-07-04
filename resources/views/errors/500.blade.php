@extends('layouts.app')

@section('title', 'Something went wrong')

@section('content')
    <div class="error-page">
        <div class="card error-card">
            <h1 class="error-code">500</h1>
            <p class="error-message">
                Something went wrong on our end. The issue has been logged and we're looking into it.
            </p>
            <a href="{{ route('login') }}" class="btn btn--primary">Back to sign in</a>
        </div>
    </div>
@endsection
