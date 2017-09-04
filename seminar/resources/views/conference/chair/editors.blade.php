@extends('layouts.master')

@section('title','Editors')

@section('content')
@include('conference.chair.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Editors</h2>
		<ol class="breadcrumb">
			<li>
				{{$topic -> conference -> title}}
			</li>
			<li>
				{{$topic -> title}}
			</li>
			<li class="active">
				<strong>Editors</strong>
			</li>
		</ol>
	</div>
	<div class="col-sm-8">
		<div class="title-action">
			<button type="button" class="btn btn-primary" data-target="#CreateEditor" data-toggle="modal">Create a Editor</button>
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
						@foreach ($editors as $e => $editor)
							<tr>
								<td>{{ $e + 1 }}</td>
								<td>
									<h4>{{ $editor -> title }}{{ $editor -> firstname }} {{ $editor -> middlename }} {{ $editor -> lastname }}</h4>
									<p>
										<i class="fa fa-university"></i> {{ $editor -> institution }}
									</p>
									<p>
										<i class="fa fa-globe"></i> {{ $editor -> country }}
									</p>
									<p>
										<i class="fa fa-phone"></i> {{ $editor -> tel }}
									</p>
									<p>
										<i class="fa fa-envelope"></i> {{ $editor -> email }}
									</p>
								</td>
								<td>
									@if ($editor -> status == 1)
										<span class="label label-success">Enabled</span>
									@else
										<span class="label label-danger">Suspened</span>
									@endif
								</td>
								<td>{{ $editor -> added_time }}</td>
								<td>{{ $editor -> modified_time }}</td>
								<td>
									@if ($editor -> status == 1)
										<button type="button" class="btn btn-danger" data-target="#SuspendEditor" data-toggle="modal" onclick="GetEditor('{{ $editor -> id }}')"><i class="fa fa-times"></i></button>
									@else
										<button type="button" class="btn btn-success" data-target="#ResumeEditor" data-toggle="modal" onclick="GetEditor('{{ $editor -> id }}')"><i class="fa fa-check"></i></button>
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
<div class="modal fade col-xs-12" id="CreateEditor" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Create a Editor</h4>
			</div>
			<div class="modal-body">
				<form action="{{ url('conference/chair/create_editor') }}" method="post">
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
					<input type="hidden" name="topic_id" value="{{ $topic -> id }}">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="SuspendEditor" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Suspend the Editor</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to suspend the chair?
				<form action="{{ url('conference/chair/suspend_editor') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="suspend_editor_id" name="id">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="ResumeEditor" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Resume the Editor</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to resume the chair?
				<form action="{{ url('conference/chair/resume_editor') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="resume_editor_id" name="id">
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
	function GetEditor(id) {
		$.ajax({
			url: '{{ url('conference/chair/get_editor') }}',
			type:'GET',
			data: {'id':id},
			success: function(result) {
				$('#suspend_editor_id').val(result.id);
				$('#resume_editor_id').val(result.id);
			}
		});
	}
</script>
@stop