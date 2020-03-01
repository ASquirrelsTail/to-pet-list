@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Set sharing permissions for {{ $share->user ? $share->user->name : $share->email}} on {{ $list->name }}</div>
                <div class="card-body">
                    <form action="{{ route('shares.update', ['list'=>$list, 'share'=>$share]) }}" method="POST">
                		@csrf
                        {{method_field('PATCH')}}
                        @include('share-form')
                        <div class="form-group row mb-0 justify-content-center">
                            <div class="col-4 text-center">
                                <button type="submit" class="btn btn-success">
                                    Set permissions
                                </button>
                            </div>
                        </div>
                    </form>
                    <form action="{{ route('shares.destroy', ['list'=>$list, 'share'=>$share]) }}" method="POST">
                        @csrf
                        {{method_field('DELETE')}}
                        <div class="form-group row mb-0 justify-content-center">
                            <div class="col-4 text-center">
                                <button type="submit" class="btn btn-danger">
                                    Unshare list with {{ $share->user ? $share->user->name : $share->email}}
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