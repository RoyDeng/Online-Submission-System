<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav metismenu" id="side-menu">
			<li class="nav-header">
				<div class="dropdown profile-element"> <span>
						<img class="img-circle" src="{{ asset('img/profile_small.jpg') }}" />
							</span>
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user() -> firstname }} {{ Auth::user() -> middlename }} {{ Auth::user() -> lastname }}</strong>
							</span> <span class="text-muted text-xs block">Author <b class="caret"></b></span> </span> </a>
					<ul class="dropdown-menu animated fadeInRight m-t-xs">
						<li><a href="{{ url('author/conference/profile') }}/{{ $conference -> number }}">Profile</a></li>
						<li class="divider"></li>
						<li><a href="{{ url('author/logout') }}/{{ $conference -> number }}">Logout</a></li>
					</ul>
				</div>
				<div class="logo-element">
					
				</div>
			</li>
			<li>
				<a href="{{ url('author/conference') }}/{{ $conference -> number }}"><i class="fa fa-graduation-cap"></i> <span class="nav-label">Topics</span></a>
			</li>
			<li>
				<a href="{{ url('author/conference/manuscripts') }}/{{ $conference -> number }}"><i class="fa fa-file"></i> <span class="nav-label">Manuscripts</span></a>
			</li>
		</ul>
	</div>
</nav>
<div id="page-wrapper" class="gray-bg">
	<div class="row border-bottom">
		<nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
			<div class="navbar-header">
				<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
			</div>
			<ul class="nav navbar-top-links navbar-right">
				<li>
					<span class="m-r-sm text-muted welcome-message">Welcome to Online Submission and Review System.</span>
				</li>
				<li>
					<a href="{{ url('author/logout') }}/{{ $conference -> number }}">
						<i class="fa fa-sign-out"></i> Log out
					</a>
				</li>
			</ul>
		</nav>
	</div>
