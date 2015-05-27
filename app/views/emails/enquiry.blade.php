<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h3>Web site enquiry</h3>
		<h4>{{$data['name']}} {{$data['surname']}}, {{$data['email']}} </h4>
		<div>
			<b>Report a {{$data['issue']}}</b>
		</div>
		<p></p>
		<p></p>
		<div>
			{{$data['message']}}

		</div>
		<div style="font-size: 10px;">
		<br/><br/><br/><br/>
		Received {{$data['timestamp']}}<br />
		IP address <a href="http://whatismyipaddress.com/ip/{{$data['remote_addr']}}">{{$data['remote_addr']}}</a><br/>
		User agent {{$data['user_agent']}}<br/>
		</div>
	</body>
</html>