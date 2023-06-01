@extends('backend.layout.default')
@section('content')
    <div class="card">
        <div class="card-body">
            <div>
                <div class="pull-right">
                    <a href="{{ route('backend.couponManagement.createCoupon', $couponGroup->id) }}"
                       class="btn btn-sm btn-info waves-effect waves-light">
                        <i class="fa fa-plus"></i> Add Coupon </a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="table-responsive">
                <x-datatable
                    :divId="'couponTable'"
                    :url="route('backend.couponManagement.showGroup', ['couponGroupId' => $couponGroup->id, 'list' => request()->get('list')])">
                    <tr>
                        <th :key="code">Code</th>
                        <th :key="couponType">Type</th>
                        <th :key="discountType">Discount Type</th>
                        <th :key="discountList">Discount</th>
                        <th :key="minOrderPrice">Min. Order Price</th>
                        <th :key="uses_quantity">Uses Quantity</th>
                        <th :key="used_quantity">Used Quantity</th>
                        <th :callback="actionMenu"></th>
                    </tr>
                </x-datatable>
            </div>
        </div>
    </div>

@endsection
@push('footer')
    <script>
        const actionMenu = (data, type, row, meta) => {
            return `
                <a href="${row.removeUrl}" data-toggle="tooltip" data-original-title="Sil" class="removeButton" data-datatableTarget="#couponTable"> <i class="table-delete-i"></i> </a>
            `;
        }
    </script>
@endpush
