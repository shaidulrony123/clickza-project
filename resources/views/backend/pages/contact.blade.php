@extends('backend.layouts.app')

@section('content')
    @include('backend.components.contact.list')
    @include('backend.components.contact.edit')
    @include('backend.components.contact.delete')

@endsection