@extends('layouts.master')

@section('title','Manuscripts')

@section('content')
@include('author.conference.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>{{ $conference -> title }}</h2>
		<ol class="breadcrumb">
			<li class="active">
				<strong>Manuscripts</strong>
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
							<th>Status</th>
							<th>Uploaded Time</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($manuscripts as $m => $ms)
							<tr>
								<td>{{ $m + 1 }}</td>
								<td>{{ $ms -> submission_type -> name }}</td>
                                <td>{{ $ms -> number }}</td>
                                <td>{{ $ms -> title }}</td>
								<td>
									@if ($ms -> final_decision != '')
										@if ($ms -> final_decision -> status == 1)
											<span class="label label-success">Pass final decision</span>
										@elseif ($ms -> final_decision -> status == 0)
											<span class="label label-danger">Rejected</span>
										@else
											<span class="label label-warning">Revision is needed</span>
										@endif
									@else
										<span class="label label-danger">Not yet decided</span>
									@endif
								</td>
								<td>{{ $ms -> added_time }}</td>
								<td>
									<button type="button" class="btn btn-primary" onclick="location.href='{{url('author/conference/manuscript')}}/{{ $ms -> number }}';"><i class="fa fa-eye"></i></button>
									@if ($ms -> invitation == '[]' && $ms -> decision == '' && $ms -> final_decision == '')
										<button type="button" class="btn btn-danger" data-target="#DeleteManuscript" data-toggle="modal" onclick="GetManuscript('{{ $ms -> id }}')"><i class="fa fa-times"></i></button>
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
<div class="modal fade col-xs-12" id="DeleteManuscript" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Delete the Manuscript</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to delete the manuscript?
				<form action="{{ url('author/conference/delete_manuscript') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="delete_manuscript_id" name="id">
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
	function GetManuscript(id) {
		$.ajax({
			url: '{{ url('author/conference/get_manuscript') }}/' + id,
			type:'GET',
			success: function(result) {
				$('#delete_manuscript_id').val(result.id);
			}
		});
	}
</script>
@stop