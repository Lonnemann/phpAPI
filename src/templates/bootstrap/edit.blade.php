@extends('::master')

@section('title')
Edit Item {{$model->id}}
@endsection

@section('content')
	<div class="container">
		<h1>Edit Item {{$model->id}}</h1>
		<form action="{{url('/::name/'.$model['attributes']['id'])}}" method="post">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="_method" value="patch">
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
					<label for="{{$column}}">Enter your {{$column}} here.</label>
                    <div class="form-group">
						<input class="form-control" type="text" value="{{$value}}" name="{{$column}}" id="{{$column}}"  placeholder="Enter your {{$column}} here." />	
					</div>
				</div>
				@endif
			@endforeach
			<div class="col-xs-12 col-sm-6 col-sm-offset-3">
				<label for="submit">Submit</label>
				<div class="form-group">
					<button type="submit" id="submit" class="form-control btn btn-success">Submit</button>
				</div>
			</div>
		
	</form>

	<form action="{{url('/::name/'.$model['attributes']['id'])}}" method="post" class="form">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="_method" value="delete">
		<div class="col-xs-12 col-sm-6 col-sm-offset-3">
				<label for="delete">Delete</label>
				<div class="form-group">
					<button type="submit" id="delete" class="form-control btn btn-danger">Delete</button>
				</div>
			</div>
	</form>
	</div>
@endsection

@section('additional-scripts')

@endsection