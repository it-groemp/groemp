@component('mail::message')
    <h2>Hello Team,</h2>
        <p>{{$query}}</p>
    <h2>
        Thanks,<br>
        {{$name}}
    </h2>
@endcomponent