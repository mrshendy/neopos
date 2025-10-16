@extends('layouts.master')

@section('title', __('pos.page_manage_products_title'))

@section('content')
<div class="page-wrap">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="mb-1 fw-bold">
                <i class="mdi mdi-package-variant-closed me-2"></i> {{ __('pos.page_manage_products_title') }}
            </h3>
            <div class="text-muted small">{{ __('pos.page_manage_products_sub') }}</div>
        </div>
    </div>

    {{-- كروت الوصول السريع --}}
    @livewire('product.manage', [
        'unitsRoute'      => 'units.index',
        'categoriesRoute' => 'categories.index',
        'productsRoute'   => 'product.index',
        'settingsRoute'   => 'settings.general'
    ])

</div>
@endsection
