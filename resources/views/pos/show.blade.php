@extends('layouts.master')
@section('title', __('pos.sales_title'))

@section('content')
    @livewire('pos.show', ['id' => $id])
@endsection
