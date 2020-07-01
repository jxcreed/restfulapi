@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Email Verification') }}</div>
                <div class="card-body">
                    <p>Your email has been verified! Please <a href="/login" style="text-decoration: underline;">click here to login</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
