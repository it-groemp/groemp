@component('mail::message')
    <h2>Hello {{$name}},</h2>
    <p>Welcome to {{config("app.name")}} Admin. This login is for the Admin section of our Website <a href="{{config('app.url')}}">{{config('app.name')}}</a></p>
    <p>To set the password to your login, you can click on this link <a href="{{$link}}">Set Password</a></p>
    <p>In case of any queries, you can contact us on <a href="mailto:{{config('app.contact')}}?subject=Query">{{config('app.contact')}}</a></p>
    <h2>
        Thanks,<br>
        {{config('app.name')}} Team
    </h2>
@endcomponent