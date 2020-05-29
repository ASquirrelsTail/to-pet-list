@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit {{ $list->name }}</div>
                <div class="card-body">
                    <form action="{{ route('lists.update', $list) }}" method="POST" enctype="multipart/form-data" id="list_form">
                		@include('list-form', ['verb'=>'Update list'])
                    </form>
                    <h2 id="shares">Shares</h2>
                    <ul>
                        @forelse($list->shares as $share)
                        <li>
                            {{ $share->user ? $share->user->name : $share->email }} can view the list
                            @if ($share->can)
                                and {{ $share->can }} tasks
                            @endif
                            .
                            <a href="{{ route('shares.edit', ['list'=>$list, 'share'=>$share]) }}" class="btn btn-primary btn-sm">Update Permissions</a>
                        </li>
                        @empty
                            This list is a complete secret!
                        @endforelse
                    </ul>
                    <div class="text-center">
                        <a href="{{ route('shares.create', $list) }}" class="btn btn-primary">Share this list with someone</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete {{ $list->name }}?</p>
                <form action="{{ route('lists.destroy', $list) }}" method="POST">
                    @csrf
                    {{method_field('DELETE')}}
                    <div class="form-group row mb-0">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-danger">
                                Delete List
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection