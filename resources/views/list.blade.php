@extends('layouts.app')
@section('header')
@if ($list->public)
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $list->name }} by {{ $list->user->name }}" />
    <meta property="og:description" content="See which animals {{ $list->user->name }} has on their to pet list!" />
    <meta property="og:image" content="{{ route('lists.image', $list) }}" />
@endif
@endsection

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
                    @if ($list->image)
                    <img src="{{ $list->image->url }}" alt="Image for {{ $list->name }}" class="img-fluid">
                    @endif
                    <ul class="task-list">
                        @forelse ($tasks as $task)
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
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newTaskModal">
                        Add a task
                    </button>
                    <a href="{{ route('lists.edit', $list) }}" class="btn btn-primary">Edit List</a>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newTaskModal" tabindex="-1" role="dialog" aria-labelledby="newTaskModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newTaskModalLabel">New Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tasks.store', $list) }}" method="POST">
                    @include('task-form', ['verb'=>'Create new task', 'task'=>null])
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection
