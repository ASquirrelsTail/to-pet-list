@csrf
@if ($list)
    {{method_field('PATCH')}}
@endif
<div class="form-group row">
    <label for="list_name" class="col-md-4 col-form-label text-md-right">List Name:</label>

    <div class="col-md-6">
        <input type="text" name="name" id="list_name" maxlength="100" minlength="1" 
            placeholder="Enter a name for your new list" value="{{ old('name', $list ? $list->name : '') }}"
            class="form-control @error('name') is-invalid @enderror" required>
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <input type="file" name="image" />
</div>
<div class="form-group row">
    <div class="col-md-6 offset-md-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="public" id="public" {{ old('public', $list ? $list->public : false) ? 'checked' : '' }}>

            <label class="form-check-label" for="public">
                Make list public?
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
