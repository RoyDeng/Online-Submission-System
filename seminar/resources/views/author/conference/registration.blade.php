@extends('layouts.master')

@section('title','Registration')

@section('content')
@include('author.conference.partials.nav')
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
    <div class="col-sm-8">
		<div class="title-action">
			@if ($registration -> status == 0)
                <button type="button" class="btn btn-primary" data-target="#CheckoutRegistration" data-toggle="modal">Checkout (Paying by Credit Card)</button>
			@endif
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
<div class="modal fade col-xs-12" id="CheckoutRegistration" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Check the Registration (Paying by Credit Card)</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
                Do you want to pay by credit card?
				<form id="confirm-registration-form" action="{{ url('author/conference/checkout_registration') }}" method="post">
					{{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$registration -> id}}">
                    <input type="hidden" name="amount" value="{{round($registration -> amount)}}">
                    <input type="hidden" name="note" value="{{$registration -> note}}">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
@stop