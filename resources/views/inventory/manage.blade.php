@extends('layouts.master')

@section('title') {{ __('pos.inventory_title') }} @stop

@section('content')
<div class="page-wrap">
    

    @livewire('inventory.manage')
</div>
@endsection
