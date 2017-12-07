@extends('layouts.master')

@section('title','Revision')

@section('content')
@include('author.conference.partials.nav')
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-4">
		<h2>Revision</h2>
		<ol class="breadcrumb">
            <li>
                {{$revision -> final_decision -> manuscript -> topic -> conference -> title}}
            </li>
            <li>
                {{$revision -> final_decision -> manuscript -> topic -> title}}
            </li>
            <li>
                {{$revision -> final_decision -> manuscript -> title}}
            </li>
			<li class="active">
				<strong>Revision</strong>
			</li>
		</ol>
	</div>
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-md-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Revision Information</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Chair</th>
                                    <td>
                                        <h4>{{ $revision -> final_decision -> chair -> title }} {{ $revision -> final_decision -> chair -> firstname }} {{ $revision -> final_decision -> chair -> middlename }} {{ $revision -> final_decision -> chair -> lastname }}</h4>
                                        <p>
                                            <i class="fa fa-university"></i> {{ $revision -> final_decision -> chair -> institution }}
                                        </p>
                                        <p>
                                            <i class="fa fa-globe"></i> {{ $revision -> final_decision -> chair -> country }}
                                        </p>
                                        <p>
                                            <i class="fa fa-phone"></i> {{ $revision -> final_decision -> chair -> tel }}
                                        </p>
                                        <p>
                                            <i class="fa fa-envelope"></i> {{ $revision -> final_decision -> chair -> email }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Comment</th>
                                    <td>{{$revision -> comment}}</td>
                                </tr>
                                <tr>
                                    <th>Deadline</th>
                                    <td>{{$revision -> deadline}}</td>
                                </tr>
                                <tr>
                                    <th>Added Time</th>
                                    <td>{{$revision -> added_time}}</td>
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
                    <h5>Revision Reviews from Reviewers</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        @if ($revision -> revised_manuscript -> re_invitation -> where('status', 1) != '[]')
                            <div style="max-height: 250px; overflow-y:scroll;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Reply Time</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($revision -> revised_manuscript -> re_invitation -> where('status', 1) as $i => $invitation)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $invitation -> modified_time }}</td>
                                                <td>
                                                    @if ($invitation -> status == 1)
                                                        <button type="button" class="btn btn-primary" data-target="#GetReview" data-toggle="modal" onclick="GetReview('{{ $invitation -> re_review -> id }}')"><i class="fa fa-eye"></i></button>
                                                    @endif
                                                    @if ($invitation -> status == 2)
                                                        <button type="button" class="btn btn-primary" data-target="#GetReply" data-toggle="modal" onclick="GetReply('{{ $invitation -> id }}')"><i class="fa fa-eye"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-danger alert-dismissible fade in">
                                <strong>You haven't received any revision review!</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="row">
        <div class="col-lg-12">
        <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Revised Manuscript</h5>
                </div>
                <div>
                    <div class="ibox-content">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Number</th>
                                    <td>{{$revision -> revised_manuscript -> number}}</td>
                                </tr>
                                <tr>
                                    <th>Files</th>
                                    <td>
                                        <ul class="list-unstyled file-list">
                                            @foreach ($revision -> revised_manuscript -> revised_file as $file)
                                                <li><a href="/upload/{{$file -> url}}" download>{{$file -> name}}</a></li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Added Time</th>
                                    <td>{{$revision -> revised_manuscript -> added_time}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
<div class="modal fade col-xs-12" id="GetReview" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">The Review Detail</h4>
			</div>
			<div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Comment</th>
                            <td><p id="comment"></p></td>
                        </tr>
                        <tr>
                            <th>File</th>
                            <td><a id="review_file_url" download><span id="review_file_name"></span></a></td>
                        </tr>
                    </tbody>
                </table>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function GetReview(id) {
		$.ajax({
			url: '{{ url('author/conference/get_re_review') }}/' + id,
			type:'GET',
			success: function(result) {
				$('#comment').text(result.comment_author);
				$('#review_file_name').text(result.re_review_file.name);
                $('#review_file_url').attr('href', '/upload/' + result.re_review_file.url);
			}
		});
	}
</script>
@stop