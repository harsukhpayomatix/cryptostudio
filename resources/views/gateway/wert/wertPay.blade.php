<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Crypto-Studio Crypto Payment</title>
</head>

<body>

    {{-- CDN & JavaScript files used in wert.io Start --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.2/uuid.min.js"></script>
    <script src="https://javascript.wert.io/wert-6.1.0.js"></script>
    {{-- CDN & JavaScript files used in wert.io End --}}

    {{-- Wert Widget Start --}}
    <script>
        const walletInput = @json($wallet_input);
        const sessionId = @json($id);


        const widget = new window.WertWidget({
            partner_id: walletInput.partnerId || '',
            origin: walletInput.origin || '',
            click_id: walletInput.clickId || '',
            currency: walletInput.currency || '',
            full_name: walletInput.full_name || '',
            email: walletInput.email || '',
            country_of_residence: walletInput.country_of_residence || '',
            // phone: walletInput.phone || '',
            // webhook_url: walletInput.webhook_url || '',
            // redirect_url: 'https://origin.us/item_id',

            // listeners: {
            //     position: (data) => console.log("step:", data.step),
            //     'payment-status': data => console.log('Payment status:', data)
            // }
            listeners: {
                position: (data) => console.log("step:", data.step),
                'payment-status': data => {
                    console.log('Payment status:', data);
                    if (data.status === 'success' || data.status === 'failed'  || data.status === 'canceled' || data.status === 'failover') {
                        // Redirect the user upon successful payment
                        const sessionId = @json($id);
                        const callbackUrl = '{{ route('Wert-callback', ['id' => ':sessionId']) }}';
                        const redirectUrl = callbackUrl.replace(':sessionId', sessionId);
                        window.location.href = redirectUrl;
                    }
                }
            },
        });
        widget.open();
    </script>
    {{-- Wert Widget End --}}
</body>

</html>
