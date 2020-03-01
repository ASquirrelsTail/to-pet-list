@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a new task for {{ $list->name }}</div>
                <div class="card-body">
                    <form action="{{ route('tasks.store', $list) }}" method="POST">
                		@include('task-form', ['verb'=>'Create new task', 'task'=>null])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection