@extends('layouts.master')

@section('title','Authors')

@section('content')
@include('admin.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Authors</h2>
		<ol class="breadcrumb">
			<li class="active">
				<strong>Authors</strong>
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
							<th>Information</th>
							<th>Status</th>
							<th>Added Time</th>
							<th>Last Updated Time</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($authors as $c => $auth)
							<tr>
								<td>{{ $c + 1 }}</td>
								<td>
									<h4>{{ $auth -> title }} {{ $auth -> firstname }} {{ $auth -> middlename }} {{ $auth -> lastname }}</h4>
									<p>
										<i class="fa fa-university"></i> {{ $auth -> institution }}
									</p>
									<p>
										<i class="fa fa-globe"></i> {{ $auth -> country }}
									</p>
									<p>
										<i class="fa fa-phone"></i> {{ $auth -> tel }}
									</p>
									<p>
										<i class="fa fa-envelope"></i> {{ $auth -> email }}
									</p>
								</td>
								<td>
									@if ($auth -> status == 1)
										<span class="label label-success">Enabled</span>
									@else
										<span class="label label-danger">Suspended</span>
									@endif
								</td>
								<td>{{ $auth -> added_time }}</td>
								<td>{{ $auth -> modified_time }}</td>
								<td>
									@if ($auth -> status == 1)
										<button type="button" class="btn btn-danger" data-target="#SuspendAuthor" data-toggle="modal" onclick="GetAuthor('{{ $auth -> id }}')"><i class="fa fa-times"></i></button>
									@else
										<button type="button" class="btn btn-success" data-target="#ResumeAuthor" data-toggle="modal" onclick="GetAuthor('{{ $auth -> id }}')"><i class="fa fa-check"></i></button>
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
<div class="modal fade col-xs-12" id="SuspendAuthor" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Suspend the Author</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to suspend the author?
				<form action="{{ url('admin/suspend_author') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="suspend_author_id" name="id">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="ResumeAuthor" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Resume the Author</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to resume the author?
				<form action="{{ url('admin/resume_author') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="resume_author_id" name="id">
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
	function GetAuthor(id) {
		$.ajax({
			url: '{{ url('admin/get_author') }}',
			type:'GET',
			data: {'id':id},
			success: function(result) {
				$('#suspend_author_id').val(result.id);
				$('#resume_author_id').val(result.id);
			}
		});
	}
</script>
@stop