@extends('layouts.auth')

@section('content')
<style>
    #wave-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
}

#wave-background svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>
{{-- Background animasi gelombang --}}
<div id="wave-background">
    <svg viewBox="0 0 1200 200" preserveAspectRatio="none">
        <path d="M0,0 C300,200 900,0 1200,200 L1200,0 L0,0 Z" fill="#0099ff">
            <animate attributeName="d" dur="6s" repeatCount="indefinite" values="
                M0,0 C300,200 900,0 1200,200 L1200,0 L0,0 Z;
                M0,0 C400,100 800,100 1200,0 L1200,0 L0,0 Z;
                M0,0 C300,200 900,0 1200,200 L1200,0 L0,0 Z
            " />
        </path>
    </svg>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center position-relative">
    <div class="row justify-content-center w-100">
        <div class="col-xl-6 col-lg-6 col-md-9">

            <div class="card o-hidden border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="text-center">
                        {{-- Logo --}}
                        <img src="{{ asset('sbadmin/img/logo_esa.png') }}" alt="Logo" class="mb-3" style="max-width: 150px;">
                        
                        <h1 class="h4 text-gray-900 mb-4">Selamat Datang!</h1>
                    </div>
{{-- Menampilkan pesan error validasi atau login --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

                   <form method="POST" action="{{ route('login') }}">
    @csrf
                        <div class="form-group">
                            <input type="email" name="email" class="form-control form-control-user" placeholder="Masukkan Email..." required autofocus>
                        </div>

                        <div class="form-group">
                            <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Login
                        </button>
                    </form>

                    <hr>
                    <div class="text-center">
                        <a class="small" href="{{ route('password.request') }}">Lupa Password?</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
