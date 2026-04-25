@extends('backend.layouts.app')

@section('content')
    @include('backend.components.handnote.list')
    @include('backend.components.handnote.create')
    @include('backend.components.handnote.edit')
    @include('backend.components.handnote.delete')

@endsection