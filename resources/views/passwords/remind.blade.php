@extends('layouts.app')

@section('content')
	<form action="{{ route('remind.store')}}" method="post" class="form__auth">
		{!! csrf_field() !!}

    		<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
      		<input type="email" name="email" class="form-control" placeholder="이메일" value="{{ old('email') }}"/>
      		{!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    		</div>

	    <button class="btn btn-primary btn-lg btn-block" type="submit">
	      {{ trans('auth.passwords.send_reminder') }}
	    </button>
	</form>
@stop