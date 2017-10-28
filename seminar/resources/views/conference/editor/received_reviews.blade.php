@extends('layouts.master')

@section('title','Received Reviews')

@section('content')
@include('conference.editor.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Received Reviews</h2>
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
				<strong>Received Reviews</strong>
			</li>
		</ol>
	</div>
    @if ($manuscript -> decision == '')
        <div class="col-sm-8">
            <div class="title-action">
                <button type="button" class="btn btn-primary" data-target="#MakeDecision" data-toggle="modal">Make a Decision</button>
            </div>
        </div>
    @endif
</div>
<div class="wrapper wrapper-content">
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
        <div class="col-md-8">
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
                    <h5>Initial Decision</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        @if ($manuscript -> decision != '')
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Editor</th>
                                        <td>
                                            <h4>{{ $manuscript -> decision -> editor -> title }} {{ $manuscript -> decision -> editor -> firstname }} {{ $manuscript -> decision -> editor -> middlename }} {{ $manuscript -> decision -> editor -> lastname }}</h4>
                                            <p>
                                                <i class="fa fa-university"></i> {{ $manuscript -> decision -> editor -> institution }}
                                            </p>
                                            <p>
                                                <i class="fa fa-globe"></i> {{ $manuscript -> decision -> editor -> country }}
                                            </p>
                                            <p>
                                                <i class="fa fa-phone"></i> {{ $manuscript -> decision -> editor -> tel }}
                                            </p>
                                            <p>
                                                <i class="fa fa-envelope"></i> {{ $manuscript -> decision -> editor -> email }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if ($manuscript -> decision -> status == 1)
                                                <span class="label label-success">Passed</span>
                                            @elseif ($manuscript -> decision -> status == 0)
                                                <span class="label label-danger">Rejected</span>
                                            @else
                                                <span class="label label-warning">Revision is needed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Comment</th>
                                        <td>{{$manuscript -> decision -> comment}}</td>
                                    </tr>
                                    <tr>
                                        <th>Added Time</th>
                                        <td>{{$manuscript -> decision -> added_time}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-danger alert-dismissible fade in">
                                <strong>You haven't made a decision!</strong>
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
                            <th>Reviewer</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Invited Time</th>
                            <th>Reply Time</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($invitations as $i => $invitation)
							<tr>
								<td>{{ $i + 1 }}</td>
                                <td>
                                    <h4>{{ $invitation -> reviewer -> title }} {{ $invitation -> reviewer -> firstname }} {{ $invitation -> reviewer -> middlename }} {{ $invitation -> reviewer -> lastname }}</h4>
                                    <p>
                                        <i class="fa fa-university"></i> {{ $invitation -> reviewer -> institution }}
                                    </p>
                                    <p>
                                        <i class="fa fa-globe"></i> {{ $invitation -> reviewer -> country }}
                                    </p>
                                    <p>
                                        <i class="fa fa-phone"></i> {{ $invitation -> reviewer -> tel }}
                                    </p>
                                    <p>
                                        <i class="fa fa-envelope"></i> {{ $invitation -> reviewer -> email }}
                                    </p>
                                </td>
                                <td>{{ $invitation -> deadline }}</td>
                                <td>
                                    @if ($invitation -> status == 0)
                                        <span class="label label-primary">Pending</span>
                                    @elseif ($invitation -> status == 1)
                                        <span class="label label-success">Accepted</span>
                                    @else
                                        <span class="label label-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $invitation -> added_time }}</td>
                                <td>{{ $invitation -> modified_time }}</td>
                                <td>
                                    @if ($invitation -> status == 1)
                                        <button type="button" class="btn btn-primary" data-target="#GetReview" data-toggle="modal" onclick="GetReview('{{ $invitation -> review -> id }}')"><i class="fa fa-eye"></i></button>
                                    @endif
                                    @if ($invitation -> status == 2)
									    <button type="button" class="btn btn-primary" data-target="#GetReply" data-toggle="modal" onclick="GetReply('{{ $invitation -> id }}')"><i class="fa fa-eye"></i></button>
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
<div class="modal fade col-xs-12" id="MakeDecision" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Make a Decision</h4>
			</div>
			<div class="modal-body">
				<form action="{{ url('conference/editor/make_decision') }}" method="post">
					{{ csrf_field() }}
					<div class="form-group">
						<label class="form-label" for="comment">Comment</label>
						<div class="controls">
                            <textarea id="comment" class="form-control" name="comment" rows="10" required></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label">Decision</label>
						<div class="controls">
                            <label> <input type="radio" value="1" id="status" name="status" checked> Accept </label>
                            <label> <input type="radio" value="0" id="status" name="status"> Reject </label>
                            <label> <input type="radio" value="2" id="status" name="status"> Need Revision </label>
						</div>
					</div>
                    <input type="hidden" name="manuscript_id" value="{{$manuscript -> id}}">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="GetReply" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Reason for Rejection</h4>
			</div>
			<div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Added Time</th>
                            <td><p id="added_time"></p></td>
                        </tr>
                        <tr>
                            <th>Reply</th>
                            <td><p id="reply"></p></td>
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
                            <th>Added Time</th>
                            <td><p id="review_added_time"></p></td>
                        </tr>
                        <tr>
                            <th>Decision</th>
                            <td><p id="review_status"></p></td>
                        </tr>
                        <tr>
                            <th>Comment to the Author</th>
                            <td><p id="review_comment_author"></p></td>
                        </tr>
                        <tr>
                            <th>Comment to the Editor</th>
                            <td><p id="review_comment_editor"></p></td>
                        </tr>
                        <tr>
                            <th>File</th>
                            <td><a id="review_file_url" download><span id="review_file_name"></span></a></td>
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
			url: '{{ url('conference/editor/get_review') }}',
			type:'GET',
			data: {'id':id},
			success: function(result) {
                $('#review_added_time').text(result.added_time);
				$('#review_comment_author').text(result.comment_author);
				$('#review_comment_editor').text(result.comment_editor);
                if (result.status == 0) $('#review_status').text('Reject');
                else if (result.status == 1) $('#review_status').text('Accept');
                else $('#review_status').text('Need Revision');
				if (result.review_file != "") {
                    $('#review_file_name').text(result.review_file.name);
                    $('#review_file_url').attr('href', '/upload/' + result.review_file.url);
                }
			}
		});
	}

    function GetReply(id) {
		$.ajax({
			url: '{{ url('conference/editor/get_reply') }}',
			type:'GET',
			data: {'id':id},
			success: function(result) {
                $('#added_time').text(result.modified_time);
				$('#reply').text(result.reply);
			}
		});
	}
</script>
@stop