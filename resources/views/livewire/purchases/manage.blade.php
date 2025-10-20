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
        .table thead th{background:#f8f9fc; white-space:nowrap}
        .table td,.table th{vertical-align:middle}
        .preview{font-size:.85rem;color:#64748b;margin-top:.25rem}
    </style>

    <div class="card shadow-sm rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-receipt-text-outline me-1"></i> {{ __('pos.purchase_title') }}
        </div>

        <div class="card-body">
            <form wire:submit.prevent="save" class="row g-3">

                {{-- Header row 1 --}}
                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('pos.purchase_date') }}</label>
                    <input type="date" class="form-control" wire:model="purchase_date">
                    <div class="preview"><i class="mdi mdi-calendar"></i> {{ $purchase_date }}</div>
                    <div class="text-muted small">{{ __('pos.hint_purchase_date') }}</div>
                    @error('purchase_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('pos.delivery_date') }}</label>
                    <input type="date" class="form-control" wire:model="delivery_date">
                    <div class="preview"><i class="mdi mdi-calendar-clock"></i> {{ $delivery_date }}</div>
                    <div class="text-muted small">{{ __('pos.hint_delivery_date') }}</div>
                    @error('delivery_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('pos.warehouse') }}</label>
                    <select class="form-select" wire:model="warehouse_id">
                        <option value="">{{ __('pos.choose') }}</option>
                        @foreach($warehouses as $w)
                            @php
                                $wname = $w->name; if (is_string($wname) && str_starts_with(trim($wname), '{')) {$a=json_decode($wname,true)?:[]; $wname=$a[app()->getLocale()]??$a['ar']??$w->name;}
                            @endphp
                            <option value="{{ $w->id }}">{{ $wname }}</option>
                        @endforeach
                    </select>
                    <div class="preview"><i class="mdi mdi-warehouse"></i> {{ $warehouse_id }}</div>
                    <div class="text-muted small">{{ __('pos.hint_warehouse') }}</div>
                    @error('warehouse_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('pos.supplier') }}</label>
                    <select class="form-select" wire:model="supplier_id">
                        <option value="">{{ __('pos.choose') }}</option>
                        @foreach($suppliers as $s)
                            @php
                                $sname = $s->name; if (is_string($sname) && str_starts_with(trim($sname), '{')) {$a=json_decode($sname,true)?:[]; $sname=$a[app()->getLocale()]??$a['ar']??$s->name;}
                            @endphp
                            <option value="{{ $s->id }}">{{ $sname }}</option>
                        @endforeach
                    </select>
                    <div class="preview"><i class="mdi mdi-truck-outline"></i> {{ $supplier_id }}</div>
                    <div class="text-muted small">{{ __('pos.hint_supplier') }}</div>
                    @error('supplier_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Notes --}}
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.notes_ar') }}</label>
                    <textarea class="form-control" rows="2" wire:model="notes_ar" placeholder="{{ __('pos.notes_ph') }}"></textarea>
                    <div class="preview"><i class="mdi mdi-note-text-outline"></i> {{ $notes_ar }}</div>
                </div>
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.notes_en') }}</label>
                    <textarea class="form-control" rows="2" wire:model="notes_en" placeholder="{{ __('pos.notes_ph') }}"></textarea>
                    <div class="preview"><i class="mdi mdi-note-text-outline"></i> {{ $notes_en }}</div>
                </div>

                {{-- ====== Lines ====== --}}
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold"><i class="mdi mdi-format-list-bulleted"></i> {{ __('pos.items_details') }}</div>
                        <button type="button" class="btn btn-outline-primary rounded-pill px-3 shadow-sm" wire:click="addRow">
                            <i class="mdi mdi-plus"></i> {{ __('pos.add_row') }}
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th style="width:36px"></th>
                                    <th style="width:260px">{{ __('pos.product') }}</th>
                                    <th style="width:120px">{{ __('pos.code') }}</th>
                                    <th style="width:160px">{{ __('pos.category') }}</th>
                                    <th style="width:160px">{{ __('pos.unit') }}</th>
                                    <th style="width:120px">{{ __('pos.qty') }}</th>
                                    <th style="width:140px">{{ __('pos.unit_price') }}</th>
                                    <th style="width:140px">{{ __('pos.onhand') }}</th>
                                    <th style="width:160px">{{ __('pos.expiry_date') }}</th>
                                    <th style="width:160px">{{ __('pos.batch_no') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $i => $r)
                                    <tr>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-light btn-sm" onclick="confirmDeleteRow({{ $i }})">
                                                <i class="mdi mdi-trash-can-outline text-danger"></i>
                                            </button>
                                        </td>

                                        <td>
                                            <select class="form-select" wire:model="rows.{{ $i }}.product_id">
                                                <option value="">{{ __('pos.choose_product') }}</option>
                                                @foreach($products as $p)
                                                    @php
                                                        $pname = $p->name; if (is_string($pname) && str_starts_with(trim($pname), '{')) {$a=json_decode($pname,true)?:[]; $pname=$a[app()->getLocale()]??$a['ar']??$p->name;}
                                                    @endphp
                                                    <option value="{{ $p->id }}">{{ $pname }}</option>
                                                @endforeach
                                            </select>
                                            @error("rows.$i.product_id") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>

                                        <td>
                                            <input type="text" class="form-control" wire:model.defer="rows.{{ $i }}.code" readonly>
                                        </td>

                                        <td>
                                            <input type="text" class="form-control" wire:model.defer="rows.{{ $i }}.category" readonly>
                                        </td>

                                        <td>
                                            <select class="form-select" wire:model="rows.{{ $i }}.unit_id">
                                                <option value="">{{ __('pos.choose_unit') }}</option>
                                                @foreach($units as $u)
                                                    @php
                                                        $uname = $u->name; if (is_string($uname) && str_starts_with(trim($uname), '{')) {$a=json_decode($uname,true)?:[]; $uname=$a[app()->getLocale()]??$a['ar']??$u->name;}
                                                    @endphp
                                                    <option value="{{ $u->id }}">{{ $uname }}</option>
                                                @endforeach
                                            </select>
                                            @error("rows.$i.unit_id") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>

                                        <td>
                                            <input type="number" step="0.0001" class="form-control text-center"
                                                   wire:model.lazy="rows.{{ $i }}.qty" placeholder="0">
                                            @error("rows.$i.qty") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>

                                        <td>
                                            <input type="number" step="0.0001" class="form-control text-center"
                                                   wire:model.lazy="rows.{{ $i }}.unit_price" placeholder="0.00">
                                        </td>

                                        <td class="text-end">
                                            <span class="{{ ($r['onhand']??0) < 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                                {{ number_format((float)($r['onhand']??0), 4) }}
                                            </span>
                                        </td>

                                        <td>
                                            <input type="date" class="form-control" wire:model="rows.{{ $i }}.expiry_date">
                                        </td>

                                        <td>
                                            <input type="text" class="form-control" wire:model="rows.{{ $i }}.batch_no" placeholder="BATCH-001">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save-outline"></i> {{ __('pos.save') }}
                    </button>
                    <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-arrow-left"></i> {{ __('pos.back') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ✅ SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'تحذير',
                text: '⚠️ هل أنت متأكد أنك تريد حذف هذا الإجراء لا يمكن التراجع عنه!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#0d6efd',
                confirmButtonText: 'نعم، احذفها',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('deleteConfirmed', id);
                    Swal.fire('تم الحذف!', '✅ تم الحذف  بنجاح.', 'success');
                }
            })
        }
    </script>

