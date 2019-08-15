@extends('layouts.frontend')

@section('pageTitle', 'Login | NAMA')
@section('content')
    <section class="bg-white">
        <div class="container">
            <div class="row">
                <form method="POST" action="{{ route('signin') }}">
                    @csrf
                    <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
                        <div class="col-md-12">
                            <h1>Login</h1>
                            <hr style="height:1px;border:none;color:#333;background-color:#333;" />
                            <br/>
                        </div>
                        <div class="col-md-12">
                            @if(\Illuminate\Support\Facades\Session::has('message'))
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                    </button>
                                    <strong>{{ \Illuminate\Support\Facades\Session::get('message') }}</strong>
                                </div>
                            @endif
                            @if($errors->count() > 0)
                                @foreach($errors->all() as $error)
                                    <span class="form-message">
                                        <strong> {{ $error }} </strong>
                                        <br/>
                                    </span>
                                @endforeach
                            @endif
                        </div>
                        <div class="col-md-12">
                            <input type="email" class="form-control" name="email" id="email" placeholder="EMAIL" required/>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <input type="password" class="form-control" name="password" id="password" placeholder="PASSWORD" required/>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <div class="input-checkbox">
                                <label class="label-checkbox">
                                    <input type="checkbox" name="remember" id="remember"/>
                                    <div class="check-indicator"></div>
                                </label>
                                <span>REMEMBER ME</span>
                            </div>
                        </div>
                        <div class="col-md-12 text--center pb-30">
                            <button type="submit" class="btn btn--secondary btn--bordered">Login</button>
                        </div>
                        <div class="col-md-12 text--center">
                            <a href="{{route('password.request')}}" style="font-size: 16px;"><span>Forgot Password?</span></a>
                            <h4 style="margin:10px;">Or</h4>
                            <a href="{{route('register-page')}}" style="font-size: 16px;"><span>Create new account</span></a>
                        </div>
                    </div><!-- .col-md-12 end -->
                </form>
            </div><!-- .row end -->
        </div><!-- .container end -->
    </section>
@endsection
