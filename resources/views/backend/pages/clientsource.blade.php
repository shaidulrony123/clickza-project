@extends('backend.layouts.app')

@section('content')
    @include('backend.components.clientsource.list')
    @include('backend.components.clientsource.create')
    @include('backend.components.clientsource.edit')
    @include('backend.components.clientsource.delete')
@endsection
