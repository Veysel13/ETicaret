@extends('backend.layout.default')
@section('content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('backend.couponManagement.createCoupon', $couponGroup->id) }}" method="post" enctype="multipart/form-data">
                {!! csrf_field().method_field('post') !!}

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <select name="coupon_type" class="form-control">
                                <option {{old('coupon_type')=='jenerik'?'selected':''}} value="jenerik">Jenerik</option>
                                <option {{old('coupon_type')=='unique'?'selected':''}} value="unique">Unique</option>
                                <option {{old('coupon_type')=='txtFile'?'selected':''}} value="txtFile">Special Coupon</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group" id="coupon-count">
                            <label for="coupon_count">Quantity</label>
                            <input type="number" name="coupon_count" id="coupon_count" value="{{old('coupon_count')??1}}"
                                   required
                                   class="form-control @error('coupon_count') is-invalid @enderror">
                        </div>
                        <div class="form-group" id="txt-file">
                            <label for="coupon_count">TXT File</label>
                            <input type="file" name="txtFile" class="form-control"/>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="coupon_prefix">Prefix</label>
                            <input type="text" name="coupon_prefix" id="coupon_prefix"
                                   placeholder="Örnek: EXP-" value="{{old('coupon_prefix')}}"
                                   class="form-control @error('coupon_prefix') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group" id="code-length">
                            <label for="code_length">Length</label>
                            <input type="number" name="code_length" id="code_length" value="{{old('code_length')??'4'}}"
                                   required
                                   class="form-control @error('code_length') is-invalid @enderror">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="discount_type">Discount Type</label>
                            <select name="discount_type" id="discount_type"
                                    class="form-control @error('discount_type') is-invalid @enderror">
                                <option {{old('discount_type')=='percent'?'selected':''}} value="percent">Percent</option>
                                <option {{old('discount_type')=='courier'?'selected':''}} value="courier">Service Fee Discount</option>
                                <option {{old('discount_type')=='discount'?'selected':''}} value="discount">Discount</option>
                            </select>
                        </div>
                        <div class="form-group isCourier" >
                            <input type="checkbox"
                                   name="courier"
                                   id="courier"
                                   class="form-check-input"
                                   {{old('courier')?'checked':''}}
                                   value="1">
                            <label class="form-check-label" for="courier">
                                Service Fee Discount
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="discount">Discount Rate</label>
                            <input type="number" name="discount" id="discount"
                                   value="{{old('discount')}}"
                                   required
                                   class="form-control @error('discount') is-invalid @enderror">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="min_order_price">Min. Order Price</label>
                            <input type="number" name="min_order_price" id="min_order_price"
                                   value="{{old('min_order_price')??0}}"
                                   class="form-control @error('min_order_price') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="uses_quantity">Max. Quantity</label>
                            <input type="number" name="uses_quantity" id="uses_quantity"
                                   value="{{old('uses_quantity')??1}}"
                                   class="form-control @error('uses_quantity') is-invalid @enderror">
                        </div>
                    </div>
                </div>

                <div class="row" id="user-private">
                    <div class="col-md-4" style="display: none">
                        <div class="form-group">
                            <label for="user_id">User Specific</label>
                            <input type="text" name="user_id" id="user_id"
                                   class="form-control @error('user_id') is-invalid @enderror">
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection
@push('footer')
    <script>
        $(function () {
            $('SELECT[NAME="coupon_type"]').change(function (e) {
                e.preventDefault();

                $('#coupon-count').hide();
                $('#txt-file').hide();
                $('#code-length').hide();
                $('#user-private').hide();

                const selectedVal = $(this).val();

                $('#coupon_count').removeAttr("readonly");
                $('label[for="uses_quantity"]').text('Max. Kullanım')
                if (selectedVal === 'jenerik') {
                    $('#coupon_count').val(1);
                    $('#uses_quantity').val(1);
                    $('#coupon_count').attr("readonly",true);

                    $('label[for="uses_quantity"]').text('Max. Kullanım (bir kullanıcının max. kuponu kullanacağı sayı)')
                }

                if (selectedVal === 'txtFile') {
                    $('#txt-file').show();
                    $('#coupon_prefix').attr("required",true);
                } else {
                    $('#coupon_prefix').removeAttr("required")
                    $('#code-length').show();
                    $('#coupon-count').show();
                    $('#user-private').show();
                }

            }).trigger('change');

            $('SELECT[NAME="discount_type"]').change(function (e) {
                e.preventDefault();

                let val = $(this).val(),
                    to = $('[for="discount"]');

                $("#discount").removeAttr("disabled");

                if (val === 'percent') {
                    $('.isCourier').css("display", "block");
                    to.text("İndirim Oranı");
                } else if (val === 'courier') {
                    to.text("Ücretsiz Kurye");
                    $('.isCourier').css("display", "none");
                    $("#courier").prop("checked", false);
                    $("#discount").val("0").attr("disabled", true);
                } else if (val === 'discount') {
                    $('.isCourier').css("display", "block");
                    to.text("İndirim Tutarı");
                }

            }).trigger('change');
        })
    </script>
@endpush
