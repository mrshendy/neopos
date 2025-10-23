@extends('layouts.master')
@section('title', __('pos.mov_title_manage') ?? 'إدارة حركة خزينة')
@section('content')
    <livewire:finance-movements.manage :id="$id ?? null" />
@endsection
