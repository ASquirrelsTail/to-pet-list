<div class="form-group row">
    <div class="col-md-6 offset-md-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="complete" id="complete" {{ old('complete', $share ? $share->complete : false) ? 'checked' : '' }}>

            <label class="form-check-label" for="complete">
                Can complete tasks
            </label>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6 offset-md-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="create" id="create" {{ old('create', $share ? $share->create : false) ? 'checked' : '' }}>

            <label class="form-check-label" for="create">
                Can create tasks
            </label>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6 offset-md-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="update" id="update" {{ old('update', $share ? $share->update : false) ? 'checked' : '' }}>

            <label class="form-check-label" for="update">
                Can edit tasks
            </label>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6 offset-md-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="delete" id="delete" {{ old('delete', $share ? $share->delete : false) ? 'checked' : '' }}>

            <label class="form-check-label" for="delete">
                Can delete tasks
            </label>
        </div>
    </div>
</div>