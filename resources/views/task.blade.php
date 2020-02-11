@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit: {{ $task->task_name }}</div>

                <div class="card-body">
                    <form method="post" action="{{ route('edit_task', $task->id) }}">
                        @csrf
                        <div class="form-group row">
                            <label for="task_name" class="col-md-4 col-form-label text-md-right">Animal to pet:</label>

                            <div class="col-md-6">
                                <input type="text" name="task_name" id="task_name" maxlength="100" minlength="1" 
                                    placeholder="{{ $task->task_name }}" value="{{ $task->task_name }}"
                                    class="form-control @if ($error) is-invalid @endif" required>
                                @if ($error)
                                {{-- Form validation error --}}
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $error }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>
                        <div class="form-group row ">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="completed" id="completed" class="mt-2"
                                    @if ($task->completed) checked @endif>

                                    <label class="form-check-label" for="completed">
                                        Done
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0 justify-content-center">
                            <div class="col-4 text-center">
                                <button type="submit" class="btn btn-success">
                                    Save
                                </button>
                            </div>
                            <div class="col-4 text-center">
                                <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                                  Delete
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Delete confirmation modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalTitle">Delete {{ $task->task_name }} from list?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <form method="post" action="{{ route('delete_task', $task->id) }}">
            @csrf
            <button type="submit" class="btn btn-danger">
                Delete
            </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
