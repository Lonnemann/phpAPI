@extends('::name.master')

@section('title')
Create
@endsection

@section('content')
	<div class="container">
		<h1>Create new item</h1>
		<a href="{{url('/::name/')}}" >Back to index</a>
		<form action="{{url('/::name')}}" method="post" class="form">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
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
				<label for="{{$column}}">Enter your {{$column}} here.</label>
                    <div class="form-group">
						<input class="form-control" type="text" value="{{old($column)}}" name="{{$column}}" id="{{$column}}" placeholder="Enter your {{$column}} here." />
					</div>	
				</div>
			@endforeach
			<div class="col-xs-12 col-sm-6 col-sm-offset-3">
				<label for="submit">Submit</label>
				<div class="form-group">
					<button type="submit" id="submit" class="form-control btn btn-success">Submit</button>	
				</div>
			</div>
		</form>
	</div>
@endsection

@section('additional-scripts')

@endsection