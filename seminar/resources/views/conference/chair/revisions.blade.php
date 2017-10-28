@extends('layouts.master')

@section('title','Revisions')

@section('content')
@include('conference.chair.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Received Revisions</h2>
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
				<strong>Revisions</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-8">
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
                                        <h4>{{ $manuscript -> author -> title }} {{ $manuscript -> author -> firstname }} {{ $manuscript -> author -> middlename }} {{ $manuscript -> author -> lastname }}</h4>
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
                                    <th>Presentation Type</th>
                                    <td>
                                        @if ($manuscript -> type == 0)
											Oral
										@else
                                            Poster
										@endif
                                    </td>
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
                                                <li><a href="/upload/{{$file -> url}}" download>{{$file -> name}}</a></li>
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
        <div class="col-md-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Decision from Chair</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        @if ($manuscript -> final_decision != '')
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Chair</th>
                                        <td>
                                            <h4>{{ $manuscript -> final_decision -> chair -> title }}{{ $manuscript -> final_decision -> chair -> firstname }} {{ $manuscript -> final_decision -> chair -> middlename }} {{ $manuscript -> final_decision -> chair -> lastname }}</h4>
                                            <p>
                                                <i class="fa fa-university"></i> {{ $manuscript -> final_decision -> chair -> institution }}
                                            </p>
                                            <p>
                                                <i class="fa fa-globe"></i> {{ $manuscript -> final_decision -> chair -> country }}
                                            </p>
                                            <p>
                                                <i class="fa fa-phone"></i> {{ $manuscript -> final_decision -> chair -> tel }}
                                            </p>
                                            <p>
                                                <i class="fa fa-envelope"></i> {{ $manuscript -> final_decision -> chair -> email }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if ($manuscript -> final_decision -> status == 1)
                                                <span class="label label-success">Passed</span>
                                            @elseif ($manuscript -> final_decision -> status == 0)
                                                <span class="label label-danger">Rejected</span>
                                            @else
                                                <span class="label label-warning">Revision is needed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Comment</th>
                                        <td>{{$manuscript -> final_decision -> comment}}</td>
                                    </tr>
                                    <tr>
                                        <th>Added Time</th>
                                        <td>{{$manuscript -> final_decision -> added_time}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-danger alert-dismissible fade in">
                                <strong>You haven't made any decision!</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
				<table class="table table-striped table-bordered table-hover dataTables-example">
					<thead>
						<tr>
							<th>No.</th>
                            <th>Comment</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Added Time</th>
                            <th>Received Time</th>
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
                                        @if ($rev -> revised_manuscript -> re_decision == '')
                                            <span class="label label-danger">Not yet received decision</span>
                                        @else
                                            @if ($rev -> status == 0)
                                                <span class="label label-danger">Not yet decided</span>
                                            @elseif ($rev -> status == 1)
                                                <span class="label label-success">Passed</span>
                                            @elseif ($rev -> status == 2)
                                            <span class="label label-danger">Rejected</span>
                                            @else
                                                <span class="label label-warning">Revision is needed</span>
                                            @endif
                                        @endif
                                    @else
                                        <span class="label label-danger">Not yet received revision</span>
                                    @endif
                                </td>
                                <td>{{ $rev -> added_time }}</td>
                                <td>{{ $rev -> modified_time }}</td>
                                <td>
                                    @if ($rev -> revised_manuscript != '')
                                        <button type="button" class="btn btn-primary" onclick="location.href='{{url('conference/chair/received_re_reviews')}}/{{ $rev -> id }}';"><i class="fa fa-eye"></i></button>
                                    @endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
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