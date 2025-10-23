<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .preview-chip{display:inline-flex;align-items:center;gap:.35rem;background:#f8f9fa;border:1px solid rgba(0,0,0,.06);border-radius:12px;padding:.35rem .6rem;font-size:.85rem;color:#6c757d}
        .preview-box{background:#fff;border:1px dashed #dee2e6;border-radius:.5rem;padding:.5rem .75rem;margin-top:.4rem;color:#6c757d}
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .help{font-size:.8rem;color:#6c757d}
    </style>

    <div class="card border-0 shadow-lg rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-warehouse me-2"></i> {{ __('pos.btn_new_warehouse') }}
        </div>

        <div class="card-body p-4">
            <form wire:submit.prevent="save" class="row g-3">

                {{-- اسم المخزن (ع) --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold"><i class="mdi mdi-alphabetical"></i> {{ __('pos.warehouse_name_ar') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.500ms="name.ar" placeholder="{{ __('pos.ph_warehouse_name_ar') }}">
                    <small class="help d-block">{{ __('pos.hint_warehouse_name') }}</small>
                    @if(!empty($name['ar'])) <div class="preview-box"><i class="mdi mdi-eye-outline"></i> {{ $name['ar'] }}</div> @endif
                    @error('name.ar') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- اسم المخزن (En) --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold"><i class="mdi mdi-alphabetical-variant"></i> {{ __('pos.warehouse_name_en') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.500ms="name.en" placeholder="{{ __('pos.ph_warehouse_name_en') }}">
                    <small class="help d-block">{{ __('pos.hint_warehouse_name_en') }}</small>
                    @if(!empty($name['en'])) <div class="preview-box"><i class="mdi mdi-eye-outline"></i> {{ $name['en'] }}</div> @endif
                    @error('name.en') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- الكود --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="mdi mdi-pound-box"></i> {{ __('pos.code') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.500ms="code" placeholder="{{ __('pos.ph_code') }}">
                    <small class="help d-block">{{ __('pos.hint_unique_warehouse_code') }}</small>
                    @if(!empty($code)) <div class="preview-box"><i class="mdi mdi-eye-outline"></i> {{ $code }}</div> @endif
                    @error('code') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- الفرع --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="mdi mdi-home-city-outline"></i> {{ __('pos.branch') }}</label>
                    <select class="form-select" wire:model="branch_id">
                        <option value="">{{ __('pos.select_branch') }}</option>
                        @foreach(($branches ?? []) as $b)
                            <option value="{{ data_get($b,'id') }}">{{ data_get($b,'label') }}</option>
                        @endforeach
                    </select>
                    <small class="help d-block">{{ __('pos.hint_select_branch') }}</small>
                    @php $selB = collect($branches ?? [])->firstWhere('id', $branch_id); @endphp
                    @if(!empty($branch_id)) <div class="preview-box"><i class="mdi mdi-eye-outline"></i> {{ data_get($selB,'label') }}</div> @endif
                    @error('branch_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- الحالة --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">{{ __('pos.status') }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="active">{{ __('pos.active') }}</option>
                        <option value="inactive">{{ __('pos.inactive') }}</option>
                    </select>
                    @if(!empty($status))
                        <div class="preview-box"><i class="mdi mdi-eye-outline"></i>
                            {{ $status === 'active' ? __('pos.active') : __('pos.inactive') }}
                        </div>
                    @endif
                </div>

                {{-- نوع المخزن --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="mdi mdi-office-building-outline"></i> {{ __('pos.warehouse_type') }}</label>
                    <select class="form-select" wire:model="warehouse_type">
                        <option value="main">{{ __('pos.main') }}</option>
                        <option value="sub">{{ __('pos.sub') }}</option>
                    </select>
                    <small class="help d-block">{{ __('pos.hint_warehouse_type') }}</small>
                    @if(!empty($warehouse_type))
                        <div class="preview-box"><i class="mdi mdi-eye-outline"></i>
                            {{ $warehouse_type === 'main' ? __('pos.main') : __('pos.sub') }}
                        </div>
                    @endif
                    @error('warehouse_type') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- المسئولون --}}
                <div class="col-md-8">
                    <label class="form-label fw-bold"><i class="mdi mdi-account-multiple-outline"></i> {{ __('pos.warehouse_managers') }}</label>
                    <select class="form-select" wire:model="manager_ids" multiple>
                        @foreach(($users ?? []) as $u)
                            <option value="{{ data_get($u,'id') }}">{{ data_get($u,'name') }}</option>
                        @endforeach
                    </select>
                    <small class="help d-block">{{ __('pos.hint_select_multiple_managers') }}</small>
                    @if(!empty($manager_ids))
                        <div class="preview-box">
                            @foreach(collect($users ?? [])->whereIn('id', (array)$manager_ids) as $sel)
                                <span class="preview-chip"><i class="mdi mdi-account"></i> {{ data_get($sel,'name') }}</span>
                            @endforeach
                        </div>
                    @endif
                    @error('manager_ids') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- العنوان --}}
                <div class="col-12">
                    <label class="form-label fw-bold"><i class="mdi mdi-map-marker-outline"></i> {{ __('pos.warehouse_address') }}</label>
                    <textarea class="form-control" rows="2" wire:model.debounce.500ms="address" placeholder="{{ __('pos.ph_warehouse_address') }}"></textarea>
                    <small class="help d-block">{{ __('pos.hint_address') }}</small>
                    @if(!empty($address)) <div class="preview-box"><i class="mdi mdi-eye-outline"></i> {{ $address }}</div> @endif
                    @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- القسم --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="mdi mdi-shape-outline"></i> {{ __('pos.category') }}</label>
                    <select class="form-select" wire:model="category_id">
                        <option value="">{{ __('pos.select_category') }}</option>
                        @foreach(($categories ?? []) as $cat)
                            <option value="{{ data_get($cat,'id') }}">{{ data_get($cat,'label') }}</option>
                        @endforeach
                    </select>
                    <small class="help d-block">{{ __('pos.hint_select_category') }}</small>
                    @php $selC = collect($categories ?? [])->firstWhere('id', $category_id); @endphp
                    @if(!empty($category_id)) <div class="preview-box"><i class="mdi mdi-eye-outline"></i> {{ data_get($selC,'label') }}</div> @endif
                    @error('category_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- المنتجات (Checkboxes) --}}
                <div class="col-md-8">
                    <label class="form-label fw-bold"><i class="mdi mdi-package-variant-closed"></i> {{ __('pos.products') }}</label>

                    @php
                        $allSelected = collect($product_ids ?? [])->contains('__ALL__');
                        $hasCategory = !empty($category_id);
                    @endphp

                    <div class="border rounded p-2" style="max-height:240px;overflow:auto;">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="prod_all" value="__ALL__"
                                   wire:model="product_ids" @disabled(!$hasCategory)>
                            <label class="form-check-label" for="prod_all">{{ __('pos.products_all_category') }}</label>
                        </div>

                        <hr class="my-2">

                        @forelse(($products ?? []) as $p)
                            @php $pid=data_get($p,'id'); $pl=data_get($p,'label'); $ps=data_get($p,'sku'); @endphp
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="prod_{{ $pid }}"
                                       value="{{ $pid }}" wire:model="product_ids"
                                       @disabled(!$hasCategory || $allSelected)>
                                <label class="form-check-label" for="prod_{{ $pid }}">
                                    {{ $pl }}@if($ps) — {{ $ps }} @endif
                                </label>
                            </div>
                        @empty
                            <div class="text-muted small">
                                {{ $hasCategory ? __('pos.no_products_in_category') : __('pos.select_category_first') }}
                            </div>
                        @endforelse
                    </div>

                    <small class="help d-block">{{ __('pos.hint_products_all') }}</small>

                    @if(!empty($product_ids))
                        <div class="preview-box mt-2">
                            @if($allSelected)
                                <span class="preview-chip"><i class="mdi mdi-check-all"></i> {{ __('pos.products_all_chip') }}</span>
                            @else
                                @foreach(collect($products ?? [])->whereIn('id', (array)$product_ids) as $pp)
                                    <span class="preview-chip"><i class="mdi mdi-cube-outline"></i> {{ data_get($pp,'label') }}</span>
                                @endforeach
                            @endif
                        </div>
                    @endif

                    @error('product_ids') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- أزرار --}}
                <div class="col-12 text-end">
                    <a href="{{ route('inventory.warehouses.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-close"></i> {{ __('pos.btn_cancel') }}
                    </a>
                    <button class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save"></i> {{ __('pos.btn_save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
