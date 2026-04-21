@extends('frontend.layouts.app')

@section('content')
    @include('frontend.components.home.hero')
    @include('frontend.components.home.about')
    @include('frontend.components.home.skills')
    @include('frontend.components.home.project')
    @include('frontend.components.home.marketplace-profiles')
    @include('frontend.components.home.products')
    @include('frontend.components.home.testimonials')
    @include('frontend.components.home.contact')
@endsection