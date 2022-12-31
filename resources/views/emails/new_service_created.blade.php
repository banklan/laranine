@component('mail::message')
# A new service has been created.

<p>A new service, <strong>{{ $service->title }}</strong> has been created on Hozumart.
Kindly review the service and approve.</p>
<p>If there are any questions, kindly reach the user via the email address {{ $service->email }} or phone number {{ $service->phone }}.</p>
<p>Kind regards,</p>


{{ config('app.name') }}
@endcomponent
