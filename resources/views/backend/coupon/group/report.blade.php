@extends('backend.layout.default')
@section('content')
    <div class="card">
        <div class="card-body">
            <div>
                <h4 class="card-title pull-left">Sipariş Kupon Rapor</h4>
                <div class="pull-right">
                    <a href="{{ route('backend.couponManagement.usedCouponReportExport', $couponGroupId) }}"
                       target="_blank"
                       class="btn btn-sm btn-secondary">
                        <i class="fa fa-file-excel-o"></i> Excel Aktar </a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="table-responsive">

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Grup</th>

                        <th>İndirim Tipi</th>
                        <th>Kupon Kodu</th>
                        <th>Kupon İndirimi</th>

                        <th>Sipariş No</th>
                        <th>Sipariş Toplam</th>

                        <th>Ad Soyad</th>

                        <th>Kullanım Tarihi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($orderCoupons)
                        @foreach($orderCoupons as $orderCoupon)
                            <tr>
                                <td>{{ $orderCoupon->coupon_group_name }}</td>

                                <td>{{ $orderCoupon->discountType }}</td>

                                <td>{{ $orderCoupon->code }}</td>
                                <td>{{ priceFormat($orderCoupon->discountPrice) }}</td>

                                <td><a href="{{ route('backend.order.view', $orderCoupon->order_id) }}"
                                       target="_blank">{{ $orderCoupon->order_id }}</a></td>
                                <td>{{ priceFormat($orderCoupon->total) }}</td>

                                <td>{{ $orderCoupon->fullname }}</td>

                                <td>{{ $orderCoupon->created_at }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('footer')
@endpush
