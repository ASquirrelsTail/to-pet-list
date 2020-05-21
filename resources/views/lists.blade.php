@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $name }}'s To Pet Lists</div>

                <div class="card-body">
                    <ul>
                        @forelse ($lists as $list)
                            <li id="list-{{ $list->id }}">
                                <a href="{{ route('lists.show', $list)}}">{{ $list->name }}</a> 
                                @if($list->public)
                                    (Public)
                                @endif
                            </li>
                        @empty
                            <p>You need to make some lists!</p>
                        @endforelse
                        @if ($shared_lists)
                            <h3>Shared Lists</h3>
                            @foreach ($shared_lists as $list)
                                <li id="list-{{ $list->id }}">
                                    <a href="{{ route('lists.show', $list)}}">{{ $list->name }}</a> 
                                    @if($list->public)
                                        (Public)
                                    @endif
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newListModal">
                        Create a new list
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newListModal" tabindex="-1" role="dialog" aria-labelledby="newListModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newListModalLabel">New List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('lists.store') }}" method="POST">
                    @include('list-form', ['verb'=>'Create new list', 'list'=>null])
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@endsection
