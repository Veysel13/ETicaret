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
                                                        \App\Constants\AuthorityType::USERREPORTALL,
                                                    ], auth('backend')->user()->groupsArr))
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="user_id" class="control-label">User</label> <span data-target="user_id" class="text-success float-right selectAll" style="cursor: pointer;">Clear User</span>
                                    <select name="user_id" id="user_id" class="form-control selectUser">
                                        <option value="">Select User</option>
                                        @if(isset($user))
                                            <option selected value="{{$user->id}}">{{$user->name.' '.$user->surname}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @endif

                            <div class="col-3 mt30">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{route('backend.report.user')}}" type="submit" class="btn btn-warning">Clear</a>
                            </div>

                        </div>
                    </form>

                </div>

                <div class="col-md-12 mt10">
                    @if(request()->type!='graphic')
                        <a href="{{route('backend.report.user',array_merge(request()->all(),['type'=>'graphic']))}}" class="btn btn-success pull-right btn-sm">Graphic</a>
                    @else
                        <a href="{{route('backend.report.user',array_merge(request()->all(),['type'=>'list']))}}" class="btn btn-success pull-right btn-sm">List</a>
                    @endif
                    <a href="{{route('backend.report.user.excelExport',request()->all())}}" class="btn btn-secondary pull-right btn-sm">
                        <i class="fa fa-file-excel-o"></i> Excel Export
                    </a>

                    <a style="margin-right: 5px" href="{{route('backend.report.user.excelExport',array_merge(request()->all(),['section'=>'pdf']))}}" class="btn btn-secondary pull-right btn-sm">
                        <i class="fa fa-file-pdf-o"></i> Pdf Export
                    </a>
                </div>

                <div class="card-content pb20">
                    @if(request()->type!='graphic')
                    <x-datatable :sort="true" :url="route('backend.report.user.xhrIndex', request()->all())"
                                 :pageLength="20"
                                 :divId="'storeTable'">
                        <tr>
                            <th :key="fullname"><span class="table-th-span-mnt">User Name</span></th>
                            <th :key="total_order"><span class="table-th-span-mnt">Total Order</span></th>
                            <th :key="total_price_format"><span class="table-th-span-mnt">Total Price</span></th>
{{--                            <th :key="sub_total_price_format"><span class="table-th-span-mnt">Sub Total Price</span></th>--}}
                        </tr>
                    </x-datatable>
                    @else
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
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script src="{{asset('assets/bower_components/chart.js/dist/Chart.min.js')}}"></script>

    <script>
        @if(request()->type=='graphic')

        var params='';
        const getUrlParams=(page=true)=>{
            var index=0;
            params=''
            window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str,key,value) {
                if(page || key!='page'){
                    if(index==0)
                        params=params+'?'+key+'='+value;
                    else
                        params=params+'&'+key+'='+value;

                    index++;
                }
            });

            return params;
        }

        const ajaxUrl='{{route('backend.report.user.xhrIndex')}}';

        $.ajax({
            url: ajaxUrl+getUrlParams(),
            method: 'POST',
            data: {
            },
            success: function (response) {

                var url      = window.location.origin+window.location.pathname+getUrlParams(false)

                for (var i=1;i<=response.lastPage;i++){
                    $('.pagination').append(`<li class="page-item ${response.currentPage==i?'active':''}"><a class="page-link" href="${url+'&page='+i}">${i}</a></li>`);
                }

                const labels=[];
                const totalOrder=[];
                const totalPrice=[];
                const subTotalPrice=[];
                $.each( response.data, function( key, value ) {
                    labels.push(value.fullname)
                    totalOrder.push(value.total_order)
                    totalPrice.push(value.total_price)
                    subTotalPrice.push(value.sub_total_price)
                });

                var barData = {
                    labels: labels,
                    datasets: [
                        {
                            label: "Total Order",
                            backgroundColor: 'rgb(241,178,12)',
                            pointBorderColor: "#fff",
                            data: totalOrder
                        }, {
                            label: "Total Price",
                            backgroundColor: 'rgba(245,153,136,0.68)',
                            pointBorderColor: "#fff",
                            data: totalPrice
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
                new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});
            },
            error: function (xhr, status, error) {

            }
        });
        @endif
    </script>
@endpush
