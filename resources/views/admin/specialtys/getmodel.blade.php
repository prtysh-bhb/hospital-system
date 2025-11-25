<form autocorrect="off" action="{{ route('admin.specialtys-store') }}" autocomplete="off" method="post"
    class="form-horizontal form-bordered specialtys-formsubmit" enctype="multipart/form-data">

    {{ csrf_field() }}

    @if (!empty($data->id))
        <input type="hidden" name="id" value="{{ $data->id }}">
    @endif

    <div class="row">
        <div class="col-lg">
            <label>Name</label>
            <input type="text" name="name" id="name" value="{{ $data->name ?? '' }}" class="form-control"
                placeholder="Enter name">
            <span class="text-danger error name_error"></span>
        </div>

        <div class="col-lg">
            <label>Description</label>
            <input type="text" name="description" id="description" value="{{ $data->description ?? '' }}"
                class="form-control" placeholder="Enter description">
            <span class="text-danger error description_error"></span>
        </div>
    </div>

    <div class="row">
        <div class="col-lg">
            <label>Status</label>
            <select name="status" id="status" class="form-control">
                <option value="">Select Status</option>
                <option value="active" {{ ($data->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ ($data->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <span class="text-danger error status_error"></span>
        </div>
    </div>

    <div class="text-right mt-4">
        <button type="submit" class="btn btn-primary btn-pill me-2">Save</button>
        <button type="button" class="btn btn-secondary btn-pill" data-dismiss="modal">Close</button>
    </div>
</form>
