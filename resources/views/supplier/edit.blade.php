@extends('layouts.master')

@section('title')
    {{ __('pos.supplier_edit') }}
@endsection

@section('content')
<div class="container-fluid py-3">
    @livewire('supplier.edit', ['supplier' => $supplier])
</div>
@endsection
