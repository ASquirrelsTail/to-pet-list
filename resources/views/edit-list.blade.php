@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit {{ $list->name }}</div>
                <div class="card-body">
                    <form action="{{ route('lists.update', $list) }}" method="POST">
                		@include('list-form', ['verb'=>'Update list'])
                    </form>
                    <form action="{{ route('lists.destroy', $list) }}" method="POST">
                        @csrf
                        {{method_field('DELETE')}}
                        <div class="form-group row mb-0 justify-content-center">
                            <div class="col-4 text-center">
                                <button type="submit" class="btn btn-danger">
                                    Delete List
                                </button>
                            </div>
                        </div>
                    </form>
                    <ul>
                        @forelse($list->shares as $share)
                        <li>
                            {{ $share->user ? $share->user->name : $share->email }} <a href="{{ route('shares.edit', ['list'=>$list, 'share'=>$share]) }}" class="btn btn-small btn-warning">Set Permissions</a>
                        </li>
                        @empty
                            This list is a complete secret!
                        @endforelse
                    </ul>
                    <a href="{{ route('shares.create', $list) }}" class="btn btn-primary">Share this list</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection