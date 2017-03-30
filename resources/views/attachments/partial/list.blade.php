	@if ($attachments->count())
	<ul class="attachment__article">
		@foreach ($attachments as $attachment)
		<li>
			<i class="fa fa-paperclip"></i>
			<a href="{{ $attachment->url }}">{{ $attachment->filename }} ({{ $attachment->bytes }})</a>
		</li>
		@endforeach
	</ul>
	@endif