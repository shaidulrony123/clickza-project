@extends('backend.layouts.app')

@section('content')
    @include('backend.components.completeprojecturl.list')
    @include('backend.components.completeprojecturl.create')
    @include('backend.components.completeprojecturl.edit')
    @include('backend.components.completeprojecturl.delete')

@endsection