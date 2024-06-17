@component('mail::message')
    <h2>Hello {{$name}},</h2>
    <p>We have processed your request of resetting your password.</p>
    <p>If you didn't request this, please contact our team on <a href="mailto:{{config('app.contact')}}?subject=Password Reset Query">{{config('app.contact')}}</a></p>
    <h2>
        Thanks,<br>
        {{config('app.name')}} Team
    </h2>
@endcomponent