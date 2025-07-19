@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card mx-4 shadow border-0">

            <div class="card-body p-4 rounded mx-3 mb-3">
                <div class="pb-4 mb-2">
                    <img src="{{asset('images/harvin-chairs-logo.png')}}" class="w-100" alt="">
                </div>
                {{-- <h1>{{ trans('panel.site_title') }}</h1> --}}
                <!-- <p class="text-muted h4">{{ trans('global.login') }}</p> -->

                @if(session('message'))
                    <div class="alert alert-info" role="alert">
                        {{ session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <input id="email" name="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} form-control-lg" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email', null) }}">

                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="input-group mb-2">

                        <input id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} form-control-lg" required placeholder="{{ trans('global.login_password') }}">

                        @if($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        @if(Route::has('password.request'))
                            <a class="btn btn-link px-0" href="{{ route('password.request') }}">
                                {{ trans('global.forgot_password') }}
                            </a><br>
                        @endif
                    </div>

                    <!-- <div class="input-group mb-4">
                        <div class="form-check checkbox d-flex align-items-center">
                            <input class="form-check-input" name="remember" type="checkbox" id="remember" style="vertical-align: middle;" />
                            <label class="form-check-label mt-1" for="remember" style="vertical-align: middle;">
                                {{ trans('global.remember_me') }}
                            </label>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg px-4 w-100">
                                {{ trans('global.login') }}
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
