@extends('backend.layout.default')
@push('header')

@endpush
@section('content')

    <div class="row">

        <div class="col-md-12">
            <div class="card mb30">

                <div class="card-content">
                    <form action="{{route('backend.user.passwordChange')}}" method="post" id="form-validation"
                          method="post"
                          class="ajaxForm"
                    >
                        @csrf
                        <div class="form-error"></div>
                        <div class="form-group row">

                            <div class="col-md-12 mt10">
                                <label for="old_password">Last Password</label>
                                <input type="text" class="form-control" id="old_password" name="old_password" placeholder="Last Password...">
                            </div>

                            <div class="col-md-6 mt10">
                                <label for="password">New Password</label>
                                <input type="text" class="form-control" id="password" name="password" placeholder="New Password...">
                            </div>

                            <div class="col-md-6 mt10">
                                <label for="password_confirmation">Password Confirm</label>
                                <input type="text" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Password Confirm...">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-warning">Save</button>
                        </div>
                    </form>
                </div>
            </div><!--card-->
        </div>
    </div>

@endsection

@push('footer')
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/validation.init.js') }}" type="text/javascript"></script>

    <script>
        $("#form-validation").validate({
            rules: {
                old_password: "required",
                password: {
                    required: true,
                    minlength: 8
                },
                password_confirmation: {
                    required: true,
                    minlength: 8
                },
            }
        });
    </script>
@endpush

