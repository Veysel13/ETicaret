@extends('backend.layout.default')
@push('header')
    <style type="text/css">
        .ck-editor__editable_inline {
            min-height: 300px;
        }
        .displayNone{
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Grup Düzenle</h4>

            <form action="{{ route('backend.couponManagement.editGroup', $couponGroup->id) }}"
                  method="post"
                  enctype="multipart/form-data"
                  class="ajaxForm"
            >
                <div class="form-error"></div>

                <div class="form-group">
                    <label for="name">Title</label>
                    <input type="text" name="name" id="name"
                           value="{{ $couponGroup->name }}"
                           class="form-control @error('name') is-invalid @enderror">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Başlangıç Tarihi</label>
                            <input type="text" name="start_date" id="start_date"
                                   value="{{ $couponGroup->start_date }}"
                                   autocomplete="off"
                                   class="form-control datepicker @error('start_date') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="start_time">Başlangıç Saati <span class="text-danger">(Seçili Saatler Arasında Gösterilir)</span></label>
                        <input type="time" name="start_time" value="{{ $couponGroup->start_time }}" class="form-control" id="start_time"/>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">Bitiş Tarihi</label>
                            <input type="text" name="end_date" id="end_date"
                                   value="{{ $couponGroup->end_date }}"
                                   autocomplete="off"
                                   class="form-control datepicker @error('end_date') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="end_time">Bitiş Saati <span class="text-danger">(Seçili Saatler Arasında Gösterilir)</span></label>
                        <input type="time" name="end_time" value="{{ $couponGroup->end_time }}" class="form-control" id="end_time"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">İçerik <span class="text-danger">(Bu alan Kupon Pop up'ında gösterilecektir. )</span></label>
                    <textarea name="description" id="content"
                              class="form-control textEditor">{!! $couponGroup->description !!}</textarea>
                </div>

                <div class="form-group">
                    <label for="max_discount">Max İndirim Tutarı</label>
                    <input type="number" name="max_discount" id="max_discount"
                           value="{{ $couponGroup->max_discount }}"
                           class="form-control @error('max_discount') is-invalid @enderror">
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="status" class="control-label">Durum</label>
                        <br>
                        <div class="switch">
                            <label>
                                <input type="checkbox" {{ $couponGroup->status == 1 ? "checked" : "" }} name="status" value="1" />
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="first_order" class="control-label">İlk Sipariş'de Geçerli</label>
                        <br>
                        <div class="switch">
                            <label>
                                <input type="checkbox" {{ $couponGroup->first_order == 1 ? "checked" : "" }}  name="first_order" value="1" />
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>


                <button class="btn btn-primary">Kaydet</button>
            </form>
        </div>
    </div>
@endsection
@push('footer')
    <script>

        const startDate = $("#start_date"),
            endDate = $("#end_date");

        endDate.daterangepicker({
            "singleDatePicker": true,
            "autoUpdateInput": false,
            "timePicker": false,
            "locale": {
                format: 'YYYY-MM-DD'
            }
        }).on("apply.daterangepicker", function (ev, picker) {
            endDate.val(picker.endDate.format('YYYY-MM-DD'));
        });

        startDate.daterangepicker({
            "singleDatePicker": true,
            "autoUpdateInput": false,
            "timePicker": false,
            "locale": {
                format: 'YYYY-MM-DD'
            }
        }).on("apply.daterangepicker", function (ev, picker) {
                startDate.val(picker.startDate.format('YYYY-MM-DD'));
                endDate.removeAttr("disabled")
                    .daterangepicker({
                        "startDate": picker.startDate.format('YYYY-MM-DD'),
                        "minDate": picker.startDate.format('YYYY-MM-DD'),
                        "singleDatePicker": true,
                        "autoUpdateInput": false,
                        "timePicker": false,
                        "locale": {
                            format: 'YYYY-MM-DD',
                            cancelLabel: 'Clear'
                        }
                    })
                    .on('cancel.daterangepicker', function (ev, picker) {
                        endDate.val('');
                    })
                    .on('apply.daterangepicker', function (ev, picker) {
                        endDate.val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
                    });
            });
    </script>
@endpush
