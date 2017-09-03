@section('title','The Page is Expired!')

<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
	@include('partials.head')
</head>
<body class="gray-bg">
    <div class="middle-box text-center">
        <h1>Oops!</h1>
        <h3 class="font-bold">The conference is expired!</h3>
        <div class="error-desc">
            The conference has been closed.<br>
            Please let me know if you have any questions: <br><a href="{{ url('contact') }}" class="btn btn-primary m-t">Contact Us</a>
        </div>
    </div>
    @include('partials.footer')
</body>
</html>