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
            <h2 class="font-bold">Welcome to {{$conference -> title}}</h2>
            <h3>Topics</h3>
			<div class="list-group">
                @foreach ($conference -> topic -> where('status', 1) as $t => $topic)
                    <a class="list-group-item" href="{{url('conference/editor/login')}}/{{ $topic -> number }}">
                        <h3 class="list-group-item-heading">{{ $topic -> title }}</h3>
                    </a>
                @endforeach
			</div>
			<p class="m-t"> <small>Copyright © {{ date("Y") }} Chung Yuan Christian University All Rights</small> </p>
		</div>
	</div>
	@include('partials.footer')
</body>
</html>