@extends('layouts.master')

@section('title','Manuscript')

@section('content')
@include('conference.editor.partials.nav')
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
				Manuscript
			</li>
            <li class="active">
				<strong>{{$manuscript -> title}}</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content">
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
                    <h5>Selected Reviewers</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        @if ($manuscript -> invitation != '[]')
                            <div style="max-height: 250px; overflow-y:scroll;">
                                <ul class="list-group elements-list">
                                    @foreach ($invitations as $invitation)
                                        <li class="list-group-item">
                                            <small class="pull-right text-muted"> {{$invitation -> added_time}}</small>
                                            <strong>{{ $invitation -> reviewer -> title }} {{ $invitation -> reviewer -> firstname }} {{ $invitation -> reviewer -> middlename }} {{ $invitation -> reviewer -> lastname }}</strong>
                                            <div class="small m-t-xs">
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
												<p>
													@if ($invitation -> status == 0)
                                                        <span class="label label-warning">Pending</span>
                                                    @elseif ($invitation -> status == 1)
                                                        <span class="label label-success">Accepted</span>
                                                    @else
                                                        <span class="label label-danger">Rejected</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="alert alert-danger alert-dismissible fade in">
                                <strong>You haven't selected any reviewer!</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
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
			<div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Select one or more reviewers from the dialog box.</h5>
                </div>
                <div>
                    <div class="ibox-content">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover dataTables-example">
								<thead>
									<tr>
										<th>No.</th>
										<th>Information</th>
										<th>Status</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($reviewers as $r => $reviewer)
										<tr>
											<td>{{ $r + 1 }}</td>
											<td>
												<h4>{{ $reviewer -> title }} {{ $reviewer -> firstname }} {{ $reviewer -> middlename }} {{ $reviewer -> lastname }}</h4>
												<p>
													<i class="fa fa-university"></i> {{ $reviewer -> institution }}
												</p>
												<p>
													<i class="fa fa-globe"></i> {{ $reviewer -> country }}
												</p>
												<p>
													<i class="fa fa-phone"></i> {{ $reviewer -> tel }}
												</p>
												<p>
													<i class="fa fa-envelope"></i> {{ $reviewer -> email }}
												</p>
											</td>
											<td>
												<p>
													<b>Pending:</b> {{ count($reviewer -> invitation -> where('status', 0)) + count($reviewer -> re_invitation -> where('status', 0)) }}
												</p>
												<p>
													<b>Accepted:</b> {{ count($reviewer -> invitation -> where('status', 1)) + count($reviewer -> re_invitation -> where('status', 1)) }}
												</p>
												<p>
													<b>Rejected:</b> {{ count($reviewer -> invitation -> where('status', 2)) + count($reviewer -> re_invitation -> where('status', 2)) }}
												</p>
											</td>
											<td>
												<button type="button" class="btn btn-success" data-target="#ConfirmReviewer" data-toggle="modal" onclick="GetReviewer('{{ $reviewer -> id }}')"><i class="fa fa-check"></i></button>
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
<div class="modal fade col-xs-12" id="ConfirmReviewer" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Send Invitation to the Reviewer</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to send invitation to the reviewer?
				<form action="{{ url('conference/editor/send_invitation') }}" method="post">
					{{ csrf_field() }}
                    <div class="form-group">
						<label class="form-label" for="deadline">Deadline</label>
						<div class="controls">
							<input class="form-control deadline" id="deadline" name="deadline" required>
						</div>
					</div>
                    <input type="hidden" name="manuscript_id" value="{{$manuscript -> id}}">
					<input type="hidden" id="invite_reviewer_id" name="reviewer_id">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function GetReviewer(id) {
		$.ajax({
			url: '{{ url('conference/editor/get_reviewer') }}',
			type:'GET',
			data: {'id':id},
			success: function(result) {
				$('#invite_reviewer_id').val(result.id);
			}
		});
	}
</script>
@stop