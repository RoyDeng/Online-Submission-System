<!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>
	@include('partials.head')
</head>
<body>
	<div id="wrapper">
		@section('content')
		@show
	</div>
	@include('partials.footer')
</body>
</html> 