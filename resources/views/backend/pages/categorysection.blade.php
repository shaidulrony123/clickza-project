@extends('backend.layouts.app')

@section('content')
    @include('backend.components.category.list')
    @include('backend.components.category.create')
    @include('backend.components.category.edit')
    @include('backend.components.category.delete')

@endsection