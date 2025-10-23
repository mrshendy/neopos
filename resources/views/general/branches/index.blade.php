@extends('layouts.master')

@section('title', 'فروع ')

@section('content')
<div class="page-wrap">

    {{-- Livewire Component --}}
    <livewire:general.branches.index />
</div>
@endsection
