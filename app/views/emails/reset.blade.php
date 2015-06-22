<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	Hi {{$data['user']->first_name}} {{$data['user']->last_name}},<br/><br/>
	We have received a reuqest to reset your password.<br />
	If that was send by you then please the code below to reset your password.<br/><br/>
	Please visit {{$data['resetUrl']}} and use this code  {{$data['code']}} to reset your password.
	</body>
</html>