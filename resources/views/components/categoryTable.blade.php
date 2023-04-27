@push('header')
    <style type="text/css">
        td.details-control {
            background: url('{{ asset('assets/images/plus-table.svg') }}') no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url('{{ asset('assets/images/minus-table.svg') }}') no-repeat center center;
        }
    </style>
@endpush
<div>
    <div class="row">
        <div class="col-4">
            <form action="" id="foodSearchForm">
                <div class="input-group">
                    <input type="text" name="foodSearch" id="foodSearch" value="{{request()->foodSearch}}" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary">Search</button>
                    </div>
                </div>
            </form>


        </div>

        <div class="col-8 text-right">
            <button class="btn btn-sm btn-info waves-effect waves-light"
                    data-toggle="modal"
                    data-target="#categoryAddModal"
                    type="button">
                <i class="fa fa-plus"></i> New Category
            </button>
        </div>
    </div>

    <x-datatable :sort="false"
                 :url="route('backend.category.xhrIndex',array_merge(['restaurantId'=>$restaurantId],request()->all()))"
                 :pageLength="20"
                 :divId="'categoryTable'">
        <tr>
            <th :key="" :className="details-control"></th>
            <th :key="imageUrl" :callback="imageHtml">Image</th>
            <th :key="name" :className="table-text">Name</th>
            <th :key="totalFoods" :className="hidden-xs hidden-sm">Product</th>
            <th :key="status" :callback="inputCategoryStatusToHtml">Status</th>
            <th :key="sort" :className="hidden-xs hidden-sm">Sort</th>
            <th :callback="categoryActionMenu">Actions</th>
        </tr>
    </x-datatable>
</div>

<div class="modal" id="categoryAddModal" tabindex="-1" role="dialog" aria-labelledby="categoryAddModalLabel">
    <form action="{{ route('backend.category.store', $restaurantId) }}"
          method="post"
          class="ajaxForm"
          enctype="multipart/form-data"
          data-datatableTarget="#categoryTable"
          data-modalClose="#categoryAddModal"
    >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="categoryAddModalLabel">Yeni Kategori Ekle</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-error"></div>

                    <div class="form-group row">
                        <div class="col-12 col-md-6">
                            <label for="sort" class="control-label">Status</label>
                            <br>
                            <label class="switch">
                                <input type="checkbox" checked name="status" value="1" id="status">
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="sort" class="control-label">Sort</label>
                            <input type="number" name="sort" value="0" class="form-control" id="sort" required/>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="name" class="control-label">Category Name</label>
                        <input type="text" name="name" class="form-control" id="name" required/>
                    </div>
                    <div class="form-group">
                        <label for="price" class="control-label">Products</label>
                        <select name="products[]" class="select2Sortable form-control ajaxSelect2"
                                data-placeholder="Choose Product"
                                multiple
                                data-url="{{ route('backend.xhr.products.search', $restaurantId) }}"
                        ></select>
                    </div>
                    <div class="form-group">
                        <div class="file-upload-input-container">
                            <span class="file-info">Image</span>
                            <input type="file" name="image" class="form-control" id="image"/>
                            <a href="javascript:;" class="browse-btn">Choose File</a>
                        </div>
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
            </div>
        </div>
    </form>
</div>

<div class="modal" id="productAddWithCategoryModal" tabindex="-1" role="dialog" aria-labelledby="productAddWithCategoryModalLabel">
    <form
          action="{{ route('backend.product.store', $restaurantId) }}"
          method="post"
          enctype="multipart/form-data"
          class="ajaxForm"
          data-datatableTarget="#categoryTable"
          data-modalClose="#productAddWithCategoryModal"
    >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="foodAddModalLabel">Add New Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-error"></div>

                    <div class="form-group row">
                        <div class="col-12 col-md-6 col-lg-6 mb-3">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" name="name" class="form-control" id="name"/>
                        </div>

                        <div class="col-12 col-md-6 col-lg-6 mb-3">
                            <label for="status" class="control-label">Status</label>
                            <br>
                            <label class="switch">
                                <input type="checkbox"  checked name="status" value="1" id="status">
                                <span class="slider round"></span>
                            </label>
                        </div>

                        <div class="col-6 col-md-6 col-lg-6 mb-3">
                            <label for="price" class="control-label">Price</label>
                            <input type="text" name="price" value="" class="form-control" id="price"/>
                        </div>

                        <div class="col-6 col-md-6 col-lg-6 mb-3">
                            <label for="sort" class="control-label">Sort</label>
                            <input type="number" name="sort" value="" class="form-control" id="sort"/>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">Description</label>
                        <textarea type="text" name="description" class="form-control" id="description"></textarea>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-md-6 mb-3">
                            <div class="file-upload-input-container">
                                <span class="file-info">Image</span>
                                <input type="file" name="image" class="form-control" id="image"/>
                                <a href="javascript:;" class="browse-btn">Choose File</a>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="category_id" id="categoryId">
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
            </div>
        </div>
    </form>
</div>


