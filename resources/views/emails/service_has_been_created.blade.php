@component('mail::message')
# Congratulations, your service has been set-up at Hozumart!

Dear {{ $user->first_name }},<br>
<p>We are excited to have your service <strong>{{ $service->title }}</strong> listed on our platform. We consider it a great honour to be the platform you trust to help take your services to your clients.</p>
<p>Within the next couple of hours, we will approve your service so it will be visible on our website.</p>
<p>Kindly note that we may need to reach you through your email {{ $user->email }} or your phone number {{ $user->phone }}, if we have any questions.</p>
<p>Best regard,</p>

{{ config('app.name') }}
@endcomponent
