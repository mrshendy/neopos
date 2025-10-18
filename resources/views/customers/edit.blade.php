@extends('layouts.master')

@section('title')
    {{ __('pos.title_customers_edit') }}
@endsection

@section('content')
<div class="container-fluid py-3">
    @livewire('customer.edit-customer', ['id' => $id])
</div>
@endsection
