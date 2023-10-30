@component('mail::message')
<p style="text-transform: capitalize;">Hi,</p>
<p>Thank you for choosing {{ config('app.name') }}.</p>
<p>You have successfully signed up with us. Kindly activate your account by clicking on the link below.</p>
<a href="{{ route('agent-activate',$token) }}" class="custom-btn">Verify your email</a>
@endcomponent