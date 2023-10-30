@component('mail::message')
<p style="text-transform: capitalize;">Dear Valued Client,</p>
<p>Greetings! We hope this message finds you well. Thank you for accepting the Fee Schedule. We are pleased to share our
	Service Level Agreement (SLA) for your review with respect to the accepted Fee schedule.</p>

<p>To proceed with the onboarding process, we require your signatures/initials on the last three pages of the SLA.
	Please refer to 'Annexure-I' for complete information on Charges and Fee Schedule.</p>

<p>To upload the signed agreement directly on the portal, please click on the button below:</p>
<a href="{!! $url !!}" target="_blank" class="custom-btn">Upload Agreement</a>

<p>Upon receipt of the signed SLA, we will initiate the onboarding process. Thank you for choosing {{
	config('app.name') }} as your payment processing partner.</p>

<p>Please feel free to contact our dedicated customer support team if you require any further assistance.</p>


@endcomponent