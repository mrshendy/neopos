@extends('layouts.master')
@section('title', __('pos.currencies_manage'))
@section('content')
    <h1>{{ __('pos.currencies_manage') }}</h1>
    <livewire:currencies.manage />
@endsection
