@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a new list</div>
                <div class="card-body">
                    <form action="{{ route('lists.store') }}" method="POST" enctype="multipart/form-data">
                		@include('list-form', ['verb'=>'Create new list', 'list'=>false])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection