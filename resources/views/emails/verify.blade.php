<p>Hello,</p>
<p>Click the button below to verify your email:</p>
<a href="{{ url('/verify-email?email='.$email.'&token='.$token) }}"
   style="padding:10px;background:#1b2838;color:#fff;text-decoration:none;">
   Verify Email
</a>
