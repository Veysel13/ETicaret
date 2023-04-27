@extends('backend.layout.default')
@push('header')

@endpush
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="col-md-12 mt10">
                <a href="{{ route('backend.user.create') }}" class="btn btn-success pull-right"> <i class="fa fa-plus"></i> New User</a>
            </div>
            <div class="card-content pb20">
                <x-datatable :sort="false" :url="route('backend.user.xhrIndex', request()->all())"
                             :pageLength="20"
                             :divId="'userTable'">
                    <tr>
                        <th :key="id"><span class="table-th-span-mnt">Id</span>
                            <input type="number" name="id" class="input-filter mnt-custom-input-1" value=""/>
                        </th>
                        <th :key="fullname"><span class="table-th-span-mnt">Name</span>
                            <input type="text" name="fullname" class="input-filter mnt-custom-input-1" value=""/>
                        </th>
                        <th :key="email"><span class="table-th-span-mnt">Email</span>
                            <input type="text" name="email" class="input-filter mnt-custom-input-1" value=""/></th>
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

@endsection

@push('footer')
    <script type="text/javascript">
        const userStatusUpdateUrl = '{{ route('backend.xhr.users.statusUpdate') }}';

        const userActionMenu = (data, type, row, meta) => {
            let html=``;
            if(row.editUrl)
                html+=`<a href="${row.editUrl}" data-toggle="tooltip" data-original-title="Edit" class="ajaxEditForm"> <i class="table-edit-i m-r-10"></i> </a>`;

            if(row.removeUrl)
                html+=`<a href="${row.removeUrl}" data-toggle="tooltip" data-original-title="Delete" class="removeButton" data-datatableTarget="#userTable"> <i class="table-delete-i"></i> </a>`;

            return html;
        }

        const inputStatusToHtml = (data, type, row, meta) => {
            return `<label class="switch">
                        <input type="checkbox"  data-refId="${row.id}" data-method="PUT" data-url="${userStatusUpdateUrl}" ${parseInt(data)===1?'checked' : ''} name="status" value="1" id="status" class="tableSelectStatusChangeWithSwitch">
                        <span class="slider round"></span>
                    </label>`;
        }

    </script>
@endpush
