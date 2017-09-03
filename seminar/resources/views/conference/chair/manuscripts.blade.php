@extends('layouts.master')

@section('title','Manuscripts')

@section('content')
@include('conference.chair.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Manuscripts</h2>
		<ol class="breadcrumb">
			<li>
				{{$conference -> title}}
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
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Receivied Time</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($manuscripts as $m => $ms)
							<tr>
								<td>{{ $m + 1 }}</td>
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
									@if ($ms -> final_decision != '')
										@if ($ms -> final_decision -> status == 1)
											<span class="label label-success">Passed</span>
										@elseif ($ms -> final_decision -> status == 0)
											<span class="label label-danger">Rejected</span>
										@else
											<span class="label label-warning">Revision is needed</span>
										@endif
									@elseif ($ms -> decision != '')
										<span class="label label-danger">Not yet decided</span>
									@else
										<span class="label label-danger">Not yet received decision</span>
									@endif
								</td>
                                <td>{{ $ms -> added_time }}</td>
                                <td>
									@if ($ms -> decision != '')
                                    	<button type="button" class="btn btn-{{$ms -> final_decision == '' ? 'success' : 'primary' }}" onclick="location.href='{{url('conference/chair/received_decision')}}/{{ $ms -> number }}';"><i class="fa fa-{{$ms -> final_decision == '' ? 'reply' : 'eye' }}"></i></button>
									@endif
									@if ($ms -> final_decision != '' && $ms -> final_decision -> status == 2)
										<button type="button" class="btn btn-warning" onclick="location.href='{{url('conference/chair/revisions')}}/{{ $ms -> number }}';"><i class="fa fa-check"></i></button>
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