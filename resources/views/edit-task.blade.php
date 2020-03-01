@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit {{ $task->name }} in list {{$list->name}}</div>
                <div class="card-body">
                    <form action="{{ route('tasks.update', ['list'=>$list, 'task'=>$task]) }}" method="POST">
                		@include('task-form', ['verb'=>'Update task'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection