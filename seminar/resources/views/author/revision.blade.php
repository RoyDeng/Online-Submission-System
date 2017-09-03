@extends('layouts.master')

@section('title','Revision')

@section('content')
@include('author.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Revision</h2>
		<ol class="breadcrumb">
            <li>
                {{$revision -> final_decision -> manuscript -> topic -> conference -> title}}
            </li>
            <li>
                {{$revision -> final_decision -> manuscript -> topic -> title}}
            </li>
            <li>
                {{$revision -> final_decision -> manuscript -> title}}
            </li>
			<li class="active">
				<strong>Revision</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content">
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
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Revision Information</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Chair</th>
                                    <td>
                                        <h4>{{ $revision -> final_decision -> chair -> title }}{{ $revision -> final_decision -> chair -> firstname }} {{ $revision -> final_decision -> chair -> middlename }} {{ $revision -> final_decision -> chair -> lastname }}</h4>
                                        <p>
                                            <i class="fa fa-university"></i> {{ $revision -> final_decision -> chair -> institution }}
                                        </p>
                                        <p>
                                            <i class="fa fa-globe"></i> {{ $revision -> final_decision -> chair -> country }}
                                        </p>
                                        <p>
                                            <i class="fa fa-phone"></i> {{ $revision -> final_decision -> chair -> tel }}
                                        </p>
                                        <p>
                                            <i class="fa fa-envelope"></i> {{ $revision -> final_decision -> chair -> email }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Comment</th>
                                    <td>{{$revision -> comment}}</td>
                                </tr>
                                <tr>
                                    <th>Deadline</th>
                                    <td>{{$revision -> deadline}}</td>
                                </tr>
                                <tr>
                                    <th>Added Time</th>
                                    <td>{{$revision -> added_time}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="row">
        <div class="col-lg-12">
        <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Revised Manuscript</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Files</th>
                                    <td>
                                        <ul class="list-unstyled file-list">
                                            @foreach ($revision -> revised_manuscript -> revised_file as $file)
                                                <li><a href="/upload/{{$file -> url}}" download>{{$file -> name}}.{{$file -> type}}</a></li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Added Time</th>
                                    <td>{{$revision -> revised_manuscript -> added_time}}</td>
                                </tr>
                            </tbody>
                        </table>
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