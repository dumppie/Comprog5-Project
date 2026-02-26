@extends('layouts.app')
@section('title', 'Verify Email')
@section('content')
<div class="card">
    <h2>Verify Your Email</h2>
    <p>Thanks for registering. Before you can log in, please verify your email by clicking the link we sent to <strong>{{ auth()->user()->email }}</strong>.</p>
    <p>If you didn't receive the email or the link has expired, click below to resend.</p>
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Resend verification email</button>
    </form>
    <p style="margin-top:1rem;"><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></p>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
</div>
@endsection
