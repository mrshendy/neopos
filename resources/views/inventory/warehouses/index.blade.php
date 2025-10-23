@extends('layouts.master')

@section('title') {{ __('pos.inventory_warehouses_title') }} @stop

@section('content')
<div class="page-wrap">
   

    @livewire('inventory.warehouses.index')
</div>
@endsection
