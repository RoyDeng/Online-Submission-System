@extends('layouts.master')

@section('title','Topics')

@section('content')
@include('conference.chair.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Topics</h2>
		<ol class="breadcrumb">
			<li>
				{{$conference -> title }}
			</li>
			<li class="active">
				<strong>Topics</strong>
			</li>
		</ol>
	</div>
	<div class="col-sm-8">
		<div class="title-action">
			<button type="button" class="btn btn-primary" data-target="#CreateTopic" data-toggle="modal">Create a Topic</button>
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
							<th>Title</th>
							<th>Status</th>
							<th>Added Time</th>
							<th>Last Updated Time</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($topics as $t => $topic)
							<tr>
								<td>{{ $t + 1 }}</td>
								<td>{{ $topic -> number }}</td>
								<td>{{ $topic -> title }}</td>
								<td class="align-middle text-nowrap text-center">
									@if ($topic -> status == 1)
										<span class="label label-success">Open</span>
									@else
										<span class="label label-danger">Close</span>
									@endif
								</td>
								<td class="align-middle text-nowrap text-center">{{ $topic -> added_time }}</td>
								<td class="align-middle text-nowrap text-center">{{ $topic -> modified_time }}</td>
								<td class="align-middle text-nowrap text-center">
									<button type="button" class="btn btn-primary" onclick="location.href='{{url('conference/chair/editors')}}/{{ $topic -> number }}';"><i class="fa fa-eye"></i></button>
									<button type="button" class="btn btn-info" data-target="#EditTopic" data-toggle="modal" onclick="GetTopic('{{ $topic -> id }}')"><i class="fa fa-pencil-square-o"></i></button>
									@if ($topic -> status == 1)
										<button type="button" class="btn btn-danger" data-target="#CloseTopic" data-toggle="modal" onclick="GetTopic('{{ $topic -> id }}')"><i class="fa fa-times"></i></button>
									@else
										<button type="button" class="btn btn-success" data-target="#OpenTopic" data-toggle="modal" onclick="GetTopic('{{ $topic -> id }}')"><i class="fa fa-check"></i></button>
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
<div class="modal fade col-xs-12" id="CreateTopic" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Create a Topic</h4>
			</div>
			<div class="modal-body">
				<form action="{{ url('conference/chair/create_topic') }}" method="post">
					{{ csrf_field() }}
					<div class="form-group">
						<label class="form-label" for="title">Title</label>
						<div class="controls">
							<input class="form-control" id="title" name="title" required>
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
<div class="modal fade col-xs-12" id="EditTopic" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit the Topic</h4>
			</div>
			<div class="modal-body">
				<form action="{{ url('conference/chair/edit_topic') }}" method="post">
					{{ csrf_field() }}
					<div class="form-group">
						<label class="form-label" for="edit_topic_title">Title</label>
						<div class="controls">
							<input class="form-control" id="edit_topic_title" name="title" required>
						</div>
					</div>
					<input type="hidden" id="edit_topic_id" name="id">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="CloseTopic" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Close the Conference</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to close the conference?
				<form action="{{ url('conference/chair/close_topic') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="close_topic_id" name="id">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="OpenTopic" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Open the Topic</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to open the conference?
				<form action="{{ url('conference/chair/open_topic') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="open_topic_id" name="id">
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
	function GetTopic(id) {
		$.ajax({
			url: '{{ url('conference/chair/get_topic') }}',
			type:'GET',
			data: {'id':id},
			success: function(result) {
				$('#edit_topic_id').val(result.id);
				$('#edit_topic_title').val(result.title);
				$('#close_topic_id').val(result.id);
				$('#open_topic_id').val(result.id);
			}
		});
	}
</script>
@stop