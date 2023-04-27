@extends('backend.layout.default')
@push('header')

@endpush
@section('content')

<div class="row">

    <div class="col-md-12">
        <div class="card mb30">

            <div class="card-content">
                <form action="{{route('backend.user.store')}}"
                      method="post"
                      class="ajaxForm"
                      id="form-validation">

                    <div class="form-error"></div>
                    <div class="form-group row">

                        <div class="col-md-6">
                            <label for="fullname">Full Name</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name...">
                        </div>

                        <div class="col-md-6 mt10">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}"  placeholder="Email...">
                        </div>

                        <div class="col-md-6 mt10">
                            <label for="phone">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password...">
                        </div>

                        <div class="col-6 mt10">
                            <label for="password_confirmation" class="control-label">Password Confirm</label>
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"/>
                        </div>

                        <div class="col-md-6 mt10">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" id="status">
                                <option value="1">Active</option>
                                <option value="0">Passive</option>
                            </select>
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
                fullname: "required",
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength:6
                },
            }
        });
    </script>
@endpush

