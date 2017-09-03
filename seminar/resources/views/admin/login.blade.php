@section('title','Mintainer Login')

<!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>
	@include('partials.head')
</head>
<body class="gray-bg">
	<div class="middle-box text-center loginscreen">
		<div>
			<h3>Mintainer Login</h3>
			<form class="m-t" role="form"  action="{{ url('admin/login') }}" method="post">
				{{ csrf_field() }}
				<div class="form-group">
					<input type="email" class="form-control" name="email" placeholder="Email" required>
					@if ($errors -> has('email'))
						<span class="help-block">
							<strong>{{ $errors -> first('email') }}</strong>
						</span>
					@endif
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="password" placeholder="Password" required>
					@if ($errors -> has('password'))
						<span class="help-block">
							<strong>{{ $errors -> first('password') }}</strong>
						</span>
					@endif
				</div>
				<button type="submit" class="btn btn-primary block full-width m-b">Login</button>
				<a href="{{ url('admin/forgot_password') }}"><small>Forgot password?</small></a>
			</form>
			<p class="m-t"> <small>Copyright © {{ date("Y") }} Chung Yuan Christian University All Rights</small> </p>
		</div>
	</div>
	@include('partials.footer')
</body>
</html>