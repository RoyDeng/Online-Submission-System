@section('title','Chair Login')

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
                <h2 class="font-bold">Welcome to {{$conference -> title}}</h2>
                <h3>Chair Login</h3>
                <p>To change the login role, select an available role from the list.</p>
                <a class="btn btn-primary block full-width m-b" href="{{url('conference/author/login')}}/{{$conference -> number}}">Author Login</a>
                <a class="btn btn-primary block full-width m-b" href="{{url('conference/reviewer/login')}}/{{$conference -> number}}">Reviewer Login</a>
                <a class="btn btn-primary block full-width m-b" href="{{url('conference/editor/login/topics')}}/{{$conference -> number}}">Editor Login</a>
            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <form class="m-t" role="form" action="{{ url('conference/chair/login') }}" method="post">
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
                        <input type="hidden" name="number" value="{{$conference -> number}}">
                        <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
                        <a href="{{ url('conference/chair/forgot_password') }}/{{$conference -> number}}">
                            <small>Forgot password?</small>
                        </a>
                    </form>
                    <p class="m-t"> <small>Copyright © {{ date("Y") }} Chung Yuan Christian University All Rights</small> </p>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-12">
                Contact: <i class="fa fa-envelope"></i> {{$maintainer -> email}}
            </div>
        </div>
    </div>
	@include('partials.footer')
</body>
</html>