@component('mail::message')
    <h2>Hello Sir/Madam,</h2>
        <p>Cost center for the company have been uploaded to our Website. </p>
        <p>Please click on the link <a href="{{$link}}">Approve Cost Center</a> to approve the same.</p>
    <h2>
        Thanks,<br>
        {{config('app.name')}} Team
    </h2>
@endcomponent