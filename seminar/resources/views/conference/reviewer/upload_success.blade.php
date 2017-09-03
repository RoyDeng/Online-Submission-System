@extends('layouts.master')

@section('title','Upload Success')

@section('content')
@include('conference.reviewer.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Upload Success</h2>
		<ol class="breadcrumb">
			<li class="active">
				<strong>Upload Success</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content">
	<div class="row">
        <div class="col-lg-12">
            <div class="middle-box text-center">
                <div class="alert alert-success alert-dismissible fade in">
                    <strong>You have successfully uploaded your review!</strong> 
                </div>
                <a href="{{ url('conference/reviewer') }}" class="btn btn-primary m-t">Back to Invatations</a>
            </div>
		</div>
	</div>
	<div class="footer">
		<div>
			Copyright © {{ date("Y") }} Chung Yuan Christian University All Rights
		</div>
	</div>
</div>
<script>
    setTimeout("location.href='{{ url('conference/reviewer') }}'", 10000);
</script>
@stop