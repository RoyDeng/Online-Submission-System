@extends('layouts.master')

@section('title','Payment')

@section('content')
@include('conference.chair.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Payment</h2>
		<ol class="breadcrumb">
			<li>
				{{$conference -> title }}
			</li>
			<li class="active">
				<strong>Payment</strong>
			</li>
		</ol>
	</div>
	<div class="col-sm-8">
		<div class="title-action">
			<button type="button" class="btn btn-primary" data-target="#CreatePayment" data-toggle="modal">Create a Payment</button>
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
							<th>Name</th>
							<th>Price</th>
							<th>Added Time</th>
							<th>Last Updated Time</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($payment as $p => $pay)
							<tr>
								<td>{{ $p + 1 }}</td>
								<td class="align-middle text-nowrap text-center">{{ $pay -> name }}</td>
								<td class="align-middle text-nowrap text-center">NT${{ round($pay -> price) }}</td>
								<td class="align-middle text-nowrap text-center">{{ $pay -> added_time }}</td>
								<td class="align-middle text-nowrap text-center">{{ $pay -> modified_time }}</td>
								<td class="align-middle text-nowrap text-center">
									<button type="button" class="btn btn-info" data-target="#EditPayment" data-toggle="modal" onclick="GetPayment('{{ $pay -> id }}')"><i class="fa fa-pencil-square-o"></i></button>
                                    <button type="button" class="btn btn-danger" data-target="#DelPayment" data-toggle="modal" onclick="GetPayment('{{ $pay -> id }}')"><i class="fa fa-times"></i></button>
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
<div class="modal fade col-xs-12" id="CreatePayment" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Create a Payment</h4>
			</div>
			<div class="modal-body">
				<form action="{{ url('conference/chair/create_payment') }}" method="post">
					{{ csrf_field() }}
					<div class="form-group">
						<label class="form-label" for="name">Name</label>
						<div class="controls">
							<input class="form-control" id="name" name="name" required>
						</div>
					</div>
                    <div class="form-group">
						<label class="form-label" for="price">Price</label>
						<div class="controls">
							<input class="form-control" id="price" type="number" name="price" required>
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
<div class="modal fade col-xs-12" id="EditPayment" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit the Payment</h4>
			</div>
			<div class="modal-body">
				<form action="{{ url('conference/chair/edit_payment') }}" method="post">
					{{ csrf_field() }}
                    <div class="form-group">
						<label class="form-label" for="edit_payment_name">Name</label>
						<div class="controls">
							<input class="form-control" id="edit_payment_name" name="name" required>
						</div>
					</div>
                    <div class="form-group">
						<label class="form-label" for="edit_payment_price">Price</label>
						<div class="controls">
							<input class="form-control" id="edit_payment_price" type="number" name="price" required>
						</div>
					</div>
					<input type="hidden" id="edit_payment_id" name="id">
					<button type="submit" class="btn btn-success">Confirm</button>
				</form>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade col-xs-12" id="DelPayment" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Delete the Payment</h4>
			</div>
			<div class="modal-body">
				<i class="fa fa-question-circle fa-lg"></i>  
				Do you want to delete the conference?
				<form action="{{ url('conference/chair/del_payment') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" id="del_payment_id" name="id">
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
	function GetPayment(id) {
		$.ajax({
			url: '{{ url('conference/chair/get_payment') }}',
			type:'GET',
			data: {'id':id},
			success: function(result) {
				$('#edit_payment_id').val(result.id);
				$('#edit_payment_name').val(result.name);
                $('#edit_payment_price').val(Math.round(result.price));
				$('#del_payment_id').val(result.id);
			}
		});
	}
</script>
@stop