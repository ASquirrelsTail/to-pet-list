@extends('layouts.app')
@section('header')
@if ($list->public)
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $list->name }} by {{ $list->user->name }}" />
    <meta property="og:description" content="See which animals {{ $list->user->name }} has on their to pet list!" />
    <meta property="og:image" content="{{ route('lists.image', $list) }}" />
@endif
<script>
    window.permissions = @json($permissions);
</script>
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
                    <ul id="task-list">
                        @forelse ($tasks as $task)
                            <li id="task-{{ $task->id }}" data-task-id="{{ $task->id }}"
                                    data-task-path="{{ route('tasks.show', ['list'=>$list, 'task'=>$task]) }}"
                                    class="task @if ($task->completed) task-completed @endif">
                                <span class="task-name">{{ $task->name }}</span>
                            </li>
                        @empty
                            <p class="empty">You need to get some animals to pet!</p>
                        @endforelse
                    </ul>
                    @can('update', $list)
                    <a href="{{ route('lists.edit', $list) }}" class="btn btn-primary">Edit List</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

@if ($permissions['create'] || $permissions['update'])
<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">New Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tasks.store', $list) }}" method="POST" id="task_form">
                    @include('task-form', ['verb'=>'Create new task', 'task'=>null])
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
