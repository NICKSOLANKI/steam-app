@extends('front.loginfront')

@section('title', 'Set New Password')

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
        margin-bottom: 10px;
        text-align: center;
    }
    
    .steam-auth-container label {
        color: #c7d5e0;
        font-size: 14px;
        font-weight: 500;
        display: block;
        margin-bottom: 8px;
        margin-top: 15px;
    }
    
    .steam-auth-container input[type="password"],
    .steam-auth-container input[type="email"],
    .steam-auth-container input[type="text"] {
        width: 100%;
        padding: 12px;
        background: #32465a;
        border: 1px solid #417a9b;
        border-radius: 4px;
        color: #ffffff;
        font-size: 16px;
        transition: all 0.3s ease;
    }
    
    .steam-auth-container input:focus {
        outline: none;
        border-color: #67c1f5;
        background: #3d5a73;
        box-shadow: 0 0 8px rgba(103, 193, 245, 0.3);
    }
    
    .steam-btn {
        background: linear-gradient(90deg, #06BFFF 0%, #2D73FF 100%);
        color: #ffffff;
        border: none;
        padding: 14px 30px;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 25px;
    }
    
    .steam-btn:hover {
        background: linear-gradient(90deg, #1a9fd8 0%, #2563d4 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(6, 191, 255, 0.4);
    }
    
    .alert {
        padding: 12px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    
    .alert-success {
        background: rgba(76, 255, 76, 0.15);
        border: 1px solid #4cff4c;
        color: #99ff99;
    }
    
    .alert-danger {
        background: rgba(255, 76, 76, 0.15);
        border: 1px solid #ff4c4c;
        color: #ff9999;
    }
    
    .alert ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .email-badge {
        background: rgba(103, 193, 245, 0.2);
        color: #67c1f5;
        padding: 8px 16px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 25px;
        font-size: 14px;
    }
</style>

<div class="steam-auth-container">
    <h3>🔒 Set New Password</h3>
    <div style="text-align: center;">
        <span class="email-badge">{{ $email }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('forgot.password.newpass.post') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        
        <label>New Password:</label>
        <input type="password" name="new_password" required placeholder="Enter new password">
        
        <label>Confirm Password:</label>
        <input type="password" name="new_password_confirmation" required placeholder="Confirm new password">
        
        <button type="submit" class="steam-btn">Change Password</button>
    </form>
</div>
@endsection
