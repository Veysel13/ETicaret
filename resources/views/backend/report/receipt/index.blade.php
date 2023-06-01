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
                                    <input type="text" name="order_date_range" data-name="order_date"
                                           value="{{ request()->get('order_date_range') }}"
                                           autocomplete="off"
                                           class="form-control date-range-picker">
                                    <div id="order_date" class="hidden">
                                        <input type="hidden" name="order_date_1" class="filter"
                                               value="{{ request()->get('order_date_1') }}"/>
                                        <input type="hidden" name="order_date_2" class="filter"
                                               value="{{ request()->get('order_date_2') }}"/>
                                    </div>
                                </div>
                            </div>
                            @if (auth('backend')->user()->is_admin == 1 ||
                                array_intersect([
                                    \App\Constants\AuthorityType::RECEIPTREPORTALL,
                                ], auth('backend')->user()->groupsArr))
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="user_id" class="control-label">User </label> <span
                                            data-target="user_id" class="text-success float-right selectAll"
                                            style="cursor: pointer;">Clear User</span>
                                        <select name="user_id" id="user_id" class="form-control selectUser">
                                            <option value="">Select User</option>
                                            @if(isset($user))
                                                <option selected value="{{$user->id}}">{{$user->name}}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="store_id" class="control-label">Store </label> <span
                                        data-target="store_id" class="text-success float-right selectAll"
                                        style="cursor: pointer;">Clear Store</span>
                                    <select name="store_id" id="store_id" class="form-control selectStore">
                                        <option value="">Select Store</option>
                                        @if(isset($store))
                                            <option selected value="{{$store->id}}">{{$store->name}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

{{--                            <div class="col-2">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="control-label">Min-Max (Discount)</label>--}}
{{--                                    <br>--}}
{{--                                    <input type="text" name="min" value="{{ request()->get('min') }}"--}}
{{--                                           style="width: 75px;height: 40px"/>--}}
{{--                                    <input type="text" name="max" value="{{ request()->get('max') }}"--}}
{{--                                           style="width: 75px;height: 40px"/>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="brand_ids" class="control-label">Brand</label>
                                    <select name="brand_ids[]" id="brand_ids[]" class="form-control selectBrand"
                                            multiple>
                                        <option value="">Select Brand</option>
                                        @if(isset($brands))
                                            @foreach($brands as $brand)
                                                <option selected value="{{$brand->id}}">{{$brand->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{route('backend.report.receipt')}}" type="submit" class="btn btn-warning">Clear</a>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="col-md-12 mt10">
                    <p class="text-danger float-left">
                        *Do not sort by paid price or discount. We are working on it.
                    </p>
                    {{--                    @if(request()->type!='graphic')--}}
                    {{--                    <a href="{{route('backend.report.receipt',array_merge(request()->all(),['type'=>'graphic']))}}" class="btn btn-success pull-right btn-sm">Graphic</a>--}}
                    {{--                    @else--}}
                    {{--                    <a href="{{route('backend.report.receipt',array_merge(request()->all(),['type'=>'list']))}}" class="btn btn-success pull-right btn-sm">List</a>--}}
                    {{--                    @endif--}}
                    <a href="{{route('backend.report.receipt.excelExport',request()->all())}}"
                       class="btn btn-secondary pull-right btn-sm">
                        <i class="fa fa-file-excel-o"></i> Excel Export
                    </a>

                    {{--                    <a style="margin-right: 5px" href="{{route('backend.report.receipt.excelExport',array_merge(request()->all(),['section'=>'pdf']))}}" class="btn btn-secondary pull-right btn-sm">--}}
                    {{--                        <i class="fa fa-file-pdf-o"></i> Pdf Export--}}
                    {{--                    </a>--}}
                </div>
                <div class="card-content pb20">

                    <div class="row">
                        <div class="col-xs-6 col-md-4 productCopyDiv" style="display: none">
                            <button data-toggle="modal"
                                    data-target="#userFilterModal"
                                    type="button" class="btn btn-info btn-sm"><i class="fa fa-copy"></i> Create Task
                            </button>

                            <button class="btn btn-primary pull-right btn-sm selectedProductExport">
                                <i class="fa fa-file-excel-o"></i> Export Selected Product
                            </button>
                        </div>
                    </div>
                    @if(request()->type!='graphic')
                        <x-datatable :sort="true" :url="route('backend.report.receipt.xhrIndex', request()->all())"
                                     :pageLength="50"
                                     :divId="'storeTable'">
                            <tr>
                                <th :key="index"><span class="table-th-span-mnt">#</span></th>
                                <th :key="product_id" :class="selectColumn" :callback="choose">
                                    <span class="table-th-span-mnt">Chose (<strong
                                            class="chooseCounter">0</strong>)</span>
                                    <input style="opacity: 1; left: auto" type="hidden" name="choose"
                                           {{--class="allChose"--}} value=""/>
                                </th>
                                <th :key="imageUrl" :callback="imageHtml">Image</th>
                                <th :key="product_name"><span class="table-th-span-mnt">Product Name</span>
                                    <input type="text" name="product_name" class="input-filter mnt-custom-input-1"
                                           value=""/>
                                </th>
                                <th :key="product_upc"><span class="table-th-span-mnt">Product Upc</span>
                                    <input type="number" name="product_upc" class="input-filter mnt-custom-input-1"
                                           value=""/>
                                </th>
                                <th :key="product_sku"><span class="table-th-span-mnt">Product Sku</span>
                                    <input type="text" name="product_sku" class="input-filter mnt-custom-input-1"
                                           value=""/>
                                </th>
                                <th :key="shade_no"><span class="table-th-span-mnt">Shade</span>
                                    <input type="text" name="shade_no" class="input-filter mnt-custom-input-1"
                                           value=""/>
                                </th>
                                <th :key="brand_name"><span class="table-th-span-mnt">Brand Name</span>
                                    <input type="text" name="brand_name" class="input-filter mnt-custom-input-1"
                                           value=""/>
                                </th>
                                <th :key="discount"><span class="table-th-span-mnt">Discount</span></th>
                                <th :key="total_quantity"><span class="table-th-span-mnt">Total Quantity</span></th>
                                <th :key="total_paid_price_format"><span class="table-th-span-mnt">Regular Paid Price</span></th>
                                <th :key="stock"><span class="table-th-span-mnt">Storage</span></th>
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

    <div class="modal fade" id="userFilterModal" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bd-example-modal-lg">Product Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('backend.productTask.productAdd')}}" method="post" id="form-validation"
                      class="ajaxForm"
                      data-modalClose="#userFilterModal"
                >
                    @csrf
                    <div class="modal-body">
                        <div class="form-error"></div>
                        <div class="form-group row">

                            <div class="col-md-12">
                                <p class="productcount text-success"></p>
                                <input type="hidden" name="productList" value="">
                            </div>

                            <div class="col-md-12">
                                <label for="task_id" class="control-label">Task <span class="text-danger">(if task is selected products will be assigned to task)</span></label>
                                <span data-target="task_id" class="text-success float-right selectAll"
                                      style="cursor: pointer;">Clear Task</span>
                                <select name="task_id" id="task_id" class="form-control selectTask">
                                    <option value="">Select Task</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-4">
                                <label for="title" class="control-label">Title</label>
                                <input name="title" id="title" value="{{now()->format('m-d-Y')}} Product List"
                                       class="form-control">
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
    <script src="{{asset('assets/bower_components/chart.js/dist/Chart.min.js')}}"></script>

    <script>

        const imageHtml = (data, type, row, meta) => {

            if (row.imageUrl)
                return `<a target="_blank" href="${row.imageUrl}"><img src="${row.imageUrl}" class="img-thumbnail mt-2 mb-2" style="max-width: 50px;"/></a>`;
            else
                return ``;
        }

        $('.selectUserModal').select2({
            placeholder: 'Select User',
            width: 400,
            ajax: {
                url: URL.USERS,
                data: function (params) {
                    return {
                        term: params.term
                    }
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.users, function (item) {
                            return {
                                id: item.id,
                                text: item.text,
                            }
                        })
                    };
                },
            }
        });

        @if(request()->type=='graphic')

        var params = '';
        const getUrlParams = (page = true) => {
            var index = 0;
            params = ''
            window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (str, key, value) {
                if (page || key != 'page') {
                    if (index == 0)
                        params = params + '?' + key + '=' + value;
                    else
                        params = params + '&' + key + '=' + value;

                    index++;
                }
            });

            return params;
        }

        const ajaxUrl = '{{route('backend.report.receipt.xhrIndex')}}';

        $.ajax({
            url: ajaxUrl + getUrlParams(),
            method: 'POST',
            data: {},
            success: function (response) {

                var url = window.location.origin + window.location.pathname + getUrlParams(false)

                for (var i = 1; i <= response.lastPage; i++) {
                    $('.pagination').append(`<li class="page-item ${response.currentPage == i ? 'active' : ''}"><a class="page-link" href="${url + '&page=' + i}">${i}</a></li>`);
                }

                const labels = [];
                const totalQuantity = [];
                const totalPrice = [];
                const totalPaidPrice = [];
                $.each(response.data, function (key, value) {
                    labels.push(value.product_name)
                    totalQuantity.push(value.total_quantity)
                    totalPrice.push(value.product_price)
                    totalPaidPrice.push(value.product_paid_price)
                });

                var barData = {
                    labels: labels,
                    datasets: [
                        {
                            label: "Total Quantity",
                            backgroundColor: 'rgb(1,253,215)',
                            pointBorderColor: "#fff",
                            data: totalQuantity
                        }, {
                            label: "Product Price",
                            backgroundColor: 'rgba(245,153,136,0.68)',
                            pointBorderColor: "#fff",
                            data: totalPrice
                        }, {
                            label: "Paid Price",
                            backgroundColor: 'rgba(229,16,16,0.5)',
                            pointBorderColor: "#fff",
                            data: totalPaidPrice
                        }
                    ]
                };

                var barOptions = {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                };

                var ctx2 = document.getElementById("barChart").getContext("2d");
                new Chart(ctx2, {type: 'bar', data: barData, options: barOptions});
            },
            error: function (xhr, status, error) {

            }
        });
        @endif
    </script>

    <script>

        let productIds = [];

        const clickRowCheckBox = (context) => {

            if (context.is(':checked')) {
                var idx = $.inArray(context.val(), productIds);

                if (idx == -1)
                    productIds.push(context.val());
            } else {
                productIds.splice(idx, 1);
            }

            $('.productcount').html(productIds.length + ' product selected')
            $('input[name="productList"]').val(productIds)

            if (productIds.length > 0) {
                $(".productCopyDiv").css("display", "block");
            } else {
                $(".productCopyDiv").css("display", "none");
            }

        };

        $(document).on("click", ".datatableChoose", function (e) {
            clickRowCheckBox($(this));
        });

        $('.addFilterRestaurant').click(function (e) {
            productIds = [];
            const chooseStorage = localStorage.getItem('choose') ? JSON.parse(localStorage.getItem('choose')) : [];
            chooseStorage.map(c => {
                productIds.push(c.id);
            });

            Swal.fire({
                title: 'İşlem gerçekleştirilsin mi?',
                text: "bu işlemi gerçekleştirmek istiyorsanız \"Evet\" butonuna basın!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Vazgeç!',
                confirmButtonText: 'Evet!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '',
                        method: 'POST',
                        data: {scopeIds: scopeIds, restaurantIds: restaurantIds},
                        success: function (response) {

                            location.href = response.redirectUrl;
                            return;
                        },
                        error: function (xhr, status, error) {
                            const response = $.parseJSON(xhr.responseText);

                            const errors = Object.keys(response.errors).map(function (k) {
                                return response.errors[k]
                            });

                            toastr.error(errors.map(err => `${err[0]}`).join('<br />'),'Error');

                        }
                    });
                }
            });

        });

        $('.selectedProductExport').on('click', function () {
            productIds = [];
            const chooseStorage = localStorage.getItem('choose') ? JSON.parse(localStorage.getItem('choose')) : [];
            chooseStorage.map(c => {
                productIds.push(c.id);
            });

            const route = '{{route('backend.report.receipt.excelExport')}}' + '?productIds=' + productIds;

            window.location = route;
        });

    </script>
@endpush
