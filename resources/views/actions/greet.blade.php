@extends('layouts.master')

@section('content')
  <div class="centered">
      <a href="{{ route('home') }}">Home</a>
      <h1>I greet {{ $name === null ? 'you' : $name }}!</h1>
      <a href="/">Home</a>
  </div>
@endsection