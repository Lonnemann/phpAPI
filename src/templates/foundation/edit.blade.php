@extends('::master')

@section('title')
Edit Item {{$model->id}}
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="small-12 large-6 columns">
				<h1>Edit Item {{$model->id}}</h1>
			</div>
		</div>
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
			<div class="row">
					
				@foreach ($model['attributes'] as $column=>$value)
					@if (($column !=='id')&&($column !=='created_at')&&($column !=='updated_at'))	
						<div class="small-12 large-6 columns" >
							<div class="small-12 large-6 columns">
								<label class="right" for="{{$column}}">Enter your {{$column}} here.</label>
							</div>
		                    <div class="small-12 large-6 columns">
								<input  type="text" value="{{$value}}" name="{{$column}}" id="{{$column}}"  placeholder="Enter your {{$column}} here." />	
							</div>
						</div>
					@endif
				@endforeach
			</div>
					
			<div class="row">
				<div class="small-12 large-6 columns">
					<div class="small-12 large-6 columns">
						<label for="submit" class="right">Submit</label>
					</div>
					<div class="small-12 large-6 columns">
						<button type="submit" id="submit" class="small-12 large-12 button round success">Submit</button>
					</div>
				</div>
			</div>
		</form>

		<form action="{{url('/::name/'.$model['attributes']['id'])}}" method="post" class="form">
			<div class="row">
			
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="_method" value="delete">
			
				<div class="small-12 large-6 columns">
					<div class="small-12 large-6 columns">
						<label class="right" for="delete">Delete</label>
					</div>
					<div class="small-12 large-6 columns">
						<button type="submit" id="delete" class="small-12 large-12 button round alert">Delete</button>
					</div>
				</div>
			</div>
		</form>
	</div>
@endsection

@section('additional-scripts')

@endsection