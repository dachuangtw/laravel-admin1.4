{{--  @extends('layouts.app')  --}}
@extends('layouts.website')
@section('title','註冊會員')
@section('banner')
@show
@section('content')

<div class="account">
	<div class="container">
		<h1>註冊會員</h1>
		<div class="account_grid">
			<div class="col-md-6 login-right">
				<form class="form-horizontal" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                         {{--  <label for="name" class="col-md-4 control-label">Name</label>  --}}
                        <span>姓名｜Your Name</span>

                            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="請以半形輸入您的姓名。" required autofocus>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        {{--  <label for="email" class="col-md-4 control-label">E-Mail Address</label>  --}}
                        <span>電子郵件/Your E-mail</span>
                            <input id="email" type="text" name="email" value="{{ old('email') }}" placeholder="請以半形輸入，電子郵件不得重複註冊。" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif

                    </div>

                     <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        {{--  <label for="password" class="col-md-4 control-label">Password</label>  --}}
                        <span>設定密碼/Password</span>
                        {{--  <div class="col-md-6">  --}}
                            <input id="password" type="password" name="password" placeholder="請以半形輸入，8-10英數字元組合。" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        {{--  </div>  --}}
                    </div>
                    <div class="form-group">
                        {{--  <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>  --}}
                        <span>確認密碼/Confirm Password</span>
                        {{--  <div class="col-md-6">  --}}
                            <input id="password-confirm" type="password" name="password_confirmation" placeholder="請再輸入一次密碼。" required>
                        {{--  </div>  --}}
                    </div>                                
                    <input type="submit" value="Send">                     
				</form>
            </div>	    
        </div>
    </div>
</div>
{{--  
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

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
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>  --}}
@endsection
