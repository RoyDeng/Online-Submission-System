@extends('layouts.master')

@section('title','Upload Revision')

@section('content')
@include('author.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Upload Revision</h2>
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
				<strong>Upload Revision</strong>
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
                    <h5>Revision Form</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        <form class="form-horizontal" action="{{ url('author/conference/upload_revision') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="title" class="col-sm-2 control-label">Title</label>
                                <div class="col-sm-10"><input id="title" type="text" class="form-control" name="title" value="{{$revision -> final_decision -> manuscript -> title}}" required></div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label for="abstract" class="col-sm-2 control-label">Abstract</label>
                                <div class="col-sm-10"><textarea id="abstract" class="form-control" name="abstract" rows="10" required>{{$revision -> final_decision -> manuscript -> abstract}}</textarea></div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Files</label>
                                <div class="col-sm-10">
                                    <table id="files" class="table">
                                        <tbody>
                                            <tr><td>
                                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                    <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                                    <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select File</span><span class="fileinput-exists">Change</span><input type="file" name="files[]"></span>
                                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove File</a>
                                                </div>
                                            </td><td><button class="btn btn-success" type="button" onClick="AddFile();"><i class="fa fa-plus-circle"></i></button></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <input type="hidden" name="revision_id" value="{{$revision -> id}}">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
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
<script type="text/javascript">
    var file_row = 0;

    function AddFile() {
        html = '<tr id="file-row' + file_row + '"><td>';
        html += '<div class="fileinput fileinput-new input-group" data-provides="fileinput">';
        html += '<div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>';
        html += '<span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="files[]"></span>';
        html += '<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>';
        html += '</div>';
        html += '</td><td><button type="button" onclick="$(\'#file-row' + file_row  + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td></tr>';
        $('#files tbody').append(html);
        file_row++;
    }
</script>
@stop