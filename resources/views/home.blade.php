@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $name }}'s To Pet List</div>

                <div class="card-body">
                    <ul class="task-list">
                        @forelse ($tasks as $task)
                            <li id="task-{{ $task->id }}" class="task @if ($task->completed) task-completed @endif">
                                <div class="dropdown">
                                  <span tabindex="0" class="dropdown-toggle" id="task-actions-{{ $task->id }}" aria-role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $task->task_name }} 
                                    @if ($task->completed)
                                        <b>- DONE </b>
                                    @endif
                                  </span>
                                  <!-- Task complete/edit/delete drop down -->
                                  <div class="dropdown-menu" aria-labelledby="task-actions-{{ $task->id }}">
                                    @if (!$task->completed)
                                        <form method="post" action="{{ route('complete_task', $task->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Done!</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('task', $task->id) }}" class="btn btn-outline-primary btn-edit">Edit</a>
                                    <form method="post" action="{{ route('delete_task', $task->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                  </div>
                                </div>
                                
                            </li>
                        @empty
                            <p>You need to get some animals to pet!</p>
                        @endforelse
                    </ul>
                    <form method="post" action="{{ route('add_task') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="task_name" class="col-md-4 col-form-label text-md-right">Animal to pet:</label>

                            <div class="col-md-6">
                                <input type="text" name="task_name" id="task_name" maxlength="100" minlength="1" 
                                    placeholder="Name an animal"
                                    class="form-control @if ($error) is-invalid @endif" required>
                                @if ($error)
                                {{-- Form validation error --}}
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $error }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>
                        <div class="form-group row mb-0 justify-content-center">
                            <div class="col-4 text-center">
                                <button type="submit" class="btn btn-success">
                                    Add to list
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
