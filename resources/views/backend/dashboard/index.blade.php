@extends('backend.layout.default')
@push('header')
    <style>
        .progress {
            margin-bottom: 10px;
            height: 35px;
        }

        .progress-bar-warning {
            background-color: #f0ad4e;
        }

        .progress-bar-success {
            background-color: #5cb85c;
        }

        .progress-bar-danger {
            background-color: #e65252;
        }
    </style>
@endpush
@section('content')

    @if($authUser->is_admin==1 || array_intersect([
                \App\Constants\AuthorityType::DASHBOARDALL,
            ], auth('backend')->user()->groupsArr))
        <form action="">
            <div class="row mb-4">
                <div class="col-md-6 mt20">
                    <select name="user_id[]" multiple id="user_id" class="form-control selectUser">
                        <option value="">Select User</option>
                    </select>
                </div>
                <div class="col-md-4 mt20">
                    <select name="month" id="month" class="form-control">
                        <option value="">Select Month</option>
                        @foreach($months as $month)
                            <option
                                {{request()->month==$month['id']?'selected':''}} value="{{$month['id']}}">{{$month['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mt20">
                    <button class="btn btn-warning btn-xs">Filter</button>
                    <a href="{{route('backend.dashboard')}}" class="btn btn-danger btn-xs">Clear</a>
                </div>
            </div>
        </form>
    @endif



    <div class="row">
        <div class="col-md-6 col-lg-4 col-sm-12">
            <div class="widget-info clearfix mb30 bg-warning" data-toggle="modal" data-target="#storeList">
                <i class="ion-ios-home-outline"></i>
                <div class="widget-content">
                    <h4 class="text-white">{{$totalOrderStore.' / '.$totalStore}}</h4>
                    <span class="text-white-gray">Total Store</span>
                </div>
            </div>

            <div class="progress">
                <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar"
                     aria-valuenow="50"
                     aria-valuemin="0" aria-valuemax="100" style="width:{{intval($storeRate)}}%">
                    {{intval($storeRate)}}% Complete
                </div>
            </div>

        </div>

        <div class="col-md-6 col-lg-4 col-sm-12">
            <div class="widget-info clearfix mb30 bg-success">
                <i class="ion-ios-cart-outline"></i>
                <div class="widget-content">
                    <h4 class="text-white">{{$totalSaleOrder.' / '.$orderCount}}</h4>
                    <span class="text-white-gray">Total Sale Order</span>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                     aria-valuenow="50"
                     aria-valuemin="0" aria-valuemax="100" style="width:{{$totalSaleOrderRate}}%">
                    {{intval($totalSaleOrderRate)}}% Complete
                </div>
            </div>
        </div><!--col-->
        <div class="col-md-6 col-lg-4 col-sm-12">
            <div class="widget-info clearfix mb30 bg-danger">
                <i class="ion-social-usd-outline"></i>
                <div class="widget-content">
                    <h4 class="text-white">{{$totalPrice.' / '.$orderTotal}}</h4>
                    <span class="text-white-gray">Total Price</span>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="50"
                     aria-valuemin="0" aria-valuemax="100" style="width:{{intval($totalPriceRate)}}%">
                    {{intval($totalPriceRate)}}% Complete
                </div>
            </div>
        </div><!--col-->
        {{--    <div class="col-md-6 col-lg-3 col-sm-12 hidden">--}}
        {{--        <div class="widget-info clearfix mb30 bg-danger">--}}
        {{--            <i class="ion-social-usd-outline"></i>--}}
        {{--            <div class="widget-content">--}}
        {{--                <h4 class="text-white">{{$subTotalPrice}}</h4>--}}
        {{--                <span class="text-white-gray">Sub Total Price</span>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div><!--col-->--}}
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class='card-content pb20'>
                <canvas id="barChart"></canvas>

                <div class="row mt-5">
                    <div class="col-md-12">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row " style="display: none">
        <div class="col-md-12">
            <div class="card">
                <div class="card-top">
                    <h4 class="card-title">Sale Orders</h4>
                </div><!--card top-->
                <div class="card-content pb20">
                    <x-datatable :sort="false" :url="route('backend.saleOrder.xhrIndex', request()->all())"
                                 :pageLength="5"
                                 :divId="'saleOrderTable'">
                        <tr>
                            <th :key="id"><span class="table-th-span-mnt">İd</span>
                                <input type="number" name="id" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="receipt_barcode"><span class="table-th-span-mnt">Barcode</span>
                                <input type="text" name="receipt_barcode" class="input-filter mnt-custom-input-1"
                                       value=""/>
                            </th>
                            <th :key="storeName"><span class="table-th-span-mnt">Store Name</span>
                                <input type="text" name="storeName" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="trans_no"><span class="table-th-span-mnt">Trans No</span>
                                <input type="text" name="trans_no" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="dateformat"><span class="table-th-span-mnt">Date</span>
                                <input type="text" name="order_date"
                                       class="date-filter only-date-picker mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="total_format" width="80px"><span class="table-th-span-mnt">Total</span></th>
                            <th :key="receivedText"><span class="table-th-span-mnt">Received</span></th>
                            <th :key="receiptText"><span class="table-th-span-mnt">E_receipt</span></th>
                            <th :callback="actionMenu" width="80px">
                                <span class="table-th-span-mnt">Actions</span>
                            </th>
                        </tr>
                    </x-datatable>
                </div><!--content-->
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-top">
                    <h4 class="card-title">Store No Order</h4>

                </div><!--card top-->
                <div class="card-content pb20">
                    <x-datatable :sort="false" :url="route('backend.dashboard.xhrStore', request()->all())"
                                 :pageLength="5"
                                 :divId="'notOrderSaleStoreTable'">
                        <tr>
                            <th :key="name"><span class="table-th-span-mnt">Name</span>
                                <input type="text" name="name" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="code"><span class="table-th-span-mnt">Code</span>
                                <input type="text" name="code" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="city"><span class="table-th-span-mnt">City</span>
                                <input type="text" name="city" class="input-filter mnt-custom-input-1" value=""/></th>
                            <th :key="state"><span class="table-th-span-mnt">State</span>
                                <input type="text" name="state" class="input-filter mnt-custom-input-1" value=""/></th>

                        </tr>
                    </x-datatable>
                </div><!--content-->
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-top">
                    <h4 class="card-title">Missing E-receipt
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger progress-bar-striped float-right"
                                 role="progressbar" aria-valuenow="50"
                                 aria-valuemin="0" aria-valuemax="100" style="width:{{intval($eReceiptRate)}}%">
                                {{intval($eReceiptRate)}}% Complete
                            </div>
                        </div>
                    </h4>

                </div><!--card top-->
                <div class="card-content pb20">
                    <x-datatable :sort="false" :url="route('backend.dashboard.xhrEreceipt', request()->all())"
                                 :pageLength="5"
                                 :divId="'missingEreceiptTable'">
                        <tr>
                            <th :key="storeName"><span class="table-th-span-mnt">Store Name</span>
                                <input type="text" name="storeName" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="cc_no"><span class="table-th-span-mnt">Cc No</span>
                                <input type="text" name="cc_no" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="trans_no"><span class="table-th-span-mnt">Trans No</span>
                                <input type="text" name="trans_no" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="dateformat"><span class="table-th-span-mnt">Date</span>
                                <input type="text" name="order_date"
                                       class="date-filter only-date-picker mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="total_format" width="80px"><span class="table-th-span-mnt">Total</span></th>
                            <th :callback="actionMenu" width="80px">
                                <span class="table-th-span-mnt">Actions</span>
                            </th>
                        </tr>
                    </x-datatable>
                </div><!--content-->
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-top">
                    <h4 class="card-title">Missing UPS Tracking
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger progress-bar-striped float-right"
                                 role="progressbar" aria-valuenow="50"
                                 aria-valuemin="0" aria-valuemax="100" style="width:{{intval($trackingNumberRate)}}%">
                                {{intval($trackingNumberRate)}}% Complete
                            </div>
                        </div>
                    </h4>
                </div><!--card top-->
                <div class="card-content pb20">
                    <x-datatable :sort="false" :url="route('backend.dashboard.xhrTracking', request()->all())"
                                 :pageLength="5"
                                 :divId="'missingTrackingTable'">
                        <tr>
                            <th :key="storeName"><span class="table-th-span-mnt">Store Name</span>
                                <input type="text" name="storeName" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="cc_no"><span class="table-th-span-mnt">Cc No</span>
                                <input type="text" name="cc_no" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="trans_no"><span class="table-th-span-mnt">Trans No</span>
                                <input type="text" name="trans_no" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="dateformat"><span class="table-th-span-mnt">Date</span>
                                <input type="text" name="order_date"
                                       class="date-filter only-date-picker mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="total_format" width="80px"><span class="table-th-span-mnt">Total</span></th>
                            <th :callback="actionMenu" width="80px">
                                <span class="table-th-span-mnt">Actions</span>
                            </th>
                        </tr>
                    </x-datatable>
                </div><!--content-->
            </div>
        </div>
    </div>

    <div class="modal fade" id="storeList" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bd-example-modal-lg">Store List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <x-datatable :sort="false"
                                 :url="route('backend.store.xhrIndex', array_merge(request()->all(),['nonOrder'=>'yes']))"
                                 :pageLength="10"
                                 :divId="'storeTable'">
                        <tr>
                            <th :key="name"><span class="table-th-span-mnt">Name</span>
                                <input type="text" name="name" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="code"><span class="table-th-span-mnt">Code</span>
                                <input type="text" name="code" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="city"><span class="table-th-span-mnt">City</span>
                                <input type="text" name="city" class="input-filter mnt-custom-input-1" value=""/></th>
                            <th :key="state"><span class="table-th-span-mnt">State</span>
                                <input type="text" name="state" class="input-filter mnt-custom-input-1" value=""/></th>
                            <th :key="staffName"><span class="table-th-span-mnt">Staff</span>
                                <input type="text" name="staffName" class="input-filter mnt-custom-input-1" value=""/>
                            </th>
                            <th :key="gsm"><span class="table-th-span-mnt">Gsm</span></th>
                        </tr>
                    </x-datatable>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('footer')
    <script src="{{asset('assets/bower_components/chart.js/dist/Chart.min.js')}}"></script>

    <script type="text/javascript">
        const actionMenu = (data, type, row, meta) => {
            return `
            <a href="${row.viewUrl}" > <i class="fa fa-eye table-icon-i m-r-10"></i> </a>
            `;
        }

        const labels = [];
        const totalOrder = [];
        $.each( @json($orderStoreList), function (key, value) {
            labels.push(value.store_name + ' (' + value.store_code + ')')
            totalOrder.push(value.count)
        });

        var barData = {
            labels: labels,
            datasets: [
                {
                    label: "Total Order",
                    backgroundColor: 'rgb(1,253,215)',
                    pointBorderColor: "#fff",
                    data: totalOrder
                }
            ]
        };

        var barOptions = {
            responsive: true,
            scales:{
                yAxes:[{
                    ticks:{
                        beginAtZero:true
                    }
                }]
            }
        };

        var ctx2 = document.getElementById("barChart").getContext("2d");
        new Chart(ctx2, {type: 'bar', data: barData, options: barOptions});
    </script>

    <script>
        @if(isset($users))
        @foreach($users as $user)
        $('.selectUser').append(
            $('<option/>', {
                selected: true,
                text: '{{$user->fullname}}',
                value: parseInt('{{$user->id}}')
            })
        );
        @endforeach
        @endif
    </script>
@endpush
