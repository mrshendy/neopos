@extends('layouts.master')

@section('title')
    {{ __('pos.show') }}
@endsection

@section('content')
<div class="container-fluid py-3">
    <livewire:pricing.pricelists.show :id="$id" />
</div>
@endsection
