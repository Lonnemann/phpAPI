@extends('::master')

@section('title')
Create
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="small-12 large-6 columns">
				<h1>Create new item</h1>
				<a href="{{url('/::name/')}}" >Back to index</a>
			</div>
		</div>
		
		<form action="{{url('/::name')}}" method="post" class="form">
			<div class="row">
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
				<div class="small-12 large-6 columns">
					<div class="small-12 large-6 columns">
						<label for="{{$column}}" class="right">Enter your {{$column}} here.</label>
					</div>
                    <div class="small-12 large-6 columns">
						<input type="text" value="{{old($column)}}" name="{{$column}}" id="{{$column}}" placeholder="Enter your {{$column}} here." />
					</div>	
				</div>			
			@endforeach
			</div>
			<div class="row">
				<div class="small-12 large-6 columns">
					<div class="small-12 large-6 columns">
						<label for="submit" class="right">Submit</label>
					</div>
	                <div class="small-12 large-6 columns">
						<button type="submit" id="submit" class="small-12 large-12 columns button round success">Submit</button>	
					</div>
				</div>
			</div>
		</form>
	</div>
@endsection

@section('additional-scripts')

@endsection