@extends('front.gamefront')

@section('title', 'Steam Support Center')

@section('Content')
<style>
    body {
        background-color: #1b1f2a;
        color: #dcdedf;
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        padding: 0;
    }

    .support-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    .support-header {
        background: linear-gradient(135deg, #1B3B59 0%, #0B1A2A 100%);
        padding: 40px 30px;
        text-align: center;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .support-header h1 {
        font-size: 36px;
        color: #66c0f4;
        margin-bottom: 10px;
    }

    .support-header p {
        color: #b8bcbf;
        font-size: 18px;
    }

    .problem-list {
        background-color: #16202D;
        border-radius: 8px;
        padding: 30px;
        margin-bottom: 30px;
        border: 1px solid #2A3F5A;
    }

    .problem-item {
        margin-bottom: 25px;
        padding-bottom: 25px;
        border-bottom: 1px solid #2A3F5A;
    }

    .problem-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .problem-title {
        color: #66C0F4;
        font-size: 20px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .solution {
        background-color: rgba(102, 192, 244, 0.1);
        padding: 15px;
        border-radius: 6px;
        margin-top: 15px;
        border-left: 3px solid #66C0F4;
    }

    .solution h4 {
        color: #66C0F4;
        margin-bottom: 10px;
    }

    .solution-steps {
        padding-left: 20px;
    }

    .solution-steps li {
        margin-bottom: 8px;
    }

    .contact-card {
        background-color: #1E4361;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        border: 1px solid #2A3F5A;
    }

    .contact-card h2 {
        color: white;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .contact-card p {
        color: #B8BCBF;
        margin-bottom: 20px;
    }

    .contact-info {
        background-color: rgba(255,255,255,0.1);
        padding: 15px;
        border-radius: 6px;
        margin-top: 20px;
        display: inline-block;
    }

    .contact-info div {
        margin-bottom: 10px;
    }

    .contact-info a {
        color: #66C0F4;
        text-decoration: none;
    }
</style>

<div class="support-container">
    <div class="support-header">
        <h1>Steam Support Center</h1>
        <p>Common problems and their solutions</p>
    </div>

    <div class="problem-list">
        <div class="problem-item">
            <h3 class="problem-title"><i class="fas fa-gamepad"></i> Game won't launch</h3>
            <p>Many users experience issues when trying to launch games</p>
            
            <div class="solution">
                <h4>Solution:</h4>
                <ol class="solution-steps">
                    <li>Right-click the game in your Steam library and select Properties</li>
                    <li>Go to the Local Files tab and click "Verify integrity of game files"</li>
                    <li>Wait for the process to complete, then try launching again</li>
                    <li>If still not working, update your graphics drivers</li>
                </ol>
            </div>
        </div>

        <div class="problem-item">
            <h3 class="problem-title"><i class="fas fa-shopping-cart"></i> Refund not processed</h3>
            <p>Having trouble getting your refund approved?</p>
            
            <div class="solution">
                <h4>Solution:</h4>
                <ol class="solution-steps">
                    <li>Go to Steam Support and select "Purchases"</li>
                    <li>Find the game you want to refund and select "I would like a refund"</li>
                    <li>Make sure your request meets the requirements (played less than 2 hours and owned less than 14 days)</li>
                    <li>Refunds typically process within 7 days</li>
                </ol>
            </div>
        </div>

        <div class="problem-item">
            <h3 class="problem-title"><i class="fas fa-user"></i> Can't log in to account</h3>
            <p>Forgot your password or Steam Guard issues?</p>
            
            <div class="solution">
                <h4>Solution:</h4>
                <ol class="solution-steps">
                    <li>Click "Forgot your password?" on the login page</li>
                    <li>Enter your account name or email associated with the account</li>
                    <li>Check your email for the password reset link</li>
                    <li>If you lost access to your email, you'll need to contact support</li>
                </ol>
            </div>
        </div>

        <div class="problem-item">
            <h3 class="problem-title"><i class="fas fa-desktop"></i> Steam client crashes</h3>
            <p>Steam keeps closing unexpectedly</p>
            
            <div class="solution">
                <h4>Solution:</h4>
                <ol class="solution-steps">
                    <li>Exit Steam completely (right-click the icon in system tray and Exit)</li>
                    <li>Navigate to your Steam installation folder (usually C:\Program Files (x86)\Steam)</li>
                    <li>Delete everything EXCEPT the "steamapps" folder and "Steam.exe"</li>
                    <li>Run Steam.exe to rebuild the client files</li>
                </ol>
            </div>
        </div>

        <div class="problem-item">
            <h3 class="problem-title"><i class="fas fa-download"></i> Download stuck or slow</h3>
            <p>Downloads not progressing or very slow speeds</p>
            
            <div class="solution">
                <h4>Solution:</h4>
                <ol class="solution-steps">
                    <li>Go to Steam > Settings > Downloads</li>
                    <li>Click "Download Region" and try a different server location</li>
                    <li>Pause and resume the download</li>
                    <li>Check your internet connection and disable any VPNs</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="contact-card">
        <h2>Still Need Help?</h2>
        <p>If none of these solutions worked for you, please contact me directly:</p>
        
        <div class="contact-info">
            <div><i class="fas fa-phone"></i> Phone: <a href="tel:+1234567890">+91 8320331245</a></div>
            <div><i class="fas fa-envelope"></i> Email: <a href="mailto:support@gamestore.com">dhavalsolanki615@gmail.com</a></div>
            <div><i class="fas fa-clock"></i> Available: Mon-Fri, 9AM-5PM EST</div>
        </div>
    </div>
</div>
@endsection