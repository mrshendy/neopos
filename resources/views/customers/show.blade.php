@extends('layouts.master')

@section('title')
    {{ __('pos.title_customers_show') }}
@endsection

@section('content')
<div class="container-fluid py-3">
    @livewire('customer.show', ['id' => $id])
</div>
@endsection
