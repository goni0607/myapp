@php
	$voted = null;

	if ($currentUser) {
		$voted = $comment->votes->contains('user_id', $currentUser->id)
			? 'disabled="disabled"' : null;
	}
@endphp

@if ($isTrashed and !$hasChild)
	{{-- 1. 삭제된 댓글이면서 자식 댓글도 없을 경우 아무것도 출력하지 않는다. --}}

@elseif ($isTrashed and $hasChild)
	{{-- 2. 삭제된 댓글이지면 자식 댓글이 있을 경우 '삭제되었습니다' 라고 알리고 자식 댓글은 계속 출력한다. --}}
	<div class="text-danger content__comment">
		<p>삭제된 댓글입니다.</p>
	</div>

	@forelse($comment->replies as $reply)
		@include('comments.partial.comment', [
			'comment' => $reply,
			'isReply' => true,
			'hasChild' => $reply->replies->count(),
			'isTrashed' => $reply->trashed(),
		])
	@empty
	@endforelse
@else
	<div class="media item__comment {{ $isReply ? 'sub' : 'top' }}" data-id="{{ $comment->id }}" id="comment_{{ $comment->id }}">
		@include('users.partial.avatar', ['user' => $comment->user, 'size' => 32])

		<div class="media-body">
			<h5 class="media-heading">
				<a href="{{ gravatar_profile_url($comment->user->email) }}">{{ $comment->user->name }}</a>
				<small>{{ trans('comments.created_at', ['when' => $comment->created_at->diffForHumans()]) }}</small>
			</h5>

			<div class="content__comment">
				{!! markdown($comment->content) !!}
			</div>

			<div class="action__comment">
			@if ($currentUser)
				<button class="btn__vote__comment" data-vote="up" title="좋아요" {{ $voted }}>
					<i class="fa fa-chevron-up"></i> <span>{{ $comment->up_count }}</span>
				</button>
				<span> | </span>
				<button class="btn__vote__comment" data-vote="down" title="싫어요" {{ $voted }}>
					<i class="fa fa-chevron-down"></i> <span>{{ $comment->down_count }}</span>
				</button>
				•
			@endif
			@can('update', $comment)
				<button class="btn__delete_comment">댓글 삭제</button>
				<button class="btn__edit_comment">댓글 수정</button>
			@endcan

			@if ($currentUser)
				<button class="btn__reply__comment">답글 쓰기</button>
			@endif
			</div>

			@if($currentUser)
				@include('comments.partial.create', ['parentId' => $comment->id])
			@endif

			{{--
			@can('update', $comment)
				@include('comment.partial.edit')
			@endcan
			--}}
			
			@forelse($comment->replies as $reply)
				@include('comments.partial.comment', [
					'comment' => $reply,
					'isReply' => true,
					'hasChild' => $reply->replies->count(),
					'isTrashed' => $reply->trashed(),
				])
			@empty
			@endforelse
		</div>
	</div>
@endif