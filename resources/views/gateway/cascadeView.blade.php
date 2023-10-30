<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Select payment method</title>
	</head>
	<body>
		<div class="container">
			<center>
		        <h1>Please do not refresh this page...</h1>
		    </center>
			<form action="{{ route('transactionRepo.gatewaySubmit', $session_id) }}" method="post" name="finvertpaymentform" id="finvertpaymentform">
				@csrf
				<input type="hidden" name="payment_gateway_id" value="{{ $payment_gateway_id }}" />
			</form>
		</div>
		<script type="text/javascript" src="{{ storage_asset('ThemeCryptostudio/js/jquery-latest.min.js') }}"></script>
	    <script type="text/javascript">
	        $(document).ready(function () {
	            document.finvertpaymentform.submit();
	        });
	    </script>
	</body>
</html>