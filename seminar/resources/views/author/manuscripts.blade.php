@extends('layouts.master')

@section('title','Manuscripts')

@section('content')
@include('author.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Manuscripts</h2>
		<ol class="breadcrumb">
			<li class="active">
				<strong>Manuscripts</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content">
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
							<th>Uploaded Time</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($manuscripts as $m => $ms)
							<tr>
								<td>{{ $m + 1 }}</td>
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
@stop