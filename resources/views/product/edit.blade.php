@extends('layouts.master')
@section('title', __('pos.product_create_title'))

@section('content')
    {{-- نمرر المعرّف القادم من الكنترولر إلى لايفواير --}}
    <livewire:product.edit :id="$id" />
@endsection
