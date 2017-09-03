@extends('layouts.master')

@section('title','Upload Review')

@section('content')
@include('conference.reviewer.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Upload Review</h2>
		<ol class="breadcrumb">
            <li>
                {{$invitation -> manuscript -> topic -> conference -> title}}
            </li>
            <li>
                {{$invitation -> manuscript -> topic -> title}}
            </li>
            <li>
                {{$invitation -> manuscript -> title}}
            </li>
			<li class="active">
				<strong>Upload Review</strong>
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
                    <h5>Manuscript Information</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Author</th>
                                    <td>
                                        <h4>{{ $invitation -> manuscript -> author -> title }}{{ $invitation -> manuscript -> author -> firstname }} {{ $invitation -> manuscript -> author -> middlename }} {{ $invitation -> manuscript -> author -> lastname }}</h4>
                                        <p>
                                            <i class="fa fa-university"></i> {{ $invitation -> manuscript -> author -> institution }}
                                        </p>
                                        <p>
                                            <i class="fa fa-globe"></i> {{ $invitation -> manuscript -> author -> country }}
                                        </p>
                                        <p>
                                            <i class="fa fa-phone"></i> {{ $invitation -> manuscript -> author -> tel }}
                                        </p>
                                        <p>
                                            <i class="fa fa-envelope"></i> {{ $invitation -> manuscript -> author -> email }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Number</th>
                                    <td>{{$invitation -> manuscript -> number}}</td>
                                </tr>
                                <tr>
                                    <th>Submission Type</th>
                                    <td>{{$manuscript -> submission_type -> name}}</td>
                                </tr>
                                <tr>
                                    <th>Title</th>
                                    <td>{{$invitation -> manuscript -> title}}</td>
                                </tr>
                                <tr>
                                    <th>Abstract</th>
                                    <td>{{$invitation -> manuscript -> abstract}}</td>
                                </tr>
                                <tr>
                                    <th>Files</th>
                                    <td>
                                        <ul class="list-unstyled file-list">
                                            @foreach ($invitation -> manuscript -> file as $file)
                                                <li><a href="/upload/{{$file -> url}}" download>{{$file -> name}}.{{$file -> type}}</a></li>
                                            @endforeach
                                        </ul>
                                    </td>
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
            <form class="form-horizontal" action="{{ url('conference/reviewer/upload_review') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="comment-author" class="col-sm-2 control-label">Comment to the Author</label>
                    <div class="col-sm-10"><textarea id="comment-author" class="form-control" name="comment_author" rows="10" required></textarea></div>
                </div>
                <div class="form-group">
                    <label for="comment-editor" class="col-sm-2 control-label">Comment to the Editor</label>
                    <div class="col-sm-10"><textarea id="comment-editor" class="form-control" name="comment_editor" rows="10" required></textarea></div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">File</label>
                    <div class="col-sm-10">
                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                            <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                            <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select File</span><span class="fileinput-exists">Change</span><input type="file" name="file"></span>
                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove File</a>
                        </div>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <input type="hidden" name="invitation_id" value="{{$invitation -> id}}">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
		<div>
			Copyright © {{ date("Y") }} Chung Yuan Christian University All Rights
		</div>
	</div>
</div>
@stop