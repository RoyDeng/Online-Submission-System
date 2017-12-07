@extends('layouts.master')

@section('title','Registration')

@section('content')
@include('conference.chair.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Registration</h2>
		<ol class="breadcrumb">
            <li>
				{{$registration -> conference -> title}}
			</li>
			<li class="active">
				<strong>Registration</strong>
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
        <div class="col-md-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Registration</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Added Time</th>
                                    <td>{{$registration -> added_time}}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated Time</th>
                                    <td>{{$registration -> modified_time}}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>NT${{round($registration -> amount)}}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if ($registration -> status == 1)
                                            <span class="label label-success">Complete</span>
                                        @else
                                            <span class="label label-danger">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Note</th>
                                    <td>{{$registration -> note}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-md-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Registration Detail</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        <table class="table table-bordered">
							<thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
								@foreach ($registration -> registration_item as $item)
                                    <tr>
                                        <td>{{ $item -> payment -> name }}</p></td>
                                        <td>NT${{ round($item -> payment -> price) }}</td>
                                        <td>{{ $item -> quantity }}</td>
                                        <td>NT${{ $item -> quantity * round($item -> payment -> price) }}</td>
                                    </tr>
                                @endforeach
							</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Author</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td>{{$registration -> author -> firstname}} {{$registration -> author -> middlename}} {{$registration -> author -> lastname}}</td>
                                </tr>
                                <tr>
                                    <th>Institution</th>
                                    <td>{{$registration -> author -> institution}}</td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td>{{$registration -> author -> country}}</td>
                                </tr>
                                <tr>
                                    <th>Telephone</th>
                                    <td>{{$registration -> author -> tel}}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{$registration -> author -> email}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover dataTables-example">
					<thead>
						<tr>
							<th>No.</th>
							<th>Submission Type</th>
							<th>Presentation Type</th>
							<th>ID</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Receivied Time</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($manuscripts as $m => $ms)
							<tr>
								<td>{{ $m + 1 }}</td>
								<td>{{ $ms -> submission_type -> name }}</td>
								<td>
									@if ($ms -> type == 0)
										Oral
									@else
										Poster
									@endif
								</td>
								<td>{{ $ms -> number }}</td>
                                <td>{{ $ms -> title }}</td>
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
                                    	<button type="button" class="btn btn-primary" onclick="location.href='{{url('conference/chair/received_decision')}}/{{ $ms -> number }}';"><i class="fa fa-eye"></i></button>
									@endif
									@if ($ms -> final_decision != '')
										<button type="button" class="btn btn-success" onclick="location.href='{{url('conference/chair/revisions')}}/{{ $ms -> number }}';"><i class="fa fa-check"></i></button>
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