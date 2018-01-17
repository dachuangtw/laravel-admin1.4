@extends('layouts.website')
@section('title','登入')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif  
                    Hi~ {{ Auth::user()->name }} 登入成功!
                    <meta http-equiv="refresh" content="3;URL=/">
            </div>
        </div>
    </div>
</div>
@endsection
