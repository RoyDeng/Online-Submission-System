@section('title','Author Registration')

<!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>
	@include('partials.head')
</head>
<body class="gray-bg">
	<div class="middle-box text-center loginscreen">
		<div>
			<h3>Author Registration</h3>
			<form class="m-t" role="form" action="{{ url('author/register') }}" method="post">
				{{ csrf_field() }}
				<div class="form-group">
					<label class="form-label" for="title">Title</label>
					<div class="controls">
						<select id="title" class="form-control" name="title" required>
							<option value="Mr.">Mr.</option>
							<option value="Mrs.">Mrs.</option>
							<option value="Ms.">Ms.</option>
							<option value="Prof.">Prof.</option>
							<option value="Dr.">Dr.</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<input type="email" class="form-control" name="email" placeholder="Email" required>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="password" placeholder="Password" required>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="firstname" placeholder="First Name" required>
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="middlename" placeholder="Middle Name">
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="lastname" placeholder="Last Mame" required>
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="tel" placeholder="Telephone" required>
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="institution" placeholder="Institution" required>
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="country" placeholder="Country" required>
				</div>
				<button type="submit" class="btn btn-primary block full-width m-b">Register</button>
				<a href="{{url('author/login')}}"><small>Already have an account?</small></a>
			</form>
			@if ($msg = Session::get('success'))
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-success alert-dismissible fade in">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
							<strong>{{ $msg }}</strong>
						</div>
					</div>
				</div>
			@endif
			@if ($msg = Session::get('warn'))
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-warning alert-dismissible fade in">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
							<strong>{{ $msg }}</strong>
						</div>
					</div>
				</div>
			@endif
			@if ($msg = Session::get('danger'))
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-danger alert-dismissible fade in">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
							<strong>{{ $msg }}</strong>
						</div>
					</div>
				</div>
			@endif
			<p class="m-t"> <small>Copyright © {{ date("Y") }} Chung Yuan Christian University All Rights</small> </p>
		</div>
	</div>
	@include('partials.footer')
</body>
</html>