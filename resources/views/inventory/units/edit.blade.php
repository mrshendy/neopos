@extends('layouts.master')
@section('title', __('pos.product_create_title'))

@section('content')
  {{-- لو الـ Form::mount(unit $unit = null) --}}
  <livewire:inventory.units.form :unit="$unit" />

  {{-- أو لو الـ Form يستقبل unitId بدل الموديل:
  <livewire:inventory.units.form :unitId="$unit->id" /> --}}
@endsection