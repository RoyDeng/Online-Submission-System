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
            <form class="form-horizontal" action="{{ url('admin/change_password') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group"></div>
                <div class="form-group">
                    <label for="current_password" class="col-sm-2 control-label">Current Password</label>
                    <div class="col-sm-10"><input id="current_password" type="password" class="form-control" name="current_password"></div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label for="new-password" class="col-sm-2 control-label">New Password</label>
                    <div class="col-sm-10"><input id="new-password" type="password" class="form-control" name="new_password"></div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label for="confirm-password" class="col-sm-2 control-label">Confirm Password</label>
                    <div class="col-sm-10"><input id="confirm-password" type="password" class="form-control" name="confirm_password"></div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="footer">
		<div>
			Copyright © {{ date("Y") }} Chung Yuan Christian University All Rights
		</div>
	</div>
</div>
@stop