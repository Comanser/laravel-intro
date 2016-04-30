@extends('layouts.master')
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
		
@section('content')
  <div class="centered">
      @foreach ($actions as $action)
        <a href="{{ route('niceaction', ['action' => lcfirst($action->name)]) }}">{{ $action->name }}</a>
      @endforeach
      <br><br><br>
      @if (count($errors) > 0)
          <div>
              <ul>
                  @foreach ($errors->all() as $error)
                      {{ $error }}
                  @endforeach
              </ul>
          </div>
      @endif
      <form action="{{ route('add_action') }}" method="post">
        <label for="name">Name of Action:</label>
          <input type="text" name="name" id="name"/>
        <label for="niceness">Niceness:</label>
          <input type="text" name="niceness" id="niceness"/>
          <input type="hidden" value="{{ Session::token() }}" name="_token"/>
          <br><br>
          <button type="submit" onclick="send(event)">Add a nice action with AJAX</button>
          <button type="submit" value="Submit">Add a nice action</button>
      </form>
      <br>
      <ul>
        @foreach ($logged_actions as $logged_action)
          <li>
            {{ $logged_action->nice_action->name }}
            @foreach ($logged_action->nice_action->categories as $category)
              {{ $category->name }}
            @endforeach
          </li>
        @endforeach
      </ul>
      @if ($logged_actions->lastPage() > 1)
        <a href="{{ $logged_actions->previousPageUrl() }}">prev</a>
      	@for ($i = 1; $i <= $logged_actions->lastPage(); $i++)
      	  <a href="{{ $logged_actions->url($i) }}">{{ $i }}</a>
      	@endfor
      	<a href="{{ $logged_actions->nextPageUrl() }}">next</a>
      @endif
      <script>
        function send(event) {
          event.preventDefault();
          $.ajax({
            type: "POST",
            url: "{{ route('add_action') }}",
            data: {name: $("#name").val(), niceness: $("#niceness").val(), _token: "{{ Session::token() }}"}
          });
        }
      </script>
  </div>
@endsection
