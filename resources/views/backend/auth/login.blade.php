@extends('backend.layout.auth')

@section('content')
<div class="account-wrap">
    <div class="account-card">
        <div class="account-content smart-forms">
            <div class="text-center">
                <a href="{{ route('backend.auth.login') }}"><img src="{{asset('/assets/images/logo.png')}}" alt=""></a>
            </div>

            <form method="post" action="{{ route('backend.auth.login') }}">
                @csrf
                <div class="form-body">

                    <div class="spacer-t30 spacer-b30">
                        <div class="tagline"><span> Login </span></div><!-- .tagline -->
                    </div>

                    <div class="section">
                        <label class="field prepend-icon">
                            <input type="text" name="email" id="email" class="gui-input @error('email') is-invalid @enderror" placeholder="Enter email">
                            <span class="field-icon"><i class="fa fa-user"></i></span>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </label>
                    </div><!-- end section -->

                    <div class="section">
                        <label class="field prepend-icon">
                            <input type="text" name="password" id="password" class="gui-input @error('password') is-invalid @enderror" placeholder="Enter password">
                            <span class="field-icon"><i class="fa fa-lock"></i></span>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </label>
                    </div><!-- end section -->

                    <div class="section">
                        <label class="switch block">
                            <input type="checkbox" name="remember" id="remember" checked>
                            <span class="switch-label" for="remember" data-on="YES" data-off="NO"></span>
                            <span> Keep me logged in ?</span>
                        </label>
                    </div><!-- end section -->

                </div><!-- end .form-body section -->
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                </div><!-- end .form-footer section -->

            </form>
        </div>
    </div>
</div>
@endsection
