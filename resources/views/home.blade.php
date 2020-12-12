@extends('layouts.app')

@section('content')
<div id="app">
<input type="hidden" value="{{ Auth::user()->name }}" id="sessionName">
<input type="hidden" value="{{ Auth::user()->id }}" id="sessionId">
   <messenger-component/>
</div>
@endsection
