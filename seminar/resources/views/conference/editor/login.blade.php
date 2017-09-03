@section('title','Editor Login')

<!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>
	@include('partials.head')
</head>
<body class="gray-bg">
    <div class="loginColumns">
        <div class="row">
            <div class="col-md-6">
                <h2 class="font-bold">Welcome to {{$topic -> title}}</h2>
                <h3>Editor Login</h3>
            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <form class="m-t" role="form" action="{{ url('conference/editor/login') }}" method="post">
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
                        <input type="hidden" name="number" value="{{$topic -> number}}">
                        <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
                        <a href="{{ url('conference/editor/forgot_password') }}/{{$topic -> number}}">
                            <small>Forgot password?</small>
                        </a>
                    </form>
                    <p class="m-t"> <small>Copyright © {{ date("Y") }} Chung Yuan Christian University All Rights</small> </p>
                </div>
            </div>
        </div>
    </div>
	@include('partials.footer')
</body>
</html>