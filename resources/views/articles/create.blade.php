@extends('layouts.app')

@section('content')
	<div class="page-header">
		<h4>포럼 <small> / 글 쓰기</small></h4>
	</div>

	<form action="{{ route('articles.store') }}" method="post" enctype="multipart/form-data">
		{!! csrf_field() !!}

		@include('articles.partial.form')

		<div class="form-group">
			<button type="submit" class="btn btn-primary">저장하기</button>
		</div>
	</form>
@stop