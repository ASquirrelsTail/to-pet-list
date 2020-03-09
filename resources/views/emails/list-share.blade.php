<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<h1>{{ $author->name }} has invited you to their To Pet List</h1>
	@if ($sharee)
		<p>Just <a href="{{ route('login') }}">log in</a> to To Pet List and you'll find the shared list along with all your other lists.</p>
	@else
		<p>Just <a href="{{ route('register') }}">sign up</a> to To Pet List with this email and you'll find the shared list waiting for you.</p>
	@endif
	<h2>Get petting!</h2>
	<p>See you soon!<br>The To Pet List Team</p>
</body>
</html>