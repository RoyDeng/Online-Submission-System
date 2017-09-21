@extends('layouts.master')

@section('title','Chairs')

@section('content')
@include('admin.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Chairs</h2>
		<ol class="breadcrumb">
			<li>
				{{$conference -> title}}
			</li>
			<li class="active">
				<strong>Chairs</strong>
			</li>
		</ol>
	</div>
	<div class="col-sm-8">
		<div class="title-action">
			<button type="button" class="btn btn-primary" data-target="#CreateChair" data-toggle="modal">Create a Chair</button>
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
						@foreach ($chairs as $c => $chair)
							<tr>
								<td>{{ $c + 1 }}</td>
								<td>
									<h4>{{ $chair -> title }} {{ $chair -> firstname }} {{ $chair -> middlename }} {{ $chair -> lastname }}</h4>
									<p>
										<i class="fa fa-university"></i> {{ $chair -> institution }}
									</p>
									<p>
										<i class="fa fa-globe"></i> {{ $chair -> country }}
									</p>
									<p>
										<i class="fa fa-phone"></i> {{ $chair -> tel }}
									</p>
									<p>
										<i class="fa fa-envelope"></i> {{ $chair -> email }}
									</p>
								</td>
								<td>
									@if ($chair -> status == 1)
										<span class="label label-success">Enabled</span>
									@else
										<span class="label label-danger">Suspended</span>
									@endif
								</td>
								<td>{{ $chair -> added_time }}</td>
								<td>{{ $chair -> modified_time }}</td>
								<td>
									@if ($chair -> status == 1)
										<button type="button" class="btn btn-danger" data-target="#SuspendChair" data-toggle="modal" onclick="GetChair('{{ $chair -> id }}')"><i class="fa fa-times"></i></button>
									@else
										<button type="button" class="btn btn-success" data-target="#ResumeChair" data-toggle="modal" onclick="GetChair('{{ $chair -> id }}')"><i class="fa fa-check"></i></button>
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
<div class="modal fade col-xs-12" id="CreateChair" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Create a Chair</h4>
			</div>
			<div class="modal-body">
				<form action="{{ url('admin/conference/create_chair') }}" method="post">
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
					<input type="hidden" name="conference_id" value="{{ $conference -> id }}">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="SuspendChair" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Suspend the Chair</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to suspend the chair?
				<form action="{{ url('admin/conference/suspend_chair') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="suspend_chair_id" name="id">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="ResumeChair" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Resume the Chair</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to resume the chair?
				<form action="{{ url('admin/conference/resume_chair') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="resume_chair_id" name="id">
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
	function GetChair(id) {
		$.ajax({
			url: '{{ url('admin/conference/get_chair') }}',
			type:'GET',
			data: {'id':id},
			success: function(result) {
				$('#suspend_chair_id').val(result.id);
				$('#resume_chair_id').val(result.id);
			}
		});
	}
</script>
@stop