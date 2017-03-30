@extends('layouts.app')

@section('content')
	@php $viewName = 'articles.show'; @endphp

	<div class="page-header">
		<h4>포럼<small> / {{ $article->title }}</small></h4>
	</div>

	<article data-id="{{ $article->id }}">
		@include('articles.partial.article', compact('article'))
		{!! nl2br(markdown($article->content)) !!}
		@include('tags.partial.list', ['tags' => $article->tags])
	</article>

	<div class="text-center action__article">
	@can('update', $article)
		<a href="{{ route('articles.edit', $article->id) }}" class="btn btn-info">
			<i class="fa fa-pencil"></i> 글 수정
		</a>
		<button class="btn btn-danger button__delete">
			<i class="fa fa-trash-o"></i> 글 삭제
		</button>
	@endcan
		<a href="{{ route('articles.index') }}" class="btn btn-default">
			<i class="fa fa-list"></i> 글 목록
		</a>
	</div>
	{{ sys_get_temp_dir() }}<br />
	{{ exec("whoami") }}
@stop

@section('script')
	<script>
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$('.button__delete').on('click', function(e) {
			var articleId = $('article').data('id');

			if (confirm('글을 삭제합니다.')) {
				$.ajax({
					type: 'DELETE',
					url: '/articles/' + articleId
				}).then(function() {
					window.location.href = '/articles';
				});
			}
		});
	</script>
@stop