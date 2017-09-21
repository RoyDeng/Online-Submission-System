@extends('layouts.master')

@section('title','Profile')

@section('content')
@include('admin.partials.nav')
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
						<li class=""><a data-toggle="tab" href="#account-settings">Account Settings</a></li>
                        <li class=""><a data-toggle="tab" href="#security-settings">Security Settings</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="edit-profile" class="tab-pane active">
                            <div class="panel-body">
                                <form class="form-horizontal" action="{{ url('admin/edit_profile') }}" method="post">
                                    {{ csrf_field() }}
                                    <div class="form-group"></div>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-10"><input id="name" type="text" class="form-control" name="name" value="{{Auth::user() -> name}}" required></div>
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
						<div id="account-settings" class="tab-pane">
                            <div class="panel-body">
                                <form class="form-horizontal" action="{{ url('admin/change_email') }}" method="post">
                                    {{ csrf_field() }}
                                    <div class="form-group"></div>
                                    <div class="form-group">
                                        <label for="current-email" class="col-sm-2 control-label">Current Email</label>
                                        <div class="col-sm-10"><input id="current-email" type="email" class="form-control" name="current_email" required></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <label for="new-email" class="col-sm-2 control-label">New Email</label>
                                        <div class="col-sm-10"><input id="new-email" type="email" class="form-control" name="new_email" required></div>
                                    </div>
									<div class="hr-line-dashed"></div>
									<div class="form-group">
                                        <label for="confirm-email" class="col-sm-2 control-label">Confirm Email</label>
                                        <div class="col-sm-10"><input id="confirm-email" type="email" class="form-control" name="confirm_email" required></div>
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
                                <form class="form-horizontal" action="{{ url('admin/change_password') }}" method="post">
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