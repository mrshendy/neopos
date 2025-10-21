@extends('layouts.master')
@section('title', __('pos.finset_title_index'))
@section('content')
<div class="page-wrap">
  @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
      <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  <livewire:finance-settings.index />
</div>
@endsection
