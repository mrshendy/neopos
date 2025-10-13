@extends('layouts.master')

@section('title')
    {{ __('pos.supplier_show') }}
@endsection

@section('content')
<div class="container-fluid py-3">
    @livewire('supplier.show', ['supplier' => $supplier])
</div>
@endsection
