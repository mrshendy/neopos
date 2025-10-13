@extends('layouts.master')

@section('title')
    {{ __('pos.title_customers_index') }}
@endsection

@section('content')
<div class="container-fluid py-3">

    {{-- 🧩 مكون Livewire --}}
    @livewire('customer.index')

</div>
@endsection
