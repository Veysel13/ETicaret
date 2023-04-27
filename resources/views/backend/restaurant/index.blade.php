@extends('backend.layout.default')
@push('header')

@endpush
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="col-md-12 mt10">
                <button class="btn btn-success pull-right" data-toggle="modal" data-target="#add-modal"><i class="fa fa-plus"></i> New Restaurant</button>
            </div>
            <div class="card-content pb20">
                <x-datatable :sort="false" :url="route('backend.restaurant.xhrIndex', request()->all())"
                             :pageLength="20"
                             :divId="'restaurantTable'">
                    <tr>
                        <th :key="id"><span class="table-th-span-mnt">Id</span>
                            <input type="number" name="id" class="input-filter mnt-custom-input-1" value=""/>
                        </th>
                        <th :key="imageUrl" :callback="imageHtml">Image</th>
                        <th :key="name"><span class="table-th-span-mnt">Name</span>
                            <input type="text" name="name" class="input-filter mnt-custom-input-1" value=""/>
                        </th>
                        <th :key="status" :callback="inputStatusToHtml">
                            <span class="table-th-span-mnt">Status</span>
                            <select name="status" class="select-filter form-control">
                                <option value="">All</option>
                                <option value="1">Active</option>
                                <option value="0">Passive</option>
                            </select>
                        </th>
                        <th :callback="userActionMenu">
                            <span class="table-th-span-mnt">Actions</span>
                        </th>
                    </tr>
                </x-datatable>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"  id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bd-example-modal-lg">New Restaurant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('backend.restaurant.store')}}" method="post" id="form-validation"
                  class="ajaxForm"
                  enctype="multipart/form-data"
                  data-datatableTarget="#restaurantTable"
                  data-modalClose="#add-modal"
            >
                @csrf
                <div class="modal-body">
                    <div class="form-error"></div>
                    <div class="form-group row">

                        <div class="col-md-6">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name"  placeholder="Name...">
                        </div>

                        <div class="col-md-6">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description"  placeholder="Description...">
                        </div>

                        <div class="col-md-6 mt20">
                            <label for="latitude">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Latitude...">
                        </div>

                        <div class="col-md-6 mt20">
                            <label for="longitude">Longitude</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Longitude...">
                        </div>

                        <div class="col-md-6 mt10">
                            <label for="logo" class="control-label">Logo</label>
                            <div class="file-upload-input-container">
                                <span class="file-info">&nbsp;</span>
                                <input type="file" name="logo" class="form-control" id="logo"/>
                                <a href="javascript:;" class="browse-btn">Dosya Seç</a>
                            </div>
                        </div>

                        <div class="col-md-6 mt10">
                            <label for="status">Status</label>
                            <br>
                            <label class="switch">
                                <input type="checkbox" checked name="status" value="1" id="status">
                                <span class="slider round"></span>
                            </label>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" class="btn btn-default w-100" data-dismiss="modal">Cancel</button>
                        </div>

                        <div class="col-6">
                            <button type="submit" class="btn btn-warning w-100">Add</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('footer')
    <script type="text/javascript">
        const restaurantStatusUpdateUrl = '{{ route('backend.xhr.restaurants.statusUpdate') }}';

        const userActionMenu = (data, type, row, meta) => {
            let html=``;
            if(row.editUrl)
                html+=`<a href="${row.editUrl}" data-toggle="tooltip" data-original-title="Edit" class="ajaxEditForm"> <i class="table-edit-i m-r-10"></i> </a>`;

            if(row.removeUrl)
                html+=`<a href="${row.removeUrl}" data-toggle="tooltip" data-original-title="Delete" class="removeButton" data-datatableTarget="#storeTable"> <i class="table-delete-i"></i> </a>`;

            return html;
        }

        const inputStatusToHtml = (data, type, row, meta) => {
            return `<label class="switch">
                            <input type="checkbox"  data-refId="${row.id}" data-method="PUT" data-url="${restaurantStatusUpdateUrl}" ${parseInt(data)===1?'checked' : ''} name="status" value="1" id="status" class="tableSelectStatusChangeWithSwitch">
                            <span class="slider round"></span>
                        </label>`;
        }

        const imageHtml = (data, type, row, meta) => {

            if (row.imageUrl)
                return `<a target="_blank" href="${row.imageUrl}"><img src="${row.imageUrl}" class="img-thumbnail mt-2 mb-2" style="max-width: 50px;"/></a>`;
            else
                return ``;
        }

    </script>
@endpush
