@extends('layouts.base')
@section('title', 'Verify Email')
@section('body')
    @include('layouts.flash-messages')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card card-pastry">
                    <div class="card-body p-4 p-md-5 text-center">
                        <i class="fas fa-envelope-open-text fa-3x mb-3" style="color: var(--pastry-caramel);"></i>
                        <h2 class="font-serif mb-3" style="color: var(--pastry-brown);">Verify Your Email</h2>
                        <p class="mb-3">We sent a verification link to <strong>{{ auth()->user()->email }}</strong>. Click it to activate your account.</p>
                        <p class="text-muted small mb-4">If you didn't receive it or the link expired, click below to resend.</p>
                        <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-pastry">Resend verification email</button>
                        </form>
                        <p class="mt-4 mb-0">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </p>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
