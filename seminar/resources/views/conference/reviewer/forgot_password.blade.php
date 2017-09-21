@section('title','Reviewer Forgot Password')
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
	@include('partials.head')
</head>
<body class="gray-bg">
    <div class="passwordBox">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox-content">
                    <h2 class="font-bold">Reviewer Forgot Password</h2>
                    <p>
                        Enter your email and your password will be reset and emailed to you.
                    </p>
                    <div class="row">
                        <div class="col-lg-12">
                            <form class="m-t" role="form" action="{{ url('conference/reviewer/reset_password') }}" method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                                </div>
                                <input type="hidden" name="conference_id" value="{{$conference -> id}}">
                                <button type="submit" class="btn btn-primary block full-width m-b">Send</button>
                            </form>
                        </div>
                    </div>
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
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-12">
                Copyright © {{ date("Y") }} Chung Yuan Christian University All Rights
            </div>
        </div>
    </div>
    @include('partials.footer')
</body>
</html>