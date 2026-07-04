@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard">
        <div class="card">
            <div class="card__header">
                <h1>Welcome, {{ $user->name }}</h1>
                <p class="muted">You are signed in to the <strong>{{ $client?->client_name }}</strong> workspace.</p>
            </div>

            <x-alert />

            <dl class="detail-list">
                <div class="detail-list__row">
                    <dt>Name</dt>
                    <dd>{{ $user->name }}</dd>
                </div>
                <div class="detail-list__row">
                    <dt>Email</dt>
                    <dd>{{ $user->email }}</dd>
                </div>
                <div class="detail-list__row">
                    <dt>Tenant / Client code</dt>
                    <dd><span class="badge">{{ $client?->client_code }}</span></dd>
                </div>
                <div class="detail-list__row">
                    <dt>Tenant database</dt>
                    <dd><code>{{ tenant_database_name() }}</code></dd>
                </div>
                <!-- <div class="detail-list__row">
                    <dt>Session started</dt>
                    <dd>{{ now()->format('d M Y, H:i') }}</dd>
                </div> -->
                <!-- @if ($apiToken)
                    <div class="detail-list__row">
                        <dt>API token</dt>
                        <dd>
                            <code class="token-value">{{ $apiToken }}</code>
                            <p class="muted small">Shown once - copy it now. Use it as a Bearer token on
                                <code>/api/*</code> requests together with header
                                <code>X-Client-Code: {{ $client?->client_code }}</code>.</p>
                        </dd>
                    </div>
                @endif -->
            </dl>

            <form action="{{ route('logout') }}" method="POST" class="dashboard__logout">
                @csrf
                <button type="submit" class="btn btn--danger">Logout</button>
            </form>
        </div>
    </div>
@endsection
