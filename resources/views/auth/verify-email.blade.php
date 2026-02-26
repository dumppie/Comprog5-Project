@extends('layouts.base')
@section('title', 'Verify Email')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Verify Your Email</h2>
                <p class="card-text">Thanks for registering. Before you can log in, please verify your email by clicking the link we sent to <strong>{{ auth()->user()->email }}</strong>.</p>
                <p class="card-text">If you didn't receive the email or the link has expired, click below to resend.</p>
                <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">Resend verification email</button>
                </form>
                <p class="mt-3 mb-0">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </p>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>
    </div>
@endsection
