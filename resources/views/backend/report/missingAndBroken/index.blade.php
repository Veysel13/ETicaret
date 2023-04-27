@extends('backend.layout.default')
@push('header')

@endpush
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="col-md-12 mt10">
                    <form action="">
                        <div class="row">
                            <div class="col-3">
                                <input type="hidden" name="type" value="{{request()->type??'list'}}">
                                <div class="form-group">
                                    <label>Date Range</label>
                                    <input type="text" name="order_date_range" data-name="order_date" value="{{ request()->get('order_date_range') }}" class="form-control date-range-picker">
                                    <div id="order_date" class="hidden">
                                        <input type="hidden" name="order_date_1" class="filter" value="{{ request()->get('order_date_1') }}"/>
                                        <input type="hidden" name="order_date_2" class="filter" value="{{ request()->get('order_date_2') }}"/>
                                    </div>
                                </div>
                            </div>
                            @if (auth('backend')->user()->is_admin == 1 ||
                                array_intersect([
                                    \App\Constants\AuthorityType::MISSINGREPORTALL,
                                ], auth('backend')->user()->groupsArr))
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="user_id" class="control-label">User </label> <span data-target="user_id" class="text-success float-right selectAll" style="cursor: pointer;">Clear User</span>
                                        <select name="user_id" id="user_id" class="form-control selectUser">
                                            <option value="">Select User</option>
                                            @if(isset($user))
                                                <option selected value="{{$user->id}}">{{$user->name}}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-3">
                                <label for="status">Result (Completed)</label>
                                <br>
                                <label class="switch">
                                    <input type="checkbox" {{request()->result==1?'checked':''}} name="result" value="1" id="result">
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="col-3 mt30">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{route('backend.report.missingAndBroken')}}" type="submit" class="btn btn-warning">Clear</a>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="col-md-12 mt10">
                    <a href="{{route('backend.report.missingAndBroken.excelExport',request()->all())}}" class="btn btn-secondary pull-right btn-sm">
                        <i class="fa fa-file-excel-o"></i> Excel Export
                    </a>

                    <a style="margin-right: 5px" href="{{route('backend.report.missingAndBroken.excelExport',array_merge(request()->all(),['section'=>'pdf']))}}" class="btn btn-secondary pull-right btn-sm">
                        <i class="fa fa-file-pdf-o"></i> Pdf Export
                    </a>

                </div>
                <div class="card-content pb20">
                    <x-datatable :sort="true" :url="route('backend.report.missingAndBroken.xhrIndex', request()->all())"
                                 :pageLength="20"
                                 :divId="'receivedItemTable'">
                        <tr>
                            {{--<th :key="imageUrl" :callback="imageHtml">Image</th>--}}
                            <th :key="product_name"><span class="table-th-span-mnt">Product Name</span></th>
                            <th :callback="infoHtml"><span class="table-th-span-mnt">Product Info</span></th>
{{--                            <th :key="upc"><span class="table-th-span-mnt">Upc</span></th>--}}
{{--                            <th :key="product_sku"><span class="table-th-span-mnt">Sku</span></th>--}}
{{--                            <th :key="brand_name"><span class="table-th-span-mnt">Brand</span></th>--}}
                            <th :key="store_name"><span class="table-th-span-mnt">Store</span></th>
                            <th :key="userFullName"><span class="table-th-span-mnt">User</span></th>
                            <th :key="quantity"><span class="table-th-span-mnt">Qty</span></th>
                            <th :key="receipt_barcode"><span class="table-th-span-mnt">Receipt Barcode</span></th>
                            <th :key="orderDate"><span class="table-th-span-mnt">O.Date</span></th>
                            <th :key="receivedDate"><span class="table-th-span-mnt">R.Date</span></th>
                            <th :key="description"><span class="table-th-span-mnt">Description</span></th>
                            <th :key="contact_store"><span class="table-th-span-mnt">Contact Store</span></th>
                            <th :key="result"><span class="table-th-span-mnt">Result</span></th>
                            <th :key="statusText" :callback="statusText"><span class="table-th-span-mnt">Status</span></th>
                            <th :callback="actionMenu" width="80px">
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
    <script src="{{asset('assets/bower_components/chart.js/dist/Chart.min.js')}}"></script>

    <script>

        const actionMenu = (data, type, row, meta) => {
            let html=``;

            if(row.editUrl)
                html+=`<a href="${row.editUrl}" data-toggle="tooltip" data-original-title="Edit" class="ajaxEditForm"> <i class="table-edit-i m-r-10"></i> </a>`;

            return  html;
        }
        const imageHtml = (data, type, row, meta) => {
            if(row.imageUrl)
                return `<a target="_blank" href="${row.imageUrl}"><img src="${row.imageUrl}" class="img-thumbnail mt-2 mb-2" style="max-width: 50px;"/></a>`;
            else
                return ``;
        }

        const statusText = (data, type, row, meta) => {
            return html=`<span data-orderstatusid="${row.status_id}" class="row-status-color">${data}</span>`;
        }

        const infoHtml = (data, type, row, meta) => {
            return `${row.productInfo}`;
        }

    </script>
@endpush
