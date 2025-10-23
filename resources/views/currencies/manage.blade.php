@extends('layouts.master')

@section('content')
    <h1>{{ __('pos.currencies_manage') }}</h1>
    <livewire:currencies.manage />
@endsection
