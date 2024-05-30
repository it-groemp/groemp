@component('mail::message')
    <h2>Hello {{$name}},</h2>
        <p>We heard that you lost your {{config('app.name')}} password. Sorry to hear about that!</p>
        <p>To reset the same, you can click on this link and change your password <a href="{{$link}}">Reset Password Link</a></p>
    <h2>
        Thanks,<br>
        {{config('app.name')}} Team
    </h2>
@endcomponent