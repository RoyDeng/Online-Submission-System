@section('title','Author Login')

<!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>
	@include('partials.head')
</head>
<body class="gray-bg">
	<div class="middle-box text-center loginscreen">
		<div>
			<h3>Author Login</h3>
			<form class="m-t" role="form" action="{{ url('author/login') }}" method="post">
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
				<p class="text-muted text-center"><a href="{{url('author/forgot_password')}}"><small>Forgot password?</small></a></p>
				<p class="text-muted text-center"><a href="{{url('author/register')}}"><small>Do not have an account?</small></a></p>
			</form>
			<p class="m-t"> <small>Copyright © {{ date("Y") }} Chung Yuan Christian University All Rights</small> </p>
		</div>
	</div>
	@include('partials.footer')
</body>
</html>