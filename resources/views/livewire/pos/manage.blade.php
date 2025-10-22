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
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .table thead th{background:#f8f9fc;white-space:nowrap;position:sticky;top:0;z-index:1}
        .table td,.table th{vertical-align:middle}
        .preview{font-size:.85rem;color:#64748b;margin-top:.25rem;display:flex;align-items:center;gap:.35rem}
        .badge-soft{background:#f8fafc;border:1px solid rgba(0,0,0,.05);color:#334155}
        .form-hint{color:#6b7280;font-size:.8rem;margin-top:.2rem}
        .toolbar{display:flex;gap:.5rem;align-items:center}
        .toolbar .btn{border-radius:9999px}
        .totals-box{background:#fafafa;border:1px dashed rgba(0,0,0,.08);border-radius:12px;padding:14px}
        .totals-box .row > div{margin-bottom:6px}
    </style>

    <div class="card shadow-sm rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
            <div>
                <i class="mdi mdi-receipt-text-outline me-1"></i>
                {{ $pos_id ? __('pos.pos_title_edit') : __('pos.pos_title_new') }}
                @if($pos_id)
                    <span class="badge bg-light text-dark border ms-2">#{{ $pos_id }}</span>
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
                    <div class="preview"><i class="mdi mdi-calendar"></i> <span>{{ $pos_date ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_sale_date') }}</div>
                    @error('pos_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('pos.delivery_date') }}</label>
                    <input type="date" class="form-control" wire:model="delivery_date">
                    <div class="preview"><i class="mdi mdi-calendar-clock"></i> <span>{{ $delivery_date ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_delivery_date') }}</div>
                    @error('delivery_date') <small class="text-danger">{{ $message }}</small> @enderror
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
                    <div class="preview"><i class="mdi mdi-warehouse"></i> <span class="badge badge-soft">{{ $warehouse_id ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_warehouse') }}</div>
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
                    <div class="preview"><i class="mdi mdi-account-outline"></i> <span class="badge badge-soft">{{ $customer_id ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_customer') }}</div>
                    @error('customer_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Notes --}}
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.notes_ar') }}</label>
                    <textarea class="form-control" rows="2" wire:model="notes_ar" placeholder="{{ __('pos.notes_ph') }}"></textarea>
                    <div class="preview"><i class="mdi mdi-note-text-outline"></i> <span>{{ $notes_ar }}</span></div>
                </div>
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.notes_en') }}</label>
                    <textarea class="form-control" rows="2" wire:model="notes_en" placeholder="{{ __('pos.notes_ph') }}"></textarea>
                    <div class="preview"><i class="mdi mdi-note-text-outline"></i> <span>{{ $notes_en }}</span></div>
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
                                    <th style="width:300px">{{ __('pos.product') }}</th>
                                    <th style="width:160px">{{ __('pos.unit') }}</th>
                                    <th style="width:120px" class="text-center">{{ __('pos.qty') }}</th>
                                    <th style="width:140px" class="text-center">{{ __('pos.unit_price') }}</th>
                                    <th style="width:120px" class="text-center">{{ __('pos.onhand') }}</th>
                                    <th style="width:160px">{{ __('pos.expiry_date') }}</th>
                                    <th style="width:160px">{{ __('pos.batch_no') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $i => $r)
                                    <tr wire:key="row-{{ $i }}">
                                        <td class="text-center">
                                            <button type="button" class="btn btn-light btn-sm" onclick="confirmDeleteRow({{ $i }})" title="{{ __('pos.remove_row') }}">
                                                <i class="mdi mdi-trash-can-outline text-danger"></i>
                                            </button>
                                        </td>

                                        {{-- Product --}}
                                        <td>
                                            <select class="form-select"
                                                    wire:model="rows.{{ $i }}.product_id"
                                                    wire:change="rowProductChanged({{ $i }})">
                                                <option value="">{{ __('pos.choose_product') }}</option>
                                                @foreach($products as $p)
                                                    @php
                                                        $pname = $p->name;
                                                        if (is_string($pname) && str_starts_with(trim($pname), '{')) {
                                                            $a = json_decode($pname,true)?:[];
                                                            $pname = $a[app()->getLocale()] ?? $a['ar'] ?? $p->name;
                                                        }
                                                    @endphp
                                                    <option value="{{ $p->id }}">{{ $pname }}</option>
                                                @endforeach
                                            </select>
                                            @error("rows.$i.product_id") <small class="text-danger">{{ $message }}</small> @enderror
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
                                        </td>

                                        {{-- Qty --}}
                                        <td>
                                            <input type="number" step="0.0001" class="form-control text-center"
                                                   wire:model.lazy="rows.{{ $i }}.qty" placeholder="0">
                                            @error("rows.$i.qty") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>

                                        {{-- Unit price --}}
                                        <td>
                                            <input type="number" step="0.0001" class="form-control text-center"
                                                   wire:model.lazy="rows.{{ $i }}.unit_price" placeholder="0.00">
                                            @error("rows.$i.unit_price") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>

                                        {{-- On hand --}}
                                        <td class="text-end">
                                            @php $oh = (float)($r['onhand'] ?? 0); @endphp
                                            <span class="{{ $oh < 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                                {{ number_format($oh, 4) }}
                                            </span>
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

                {{-- خصم وضريبة --}}
                <div class="col-md-3">
                    <label class="form-label">{{ __('pos.discount') }}</label>
                    <input type="number" step="0.01" class="form-control" wire:model.lazy="discount">
                    @error('discount') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('pos.tax') }}</label>
                    <input type="number" step="0.01" class="form-control" wire:model.lazy="tax">
                    @error('tax') <small class="text-danger">{{ $message }}</small> @enderror
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
                                <div class="fw-bold">{{ number_format((float)$discount, 2) }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted small">{{ __('pos.tax') }}</div>
                                <div class="fw-bold">{{ number_format((float)$tax, 2) }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted small">{{ __('pos.grand_total') }}</div>
                                <div class="fw-bold text-black">{{ number_format((float)$grand, 2) }}</div>
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
