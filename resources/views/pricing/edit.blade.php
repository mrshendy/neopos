@extends('layouts.master')
@section('title', __('pos.price_lists_title'))

@section('content')
    <livewire:pricing.pricelists.edit :id="$id" />
@endsection
