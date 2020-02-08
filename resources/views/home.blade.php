@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">{{ $name }}'s To Pet List</div>

                <div class="card-body">
                    <ul>
                        @forelse ($tasks as $task)
                            <li>
                                {{ $task->task_name }}
                                
                            </li>
                        @empty
                            <p>You need to get some animals to pet!</p>
                        @endforelse
                    </ul>
                    <form method="post" action="{{ route('add_task') }}">
                        @csrf
                        <label for="task_name">Animal to pet:</label>
                        <input type="text" name="task_name" max="100" min="1" placeholder="Enter an animal to add to the list">
                        @if ($error)
                            <br>{{ $error }}
                        @endif
                        <br>
                        <input type="submit" value="Add to list!">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
