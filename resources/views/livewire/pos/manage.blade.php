<div class="page-wrap">
    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-alert-circle-outline me-2"></i><strong>{{ __('pos.input_errors') }}</strong>
            <ul class="mb-0 mt-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        :root{
            --pos-primary:#2563eb;  /* أزرق حديث */
            --pos-accent:#10b981;   /* أخضر */
            --pos-soft:#f8fafc;
            --pos-border:rgba(0,0,0,.06);
            --pos-muted:#64748b;
        }
        .stylish-card{border:1px solid var(--pos-border)}
        .toolbar .btn{border-radius:9999px}
        .form-hint{color:var(--pos-muted);font-size:.8rem;margin-top:.2rem}

        .table thead th{background:#f8f9fc;white-space:nowrap;position:sticky;top:0;z-index:1}
        .table td,.table th{vertical-align:middle}
        .totals-box{background:#fafafa;border:1px dashed rgba(0,0,0,.08);border-radius:12px;padding:14px}
        .totals-box .row>div{margin-bottom:6px}

        /* Chips للأقسام */
        .chipbar{display:flex;flex-wrap:wrap;gap:.5rem}
        .chip{
            display:inline-flex;align-items:center;gap:.4rem;
            background:var(--pos-soft);border:1px solid var(--pos-border);
            padding:.35rem .7rem;border-radius:999px;cursor:pointer;
            transition:.15s; user-select:none;
        }
        .chip.active{background:#e0f2fe;border-color:#93c5fd}
        .chip .dot{width:8px;height:8px;border-radius:999px;background:var(--pos-primary)}
        .preview-card{
            background:#ffffff;border:1px solid var(--pos-border);border-radius:12px;padding:10px;margin-top:6px
        }
        .preview-card .title{font-weight:700}
        .preview-card .meta{color:var(--pos-muted);font-size:.85rem}
        .badge-soft{background:#f8fafc;border:1px solid rgba(0,0,0,.05);color:#334155}
    </style>

    <div class="card shadow-sm rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="mdi mdi-receipt-text-outline me-1"></i>
                <span>{{ $pos_id ? __('pos.pos_title_edit') : __('pos.pos_title_new') }}</span>
                @if($pos_id)
                    <span class="badge bg-light text-dark border">#{{ $pos_id }}</span>
                @endif
            </div>
            <div class="toolbar">
                <a href="{{ route('pos.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="mdi mdi-arrow-left"></i> {{ __('pos.back') }}
                </a>
                <button type="button" class="btn btn-outline-primary btn-sm px-3" wire:click="$refresh">
                    <i class="mdi mdi-refresh"></i> {{ __('pos.refresh') }}
                </button>
            </div>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="save" class="row g-3">
                {{-- Header --}}
                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('pos.sale_date') }}</label>
                    <input type="date" class="form-control" wire:model="pos_date">
                    @error('pos_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('pos.delivery_date') }}</label>
                    <input type="date" class="form-control" wire:model="delivery_date">
                    <div class="form-hint">{{ __('pos.hint_delivery_date') }}</div>
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('pos.warehouse') }}</label>
                    <select class="form-select" wire:model="warehouse_id">
                        <option value="">{{ __('pos.choose') }}</option>
                        @foreach($warehouses as $w)
                            @php
                                $wname = $w->name;
                                if (is_string($wname) && str_starts_with(trim($wname), '{')) {
                                    $a = json_decode($wname,true)?:[];
                                    $wname = $a[app()->getLocale()] ?? $a['ar'] ?? $w->name;
                                }
                            @endphp
                            <option value="{{ $w->id }}">{{ $wname }}</option>
                        @endforeach
                    </select>
                    @error('warehouse_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('pos.customer') }}</label>
                    <select class="form-select" wire:model="customer_id">
                        <option value="">{{ __('pos.choose') }}</option>
                        @foreach($customers as $s)
                            @php
                                $sname = $s->name;
                                if (is_string($sname) && str_starts_with(trim($sname), '{')) {
                                    $a = json_decode($sname,true)?:[];
                                    $sname = $a[app()->getLocale()] ?? $a['ar'] ?? $s->name;
                                }
                            @endphp
                            <option value="{{ $s->id }}">{{ $sname }}</option>
                        @endforeach
                    </select>
                    @error('customer_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Notes --}}
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.notes_ar') }}</label>
                    <textarea class="form-control" rows="2" wire:model="notes_ar" placeholder="{{ __('pos.notes_ph') }}"></textarea>
                </div>
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.notes_en') }}</label>
                    <textarea class="form-control" rows="2" wire:model="notes_en" placeholder="{{ __('pos.notes_ph') }}"></textarea>
                </div>

                {{-- Fast Category Chipbar (فلتر بصري للأقسام) --}}
                <div class="col-12">
                    <div class="chipbar mb-2">
                        <span class="chip active">
                            <span class="dot"></span><span>{{ __('pos.all_categories') ?? 'كل الأقسام' }}</span>
                        </span>
                        @foreach($categories as $cat)
                            @php
                                $cname = $cat->name;
                                if (is_string($cname) && str_starts_with(trim($cname), '{')) {
                                    $a = json_decode($cname,true)?:[];
                                    $cname = $a[app()->getLocale()] ?? $a['ar'] ?? $cat->name;
                                }
                            @endphp
                            <span class="chip"><span class="dot"></span><span>{{ $cname }}</span></span>
                        @endforeach
                    </div>
                </div>

                {{-- Lines --}}
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold"><i class="mdi mdi-format-list-bulleted"></i> {{ __('pos.items_details') }}</div>
                        <div class="toolbar">
                            <button type="button" class="btn btn-outline-primary rounded-pill px-3 shadow-sm" wire:click="addRow">
                                <i class="mdi mdi-plus"></i> {{ __('pos.add_row') }}
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th style="width:40px"></th>
                                    <th style="width:200px">{{ __('pos.category') }}</th>
                                    <th style="width:320px">{{ __('pos.product') }}</th>
                                    <th style="width:140px">{{ __('pos.unit') }}</th>
                                    <th class="text-center" style="width:120px">{{ __('pos.qty') }}</th>
                                    <th class="text-center" style="width:140px">{{ __('pos.unit_price') }}</th>
                                    <th class="text-center" style="width:140px">{{ __('pos.line_total') }}</th>
                                    <th style="width:180px">{{ __('pos.expiry_date') }}</th>
                                    <th style="width:160px">{{ __('pos.batch_no') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $i => $r)
                                    @php
                                        $lineTotal = (float)($r['qty'] ?? 0) * (float)($r['unit_price'] ?? 0);
                                    @endphp
                                    <tr wire:key="row-{{ $i }}">
                                        <td class="text-center">
                                            <button type="button" class="btn btn-light btn-sm" onclick="confirmDeleteRow({{ $i }})" title="{{ __('pos.remove_row') }}">
                                                <i class="mdi mdi-trash-can-outline text-danger"></i>
                                            </button>
                                        </td>

                                        {{-- Category --}}
                                        <td>
                                            <select class="form-select" wire:model="rows.{{ $i }}.category_id" wire:change="rowCategoryChanged({{ $i }})">
                                                <option value="">{{ __('pos.select_category') ?? '— اختر القسم —' }}</option>
                                                @foreach($categories as $cat)
                                                    @php
                                                        $cname = $cat->name;
                                                        if (is_string($cname) && str_starts_with(trim($cname), '{')) {
                                                            $a = json_decode($cname,true)?:[];
                                                            $cname = $a[app()->getLocale()] ?? $a['ar'] ?? $cat->name;
                                                        }
                                                    @endphp
                                                    <option value="{{ $cat->id }}">{{ $cname }}</option>
                                                @endforeach
                                            </select>
                                            @error("rows.$i.category_id") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>

                                        {{-- Product filtered by category --}}
                                        <td>
                                            <select class="form-select"
                                                    wire:model="rows.{{ $i }}.product_id"
                                                    wire:change="rowProductChanged({{ $i }})">
                                                <option value="">{{ __('pos.choose_product') }}</option>
                                                @foreach($products as $p)
                                                    @if(!$r['category_id'] || (int)$p->category_id === (int)$r['category_id'])
                                                        @php
                                                            $pname = $p->name;
                                                            if (is_string($pname) && str_starts_with(trim($pname), '{')) {
                                                                $a = json_decode($pname,true)?:[];
                                                                $pname = $a[app()->getLocale()] ?? $a['ar'] ?? $p->name;
                                                            }
                                                            $pprice = $p->price ?? ($p->selling_price ?? ($p->sale_price ?? ($p->unit_price ?? 0)));
                                                        @endphp
                                                        <option value="{{ $p->id }}">
                                                            {{ $pname }} — {{ number_format((float)$pprice,2) }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error("rows.$i.product_id") <small class="text-danger">{{ $message }}</small> @enderror

                                            {{-- Product preview card --}}
                                            @if(!empty($r['preview']))
                                                <div class="preview-card">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <div class="title">{{ $r['preview']['name'] }}</div>
                                                            <div class="meta">
                                                                @if($r['preview']['sku']) <span>SKU: {{ $r['preview']['sku'] }}</span> @endif
                                                                @if($r['preview']['barcode']) <span class="ms-2">Barcode: {{ $r['preview']['barcode'] }}</span> @endif
                                                            </div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="badge badge-soft">{{ $r['preview']['uom'] ?? '—' }}</div>
                                                            <div class="fw-bold mt-1">{{ number_format((float)($r['preview']['price'] ?? 0), 2) }}</div>
                                                        </div>
                                                    </div>
                                                    @if(!empty($r['preview']['description']))
                                                        <div class="mt-2" style="color:#475569;font-size:.9rem">
                                                            {{ $r['preview']['description'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Unit --}}
                                        <td>
                                            <select class="form-select" wire:model="rows.{{ $i }}.unit_id">
                                                <option value="">{{ __('pos.choose_unit') }}</option>
                                                @foreach($units as $u)
                                                    @php
                                                        $uname = $u->name;
                                                        if (is_string($uname) && str_starts_with(trim($uname), '{')) {
                                                            $a = json_decode($uname,true)?:[];
                                                            $uname = $a[app()->getLocale()] ?? $a['ar'] ?? $u->name;
                                                        }
                                                    @endphp
                                                    <option value="{{ $u->id }}">{{ $uname }}</option>
                                                @endforeach
                                            </select>
                                            @error("rows.$i.unit_id") <small class="text-danger">{{ $message }}</small> @enderror
                                            {{-- العرض النصي للوحدة --}}
                                            @php $uomTxt = $r['uom_text'] ?? null; @endphp
                                            @if($uomTxt)
                                                <div class="form-hint">{{ $uomTxt }}</div>
                                            @endif
                                        </td>

                                        {{-- Qty --}}
                                        <td>
                                            <input type="number" step="0.0001" class="form-control text-center"
                                                   wire:model.lazy="rows.{{ $i }}.qty" placeholder="1">
                                            @error("rows.$i.qty") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>

                                        {{-- Unit price --}}
                                        <td>
                                            <input type="number" step="0.0001" class="form-control text-center"
                                                   wire:model.lazy="rows.{{ $i }}.unit_price" placeholder="0.00">
                                            @error("rows.$i.unit_price") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>

                                        {{-- Line total --}}
                                        <td class="text-center fw-semibold">
                                            {{ number_format($lineTotal, 2) }}
                                        </td>

                                        {{-- Expiry (toggle + date) --}}
                                        <td>
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox"
                                                       wire:model="rows.{{ $i }}.has_expiry"
                                                       id="hasExp{{ $i }}">
                                                <label class="form-check-label" for="hasExp{{ $i }}">
                                                    {{ __('pos.has_expiry') ?? 'له تاريخ صلاحية؟' }}
                                                </label>
                                            </div>
                                            <input type="date" class="form-control"
                                                   wire:model="rows.{{ $i }}.expiry_date"
                                                   {{ empty($r['has_expiry']) ? 'disabled' : '' }}>
                                            @error("rows.$i.expiry_date") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>

                                        {{-- Batch --}}
                                        <td>
                                            <input type="text" class="form-control" wire:model="rows.{{ $i }}.batch_no" placeholder="BATCH-001">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Totals --}}
                <div class="col-12">
                    <div class="totals-box">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-muted small">{{ __('pos.subtotal') }}</div>
                                <div class="fw-bold">{{ number_format((float)$subtotal, 2) }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted small">{{ __('pos.discount') }}</div>
                                <input type="number" step="0.01" class="form-control" wire:model.lazy="discount">
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted small">{{ __('pos.tax') }}</div>
                                <input type="number" step="0.01" class="form-control" wire:model.lazy="tax">
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted small">{{ __('pos.grand_total') }}</div>
                                <div class="fw-bold text-primary">{{ number_format((float)$grand, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm" wire:loading.attr="disabled">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                        <i class="mdi mdi-content-save-outline"></i>
                        {{ $pos_id ? __('pos.update') : __('pos.save') }}
                    </button>
                    <a href="{{ route('pos.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-arrow-left"></i> {{ __('pos.back') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDeleteRow(idx) {
    Swal.fire({
        title: '{{ __("pos.confirm_delete_title") }}',
        text: '{{ __("pos.confirm_delete_row_text") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#0d6efd',
        confirmButtonText: '{{ __("pos.confirm") }}',
        cancelButtonText: '{{ __("pos.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.emit('deleteConfirmed', idx);
            Swal.fire('{{ __("pos.deleted") }}', '{{ __("pos.row_deleted_ok") }}', 'success');
        }
    })
}
</script>
