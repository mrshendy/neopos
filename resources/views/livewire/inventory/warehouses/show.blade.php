<div>
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .muted{color:#6c757d}
        .chip{display:inline-flex;align-items:center;gap:.35rem;background:#f8f9fa;border:1px solid rgba(0,0,0,.06);border-radius:12px;padding:.35rem .6rem;font-size:.85rem;color:#6c757d}
        .field{margin-bottom: .65rem}
        .field .lbl{font-weight:700}
        .hr{border-top:1px dashed #e9ecef;margin:.75rem 0}
        .code-chip{font-weight:700;background:#f6f8fb;border:1px solid #e9eef5;padding:.25rem .5rem;border-radius:8px}
    </style>

    <div class="card border-0 shadow-lg rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
            <span><i class="mdi mdi-warehouse me-2"></i> {{ __('pos.warehouse_details') }}</span>
            <div class="d-flex gap-2">
                <a href="{{ route('inventory.warehouses.index') }}" class="btn btn-light btn-sm">
                    <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
                </a>
                <a href="{{ route('inventory.warehouses.edit', $this->warehouse_id) }}" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-pencil"></i> {{ __('pos.btn_edit') }}
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            {{-- الاسم والكود --}}
            <div class="row">
                <div class="col-md-6 field">
                    <div class="lbl">{{ __('pos.warehouse_name') }}</div>
                    <div>{{ $name[app()->getLocale()] ?? ($name['ar'] ?? $name['en'] ?? '—') }}</div>
                    <div class="muted">{{ __('pos.lang_current') }}: {{ app()->getLocale() }}</div>
                </div>
                <div class="col-md-6 field">
                    <div class="lbl">{{ __('pos.code') }}</div>
                    <div class="code-chip">{{ $code ?: '—' }}</div>
                </div>
            </div>

            <div class="hr"></div>

            {{-- الفرع / الحالة / النوع --}}
            <div class="row">
                <div class="col-md-4 field">
                    <div class="lbl"><i class="mdi mdi-home-city-outline"></i> {{ __('pos.branch') }}</div>
                    <div>{{ $branch_label }}</div>
                </div>
                <div class="col-md-4 field">
                    <div class="lbl">{{ __('pos.status') }}</div>
                    <div>
                        @if($status === 'active')
                            <span class="badge bg-success">{{ __('pos.active') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('pos.inactive') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 field">
                    <div class="lbl">{{ __('pos.warehouse_type') }}</div>
                    <div>{{ $warehouse_type === 'main' ? __('pos.main') : __('pos.sub') }}</div>
                </div>
            </div>

            <div class="hr"></div>

            {{-- العنوان --}}
            <div class="field">
                <div class="lbl"><i class="mdi mdi-map-marker-outline"></i> {{ __('pos.warehouse_address') }}</div>
                <div>{{ $address ?: '—' }}</div>
            </div>

            <div class="hr"></div>

            {{-- المسئولون --}}
            <div class="field">
                <div class="lbl"><i class="mdi mdi-account-multiple-outline"></i> {{ __('pos.warehouse_managers') }}</div>
                @if(!empty($managers))
                    <div style="display:flex;flex-wrap:wrap;gap:.35rem">
                        @foreach($managers as $m)
                            <span class="chip"><i class="mdi mdi-account"></i> {{ data_get($m,'name') }}</span>
                        @endforeach
                    </div>
                @else
                    <div class="muted">—</div>
                @endif
            </div>

            <div class="hr"></div>

            {{-- القسم والمنتجات --}}
            <div class="row">
                <div class="col-md-4 field">
                    <div class="lbl"><i class="mdi mdi-shape-outline"></i> {{ __('pos.category') }}</div>
                    <div>{{ $category_label }}</div>
                </div>
                <div class="col-md-8 field">
                    <div class="lbl"><i class="mdi mdi-package-variant-closed"></i> {{ __('pos.products') }}</div>

                    @if(collect($selected_products)->contains('__ALL__'))
                        <span class="chip"><i class="mdi mdi-check-all"></i> {{ __('pos.products_all_chip') }}</span>
                    @elseif(!empty($selected_products))
                        <div style="display:flex;flex-wrap:wrap;gap:.35rem">
                            @foreach($selected_products as $label)
                                <span class="chip"><i class="mdi mdi-cube-outline"></i> {{ $label }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="muted">—</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
