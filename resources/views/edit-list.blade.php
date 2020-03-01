@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit {{ $list->name }}</div>
                <div class="card-body">
                    <form action="{{ route('lists.edit', $list) }}" method="POST">
                		@include('list-form', ['verb'=>'Update list'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection