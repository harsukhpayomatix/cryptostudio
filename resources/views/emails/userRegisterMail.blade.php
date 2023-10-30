@component('mail::message')
<p style="text-transform: capitalize;">Dear Valued Client,</p>
<p>Thank you for choosing {{ config('app.name') }} as your preferred processing partner.Your "Merchant Account"
    application has been successfully processed. To fully activate your account, please verify your registered email
    address by clicking the link below</p>
<a href="{{ route('user-activate',$token) }}" class="custom-btn">Verify your email</a>

<p>Please contact our customer support team if you require assistance during the verification process.</p>
<p>Thank you for choosing Finvert. We look forward to serving you.</p>
@endcomponent