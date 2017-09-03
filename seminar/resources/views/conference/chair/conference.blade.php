@extends('layouts.master')

@section('title','Conference')

@section('content')
@include('conference.chair.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Conference</h2>
		<ol class="breadcrumb">
			<li class="active">
				<strong>Conference</strong>
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
    @if ($msg = Session::get('warn'))
		<div class="row">
			<div class="col-lg-12">
				<div class="alert alert-warning alert-dismissible fade in">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<strong>{{ $msg }}</strong>
				</div>
			</div>
		</div>
	@endif
	<div class="row">
        <div class="col-lg-12">
            <form class="form-horizontal" action="{{ url('conference/chair/edit_conference') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group"></div>
                <div class="form-group">
                    <label for="title" class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10"><input id="title" type="text" class="form-control" name="title" value="{{$conference -> title }}"></div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Submission Type</label>
                    <div class="col-sm-10">
                        <div> <label> <input type="checkbox" {{$conference_type_abstract -> status == 1 ? 'checked' : '' }} name="abstract" value="abstract"> Abstract </label> </div>
                        <div> <label> <input type="checkbox" {{$conference_type_extended_abstract -> status == 1 ? 'checked' : '' }} name="extended_abstract" value="extended_abstract"> Extended Abstract </label> </div>
                        <div> <label> <input type="checkbox" {{$conference_type_full_paper -> status == 1 ? 'checked' : '' }} name="full_paper" value="full_paper"> Full Paper </label> </div>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label for="abstract-submission-deadline" class="col-sm-2 control-label">Abstract Submission Deadline</label>
                    <div class="col-sm-10"><input id="abstract-submission-deadline" type="text" class="form-control deadline" name="abstract_submission_deadline" value="{{$conference_type_abstract -> submission_deadline }}"></div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label for="extended-abstract-submission-deadline" class="col-sm-2 control-label">Extended Abstract Submission Deadline</label>
                    <div class="col-sm-10"><input id="extended-abstract-submission-deadline" type="text" class="form-control deadline" name="extended_abstract_submission_deadline" value="{{$conference_type_extended_abstract -> submission_deadline }}"></div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label for="full-paper-submission-deadline" class="col-sm-2 control-label">Full Paper Submission Deadline</label>
                    <div class="col-sm-10"><input id="full-paper-submission-deadline" type="text" class="form-control deadline" name="full_paper_submission_deadline" value="{{$conference_type_full_paper -> submission_deadline }}"></div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">Save</button>
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