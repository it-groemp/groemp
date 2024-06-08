@component('mail::message')
    <h2>Hello Sir/Madam,</h2>
        <p>Employees Benefits have been uploaded for the month to our Website. </p>
        <p>Please click on the link <a href="{{$link}}">Approve EmployeesBenefits</a> to approve the same.</p>
    <h2>
        Thanks,<br>
        {{config('app.name')}} Team
    </h2>
@endcomponent