@extends('layouts.master')

@section('title','Conferences')

@section('content')
@include('admin.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Conferences</h2>
		<ol class="breadcrumb">
			<li class="active">
				<strong>Conferences</strong>
			</li>
		</ol>
	</div>
	<div class="col-sm-8">
		<div class="title-action">
			<button type="button" class="btn btn-primary" data-target="#CreateConference" data-toggle="modal">Create a Conference</button>
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
	<div class="row">
        <div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover dataTables-example">
					<thead>
						<tr>
							<th>No.</th>
							<th>ID</th>
							<th>Information</th>
							<th>Status</th>
							<th>Added Time</th>
							<th>Last Updated Time</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($conferences as $c => $conf)
							<tr>
								<td>{{ $c + 1 }}</td>
								<td>{{ $conf -> number }}</td>
								<td>
									<h4>{{ $conf -> title }}</h4>
									<p>
										<i class="fa fa-calendar"></i> {{ $conf -> exist_deadline }}
									</p>
								</td>
								<td>
									@if ($conf -> status == 1)
										<span class="label label-success">Open</span>
									@else
										<span class="label label-danger">Close</span>
									@endif
								</td>
								<td>{{ $conf -> added_time }}</td>
								<td>{{ $conf -> modified_time }}</td>
								<td>
									<button type="button" class="btn btn-primary" onclick="location.href='{{url('admin/conference/chairs')}}/{{ $conf -> number }}';"><i class="fa fa-eye"></i></button>
									<button type="button" class="btn btn-info" data-target="#EditConference" data-toggle="modal" onclick="GetConference('{{ $conf -> id }}')"><i class="fa fa-pencil-square-o"></i></button>
									@if ($conf -> status == 1)
										<button type="button" class="btn btn-danger" data-target="#CloseConference" data-toggle="modal" onclick="GetConference('{{ $conf -> id }}')"><i class="fa fa-times"></i></button>
									@else
										<button type="button" class="btn btn-success" data-target="#OpenConference" data-toggle="modal" onclick="GetConference('{{ $conf -> id }}')"><i class="fa fa-check"></i></button>
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
<div class="modal fade col-xs-12" id="CreateConference" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Create a Conference</h4>
			</div>
			<div class="modal-body">
				<form action="{{ url('admin/create_conference') }}" method="post">
					{{ csrf_field() }}
					<div class="form-group">
						<label class="form-label" for="title">Title</label>
						<div class="controls">
							<input class="form-control" id="title" name="title" required>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="number">ID (Abbreviation)</label>
						<div class="controls">
							<input class="form-control" id="number" name="number" required>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="deadline">Deadline</label>
						<div class="controls">
							<input class="form-control deadline" id="deadline" name="deadline" required>
						</div>
					</div>
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="EditConference" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit the Conference</h4>
			</div>
			<div class="modal-body">
				<form action="{{ url('admin/edit_conference') }}" method="post">
					{{ csrf_field() }}
					<div class="form-group">
						<label class="form-label" for="edit_conference_title">Title</label>
						<div class="controls">
							<input class="form-control" id="edit_conference_title" name="title" required>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="edit_conference_number">ID (Abbreviation)</label>
						<div class="controls">
							<input class="form-control" id="edit_conference_number" name="number" required>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="edit_conference_deadline">Deadline</label>
						<div class="controls">
							<input class="form-control deadline" id="edit_conference_deadline" name="deadline" required>
						</div>
					</div>
					<input type="hidden" id="edit_conference_id" name="id">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="CloseConference" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Close the Conference</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to close the conference?
				<form action="{{ url('admin/close_conference') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="close_conference_id" name="id">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="OpenConference" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Open the Conference</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to open the conference?
				<form action="{{ url('admin/open_conference') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="open_conference_id" name="id">
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
	function GetConference(id) {
		$.ajax({
			url: '{{ url('admin/get_conference') }}',
			type:'GET',
			data: {'id':id},
			success: function(result) {
				$('#edit_conference_id').val(result.id);
				$('#edit_conference_number').val(result.number);
				$('#edit_conference_title').val(result.title);
				$('#edit_conference_deadline').val(result.exist_deadline);
				$('#close_conference_id').val(result.id);
				$('#open_conference_id').val(result.id);
			}
		});
	}
</script>
@stop