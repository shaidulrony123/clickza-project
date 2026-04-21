@extends('backend.layouts.app')

@section('content')
    @include('backend.components.projectsection.list')
    @include('backend.components.projectsection.create')
    @include('backend.components.projectsection.edit')
    @include('backend.components.projectsection.delete')

@endsection