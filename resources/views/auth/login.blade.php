{{--  @extends('layouts.app')  --}}
@extends('layouts.website')
@section('title','登入')
@section('content')
<div class="account">
	<div class="container">
		<h1>會員登入</h1>
		<div class="account_grid">
			   <div class="col-md-6 login-right">
				    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            {{--  <label for="email" class="col-md-4 control-label">帳號/Email Address</label>  --}}
                            <span>帳號/Email Address</span>
                            <div class="col-md-6">
                                <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            {{--  <label for="password" class="col-md-4 control-label">密碼/Password</label>  --}}
                            <span>密碼/Password</span>
                            <div class="col-md-6">
                                <input id="password" type="password" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
					<div class="word-in">
                        <a class="forgot" href="{{ route('password.request') }}">忘記帳號密碼?</a>
				  		<input type="submit" value="Login">
                    </div>
                      
			    </form>
			   </div>	
			    <div class="col-md-6 login-left">
			  	 <h4>註冊新會員</h4>
				 <p>填寫基本資料，即可輕鬆加入大創娃娃屋會員，獲得更多會員專屬優惠哦!</p>
				 <a class="acount-btn" href="{{ route('register') }}">註冊新會員</a>
			   </div>
			   <div class="clearfix"> </div>
			 </div>
	</div>
</div>


{{--  <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>  --}}
@endsection
