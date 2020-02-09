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
                <div class="card-header">Edit: {{ $task->task_name }}</div>

                <div class="card-body">
                    <form method="post" action="{{ route('edit_task', $task->id) }}">
                        @csrf
                        <label for="task_name">Animal to pet:</label>
                        <input type="text" name="task_name" max="100" min="1" 
                            placeholder="{{ $task->task_name }}"
                            value="{{ $task->task_name }}">
                        @if ($error)
                            <br>{{ $error }}
                        @endif
                        <br><label for="completed">Done:</label>
                        @if ($task->completed)
                            <input type="checkbox" name="completed" checked>
                        @else
                            <input type="checkbox" name="completed">
                        @endif
                        <input type="submit" value="Edit!">
                    </form>
                    <form method="post" action="{{ route('delete_task', $task->id) }}">
                        @csrf
                        <input type="submit" value="Delete!">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
