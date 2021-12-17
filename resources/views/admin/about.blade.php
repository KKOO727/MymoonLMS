@extends('admin.newlayout.layout')
@section('title', 'About')
@section('page')
    <div class="card">
        <div class="card-body text-center">
            <img src="{!! get_option('site_logo') !!}">
            <div class="h-10"></div>
            <h3>Proacademy LMS</h3>
            <h4>Version: 2.5</h4>
            <div class="h-10"></div>
            <h5><p>Purchase from <a href="https://codecanyon.net/item/proacademy-lms-online-courses-marketplace/25384806">Codecanyon</a></p></h5>
            <p><a href="mailto:prodevelopersteam@gmail.com">Contact support</a></p>
        </div>
    </div>
@endsection
