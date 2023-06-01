@extends('backend.layout.default')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">

                    <div>
                        <div class="pull-right">
                            <a href="{{ route('backend.couponManagement.createGroup') }}"
                               class="btn btn-sm btn-info waves-effect waves-light">
                                <i class="fa fa-plus"></i> New Group </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="table-responsive">
                        <x-datatable
                            :divId="'couponTable'"
                            :url="route('backend.couponManagement.xhrIndex', request()->all())">
                            <tr>
                                <th :callback="listTitle"><span class="table-th-span-mnt">Title</span>
                                    <input type="text" name="name" class="input-filter mnt-custom-input-1" value="" size="5" autocomplete="off"/>
                                </th><th :callback="couponCountAction">Coupon</th>
                                <th :callback="usedCouponAction">Used</th>
                                <th :callback="selectedRestaurants">Restaurants</th>
                                <th :key="listStatus" ><span class="table-th-span-mnt">  Status</span>
                                    <select style="width: 100px" class="form-control mnt-custom-input-1 select-filter" name="status">
                                        <option value="">Choose</option>
                                        <option value="1">Active</option>
                                        <option value="0">Pasive</option>
                                    </select>
                                </th>
                                <th :key="start_date"><span class="table-th-span-mnt">Start Date</span>
                                    <input type="text" name="start_date" class="date-filter only-date-picker-auto-null mnt-custom-input-1" value="" size="5" autocomplete="off"/>
                                </th>
                                <th :key="end_date"><span  class="table-th-span-mnt">End Date</span>
                                    <input type="text" style="min-width: 100px" name="end_date" class="date-filter only-date-picker-auto-null mnt-custom-input-1" value="" size="5" autocomplete="off"/>
                                </th>
                                <th :key="start_time">Start Time</th>
                                <th :key="end_time">End Time</th>
                                <th :callback="actionMenu" ></th>
                            </tr>
                        </x-datatable>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('footer')
    <script>
        const listTitle = (data, type, row, meta) => {
            return '<a href="' + row.detailUrl + '">' + row.name + '</a>';
        }

        const couponCountAction = (data, type, row, meta) => {
            return '<a href="' + row.detailUrl + '?list=unused">' + row.couponCount + '</a>';
        }

        const usedCouponAction = (data, type, row, meta) => {
            return '<a href="' + row.usedCouponReport + '">' + row.usedCoupon + '</a>';
        }

        const actionMenu = (data, type, row, meta) => {
            return `
                <a href="${row.createCoupon}" class="btn btn-sm btn-secondary ml-1"><i class="fa fa-plus"></i> Add Coupon</a>
                <a href="${row.editUrl}"> <i class="table-edit-i m-r-10"></i> </a>
                <a href="${row.removeUrl}" data-toggle="tooltip" data-original-title="Sil" class="removeButton" data-datatableTarget="#categoryTable"> <i class="table-delete-i"></i> </a>
            `;
        }

        const selectedRestaurants = (data, type, row, meta) => {
            return `<a href="${row.restaurantUrl}" class="btn btn-sm btn-success"> Detail (${row.restaurantsCount})</a>`;
        }

        $('.only-date-picker-auto-null').daterangepicker({
            timePicker: false,
            autoUpdateInput: false,
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-danger',
            cancelClass: 'btn-inverse',
            singleDatePicker: true,
            timePicker24Hour: false,
            locale: {
                cancelLabel: 'Sil',
                applyLabel: 'Uygula',
                format: 'DD-MM-YYYY'
            }
        });

        $('.only-date-picker-auto-null').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY')).trigger('change');
        });

        $('.only-date-picker-auto-null').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('').trigger('change');
        });
    </script>
@endpush
