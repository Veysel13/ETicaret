<form action="{{ route('backend.product.update', $product->id) }}"
      method="post"
      enctype="multipart/form-data"
      class="ajaxForm"
      data-datatableTarget="#categoryTable"
      data-modalClose="#editModal">
    <div class="form-error"></div>

    <div class="form-group row">

        <div class="col-6 mb-3">
            <label for="name" class="control-label">Name</label>
            <input type="text" name="name" value="{{ $product->name }}" class="form-control" id="name" required/>
        </div>

        <div class="col-12 col-md-6 mb-3">
            <label for="sort" class="control-label">Status</label>
            <br>
            <label class="switch">
                <input type="checkbox"  {{ $product->status === 1 ? 'checked' : '' }} name="status" value="1" id="status">
                <span class="slider round"></span>
            </label>
        </div>

        <div class="col-6 col-md-6 col-lg-6 mb-3">
            <label for="price" class="control-label">Price</label>
            <input type="text" name="price" value="{{ $product->price }}" class="form-control" id="price" required/>
        </div>

        <div class="col-6 col-md-6 col-lg-6 mb-3">
            <label for="sort" class="control-label">Sort</label>
            <input type="number" name="sort" value="{{ $product->sort }}" class="form-control" id="sort" required/>
        </div>

    </div>

    <div class="form-group">
        <label for="description" class="control-label">Description</label>
        <textarea type="text" name="description" class="form-control"
                  id="description">{{ $product->description }}</textarea>
    </div>

    <div class="form-group row">
        <div class="col-12 col-md-6 mb-3">
            <label for="image" class="control-label">Image</label>
            <div class="file-upload-input-container">
                <span class="file-info">&nbsp;</span>
                <input type="file" name="image" class="form-control" id="image"/>
                <a href="javascript:;" class="browse-btn">Choose File</a>
            </div>
            @if($product->image !== null)
                <img src="{{ $product->image_url }}" class="img-thumbnail mt-2 mb-2" style="max-width: 200px;"/>
                <div class="m-b-10">
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" name="image_remove" value="1" class="custom-control-input">
                        <span class="custom-control-label">Remove Image</span>
                    </label>
                </div>
            @endif
        </div>
    </div>

    <div class="modal-footer d-block">
        <div class="row">
            <div class="col-6">
                <button type="button" class="btn btn-default w-100" data-dismiss="modal">Cancel</button>
            </div>

            <div class="col-6">
                <button type="submit" class="btn btn-primary w-100">Store</button>
            </div>
        </div>
    </div>
</form>

