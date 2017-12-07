@extends('layouts.master')

@section('title','Registrations')

@section('content')
@include('author.conference.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Registrations</h2>
		<ol class="breadcrumb">
			<li>
				{{$conference -> title }}
			</li>
			<li class="active">
				<strong>Registrations</strong>
			</li>
		</ol>
	</div>
    <div class="col-sm-8">
		<div class="title-action">
			<button type="button" class="btn btn-primary" onclick="location.href='{{url('author/conference/create_registration')}}/{{ $conference -> number }}';">Register the Conference</button>
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
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Registration Fee</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($conference -> payment as $p => $pay)
                                <tr>
                                    <td>{{ $p + 1 }}</td>
                                    <td class="align-middle text-nowrap text-center">{{ $pay -> name }}</td>
                                    <td class="align-middle text-nowrap text-center">NT${{ round($pay -> price) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
							<th>Status</th>
                            <th>Total</th>
							<th>Added Time</th>
							<th>Last Updated Time</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($registrations as $r => $reg)
							<tr>
								<td>{{ $r + 1 }}</td>
								<td class="align-middle text-nowrap text-center">
									@if ($reg -> status == 1)
										<span class="label label-success">Complete</span>
									@else
										<span class="label label-danger">Pending</span>
									@endif
								</td>
								<td>NT${{ round($reg -> amount) }}</td>
								<td class="align-middle text-nowrap text-center">{{ $reg -> added_time }}</td>
								<td class="align-middle text-nowrap text-center">{{ $reg -> modified_time }}</td>
								<td class="align-middle text-nowrap text-center">
									<button type="button" class="btn btn-primary" onclick="location.href='{{url('author/conference/registration')}}/{{ $reg -> id }}';"><i class="fa fa-eye"></i></button>
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