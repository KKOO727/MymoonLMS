@extends('app.layout')
@section('page')
    <div class="text-center">
        <div class="py-5">
            <H3>Your Grade</H3>
            <h2 style="color: green">{!! $grade ?? 0 !!}</h2>
        </div>
    </div>
@endsection
