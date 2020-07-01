@extends('templates.mywebsite_email')

@section('content') 
<h1>Welcome to My Website! One Last Step</h1>
<h3 style="font-weight:normal;">Hello {{$data->firstname.' '.$data->lastname}},</h3>
<p>Thank you for registering to My Website</p>
<p>To complete your registration. please verify your e-mail address by clicking on the link below:</p>
<a href="{{$data->verificationlink}}">{{$data->verificationlink}}</a>
<p>If clicking on the link doesn't work, try copying and pasting it to your browser.</p>
<p>Thank you!</p>
<br/><br/>
@endsection