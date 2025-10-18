@extends('layouts.master')

@section('title', 'فروع الشركة')

@section('content')
<div class="page-wrap">

    {{-- Livewire Component --}}
    <livewire:general.branches.index />
</div>
@endsection
