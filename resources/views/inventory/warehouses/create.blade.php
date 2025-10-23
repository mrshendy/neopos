@extends('layouts.master')

@section('title') {{ __('pos.btn_new_warehouse') }} @stop

@section('content')
<div class="page-wrap">

    @livewire('inventory.warehouses.create')
</div>
@endsection
