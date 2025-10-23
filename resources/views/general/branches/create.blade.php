@extends('layouts.master')

@section('title', 'إضافة فرع')

@section('content')
<div class="page-wrap">
    {{-- Livewire Component --}}
    <livewire:general.branches.create />
</div>
@endsection
