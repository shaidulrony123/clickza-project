@extends('backend.layouts.app')

@section('content')
    @include('backend.components.invoice.list')
    @include('backend.components.invoice.create')
    @include('backend.components.invoice.edit')
    @include('backend.components.invoice.delete')
@endsection
