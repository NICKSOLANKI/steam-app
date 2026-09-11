@extends('front.loginfront')

@section('title', 'Register')

@section('Content')
<div class="containers">
    <div class="container">

        {{-- Show session success --}}
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Show session error --}}
        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- Show validation errors --}}
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Registration Form --}}
        <form class="form" method="POST" action="{{ route('register.post') }}">
            @csrf
            <label class="about">CREATE YOUR ACCOUNT</label><br><br>

            {{-- Name --}}
            <label class="label one">Write Your Username</label>
            <input class="input" type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <br><br>

            {{-- Email --}}
            <label class="label two">Write your e-mail</label>
            <input class="input" type="email" name="email" value="{{ old('email') }}">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <br><br>

            {{-- Confirm Email --}}
            <label class="label three">Write your e-mail again</label>
            <input class="input" type="email" name="email_confirmation" value="{{ old('email_confirmation') }}">
            @error('email_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <br><br>

            {{-- Password --}}
            <label class="label four">Write your password</label>
            <input class="input" type="password" name="password">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <br><br>

            {{-- Confirm Password --}}
            <label class="label five">Write your password again</label>
            <input class="input" type="password" name="password_confirmation">
            @error('password_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <br><br>

            <button class="submit" type="submit">REGISTER</button>
        </form>
    </div>
</div>
@endsection

@section('cs')
    <link rel="stylesheet" href="{{ asset('asset/css/register.css') }}">
@endsection
