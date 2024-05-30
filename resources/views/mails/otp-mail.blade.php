@component('mail::message')
    <h2>Hello {{$name}},</h2>
        <p>Thank you for registering with us. Please enter the 6 digit OTP below for Email Verification.<p>
        <h1 style="text-align: center; font-size: xx-large;">{{$otp}}</h1>
        <p>If you didn't request this, please ignore this email.</p>
    <h2>
        Thanks,<br>
        {{config('app.name')}} Team
    </h2>
@endcomponent