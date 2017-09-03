@section('title','Contact Us')
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
                    <h2 class="font-bold">Contact Us</h2>
                    <p>
                        If you have any further queries, please do not hesitate to contact us.
                    </p>
                    <div class="row">
                        <div class="col-lg-12">
                            <form class="m-t" role="form" action="{{ url('contact') }}" action="post">
                                <div class="form-group">
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
                                <div class="form-group">
                                    <textarea class="form-control" name="content" placeholder="Content" rows="10" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary block full-width m-b">Submit</button>
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