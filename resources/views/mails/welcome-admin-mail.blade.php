@component('mail::message')
    <h2>Hello {{$name}},</h2>
    <p>Welcome to {{config("app.name")}}. You have been registered with our Company to provide numerous benefits for your employees. </p>
    <p>To set the password to your account, you can click on this link <a href="{{$link}}">Set Password</a></p>
    <p>In case of any queries, you can contact us on <a href="mailto:{{config('app.contact')}}?subject=Query">{{config('app.contact')}}</a></p>
    <h2>
        Thanks,<br>
        {{config('app.name')}} Team
    </h2>
@endcomponentcomponent