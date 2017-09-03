@extends('layouts.master')

@section('title','Manuscript')

@section('content')
@include('author.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Manuscript</h2>
		<ol class="breadcrumb">
            <li>
				{{$manuscript -> topic -> conference -> title}}
			</li>
            <li>
				{{$manuscript -> topic -> title}}
			</li>
            <li>
				{{$manuscript -> title}}
			</li>
			<li class="active">
				<strong>Manuscript</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Initial Manuscript Information</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Author</th>
                                    <td>
                                        <h4>{{ $manuscript -> author -> title }}{{ $manuscript -> author -> firstname }} {{ $manuscript -> author -> middlename }} {{ $manuscript -> author -> lastname }}</h4>
                                        <p>
                                            <i class="fa fa-university"></i> {{ $manuscript -> author -> institution }}
                                        </p>
                                        <p>
                                            <i class="fa fa-globe"></i> {{ $manuscript -> author -> country }}
                                        </p>
                                        <p>
                                            <i class="fa fa-phone"></i> {{ $manuscript -> author -> tel }}
                                        </p>
                                        <p>
                                            <i class="fa fa-envelope"></i> {{ $manuscript -> author -> email }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Number</th>
                                    <td>{{$manuscript -> number}}</td>
                                </tr>
                                <tr>
                                    <th>Submission Type</th>
                                    <td>{{$manuscript -> submission_type -> name}}</td>
                                </tr>
                                <tr>
                                    <th>Title</th>
                                    <td>{{$manuscript -> title}}</td>
                                </tr>
                                <tr>
                                    <th>Abstract</th>
                                    <td>{{$manuscript -> abstract}}</td>
                                </tr>
                                <tr>
                                    <th>Files</th>
                                    <td>
                                        <ul class="list-unstyled file-list">
                                            @foreach ($manuscript -> file as $file)
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
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Revisions</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Comment</th>
                                        <th>Deadline</th>
                                        <th>Status</th>
                                        <th>Added Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($revisions as $r => $rev)
                                        <tr>
                                            <td>{{ $r + 1 }}</td>
                                            <td>{{ $rev -> comment }}</td>
                                            <td>{{ $rev -> deadline }}</td>
                                            <td>
                                                @if ($rev -> revised_manuscript != '')
                                                    @if ($rev -> status == 0)
                                                        <span class="label label-danger">Not yet decided</span>
                                                    @elseif ($rev -> status == 1)
                                                        <span class="label label-success">Passed</span>
                                                    @elseif ($rev -> status == 2)
                                                    <span class="label label-danger">Rejected</span>
                                                    @else
                                                        <span class="label label-warning">Revision is needed</span>
                                                    @endif
                                                @else
                                                    <span class="label label-danger">Not yet sent revision</span>
                                                @endif
                                            </td>
                                            <td>{{ $rev -> added_time }}</td>
                                            <td>
                                                @if ($rev -> revised_manuscript == '')
                                                    <button type="button" class="btn btn-success" onclick="location.href='{{url('author/conference/upload_revision')}}/{{ $rev -> id }}';"><i class="fa fa-upload"></i></button>
                                                @else
                                                    <button type="button" class="btn btn-primary" onclick="location.href='{{url('author/conference/revision')}}/{{ $rev -> id }}';"><i class="fa fa-eye"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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