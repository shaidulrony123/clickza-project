@extends('backend.layouts.app')

@section('content')
    @include('backend.components.productsection.list')
    @include('backend.components.productsection.create')
    @include('backend.components.productsection.edit')
    @include('backend.components.productsection.delete')

@endsection