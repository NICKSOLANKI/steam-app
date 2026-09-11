@extends('front.loginfront')

@section('title', 'Forgot Password')

@section('Content')
<style>
    .steam-auth-container {
        background: linear-gradient(135deg, #1b2838 0%, #2a475e 100%);
        border-radius: 8px;
        padding: 40px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.6);
        max-width: 500px;
        margin: 50px auto;
    }
    
    .steam-auth-container h3 {
        color: #ffffff;
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 25px;
        text-align: center;
    }
    
    .steam-btn {
        background: linear-gradient(90deg, #06BFFF 0%, #2D73FF 100%);
        color: #ffffff;
        border: none;
        padding: 12px 30px;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        width: 100%;
        text-align: center;
        margin-bottom: 15px;
    }
    
    .steam-btn:hover {
        background: linear-gradient(90deg, #1a9fd8 0%, #2563d4 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(6, 191, 255, 0.4);
    }
    
    .steam-btn-secondary {
        background: #67c1f5;
        color: #1b2838;
    }
    
    .steam-btn-secondary:hover {
        background: #4fa9d8;
        color: #1b2838;
    }
    
    .alert {
        background: rgba(255, 76, 76, 0.15);
        border: 1px solid #ff4c4c;
        color: #ff9999;
        padding: 12px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
</style>

<div class="steam-auth-container">
    <h3>🔐 Password Recovery</h3>

    @if(session('error'))
        <div class="alert">{{ session('error') }}</div>
    @endif

    <p style="color: #c7d5e0; text-align: center; margin-bottom: 30px;">Choose your preferred recovery method</p>

    <a href="{{ route('forgot.password.otp') }}" class="steam-btn">
        📧 Use Email OTP
    </a>
    <a href="{{ route('forgot.password.old') }}" class="steam-btn steam-btn-secondary">
        🔑 Use Old Password
    </a>
</div>
@endsection
