@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1>{{ $list->name }}</h1>
                    By {{ $list->user->name }}
                </div>
                <div class="card-body">
                    <ul class="task-list">
                        @forelse ($list->tasks as $task)
                            <li id="task-{{ $task->id }}" class="task @if ($task->completed) task-completed @endif">
                                <div class="dropdown">
                                  <span tabindex="0" class="dropdown-toggle" id="task-actions-{{ $task->id }}" aria-role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $task->name }} 
                                    @if ($task->completed)
                                        <b>- DONE </b>
                                    @endif
                                  </span>
                                  <!-- Task complete/edit/delete drop down -->
                                  <div class="dropdown-menu" aria-labelledby="task-actions-{{ $task->id }}">
                                    @if (!$task->completed)
                                        <form method="post" action="{{ route('tasks.completed', ['list'=>$list, 'task'=>$task]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Done!</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('tasks.edit', ['list'=>$list, 'task'=>$task]) }}" class="btn btn-outline-primary btn-edit">Edit</a>
                                    <form method="post" action="{{ route('tasks.destroy', ['list'=>$list, 'task'=>$task]) }}">
                                        @csrf
                                        {{method_field('DELETE')}}
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                  </div>
                                </div>
                                
                            </li>
                        @empty
                            <p>You need to get some animals to pet!</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
