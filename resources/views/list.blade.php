@extends('layouts.app')
@section('header')
@if ($list->public)
    <meta name="title" content="{{ $list->name }} by {{ $list->user->name }}">
    <meta name="description" content="See which animals {{ $list->user->name }} has on their to pet list!">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:title" content="{{ $list->name }} by {{ $list->user->name }}">
    <meta property="og:description" content="See which animals {{ $list->user->name }} has on their to pet list!">
    @if ($list->image)
    <meta property="og:image" content="{{ route('lists.image', $list) }}">
    @else
    <meta property="og:image" content="{{ secure_url('images/share-image.jpg') }}">
    @endif

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ Request::url() }}">
    <meta property="twitter:title" content="{{ $list->name }} by {{ $list->user->name }}">
    <meta property="twitter:description" content="See which animals {{ $list->user->name }} has on their to pet list!">
    @if ($list->image)
    <meta property="twitter:image" content="{{ route('lists.image', $list) }}">
    @else
    <meta property="twitter:image" content="{{ secure_url('images/share-image.jpg') }}">
    @endif
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
                    @can('update', $list)
                    <a href="{{ route('lists.edit', $list) }}" id="edit-list" class="btn btn-primary">Edit List</a>
                    @else
                    By {{ $list->user->name }}
                    @endcan
                </div>
                <div class="card-body">
                    <ul id="task-list">
                        @forelse ($tasks as $task)
                            <li id="task-{{ $task->id }}" data-task-id="{{ $task->id }}"
                                    data-task-path="{{ route('tasks.show', ['list'=>$list, 'task'=>$task]) }}"
                                    class="task @if ($task->completed) task-completed @endif @if ($task->priority) task-priority @endif">
                                <span class="task-name">{{ $task->name }}</span>
                            </li>
                        @empty
                            <p class="empty">You need to get some animals to pet!</p>
                        @endforelse
                    </ul>
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

@if ($permissions['delete'])
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <span id="delete-info"></span>?</p>
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-danger text-center" id="delete-button">Confirm Delete</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
