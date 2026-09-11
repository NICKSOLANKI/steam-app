@extends('front.loginfront')

@section('title', 'Login')

@section('Content')
<div class="containers">

    {{-- Left container: Login Form --}}
    <div class="container">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin-bottom:0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Login Form --}}
        <form class="form" method="POST" action="{{ route('login.post') }}">
            @csrf
            <br>
            <label class="about">PLEASE LOGIN</label><br>

            <h3 class="label1">Steam Email</h3>
            <input class="input" type="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div style="color:red;">{{ $message }}</div>
            @enderror
            <br><br>

            <h3 class="label2">Password</h3>
            <input class="input" type="password" name="password" required>
            @error('password')
                <div style="color:red;">{{ $message }}</div>
            @enderror

            {{-- Forgot password --}}
            <div style="margin-top:5px; text-align:center;">
                <a href="{{ route('forgot.password') }}"
                   style="display:inline-block; width:100%; max-width:300px; text-align:right; color:#00c0ff; text-decoration:underline; font-weight:500;">
                    Forgot Password?
                </a>
            </div>

            <br><br>
            <button class="submit" type="submit">SUBMIT</button>
        </form>

    </div>

    {{-- Right container: Register Promo --}}
    <div class="container2">
        <h3 class="label3">Join Steam and discover thousands of games you can play.</h3>
        <img class="img" src="https://store.cloudflare.steamstatic.com/public/shared/images/login/join_pc.png?v=1">
        <h3 class="label4">Easy to use and free to join.</h3>
        <a class="goregister" href="{{ route('register') }}">Join Steam</a>
    </div>

</div>
@endsection

@section('cs')
    <link rel="stylesheet" href="{{ asset('asset/css/login.css') }}">
@endsection
