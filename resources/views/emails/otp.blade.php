@component('mail::message')
<p style="text-transform: capitalize;">Dear valued client,</p>
<p>We hope this message finds you well. You are just one step away from accessing your <strong>{{ config('app.name')
        }}</strong> account.
    Please use the OTP provided below to continue with your application process:</p>
<p>OTP : <strong>{{ $user->otp }}</strong></p>

<p>Thank you for choosing <strong>{{ config('app.name') }}</strong> as your preferred payment processing partner. Please
    contact our
    dedicated customer support team if you have any questions or concerns.</p>
@endcomponent