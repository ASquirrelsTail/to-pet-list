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
                    <form action="{{ route('tasks.destroy', ['list'=>$list, 'task'=>$task]) }}" method="POST">
                        @csrf
                        {{method_field('DELETE')}}
                        <div class="form-group row mb-0 justify-content-center">
                            <div class="col-4 text-center">
                                <button type="submit" class="btn btn-danger">
                                    Delete Task
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