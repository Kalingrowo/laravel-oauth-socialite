@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-warning btn-sm" type="button" onclick="checkLoginState()" disabled>Check Login User</button>
                            <button class="btn btn-danger btn-sm" type="button" onclick="setLogoutUser()" disabled>Logout</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection