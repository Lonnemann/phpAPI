@extends('::master.master')

@section('title')
Show Item {{$model->id}}
@endsection

@section('content')
<div class="container">
	<div class="row">
		<h1>Show Item {{$model->id}}</h1>
		<a href="{{url('/::name/')}}" >Back to index</a>
		@if ($allowed['action'])
			| <a href="{{url('::name/')}}/{{$model['attributes']['id']}}/edit">Edit</a>
		@endif
		@if (\Session::has('flash_message'))
		    <div class="alert alert-success">
		      	<span class="glyphicon glyphicon-ok-circle"></span> {{(\Session::get('flash_message'))}}
		        {{(\Session::forget('flash_message'))}}
		    </div>
		@endif
	</div>
	@if (count($model)>0) 
	@foreach ($model['attributes'] as $column	=> $value)
	<div class="row">
		@if  (($column !=='id')&&($column !=='created_at')&&($column !=='updated_at'))
		<div class="mvc-element mvc-element-show ::name-element ::name-element-show">
			{{$value}}
		</div>
		@endif
	</div>
	@endforeach
	@endif
</div>
@endsection

@section('additional-scripts')

@endsection