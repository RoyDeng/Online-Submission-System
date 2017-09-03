@extends('layouts.master')

@section('title','Conferences')

@section('content')
@include('author.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Conferences</h2>
		<ol class="breadcrumb">
			<li class="active">
				<strong>Conferences</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content">
	<div class="row">
        <div class="col-lg-12">
            <div class="middle-box text-center">
                <form id="select-conference" class="form-horizontal" action="{{ url('author/conference/upload_manuscript') }}" method="get">
                    <h3 class="font-bold">Conferences</h3>
                    <div class="form-group">
                    <select id="conference-list" class="form-control">
                        <option selected>Please Select</option>
                        @foreach ($conferences as $conf)
                            <option value="{{ $conf -> id }}">{{ $conf -> title }}</option>
                        @endforeach
                    </select>
                    </div>
                    <h3 class="font-bold">Topics</h3>
                    <div class="form-group">
                    <select id="topic-list" class="form-control" name="number"></select>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <button id="submit-select-conference" class="btn btn-primary" type="submit" disabled>Confirm</button>
                </form>
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