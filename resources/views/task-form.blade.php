@csrf
@if ($task)
    {{method_field('PATCH')}}
@endif
<div class="form-group row">
    <label for="task_name" class="col-md-4 col-form-label text-md-right">Name the animal to pet:</label>

    <div class="col-md-6">
        <input type="text" name="name" id="task_name" maxlength="100" minlength="1" 
            placeholder="Enter the animal's name" value="{{ old('name', $task ? $task->name : '') }}"
            class="form-control @error('name') is-invalid @enderror" required>
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
@if ($task)
<div class="form-group row">
    <label for="new_position" class="col-md-4 col-form-label text-md-right">Position to move task to:</label>

    <div class="col-md-6">
        <input type="text" name="new_position" id="new_position" min="0" 
            class="form-control @error('new_position') is-invalid @enderror">
        @error('new_position')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
@endif
<div class="form-group row">
    <div class="col-md-6 offset-md-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="completed" id="completed" {{ old('completed', $task ? $task->completed : false) ? 'checked' : '' }}>

            <label class="form-check-label" for="completed">
                Done?
            </label>
        </div>
    </div>
</div>
<div class="form-group row mb-0 justify-content-center">
    <div class="col-4 text-center">
        <button type="submit" class="btn btn-success">
            {{ $verb }}
        </button>
    </div>
</div>
