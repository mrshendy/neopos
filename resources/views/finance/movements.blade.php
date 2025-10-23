@extends('layouts.master')
@section('title', __('pos.mov_title_index') ?? 'حركات الخزائن')
@section('content')
    <livewire:finance-movements.index />
@endsection
