@extends('layouts.master')

@section('title','Manuscript')

@section('content')
@include('author.conference.partials.nav')
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
        <div class="col-md-8">
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
                                <strong>You haven't received a decision!</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Reviews from Reviewers</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        @if ($invitations != '[]')
                            <div style="max-height: 250px; overflow-y:scroll;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Reply Time</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invitations as $i => $invitation)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $invitation -> modified_time }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-target="#GetReview" data-toggle="modal" onclick="GetReview('{{ $invitation -> review -> id }}')"><i class="fa fa-eye"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-danger alert-dismissible fade in">
                                <strong>You haven't received any review!</strong>
                            </div>
                        @endif
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
<div class="modal fade col-xs-12" id="GetReview" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">The Review Detail</h4>
			</div>
			<div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Comment</th>
                            <td><p id="comment"></p></td>
                        </tr>
                        <tr>
                            <th>File</th>
                            <td><a id="review_file_url" download><span id="review_file_name"></span>.<span id="review_file_type"></span></a></td>
                        </tr>
                    </tbody>
                </table>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function GetReview(id) {
		$.ajax({
			url: '{{ url('author/conference/get_review') }}/' + id,
			type:'GET',
			success: function(result) {
				$('#comment').text(result.comment_author);
				$('#review_file_name').text(result.review_file.name);
				$('#review_file_type').text(result.review_file.type);
                $('#review_file_url').attr('href', '/upload/' + result.review_file.url);
			}
		});
	}
</script>
@stop