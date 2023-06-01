@extends('backend.layout.default')
@push('header')
    <style type="text/css">
        .ck-editor__editable_inline {
            min-height: 200px;
        }
        .displayNone {
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Yeni Grup Oluştur</h4>

            <form action="{{ route('backend.couponManagement.storeGroup') }}"
                  method="post"
                  enctype="multipart/form-data"
                  class="ajaxForm"
            >
                <div class="form-error"></div>

                <div class="form-group">
                    <label for="name">Başlık</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                </div>

                <div class="form-group" id="restaurantFilterContainer">
                    <label for="restaurant_id">Restoran</label>
                    <button class="btn btn-sm btn-info waves-effect waves-light m-l-15"
                            data-toggle="modal"
                            data-target="#restaurantFilterModal"
                            type="button">
                        <i class="fa fa-plus"></i> Restoran Filtresi
                    </button>

                    <span class="text-success selectedRestaurantCount"> (0 Restoran Seçili)</span>
                    <input type="hidden" name="restaurant_ids" id="restaurantIds">
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Başlangıç Tarihi</label>
                            <input type="text" name="start_date" id="start_date"
                                   autocomplete="off"
                                   class="form-control datepicker @error('start_date') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="start_time">Başlangıç Saati <span class="text-danger">(Seçili Saatler Arasında Gösterilir)</span></label>
                        <input type="time" name="start_time" value="" class="form-control" id="start_time"/>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">Bitiş Tarihi</label>
                            <input type="text" name="end_date" id="end_date"
                                   value=""
                                   autocomplete="off"
                                   disabled
                                   class="form-control datepicker @error('end_date') is-invalid @enderror">
                        </div>
                    </div>

                    <div class="form-group col-6">
                        <label for="end_time">Bitiş Saati <span class="text-danger">(Seçili Saatler Arasında Gösterilir)</span></label>
                        <input type="time" name="end_time" value="" class="form-control" id="end_time"/>
                    </div>

                </div>

                <div class="form-group">
                    <label for="description">İçerik <span class="text-danger">(Bu alan Kupon Pop up'ında gösterilecektir. )</span></label>
                    <textarea name="description" id="content" class="form-control textEditor"></textarea>
                </div>

                <div class="form-group">
                    <label for="max_discount">Max İndirim Tutarı</label>
                    <input type="number" name="max_discount" id="max_discount"
                           class="form-control @error('max_discount') is-invalid @enderror">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status">Status</label>
                        <br>
                        <label class="switch">
                            <input type="checkbox" checked name="status" value="1" id="status">
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div class="col-6 m-t-10">
                        <label for="first_order" class="control-label">İlk Sipariş'de Geçerli</label>
                        <br>
                        <div class="switch">
                            <label>
                                <input type="checkbox"  name="first_order" value="1" />
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary">Kaydet</button>
            </form>
        </div>
    </div>

    <div class="modal" id="restaurantFilterModal" tabindex="-1" role="dialog" aria-labelledby="restaurantFilterLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="restaurantFilterModal">Restoran Filtresi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-12 text-right">
                                    <button class="btn btn-sm btn-info waves-effect waves-light addFilterRestaurant" type="button">
                                        <i class="fa fa-plus"></i> Seçili Restoranları Filtreye Ekle
                                    </button>
                                </div>
                                <x-datatable :pageLength="100" :sort="false" :url="route('backend.couponManagement.xhrRestaurantFilter')">
                                    <tr>
                                        <th :key="id" :class="selectColumn" :callback="choose">
                                            <span class="table-th-span-mnt">Seçim (<strong class="chooseCounter">0</strong>)</span>
                                            <input style="opacity: 1; left: auto" type="checkbox" class="allChose" name="choose" value=""/>
                                        </th>
                                        <th :key="name">
                                            <span class="table-th-span-mnt">Adı</span>
                                            <input type="text" name="name" class="input-filter mnt-custom-input-1" value="" size="5"/>
                                        </th>
                                    </tr>
                                </x-datatable>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
@push('footer')
    <script>

        const startDate = $("#start_date"),
            endDate = $("#end_date");

        startDate.daterangepicker({
            "singleDatePicker": true,
            "timePicker": false,
            "autoUpdateInput": false,
            "locale": {
                format: 'YYYY-MM-DD'
            }
        })
            .on("apply.daterangepicker", function (ev, picker) {
                startDate.val(picker.startDate.format('YYYY-MM-DD'));
                endDate.removeAttr("disabled")
                    .daterangepicker({
                        "startDate": picker.startDate.format('YYYY-MM-DD'),
                        "minDate": picker.startDate.format('YYYY-MM-DD'),
                        "singleDatePicker": true,
                        "timePicker": false,
                        "autoUpdateInput": false,
                        "locale": {
                            format: 'YYYY-MM-DD',
                            cancelLabel: 'Clear'
                        }
                    })
                    .on('cancel.daterangepicker', function (ev, picker) {
                        endDate.val('');
                    })
                    .on('apply.daterangepicker', function (ev, picker) {
                        endDate.val(picker.startDate.format('YYYY-MM-DD'));
                    });
            });
    </script>

    <script>
        let restaurantIds = [];

        $('.datatableChoose').click(function (e) {
            if (!$('input.checkbox_check').is(':checked')) {
                var idx = $.inArray($(this).val(), restaurantIds);
                if (idx != -1)
                    restaurantIds.splice(idx, 1);
            }
        });

        $('.addFilterRestaurant').click(function (e) {
            restaurantIds = [];
            const chooseStorage = localStorage.getItem('choose') ? JSON.parse(localStorage.getItem('choose')) : [];
            chooseStorage.map(c => {
                restaurantIds.push(c.id);
            });

            if(restaurantIds.length>0)
            toastr.success(restaurantIds.length+" restoran filtreye eklendi",'Success');
            else
            toastr.error(restaurantIds.length+" restoran filtreye eklendi",'Error');

            $("#restaurantIds").val(restaurantIds);

            $(".selectedRestaurantCount").text("("+restaurantIds.length+" Restoran Seçili)");

            $("#restaurantFilterModal").modal("hide");
        });
    </script>
@endpush
