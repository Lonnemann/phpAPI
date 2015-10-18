@extends('::name.master')

@section('title')
Edit Item {{$model->id}}
@endsection

@section('content')
	<h1>Edit Item {{$model->id}}</h1>
	<form action="{{url('/::name/'.$model['attributes']['id'])}}" method="post">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="_method" value="patch">
		<div class="container">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>{!!trans('errors.errors_headline')!!}</strong><br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

			@foreach ($model['attributes'] as $column=>$value)
				@if (($column !=='id')&&($column !=='created_at')&&($column !=='updated_at'))
				<div class="col-xs-12 col-sm-6 col-sm-offset-3">

					<input type="text" value="{{$value}}" name="{{$column}}" placeholder="Enter your {{$column}} here." />	
				</div>
				@endif
			@endforeach
			<div class="col-xs-12 col-sm-6 col-sm-offset-3">
				<input type="submit" value="Submit">
			</div>
		</div>
	</form>

	<form action="{{url('/::name/'.$model['attributes']['id'])}}" method="post">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="_method" value="delete">
		<div class="col-xs-12 col-sm-6 col-sm-offset-3">
			<input type="submit" value="Delete this item.">
		</div>
	</form>
@endsection

@section('additional-scripts')

@endsection