<form action="{{ route('backend.category.update', $category->id) }}"
      method="post"
      class="ajaxForm"
      enctype="multipart/form-data"
      data-datatableTarget="#categoryTable"
      data-modalClose="#editModal"
>
    <div class="form-error"></div>

    <div class="form-group row">

        <div class="col-12 col-md-6">
            <label for="sort" class="control-label">Status</label>
            <br>
            <label class="switch">
                <input type="checkbox" {{ $category->status === 1 ? 'checked' : '' }} name="status" value="1" id="status">
                <span class="slider round"></span>
            </label>
        </div>

        <div class="col-12 col-md-6 mb-3">
            <label for="sort" class="control-label">Sort</label>
            <input type="number" name="sort" value="{{ $category->sort }}" class="form-control" id="sort" required/>
        </div>
    </div>

    <div class="form-group">
        <label for="name" class="control-label">Name</label>
        <input type="text" name="name" value="{{ $category->name }}" class="form-control" id="name" required/>
    </div>

    <div class="form-group">
        <label for="price" class="control-label">Prodcuts</label>
        <select name="products[]" class="select2Sortable form-control ajaxSelect2"
                data-placeholder="Yemek Seçiniz"
                multiple
                data-url="{{ route('backend.xhr.products.search', $category->restaurant_id) }}"
                id="categoryFood"
        >
            @foreach($categoryFoods as $categoryFood)
                <option value="{{ $categoryFood->id }}" selected>{{ $categoryFood->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="image" class="control-label">Image</label>
        <div class="file-upload-input-container">
            <span class="file-info">&nbsp;</span>
            <input type="file" name="image" class="form-control" id="image"/>
            <a href="javascript:;" class="browse-btn">Choose File</a>
        </div>
        @if($category->image !== null)
            <img src="{{ $category->image_url }}" class="img-thumbnail mt-2 mb-2" />
            <div class="m-b-10">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" name="image_remove" value="1" class="custom-control-input">
                    <span class="custom-control-label">Image Remove</span>
                </label>
            </div>
        @endif
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
<script type="text/javascript">
    ajaxSelect2($('SELECT[NAME="products[]"]'));
    select2Sortable($('#categoryFood'));
</script>