@push('footer')
    <script type="text/javascript">

        const categoryStatusUpdateUrl = '{{ route('backend.xhr.categories.statusUpdate') }}';

        const categoryActionMenu = (data, type, row, meta) => {
            return `
                <button data-refid="${row.id}" data-toggle="tooltip" data-original-title="Yeni Yemek Ekle" class="btn btn-success btn-sm m-r-10 addProductWithCategory"> <i class="fa fa-plus"></i> New Product</button>
                <a href="${row.editUrl}" data-toggle="tooltip" data-original-title="Düzenle" class="ajaxEditForm"> <i class="table-edit-i m-r-10"></i> </a>
                <a href="${row.removeUrl}" data-toggle="tooltip" data-original-title="Sil" class="removeButton" data-datatableTarget="#categoryTable"> <i class="table-delete-i"></i> </a>
            `;
        }

        const imageHtml = (data, type, row, meta) => {

            if (row.imageUrl)
                return `<a target="_blank" href="${row.imageUrl}"><img src="${row.imageUrl}" class="img-thumbnail mt-2 mb-2" style="max-width: 50px;"/></a>`;
            else
                return ``;
        }

        const inputCategoryStatusToHtml = (data, type, row, meta) => {

            return `<label class="switch">
                        <input type="checkbox"  data-refId="${row.id}" data-method="PUT" data-url="${categoryStatusUpdateUrl}" ${parseInt(data)===1?'checked' : ''} name="status" value="1" id="status" class="tableSelectStatusChangeWithSwitch">
                        <span class="slider round"></span>
                    </label>`;
        }

        const productStatusUpdateUrlForCategory = '{{ route('backend.xhr.products.statusUpdate') }}';
        const productPriceUpdateUrlForCategory = '{{ route('backend.xhr.products.priceUpdate') }}';

        const categoryFormat = (d) => {

            let categoryProductText = "";
            $.ajax({
                url: '/backend/category/' + d.id + '/products',
                method: "Post",
                async: false,
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                processData: true,
                data: {foodSearch: '{{request()->foodSearch}}'},
                success: function (response) {

                    categoryProductText = `<table  class="display nowrap table table-hover table-striped table-bordered productSubTable" id="productSubTable">
                    <thead>
                        <tr>
                            <th>Ürün Adı</th>
                            <th class="text-center">Fiyat</th>
                            <th class="text-center">Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                       ${response.data.products.map(item => {

                        return `<tr class="productId-${item.product_id}" data-id="${item.product_id}">
                                    <td class="table-text">${item.name}</td>
                                    <td class="text-center">
                                        <input type="text" style="max-width: 100px" class="tableSelectPriceChange" value="${item.price}" data-refId="${item.product_id}" data-method="PUT" data-url="${productPriceUpdateUrlForCategory}" />
                                    </td>
                                    <td class="text-center">
                                        <label class="switch">
                                            <input type="checkbox" data-refId="${item.product_id}" data-method="PUT" data-url="${productStatusUpdateUrlForCategory}" ${parseInt(item.status) === 1 ? 'checked' : ''} name="status" value="1" id="status" class="tableSelectStatusChangeWithSwitch">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a href="${item.editUrl}" data-toggle="tooltip" data-original-title="Düzenle" class="ajaxEditForm"> <i class="table-edit-i m-r-10"></i> </a>
                                        <a href="${item.removeUrl}" data-refId="${item.product_id}" data-toggle="tooltip" data-original-title="Sil" class="removeButton" data-datatableTarget="#categoryTable" data-dataRemove="productremove"> <i class="table-delete-i"></i> </a>
                                    </td>
                                </tr>`
                    }).join('')}
                    </tbody>
                </table>`;

                    return categoryProductText;
                }
            });

            return categoryProductText;
        }

        $('#categoryTable').on('click', 'td.details-control', function () {
            const tr = $(this).closest('tr');
            const row = $('#categoryTable').DataTable().row(tr);
            const categoryId = row.data().id;

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(categoryFormat(row.data())).show();

                $('.productSubTable').each(function (i, e){
                    const $tableThis = $(this);
                    $(e).sortable({
                        items: 'tr',
                        stop: function(event, ui) {
                            const selectedData = [];

                            $tableThis.find('tr').each(function (x, y){
                                if($(this).data('id') !== undefined){
                                    selectedData.push($(this).data('id'));
                                }
                            })

                            updateOrder(selectedData,categoryId);
                        }
                    });
                });

                tr.addClass('shown');
            }
        });

        function updateOrder(ids,categoryId){
            $.ajax({
                url: '{{ route('backend.product.reOrder') }}',
                type: 'POST',
                data: {
                    ids:ids,
                    categoryId:categoryId
                },
                success: () => {

                }
            })
        }

        $(document).ready(function () {
            @if (request()->foodSearch)
            setTimeout(function () {
                $('#categoryTable').find("td.details-control").trigger("click")
            }, 1000);
            @endif
        });


        $('#categoryTable').on('click', '.addProductWithCategory', function () {

            $('#categoryId').val($(this).data('refid'));

            $('#productAddWithCategoryModal').modal('show');
        });

    </script>


@endpush
