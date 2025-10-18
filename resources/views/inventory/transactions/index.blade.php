@extends('layouts.master')

@section('title') {{ __('pos.inventory_transactions_title') }} @stop

@section('content')
<div class="page-wrap">
   

    @livewire('inventory.transactions.index')
</div>
@endsection
