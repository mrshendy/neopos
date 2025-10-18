<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .preview-chip{
            display:inline-flex;align-items:center;gap:.35rem;
            background:#f8f9fa;border:1px solid rgba(0,0,0,.06);
            border-radius:999px;padding:.25rem .6rem;font-size:.8rem;color:#6c757d
        }
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .help{font-size:.8rem;color:#6c757d}
    </style>

    <div class="card border-0 shadow-lg rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-warehouse me-2"></i> {{ __('pos.btn_new_warehouse') }}
        </div>

        <div class="card-body p-4">
            <form wire:submit.prevent="save" class="row g-3">

                {{-- ===== اسم المخزن (ع) / (En) ===== --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="mdi mdi-alphabetical"></i> {{ __('pos.warehouse_name_ar') }}
                    </label>
                    <input type="text" class="form-control" wire:model.defer="name.ar" placeholder="{{ __('pos.ph_warehouse_name_ar') }}">
                    <small class="help d-block">{{ __('pos.hint_warehouse_name') }}</small>
                    <div class="mt-1 text-primary">
                        <i class="mdi mdi-eye-outline"></i> {{ $name['ar'] ?? '' }}
                    </div>
                    @error('name.ar') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="mdi mdi-alphabetical-variant"></i> {{ __('pos.warehouse_name_en') }}
                    </label>
                    <input type="text" class="form-control" wire:model.defer="name.en" placeholder="{{ __('pos.ph_warehouse_name_en') }}">
                    <small class="help d-block">{{ __('pos.hint_warehouse_name_en') }}</small>
                    <div class="mt-1 text-primary">
                        <i class="mdi mdi-eye-outline"></i> {{ $name['en'] ?? '' }}
                    </div>
                    @error('name.en') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- ===== كود المخزن ===== --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="mdi mdi-pound-box"></i> {{ __('pos.code') }}</label>
                    <input type="text" class="form-control" wire:model.defer="code" placeholder="{{ __('pos.ph_code') }}">
                    <small class="help d-block">{{ __('pos.hint_unique_warehouse_code') }}</small>
                    <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ $code }}</div>
                    @error('code') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- ===== الفرع (دروب منيو) ===== --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="mdi mdi-home-city-outline"></i> {{ __('pos.branch') }}</label>
                    <select class="form-select" wire:model.defer="branch_id">
                        <option value="">{{ __('pos.select_branch') }}</option>
                        @foreach($branches ?? [] as $b)
                            @php
                                $bName = is_array($b->name ?? null) ? ($b->name['ar'] ?? $b->name['en'] ?? '') : ($b->name ?? '');
                            @endphp
                            <option value="{{ $b->id }}">{{ $bName ?: ('#'.$b->id) }}</option>
                        @endforeach
                    </select>
                    <small class="help d-block">{{ __('pos.hint_select_branch') }}</small>
                    <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ $branch_id }}</div>
                    @error('branch_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- ===== حالة المخزن ===== --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">{{ __('pos.status') }}</label>
                    <select class="form-select" wire:model.defer="status">
                        <option value="active">{{ __('pos.active') }}</option>
                        <option value="inactive">{{ __('pos.inactive') }}</option>
                    </select>
                </div>

                {{-- ===== نوع المخزن (رئيسي/فرعي) ===== --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="mdi mdi-office-building-outline"></i> {{ __('pos.warehouse_type') }}</label>
                    <select class="form-select" wire:model.defer="warehouse_type">
                        <option value="main">{{ __('pos.main') }}</option>
                        <option value="sub">{{ __('pos.sub') }}</option>
                    </select>
                    <small class="help d-block">{{ __('pos.hint_warehouse_type') }}</small>
                    @error('warehouse_type') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- ===== مسئولين المخزن ===== --}}
                <div class="col-md-8">
                    <label class="form-label fw-bold"><i class="mdi mdi-account-multiple-outline"></i> {{ __('pos.warehouse_managers') }}</label>
                    <select class="form-select" wire:model.defer="manager_ids" multiple>
                        @foreach($users ?? [] as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                    <small class="help d-block">{{ __('pos.hint_select_multiple_managers') }}</small>
                    @error('manager_ids') <div class="text-danger small">{{ $message }}</div> @enderror

                    <div class="mt-2" style="display:flex;flex-wrap:wrap;gap:.35rem">
                        @foreach(($users ?? collect())->whereIn('id', (array)($manager_ids ?? [])) as $sel)
                            <span class="preview-chip"><i class="mdi mdi-account"></i> {{ $sel->name }}</span>
                        @endforeach
                    </div>
                </div>

                {{-- ===== عنوان المخزن ===== --}}
                <div class="col-12">
                    <label class="form-label fw-bold"><i class="mdi mdi-map-marker-outline"></i> {{ __('pos.warehouse_address') }}</label>
                    <textarea class="form-control" rows="2" wire:model.defer="address" placeholder="{{ __('pos.ph_warehouse_address') }}"></textarea>
                    <small class="help d-block">{{ __('pos.hint_address') }}</small>
                    @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- ===== الأقسام ← المنتجات ===== --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="mdi mdi-shape-outline"></i> {{ __('pos.category') }}</label>
                    <select class="form-select" wire:model="category_id">
                        <option value="">{{ __('pos.select_category') }}</option>
                        @foreach($categories ?? [] as $cat)
                            @php
                                $cName = is_array($cat->name ?? null) ? ($cat->name['ar'] ?? $cat->name['en'] ?? '') : ($cat->name ?? '');
                            @endphp
                            <option value="{{ $cat->id }}">{{ $cName ?: ('#'.$cat->id) }}</option>
                        @endforeach
                    </select>
                    <small class="help d-block">{{ __('pos.hint_select_category') }}</small>
                    @error('category_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-bold"><i class="mdi mdi-package-variant-closed"></i> {{ __('pos.products') }}</label>
                    <select class="form-select" multiple wire:model.defer="product_ids" @disabled(empty($category_id))>
                        <option value="__ALL__">{{ __('pos.products_all_category') }}</option>
                        @foreach($products ?? [] as $p)
                            @php
                                $pName = is_array($p->name ?? null) ? ($p->name['ar'] ?? $p->name['en'] ?? '') : ($p->name ?? '');
                                $sku = $p->sku ?? null;
                            @endphp
                            <option value="{{ $p->id }}">
                                {{ $pName }}{{ $sku ? ' — '.$sku : '' }}
                            </option>
                        @endforeach
                    </select>
                    <small class="help d-block">{{ __('pos.hint_products_all') }}</small>
                    @error('product_ids') <div class="text-danger small">{{ $message }}</div> @enderror

                    <div class="mt-2" style="display:flex;flex-wrap:wrap;gap:.35rem">
                        @if(collect($product_ids ?? [])->contains('__ALL__'))
                            <span class="preview-chip"><i class="mdi mdi-check-all"></i> {{ __('pos.products_all_chip') }}</span>
                        @else
                            @foreach(($products ?? collect())->whereIn('id', (array)($product_ids ?? [])) as $pp)
                                @php
                                    $ppName = is_array($pp->name ?? null) ? ($pp->name['ar'] ?? $pp->name['en'] ?? '') : ($pp->name ?? '');
                                @endphp
                                <span class="preview-chip"><i class="mdi mdi-cube-outline"></i> {{ $ppName }}</span>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- ===== أزرار الحفظ ===== --}}
                <div class="col-12 text-end">
                    <button class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save"></i> {{ __('pos.btn_save') }}
                    </button>
                    <a href="{{ route('inventory.warehouses.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-close"></i> {{ __('pos.btn_cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
