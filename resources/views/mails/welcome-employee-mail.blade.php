@component('mail::message')
    <h2>Hello {{$name}},</h2>
    <p>Welcome to {{config("app.name")}}. Your company has registered with us to help you earn benefits on your allowances. </p>
    <p>To set the password to your account, you can click on this link <a href="{{$link}}">Set Password</a></p>
    <p>In case of any queries, you can contact us on <a href="mailto:{{config('app.contact')}}?subject=Query">{{config('app.contact')}}</a></p>
    <h2>
        Thanks,<br>
        {{config('app.name')}} Team
    </h2>
@endcomponent