@extends('layouts.master')

@section('title')
    {{ __('pos.supplier_list') }}
@endsection

@section('content')
<div class="container-fluid py-3">
    @livewire('supplier.index')
</div>
@endsection
