@extends('backend.layout.default')
@push('header')

@endpush
@section('content')

    <div class="card">
        <form action="{{ route('backend.couponManagement.restaurantUpdate', $couponGroup->id) }}"
              method="post"
              class="couponForm"
              id="couponForm"
        >
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <a href="{{route('backend.couponManagement.index')}}" class="float-right">Return List</a>
                    </div>
                </div>
                <hr>
            <div class="row">
                <div class="col-6">
                    <h6>Restaurant List</h6>
                    <x-datatable :sort="false"
                                 :lengthChange="'false'"
                                 :url="route('backend.couponManagement.xhrRestaurantFilter',['couponGroupId'=>$couponGroup->id])">
                        <tr>
                            <th :key="id" :callback="allSelected">
                                <span class="table-th-span-mnt">All<br />Chose</span>
                                <input type="checkbox" class="allChoose" style="opacity: 1; position: relative; left: auto" value=""/>
                                <button type="button" class="btn btn-primary" id="btnChooseTransfer" style="display: none"> Transfer >> </button>
                            </th>
                            <th :key="name">
                                <span class="table-th-span-mnt">Name</span>
                                <input type="text" name="name" class="input-filter mnt-custom-input-1" value=""
                                       size="5"/>
                            </th>
                            <th :key="status" :callback="statusToHtml">
                                <span class="table-th-span-mnt">Status</span>
                            </th>
                            <th :callback="transferHtml">
                                <span class="table-th-span-mnt"></span>
                            </th>
                        </tr>
                    </x-datatable>
                </div>
                <div class="col-6">
                    <h3>Choose List</h3>
                    <div class="table-responsive">
                        <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0"
                               width="100%" id="table-choose">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        </form>

        <div class="card-footer" style="display: flex; justify-content: center">
            <button type="submit" class="btn btn-block btn-primary btn-save" value="" style="position: fixed;bottom: 0;left: 0;z-index: 9;">SAVE</button>
        </div>
    </div>

@endsection

@push('footer')

    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>

    <script type="text/javascript">
        let selectedItems = @json($restaurants);
    </script>

    <script type="text/javascript">

        const imageHtml = (data, type, row, meta) => {
            return `<img src="${data}" style="max-width: 75px" />`;
        }

        const transferHtml = (data, type, row, meta) => {
            return `<a href="javascript:;" class="btn-row-transfer" id="btn-row-transfer-${row.id}"><i class="fa fa-arrow-right"></i></a>`;
        }

        const allSelected = (data, type, row, meta) => {
            return `<input type="checkbox" class="choose" style="opacity: 1; position: relative; left: auto" value="${data}">`;
        }

        const btnChooseTransferDisplay = () => {
            if ($('.choose:checked').length) {
                $('#btnChooseTransfer').show();
            } else {
                $('#btnChooseTransfer').hide();
            }
        }

        $('.allChoose').change(function (e) {
            e.preventDefault();
            if ($(this).is(':checked')) {
                $('.choose').attr('checked', 'checked');
            } else {
                $('.choose').removeAttr('checked', 'checked');
            }

            btnChooseTransferDisplay();
        });

        $('#btnChooseTransfer').click(function (e) {
            e.preventDefault();
            $('.choose').each(function (i, e) {
                if ($(this).is(':checked')) {
                    $('#btn-row-transfer-' + $(this).val()).trigger('click');
                }
            })
        })

        $(document).on('change', '.choose', function (e) {
            e.preventDefault();
            btnChooseTransferDisplay();
        })

        const onLoadData = [];
        selectedItems.map((selectedItem) => {
            onLoadData.push({
                "name": selectedItem.name,
                "status": statusToHtml(selectedItem.status),
                "action": `<a href="javascript:;" data-id="${selectedItem.id}" class="btn-row-remove"><i class="fa fa-window-close text-danger"></i> </a>`,
            });
        })

        $(document).on('click', '.btn-row-transfer', function (e) {
            const data = $('.dataTable').DataTable().row($(this).parents('tr')).data();

            const check = selectedItems.find(selectedItem => selectedItem.id === data.id);
            if (!check) {
                selectedItems.push(data);

                $('#table-choose').DataTable().row.add({
                    "name": data.name,
                    "status": statusToHtml(data.status),
                    "action": `<a href="javascript:;" data-id="${data.id}" class="btn-row-remove"><i class="fa fa-window-close text-danger"></i> </a>`,
                }).draw();
            } else {
                toastr.error(data.name + ' - Already Choose','Error');
            }
        });

        $('#table-choose').on('click', '.btn-row-remove', function (e) {
            const id = $(this).data('id');

            $('#table-choose').DataTable().row($(this).parents('tr')).remove().draw();
            selectedItems = selectedItems.filter(selectedItem => selectedItem.id !== id);
        });

        $(document).ready(function () {
            $('#table-choose').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Export',
                        title: '{{$couponGroup->name}} Restaurant'
                    }
                ],
                lengthChange: false,
                ordering:false,
                pageLength: 20,
                data: onLoadData,
                columns: [
                    {data: 'name'},
                    {data: 'status'},
                    {data: 'action'}
                ]
            });

            $('.btn-save').click(function (e) {
                e.preventDefault();

                const context = $(this);
                context.hide();

                const restaurantIds = selectedItems.map(selectedItem => selectedItem.id);

                const form = $('#couponForm');
                const formUrl = form.attr('action');

                const fData = {};
                form.serializeArray().map((input) => {
                    fData[input.name] = input.value;
                });

                fData['restaurant_ids'] = restaurantIds;

                $.ajax({
                    url: formUrl,
                    method: 'POST',
                    data: fData,
                    success: function (res) {

                        if (res.status) {

                            toastr.success(res.message,'Success');

                            window.location.href = res.redirectUrl;
                        } else {

                            toastr.error(res.message,'Error');

                            context.show();
                        }
                    },
                    error: function (xhr, status, error) {
                        const response = $.parseJSON(xhr.responseText);

                        const errors = Object.keys(response.errors).map(function (k) {
                            return response.errors[k]
                        });

                        toastr.error(errors.map(err => `${err[0]}`).join('<br />'),'Error');

                        context.show();
                    },
                });

            });
        });

    </script>

@endpush
