@extends('layouts.master')
@section('title', __('pos.category_edit_title'))

@section('content')
    <livewire:product.categories.edit :id="$id" />
@endsection
