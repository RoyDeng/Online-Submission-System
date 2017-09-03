@extends('layouts.master')

@section('title','Reviewers')

@section('content')
@include('conference.editor.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Reviewers</h2>
		<ol class="breadcrumb">
			<li>
				{{$topic -> conference -> title}}
			</li>
			<li>
				{{$topic -> title}}
			</li>
			<li class="active">
				<strong>Reviewers</strong>
			</li>
		</ol>
	</div>
	<div class="col-sm-8">
		<div class="title-action">
			<button type="button" class="btn btn-primary" data-target="#CreateReviewer" data-toggle="modal">Create a Reviewer</button>
		</div>
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
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover dataTables-example">
					<thead>
						<tr>
							<th>No.</th>
							<th>Information</th>
							<th>Status</th>
							<th>Added Time</th>
							<th>Last Updated Time</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($reviewers as $r => $reviewer)
							<tr>
								<td>{{ $r + 1 }}</td>
								<td>
									<h4>{{ $reviewer -> title }}{{ $reviewer -> firstname }} {{ $reviewer -> middlename }} {{ $reviewer -> lastname }}</h4>
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
									@if ($reviewer -> status == 1)
										<span class="label label-success">Enabled</span>
									@else
										<span class="label label-danger">Suspened</span>
									@endif
								</td>
								<td>{{ $reviewer -> added_time }}</td>
								<td>{{ $reviewer -> modified_time }}</td>
								<td>
									@if ($reviewer -> status == 1)
										<button type="button" class="btn btn-danger" data-target="#SuspendReviewer" data-toggle="modal" onclick="GetReviewer('{{ $reviewer -> id }}')"><i class="fa fa-times"></i></button>
									@else
										<button type="button" class="btn btn-success" data-target="#ResumeReviewer" data-toggle="modal" onclick="GetReviewer('{{ $reviewer -> id }}')"><i class="fa fa-check"></i></button>
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
<div class="modal fade col-xs-12" id="CreateReviewer" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Create a Reviewer</h4>
			</div>
			<div class="modal-body">
				<form action="{{ url('conference/editor/create_reviewer') }}" method="post">
					{{ csrf_field() }}
					<div class="form-group">
						<label class="form-label" for="title">Title</label>
						<div class="controls">
							<select id="title" class="form-control" name="title" required>
								<option value="Mr.">Mr.</option>
								<option value="Mrs.">Mrs.</option>
								<option value="Ms.">Ms.</option>
								<option value="Prof.">Prof.</option>
								<option value="Dr.">Dr.</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="email">Email</label>
						<div class="controls">
							<input class="form-control" id="email" name="email" required>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="password">Password</label>
						<div class="controls">
							<input class="form-control" id="password" name="password" required>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="firstname">First Name</label>
						<div class="controls">
							<input class="form-control" id="firstname" name="firstname" required>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="middlename">Middle Name</label>
						<div class="controls">
							<input class="form-control" id="middlename" name="middlename">
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="lastname">Last Name</label>
						<div class="controls">
							<input class="form-control" id="lastname" name="lastname" required>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="tel">Telephone</label>
						<div class="controls">
							<input class="form-control" id="tel" name="tel" required>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="institution">Institution</label>
						<div class="controls">
							<input class="form-control" id="institution" name="institution" required>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="country">Country</label>
						<div class="controls">
							<input class="form-control" id="country" name="country" required>
						</div>
					</div>
					<input type="hidden" name="conference_id" value="{{ $topic -> conference -> id }}">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="SuspendReviewer" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Suspend the Reviewer</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to suspend the reviewer?
				<form action="{{ url('conference/editor/suspend_reviewer') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="suspend_reviewer_id" name="id">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="ResumeReviewer" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Resume the Reviewer</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to resume the reviewer?
				<form action="{{ url('conference/editor/resume_reviewer') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="resume_reviewer_id" name="id">
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
				$('#suspend_reviewer_id').val(result.id);
				$('#resume_reviewer_id').val(result.id);
			}
		});
	}
</script>
@stop