@extends('layouts.master')

@section('title','Invitations')

@section('content')
@include('conference.reviewer.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Invitations</h2>
		<ol class="breadcrumb">
            <li>
				{{$conference -> title}}
			</li>
			<li class="active">
				<strong>Invitations</strong>
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
	<div class="row">
        <div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover dataTables-example">
					<thead>
						<tr>
							<th>No.</th>
							<th>Submission Type</th>
							<th>ID</th>
                            <th>Title</th>
							<th>Author</th>
							<th>Deadline</th>
                            <th>Status</th>
                            <th>Recieved Time</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($invitations as $i => $invitation)
							<tr>
								<td>{{ $i + 1 }}</td>
								<td>{{ $invitation -> manuscript -> submission_type -> name }}</td>
								<td>{{ $invitation -> manuscript -> number }}</td>
                                <td>{{ $invitation -> manuscript -> title }}</td>
								<td>
									<h4>{{ $invitation -> manuscript -> author -> title }} {{ $invitation -> manuscript -> author -> firstname }} {{ $invitation -> manuscript -> author -> middlename }} {{ $invitation -> manuscript -> author -> lastname }}</h4>
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
								<td>{{ $invitation -> deadline }}</td>
								<td>
									@if ($invitation -> status == 0)
										<span class="label label-warning">Pending</span>
									@elseif ($invitation -> status == 1)
										<span class="label label-success">Accepted</span>
									@else
										<span class="label label-danger">Rejected</span>
									@endif
								</td>
                                <td>{{ $invitation -> added_time }}</td>
								<td>
									@if ($invitation -> status == 0)
										<button type="button" class="btn btn-success" onclick="location.href='{{url('conference/reviewer/accept_invitation')}}/{{ $invitation -> id }}';"><i class="fa fa-check"></i></button>
										<button type="button" class="btn btn-danger" data-target="#RejectInvitation" data-toggle="modal" onclick="GetInvitation('{{ $invitation -> id }}')"><i class="fa fa-times"></i></button>
									@elseif ($invitation -> status == 1)
										<button type="button" class="btn btn-primary" data-target="#GetReview" data-toggle="modal" onclick="GetReview('{{ $invitation -> review -> id }}')"><i class="fa fa-eye"></i></button>
									@else
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
<div class="modal fade col-xs-12" id="RejectInvitation" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Reject the Invitation</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to reject the invitation?
				<form action="{{ url('conference/reviewer/reject_invitation') }}" method="post">
					{{ csrf_field() }}
					<div class="form-group">
						<label class="form-label" for="reply">Reply</label>
						<div class="controls">
							<textarea id="reply" class="form-control" name="reply" rows="5" required></textarea>
						</div>
					</div>
					<input type="hidden" id="reject_invitation_id" name="id">
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
                            <td><p id="reason"></p></td>
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
	function GetInvitation(id) {
		$.ajax({
			url: '{{ url('conference/reviewer/get_invitation') }}',
			type:'GET',
			data: {'id':id},
			success: function(result) {
				$('#reject_invitation_id').val(result.id);
			}
		});
	}

	function GetReview(id) {
		$.ajax({
			url: '{{ url('conference/reviewer/get_review') }}',
			type:'GET',
			data: {'id':id},
			success: function(result) {
                $('#review_added_time').text(result.added_time);
				$('#review_comment_author').text(result.comment_author);
				$('#review_comment_editor').text(result.comment_editor);
				$('#review_file_name').text(result.review_file.name);
                $('#review_file_url').attr('href', '/upload/' + result.review_file.url);
			}
		});
	}

    function GetReply(id) {
		$.ajax({
			url: '{{ url('conference/reviewer/get_reply') }}',
			type:'GET',
			data: {'id':id},
			success: function(result) {
                $('#added_time').text(result.modified_time);
				$('#reason').text(result.reply);
			}
		});
	}
</script>
@stop