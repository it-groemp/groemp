@component('mail::message')
    <h2>Hello Sir/Madam,</h2>
        <p>Benefits for the company have been {{$function}} have been added. </p>
        <p>Please click on the link <a href="{{$link}}">Approve Company Selected Benefits</a> to approve the same so that the Employees can avail the benefit.</p>
    <h2>
        Thanks,<br>
        {{config('app.name')}} Team
    </h2>
@endcomponent