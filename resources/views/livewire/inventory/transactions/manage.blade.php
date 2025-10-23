<div class="container-fluid">

    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-alert-circle-outline me-2"></i><strong>{{ __('pos.form_errors_title') }}</strong>
            <ul class="mb-0 mt-2">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .table thead th{background:#f8f9fc; white-space:nowrap}
        .table td,.table th{vertical-align:middle}
        .preview-chip{display:flex;align-items:center;gap:.5rem;background:#f8fafc;border:1px dashed rgba(0,0,0,.08);border-radius:12px;padding:.35rem .6rem;margin-top:.4rem;font-size:.85rem;color:#4b5563}
        .preview-chip i{opacity:.7}
    </style>

    <div class="card shadow-sm rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-swap-horizontal-bold me-1"></i> {{ __('pos.trx_form_title') }}
        </div>

        <div class="card-body">
            <form wire:submit.prevent="save" class="row g-3">

                {{-- التاريخ --}}
                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-calendar"></i> {{ __('pos.trx_date') }}</label>
                    <input type="date" class="form-control" wire:model="trx_date">
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $trx_date }}</span></div>
                    @error('trx_date') <small class="text-danger">{{ $message }}</small> @enderror
                    <div class="form-text">{{ __('pos.hint_trx_date') }}</div>
                </div>

                {{-- النوع --}}
                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-shuffle-variant"></i> {{ __('pos.trx_type') }}</label>
                    <select class="form-select" wire:model="type">
                        <option value="in">{{ __('pos.trx_type_in') }}</option>
                        <option value="out">{{ __('pos.trx_type_out') }}</option>
                        <option value="transfer">{{ __('pos.trx_type_transfer') }}</option>
                        <option value="direct_add">{{ __('pos.trx_type_direct_add') }}</option>
                    </select>
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ strtoupper($type) }}</span></div>
                    @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                    <div class="form-text">{{ __('pos.hint_trx_type') }}</div>
                </div>

                {{-- مخزن المصدر --}}
                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-store-outline"></i> {{ __('pos.from_warehouse') }}</label>
                    <select class="form-select"
                            wire:model="warehouse_from_id"
                            @disabled(! in_array($type, ['out','transfer']))>
                        <option value="">{{ __('pos.choose') }}</option>
                        @foreach($warehouses as $w) <option value="{{ $w->id }}">{{ $w->name }}</option> @endforeach
                    </select>
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>
                        @php $whf = collect($warehouses)->firstWhere('id',(int)$warehouse_from_id); echo $whf->name ?? '—'; @endphp
                    </span></div>
                    @error('warehouse_from_id') <small class="text-danger">{{ $message }}</small> @enderror
                    <div class="form-text">{{ __('pos.hint_from_warehouse') }}</div>
                </div>

                {{-- مخزن الوجهة --}}
                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-warehouse"></i> {{ __('pos.to_warehouse') }}</label>
                    <select class="form-select"
                            wire:model="warehouse_to_id"
                            @disabled(! in_array($type, ['in','transfer','direct_add']))>
                        <option value="">{{ __('pos.choose') }}</option>
                        @foreach($warehouses as $w) <option value="{{ $w->id }}">{{ $w->name }}</option> @endforeach
                    </select>
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>
                        @php $wht = collect($warehouses)->firstWhere('id',(int)$warehouse_to_id); echo $wht->name ?? '—'; @endphp
                    </span></div>
                    @error('warehouse_to_id') <small class="text-danger">{{ $message }}</small> @enderror
                    <div class="form-text">{{ __('pos.hint_to_warehouse') }}</div>
                </div>

                {{-- ملاحظات --}}
                <div class="col-12">
                    <label class="form-label mb-1"><i class="mdi mdi-note-text-outline"></i> {{ __('pos.notes') }}</label>
                    <textarea class="form-control" rows="2" wire:model="notes" placeholder='{"ar":"ملاحظة","en":"Note"}'></textarea>
                    <div class="form-text">{{ __('pos.hint_notes_json') }}</div>
                </div>

                {{-- تفاصيل الحركة --}}
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold"><i class="mdi mdi-format-list-bulleted"></i> {{ __('pos.trx_items_title') }}</div>
                        <button type="button" class="btn btn-outline-primary rounded-pill px-3 shadow-sm" wire:click="addRow">
                            <i class="mdi mdi-plus"></i> {{ __('pos.add_row') }}
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th style="width:40px"></th>
                                    <th style="width:220px">{{ __('pos.product') }}</th>
                                    <th style="width:140px">{{ __('pos.unit') }}</th>
                                    <th style="width:120px" class="text-center">{{ __('pos.onhand') }}</th>
                                    <th style="width:120px">{{ __('pos.qty') }}</th>
                                    <th style="width:160px">{{ __('pos.uom_text') }}</th>
                                    <th style="width:160px">{{ __('pos.expiry_date') }}</th>
                                    <th style="width:160px">{{ __('pos.batch_no') }}</th>
                                    <th style="width:180px">{{ __('pos.reason') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $i => $row)
                                    <tr>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-light btn-sm" onclick="confirmDelete({{ $i }})" title="{{ __('pos.remove_row') }}">
                                                <i class="mdi mdi-trash-can-outline text-danger"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <select class="form-select" wire:model="rows.{{ $i }}.product_id" wire:change="refreshOnhand({{ $i }})">
                                                <option value="">{{ __('pos.choose_product') }}</option>
                                                @foreach($products as $p) <option value="{{ $p->id }}">{{ $p->name }}</option> @endforeach
                                            </select>
                                            @error("rows.$i.product_id") <small class="text-danger">{{ $message }}</small> @enderror
                                            <div class="form-text">{{ __('pos.hint_product') }}</div>
                                        </td>
                                        <td>
                                            <select class="form-select" wire:model="rows.{{ $i }}.unit_id">
                                                <option value="">{{ __('pos.choose_unit') }}</option>
                                                @foreach($units as $u) <option value="{{ $u->id }}">{{ $u->name }}</option> @endforeach
                                            </select>
                                            @error("rows.$i.unit_id") <small class="text-danger">{{ $message }}</small> @enderror
                                            <div class="form-text">{{ __('pos.hint_unit') }}</div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border">{{ number_format((float)($row['onhand'] ?? 0), 4) }}</span>
                                        </td>
                                        <td>
                                            <input type="number" step="0.0001" class="form-control text-center" wire:model.lazy="rows.{{ $i }}.qty" placeholder="0.0000">
                                            @error("rows.$i.qty") <small class="text-danger">{{ $message }}</small> @enderror
                                            <div class="form-text">{{ __('pos.hint_qty') }}</div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" wire:model.lazy="rows.{{ $i }}.uom" placeholder="PCS / BOX">
                                            <div class="form-text">{{ __('pos.hint_uom_text') }}</div>
                                        </td>
                                        <td>
                                            <input type="date" class="form-control" wire:model="rows.{{ $i }}.expiry_date">
                                            @error("rows.$i.expiry_date") <small class="text-danger">{{ $message }}</small> @enderror
                                            <div class="form-text">{{ __('pos.hint_expiry') }}</div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" wire:model.lazy="rows.{{ $i }}.batch_no" placeholder="BATCH-001">
                                            @error("rows.$i.batch_no") <small class="text-danger">{{ $message }}</small> @enderror
                                            <div class="form-text">{{ __('pos.hint_batch') }}</div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" wire:model.lazy="rows.{{ $i }}.reason" placeholder="{{ __('pos.reason_ph') }}">
                                            @error("rows.$i.reason") <small class="text-danger">{{ $message }}</small> @enderror
                                            <div class="form-text">{{ __('pos.hint_reason') }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm" wire:loading.attr="disabled">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                        <i class="mdi mdi-content-save-outline"></i> {{ __('pos.save') }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm"
                            wire:click="$refresh" wire:loading.attr="disabled">
                        <i class="mdi mdi-refresh"></i> {{ __('pos.refresh') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SweetAlert2 حذف صف --}}
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


</div>
