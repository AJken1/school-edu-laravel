@extends('layouts.public')

@section('title', 'EDUgate - Future is here')

@section('content')
<div class="big-wrapper light">
    <img src="{{ asset('images/shape.png') }}" alt="" class="shape" />
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-md-6 d-flex justify-content-center get-started" style="height: 550px;">
                <div class="d-flex justify-content-center align-items-center">
                    <div>
                        <div class="big-title">
                            <h1>Future is here,</h1>
                            <h1>Start Exploring now.</h1>
                        </div>
                        <p class="text">
                            Hello World!
                        </p>
                        <div class="cta"> 
                            <a href="{{ route('enrollment') }}" class="btn">Get started</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-6 image-box">
                <img src="{{ asset('images/children.png') }}" alt="Person Image" class="person" />
            </div>
        </div>
    </div>
</div>
@endsection