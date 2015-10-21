@extends('::name.master')

@section('title')
Index
@endsection

@section('content')
<div class="container">
	<div class="row">
		<h1>Index</h1>
		@if (!(Auth::guest()))
			<a href="{{url('/::name/create')}}" >Add Item</a>
		@endif
		@if (\Session::has('flash_message'))
		    <div class="alert alert-success">
		        <span class="glyphicon glyphicon-ok-circle"></span> {{(\Session::get('flash_message'))}}
		        {{(\Session::forget('flash_message'))}}	        
		    </div>
		@endif
	</div>
	@if (count($models)>0) 
	<div class="row">
		<table class="table">
		@foreach ($models as $element)
			<tr>
			@foreach ($element['attributes'] as $column	=> $value)
				@if  (($column !=='id')&&($column !=='created_at')&&($column !=='updated_at'))
				<td  class="mvc-element mvc-element-index ::name-element ::name-element-index">
					<a href="{{url('::name/')}}/{{$element['attributes']['id']}}">{{$value}}</a>
				</td>
				@endif
			@endforeach
			</tr>
		@endforeach
		</table>
	</div>
	@endif
</div>
@endsection

@section('additional-scripts')

@endsection