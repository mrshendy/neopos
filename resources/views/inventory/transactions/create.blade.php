@extends('layouts.master')

@section('title') {{ __('pos.inventory_transactions_create') }} @stop

@section('content')
<div class="page-wrap">
  

    @livewire('inventory.transactions.manage')
</div>
@endsection
