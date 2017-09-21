@extends('layouts.master')

@section('title','Profile')

@section('content')
@include('conference.reviewer.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Profile</h2>
		<ol class="breadcrumb">
			<li class="active">
				<strong>Profile</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content">
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
	<div class="row">
        <div class="col-lg-12">
            <div class="tabs-container">
                <div class="tabs-left">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#edit-profile">Edit Profile</a></li>
                        <li class=""><a data-toggle="tab" href="#security-settings">Security Settings</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="edit-profile" class="tab-pane active">
                            <div class="panel-body">
                                <form class="form-horizontal" action="{{ url('conference/reviewer/edit_profile') }}" method="post">
                                    {{ csrf_field() }}
                                    <div class="form-group"></div>
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Title</label>
                                        <div class="col-sm-10">
                                            <select id="title" class="form-control m-b" name="title">
                                                <option value="Mr." {{Auth::user() -> title == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                                <option value="Mrs." {{Auth::user() -> title == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                                <option value="Ms." {{Auth::user() -> title == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                                <option value="Prof." {{Auth::user() -> title == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                                                <option value="Dr." {{Auth::user() -> title == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label for="firstname" class="col-sm-2 control-label">First Name</label>
                                        <div class="col-sm-10"><input id="firstname" type="text" class="form-control" name="firstname" value="{{Auth::user() -> firstname}}" required></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label for="middlename" class="col-sm-2 control-label">Middle Name</label>
                                        <div class="col-sm-10"><input id="middlename" type="text" class="form-control" name="middlename" value="{{Auth::user() -> middlename}}"></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label for="lastname" class="col-sm-2 control-label">Last Name</label>
                                        <div class="col-sm-10"><input id="lastname" type="text" class="form-control" name="lastname" value="{{Auth::user() -> lastname}}" required></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label for="tel" class="col-sm-2 control-label">Telephone</label>
                                        <div class="col-sm-10"><input id="tel" type="text" class="form-control" name="tel" value="{{Auth::user() -> tel}}"></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label for="institution" class="col-sm-2 control-label">Institution</label>
                                        <div class="col-sm-10"><input id="institution" type="text" class="form-control" name="institution" value="{{Auth::user() -> institution}}" required></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label for="country" class="col-sm-2 control-label">Country</label>
                                        <div class="col-sm-10"><input id="country" type="text" class="form-control" name="country" value="{{Auth::user() -> country}}" required></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary" type="submit">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="security-settings" class="tab-pane">
                            <div class="panel-body">
                                <form class="form-horizontal" action="{{ url('conference/reviewer/change_password') }}" method="post">
                                    {{ csrf_field() }}
                                    <div class="form-group"></div>
                                    <div class="form-group">
                                        <label for="current-password" class="col-sm-2 control-label">Current Password</label>
                                        <div class="col-sm-10"><input id="current-password" type="text" class="form-control" name="current_password" required></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label for="new-password" class="col-sm-2 control-label">New Password</label>
                                        <div class="col-sm-10"><input id="new-password" type="text" class="form-control" name="new_password" required></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label for="confirm-password" class="col-sm-2 control-label">Confirm Password</label>
                                        <div class="col-sm-10"><input id="confirm-password" type="text" class="form-control" name="confirm_password" required></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary" type="submit">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
		<div>
			Copyright © {{ date("Y") }} Chung Yuan Christian University All Rights
		</div>
	</div>
</div>
@stop