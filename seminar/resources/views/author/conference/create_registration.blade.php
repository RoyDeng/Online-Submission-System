@extends('layouts.master')

@section('title','Register the Conference')

@section('content')
@include('author.conference.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Register the Conference</h2>
		<ol class="breadcrumb">
            <li>
                {{$conference -> title}}
            </li>
			<li class="active">
				<strong>Register the Conference</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content">
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
        <div class="col-lg-12">
            <form class="form-horizontal" action="{{ url('author/conference/create_registration') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-sm-2 control-label">Registration Fee</label>
                    <div class="col-sm-10">
                        <table id="registration-fee" class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($conference -> payment as $p => $pay)
                                    <tr>
                                        <td><input type="hidden" name="items[{{ $p }}][id]" value="{{$pay -> id}}"><p class="form-control-static">{{ $pay -> name }}</p></td>
                                        <td><p class="form-control-static">NT$<span class="price">{{ round($pay -> price) }}</span></p></td>
                                        <td>
                                            <select class="form-control m-b quantity" name="items[{{ $p }}][quantity]" required>
                                                @for ($i = 0; $i <= 5; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </td>
                                        <td><p class="form-control-static">NT$<span class="sub-total">0</span></p></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><p class="form-control-static">Other</p></td>
                                    <td colspan="3"><span class="price" style="display:none">1</span><input type="number" class="form-control quantity"><span class="sub-total" style="display:none">0</span></td>
                                </tr>
                            </tbody>
                        </table>
                        <span class="help-block m-b-none">If you select other, please explain in the note.</span>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Total</label>
                    <div class="col-sm-10"><input type="hidden" class="input-total" value="0" name="amount"><p class="form-control-static">NT$<span class="text-total">0</span></p></div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label for="note" class="col-sm-2 control-label">Note<br/>
                    <small class="text-navy">optional</small></label>
                    <div class="col-sm-10"><textarea id="note" class="form-control" name="note" rows="10"></textarea></div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <input type="hidden" name="conference_id" value="{{$conference -> id}}">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
		<div>
			Copyright © {{ date("Y") }} Chung Yuan Christian University All Rights
		</div>
	</div>
</div>
@stop