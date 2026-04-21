@extends('backend.layouts.app')

@section('content')
    @include('backend.components.marketplacesection.list')
    @include('backend.components.marketplacesection.create')
    @include('backend.components.marketplacesection.edit')
    @include('backend.components.marketplacesection.delete')

@endsection