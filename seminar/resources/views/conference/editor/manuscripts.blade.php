@extends('layouts.master')

@section('title','Manuscripts')

@section('content')
@include('conference.editor.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Manuscripts</h2>
		<ol class="breadcrumb">
            <li>
				{{$topic -> conference -> title}}
			</li>
            <li>
                {{$topic -> title}}
            </li> 
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
							<th>Author</th>
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
									<h4>{{ $ms -> author -> title }}{{ $ms -> author -> firstname }} {{ $ms -> author -> middlename }} {{ $ms -> author -> lastname }}</h4>
									<p>
										<i class="fa fa-university"></i> {{ $ms -> author -> institution }}
									</p>
									<p>
										<i class="fa fa-globe"></i> {{ $ms -> author -> country }}
									</p>
									<p>
										<i class="fa fa-phone"></i> {{ $ms -> author -> tel }}
									</p>
									<p>
										<i class="fa fa-envelope"></i> {{ $ms -> author -> email }}
									</p>
								</td>
								<td>
									@if ($ms -> decision != '')
										@if ($ms -> final_decision != '')
											@if ($ms -> final_decision -> status == 1)
												<span class="label label-success">Passed</span>
											@elseif ($ms -> final_decision -> status == 0)
												<span class="label label-danger">Rejected</span>
											@else
												<span class="label label-warning">Revision is needed</span>
											@endif
										@else
										<span class="label label-success">Not yet final decided</span>
										@endif
									@else
										<span class="label label-danger">Not yet decided</span>
									@endif
								</td>
								<td>{{ $ms -> added_time }}</td>
								<td>
									<button type="button" class="btn btn-primary" onclick="location.href='{{url('conference/editor/received_reviews')}}/{{ $ms -> number }}';"><i class="fa fa-eye"></i></button>
									@if ($ms -> decision == '')
										<button type="button" class="btn btn-success" onclick="location.href='{{url('conference/editor/send_invitation')}}/{{ $ms -> number }}';"><i class="fa fa-envelope"></i></button>
									@endif
									@if ($ms -> final_decision != '')
										<button type="button" class="btn btn-warning" onclick="location.href='{{url('conference/editor/revisions')}}/{{ $ms -> number }}';"><i class="fa fa-reply"></i></button>
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
@stop