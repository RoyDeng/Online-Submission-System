@extends('layouts.master')

@section('title','Conferences')

@section('content')
@include('author.conference.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>{{ $conference -> title }}</h2>
		<ol class="breadcrumb">
			<li class="active">
				<strong>Topics</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
			<div class="ibox-title">
                <h5>Select a topic that you would like to submit.</h5>
            </div>
            <div class="ibox-content">
                <div class="list-group">
                    @foreach ($conference -> topic -> where('status', 1) as $t => $topic)
						<a class="list-group-item" href="{{url('author/conference/upload_manuscript')}}/{{ $topic -> number }}">
							<h3 class="list-group-item-heading">{{ $topic -> title }}</h3>
						</a>
                    @endforeach
                </div>
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