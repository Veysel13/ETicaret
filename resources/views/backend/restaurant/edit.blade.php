<form action="{{ route('backend.restaurant.update', $restaurant->id) }}"
      method="post"
      enctype="multipart/form-data"
      class="ajaxForm"
      data-datatableTarget="#restaurantTable"
      data-modalClose="#editModal">
    <div class="form-error"></div>

    <div class="form-group row">

        <div class="col-6 mb-3">
            <label for="name" class="control-label">Name</label>
            <input type="text" name="name" value="{{ $restaurant->name }}" class="form-control" id="name"/>
        </div>

        <div class="col-md-6 mb-3">
            <label for="description">Description</label>
            <input type="text" class="form-control" id="description" name="description" value="{{ $restaurant->description }}">
        </div>

        <div class="col-md-6 mt20">
            <label for="latitude">Latitude</label>
            <input type="text" class="form-control" id="latitude" name="latitude" value="{{ $restaurant->latitude }}" placeholder="Latitude...">
        </div>

        <div class="col-md-6 mt20">
            <label for="longitude">Longitude</label>
            <input type="text" class="form-control" id="longitude" name="longitude" value="{{ $restaurant->longitude }}" placeholder="Longitude...">
        </div>

        <div class="col-md-6 mt10">
            <label for="logo" class="control-label">Logo</label>
            <div class="file-upload-input-container">
                <span class="file-info">&nbsp;</span>
                <input type="file" name="logo" class="form-control" id="logo"/>
                <a href="javascript:;" class="browse-btn">Choose File</a>
            </div>
            @if($restaurant->logo !== null)
                <img src="{{ $restaurant->logo_url }}" class="img-thumbnail mt-2 mb-2" style="max-width: 200px;"/>
                <div class="m-b-10">
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="logo_remove" name="logo_remove" value="1">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Delete Logo</span>
                    </label>
                </div>
            @endif
        </div>

        <div class="col-md-6 mb-3">
            <label for="status">Status</label>
            <br>
            <label class="switch">
                <input type="checkbox" {{ $restaurant->status==1?'checked':'' }} name="status" value="1" id="status">
                <span class="slider round"></span>
            </label>
        </div>

        <div class="modal-footer d-block">
            <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-default w-100" data-dismiss="modal">Cancel</button>
                </div>

                <div class="col-6">
                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </div>
             </div>
        </div>

    </div>
</form>

<script>
    $('.selectEditUser').select2({
        placeholder: 'Select User',
        width: 400,
        ajax: {
            url: URL.USERS,
            data: function (params) {
                return {
                    term: params.term
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data.users, function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                        }
                    })
                };
            },
        }
    });

    @if(isset($user))
    $('.selectEditUser').append(
        $('<option/>', {
            selected: true,
            text: '{{$user->fullname}}',
            value: parseInt('{{$user->id}}')
        })
    );
    @endif
</script>
