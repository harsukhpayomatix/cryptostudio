<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout Page</title>
</head>

<body>
    <h1>Do not refresh the page.</h1>
    <script src="https://javascript.wert.io/wert-6.1.0.js"></script>

    <script>
        console.log("The payload ", "{{ $payload['partner_id'] }}")
        const wertWidget = new window.WertWidget({
            partner_id: "{{ $payload['partner_id'] }}",
            phone: "{{ $payload['phone'] }}",
            email: "{{ $payload['email'] }}",
            full_name: "{{ $payload['full_name'] }}",
            date_of_birth: "{{ $payload['date_of_birth'] }}",
            country_of_residence: "{{ $payload['country_of_residence'] }}",
            listeners: {
                'payment-status': data => console.log('Payment status:', data),
                'error': data => console.log("The error is", error)
            },
        });

        wertWidget.open()
    </script>
</body>

</html>
