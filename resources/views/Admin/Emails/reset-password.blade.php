<!DOCTYPE html>
<html>
<head>
	<title>Reset Password Email</title>
	<style type="text/css">
		.container {
			margin: 0 auto;
			max-width: 600px;
			padding: 20px;
			background-color: #f7f7f7;
			font-family: Arial, sans-serif;
		}

		h1 {
			color: #1c1c1c;
			font-size: 24px;
			margin-top: 0;
		}

		p {
			margin-top: 0;
		}

		.button {
			display: inline-block;
			padding: 10px 20px;
			background-color: #007bff;
			color: #ffffff;
			font-size: 16px;
			font-weight: bold;
			text-decoration: none;
			border-radius: 5px;
		}

		.footer {
			margin-top: 20px;
			padding-top: 20px;
			border-top: 1px solid #d8d8d8;
			font-size: 12px;
			color: #666666;
			text-align: center;
		}
	</style>
</head>
<body>
	<div class="container">
		<h1>Reset your password</h1>
		<p>Hi {{ $name }},</p>
		<p>Click the button below to reset your password:</p>
		<p><a href="{{ $url }}" class="button">Reset Password</a></p>
		<p>If you did not request a password reset, you can safely ignore this email.</p>
		<div class="footer">
			<p>This email was sent from {{ config('app.name') }}.</p>
			<p>Please do not reply to this email.</p>
		</div>
	</div>
</body>
</html>
