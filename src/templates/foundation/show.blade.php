@extends('::master')

@section('title')
Show Item {{$model->id}}
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="small-12 large-6 columns">
			<h1>Show Item {{$model->id}}</h1>
			<a href="{{url('/::name/')}}" >Back to index</a>
			@if ($allowed['action'])
				| <a href="{{url('::name/')}}/{{$model['attributes']['id']}}/edit">Edit</a>
			@endif
		</div>
	</div>
	<div class="row">
		@if (\Session::has('flash_message'))
		    <div class="alert alert-success">
		      	<span class="glyphicon glyphicon-ok-circle"></span> {{(\Session::get('flash_message'))}}
		        {{(\Session::forget('flash_message'))}}
		    </div>
		@endif
	</div>
	<div class="row">
		@if (count($model)>0) 
			<div class="small-12 large-6 columns">
			@foreach ($model['attributes'] as $column	=> $value)
				@if  (($column !=='id')&&($column !=='created_at')&&($column !=='updated_at'))
				<div class="mvc-element mvc-element-show ::name-element ::name-element-show">
					{{$value}}
				</div>
				@endif
			@endforeach
			</div>
		@endif
	</div>
</div>
@endsection

@section('additional-scripts')

@endsection