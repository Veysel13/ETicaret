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
                            <div class="col">
                                <input type="hidden" name="type" value="{{request()->type??'list'}}">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="text" name="selected_date" value="{{ request()->get('selected_date') }}" class="form-control only-date-picker">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">Min-Max (Day)</label>
                                    <br>
                                    <input type="text" name="min" value="{{ request()->get('min') }}" style="width: 75px;height: 40px"/>
                                    <input type="text" name="max" value="{{ request()->get('max') }}" style="width: 75px;height: 40px"/>
                                </div>
                            </div>
                            @if (auth('backend')->user()->is_admin == 1 ||
                                array_intersect([
                                    \App\Constants\AuthorityType::RECEIPTREPORTALL,
                                ], auth('backend')->user()->groupsArr))
                                <div class="col">
                                    <div class="form-group">
                                        <label for="user_id" class="control-label">User </label> <span data-target="user_id" class="text-success float-right selectAll" style="cursor: pointer;">Clear User</span>
                                        <select name="user_id" id="user_id" class="form-control selectUser">
                                            <option value="">Select User</option>
                                            @if(isset($user))
                                                <option selected value="{{$user->id}}">{{$user->fullname}}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col">
                                <div class="form-group">
                                    <label for="store_id" class="control-label">Store </label> <span data-target="store_id" class="text-success float-right selectAll" style="cursor: pointer;">Clear Store</span>
                                    <select name="store_id" id="store_id" class="form-control selectStore">
                                        <option value="">Select Store</option>
                                        @if(isset($store))
                                            <option selected value="{{$store->id}}">{{$store->name}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col mt-4">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{route('backend.report.received')}}" type="submit" class="btn btn-warning">Clear</a>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="col-md-12 mt10">

                    <a href="{{route('backend.report.received.excelExport',request()->all())}}" class="btn btn-secondary pull-right btn-sm">
                        <i class="fa fa-file-excel-o"></i> Excel Export
                    </a>

{{--                    <a style="margin-right: 5px" href="{{route('backend.report.received.excelExport',array_merge(request()->all(),['section'=>'pdf']))}}" class="btn btn-secondary pull-right btn-sm">--}}
{{--                        <i class="fa fa-file-pdf-o"></i> Pdf Export--}}
{{--                    </a>--}}
                </div>
                <div class="card-content pb20">

                    <div class="row" >
                        <div class="col-xs-6 col-md-3 productCopyDiv"  style="display: none">
                            <button  data-toggle="modal"
                                     data-target="#userFilterModal"
                                     type="button" class="btn btn-info btn-sm"> <i class="fa fa-copy"></i>  Create Task</button>
                        </div>
                    </div>

                    <x-datatable :sort="true" :url="route('backend.report.received.xhrIndex', request()->all())"
                                     :pageLength="20"
                                     :divId="'storeTable'">
                            <tr>
                                <th :key="receipt_barcode"><span class="table-th-span-mnt">Barcode</span></th>
                                <th :key="storeName"><span class="table-th-span-mnt">Store Name</span></th>
                                <th :key="userName"><span class="table-th-span-mnt">User Name</span></th>
                                <th :key="trans_no"><span class="table-th-span-mnt">Trans No</span></th>
                                <th :key="dateformat"><span class="table-th-span-mnt">Date</span></th>
                                <th :key="total_format" width="80px"><span class="table-th-span-mnt">Total</span></th>
                                <th :key="receivedText" ><span class="table-th-span-mnt">Received</span></th>
                                <th :key="receiptText"  ><span class="table-th-span-mnt">E_receipt</span></th>
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

    <script>
        const actionMenu = (data, type, row, meta) => {
            let html=``;
            if(row.viewUrl)
                html+=`<a href="${row.viewUrl}" > <i class="fa fa-eye table-icon-i m-r-10"></i> </a>`;

            return html;
        }
    </script>
@endpush
