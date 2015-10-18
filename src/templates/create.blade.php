@extends('::name.master')

@section('title')
Create
@endsection

@section('content')
	<h1>Create</h1>
	<form action="{{url('/::name')}}" method="post">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
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
				<div class="col-xs-12 col-sm-6 col-sm-offset-3">
					<input type="text" value="{{old($column)}}" name="{{$column}}" placeholder="Enter your {{$column}} here." />	
				</div>
			@endforeach
		<div class="col-xs-12 col-sm-6 col-sm-offset-3">
			<input type="submit" value="Submit">
		</div>
		</div>
	</form>
@endsection

@section('additional-scripts')

@endsection