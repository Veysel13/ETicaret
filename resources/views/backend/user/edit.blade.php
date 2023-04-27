<form action="{{ route('backend.user.update', $user->id) }}"
      method="post"
      enctype="multipart/form-data"
      class="ajaxForm"
      data-datatableTarget="#userTable"
      data-modalClose="#editModal">
    <div class="form-error"></div>

    <div class="form-group row">

        <div class="col-6 mb-3">
            <label for="fullname" class="control-label">Fullname</label>
            <input type="text" name="fullname" value="{{ $user->fullname }}" class="form-control" id="fullname"/>
        </div>

        <div class="col-6 mb-3">
            <label for="name" class="control-label">Email</label>
            <input type="text" name="email" value="{{ $user->email }}" class="form-control" id="email"/>
        </div>

        <div class="col-6 mb-3">
            <label for="password" class="control-label">Password</label>
            <input type="password" name="password" class="form-control" id="password"/>
        </div>

        <div class="col-6 mb-3">
            <label for="password_confirmation" class="control-label">Password Confirm</label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"/>
        </div>


        <div class="col-12 col-md-12 mb-3">
            <label for="sort" class="control-label">Status</label>
            <select class="form-control" id="status" name="status">
                <option {{ $user->status==1?'selected':'' }} value="1">Active</option>
                <option {{ $user->status==0?'selected':'' }} value="0">Passive</option>
            </select>
        </div>

    </div>
    <div class="form-group row">
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
    $(document).on('click', '.authority_all_check', function () {
        const context = $(this);
        const value = context.val();

        if (context.is(':checked')) {
            $('.' + value).prop('checked', true)
        } else {
            $('.' + value).prop('checked', false)
        }
    })
</script>
