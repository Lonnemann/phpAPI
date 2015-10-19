@extends('::name.master')

@section('title')
Index
@endsection

@section('content')
<div class="container">
	<h1>Index</h1>
	<a href="{{url('/::name/create')}}" >Add Item</a>
	@if (\Session::has('flash_message'))
	    <div class="alert alert-success">
	        {{(\Session::get('flash_message'))}}
	        {{(\Session::forget('flash_message'))}}	        
	    </div>
	@endif
	@if (count($models)>0) 
	@foreach ($models as $element)
	<div class="row">
		@foreach ($element['attributes'] as $column	=> $value)
			@if  (($column !=='id')&&($column !=='created_at')&&($column !=='updated_at'))
			<div  class="mvc-element mvc-element-index ::name-element ::name-element-index"><a href="{{url('::name/')}}/{{$element['attributes']['id']}}">{{$value}}</a></div>
			@endif
		@endforeach
	</div>
	@endforeach
	@endif
</div>
@endsection

@section('additional-scripts')

@endsection