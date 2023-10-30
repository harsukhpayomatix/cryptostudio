@component('mail::message')
<p style="text-transform: capitalize;">Dear Valued Client,</p>
<p>We are excited to inform you that your account with {{ config('app.name') }} has been pre-approved! Please click the
    link below for more
    information:</p>

@component('mail::button', ['url' => $url])
Application
@endcomponent

<p>For any further assistance or questions, please contact our dedicated customer support team.</p>
Thank you for choosing {{ config('app.name') }} as your payment processing partner.
@endcomponent