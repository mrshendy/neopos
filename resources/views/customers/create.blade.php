@extends('layouts.master')

@section('title')
    {{ __('pos.title_customers_create') }}
@endsection

@section('content')
<div class="container-fluid py-3">
    @livewire('customer.create')
</div>
@endsection
