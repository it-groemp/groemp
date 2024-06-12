@component('mail::message')
    <h2>Hello {{$name}},</h2>
    <p>Welcome to {{config("app.name")}} Admin. Your passsword is updated for our Website. </p>
    <p>You can login to our Website by clicking the link<a href="{{config('app.url').'/admin/login'}}">{{config('app.name')}}</a></p>
    <p>In case of any queries, you can contact us on <a href="mailto:{{config('mail.from.address')}}?subject=Query">{{config('mail.from.address')}}</a></p>
    <h2>
        Thanks,<br>
        {{config('app.name')}} Team
    </h2>
@endcomponent