<div class="page-wrap">

    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .table thead th{background:#f8f9fc; white-space:nowrap}
        .table td,.table th{vertical-align:middle}
        .w-110{width:110px}.w-140{width:140px}.w-180{width:180px}.w-220{width:220px}
    </style>

    <div class="card shadow-sm rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-database-plus-outline me-1"></i> {{ __('inventory.ds_title') }}
        </div>
        <div class="card-body">
            <form wire:submit.prevent="save" class="row g-3">
                {{-- الصف الأول --}}
                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-calendar"></i> {{ __('inventory.ds_movement_date') }}</label>
                    <input type="date" class="form-control" wire:model="movement_date">
                    @error('movement_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-store-outline"></i> {{ __('inventory.ds_warehouse') }}</label>
                    <select class="form-select" wire:model="warehouse_id">
                        <option value="">{{ __('inventory.choose') }}</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                        @endforeach
                    </select>
                    @error('warehouse_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-truck-outline"></i> {{ __('inventory.ds_supplier') }}</label>
                    <select class="form-select" wire:model="supplier_id">
                        <option value="">{{ __('inventory.optional') }}</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- الصف الثاني --}}
                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-file-document-edit-outline"></i> {{ __('inventory.ds_invoice_no') }}</label>
                    <input type="text" class="form-control" wire:model="invoice_no" placeholder="{{ __('inventory.ph_invoice') }}">
                    @error('invoice_no') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-pound"></i> {{ __('inventory.ds_manual_ref') }}</label>
                    <input type="text" class="form-control" wire:model="manual_ref" placeholder="{{ __('inventory.ph_manual_ref') }}">
                    @error('manual_ref') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label mb-1"><i class="mdi mdi-note-text-outline"></i> {{ __('inventory.ds_notes') }}</label>
                    <textarea class="form-control" rows="2" wire:model="notes" placeholder="{{ __('inventory.ph_notes') }}"></textarea>
                    @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- ===== تفاصيل الأصناف ===== --}}
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold"><i class="mdi mdi-format-list-bulleted"></i> {{ __('inventory.ds_items_title') }}</div>
                        <button type="button" class="btn btn-outline-primary rounded-pill px-3 shadow-sm"
                                wire:click="addRow">
                            <i class="mdi mdi-plus"></i> {{ __('inventory.ds_add_row') }}
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th style="width:36px"></th>
                                    <th class="w-220">{{ __('inventory.ds_product') }}</th>
                                    <th class="w-140">{{ __('inventory.ds_unit') }}</th>
                                    <th class="w-110">{{ __('inventory.ds_qty') }}</th>
                                    <th class="w-180">{{ __('inventory.ds_expiry_date') }}</th>
                                    <th class="w-180">{{ __('inventory.ds_batch_no') }}</th>
                                    <th class="w-140">{{ __('inventory.ds_cost_price') }}</th>
                                    <th class="w-140">{{ __('inventory.ds_sale_price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $i => $row)
                                    <tr>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-light btn-sm" title="{{ __('inventory.ds_remove_row') }}"
                                                    wire:click="removeRow({{ $i }})">
                                                <i class="mdi mdi-trash-can-outline text-danger"></i>
                                            </button>
                                        </td>

                                        <td>
                                            <select class="form-select" wire:model="rows.{{ $i }}.product_id">
                                                <option value="">{{ __('inventory.choose_product') }}</option>
                                                @foreach($products as $p)
                                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                                @endforeach
                                            </select>
                                            @error("rows.$i.product_id") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>

                                        <td>
                                            <select class="form-select" wire:model="rows.{{ $i }}.unit_id">
                                                <option value="">{{ __('inventory.choose_unit') }}</option>
                                                @foreach($units as $u)
                                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
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
                                            <input type="date" class="form-control" wire:model="rows.{{ $i }}.expiry_date">
                                            @error("rows.$i.expiry_date") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>

                                        <td>
                                            <input type="text" class="form-control" wire:model="rows.{{ $i }}.batch_no" placeholder="{{ __('inventory.ph_batch_no') }}">
                                            @error("rows.$i.batch_no") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>

                                        <td>
                                            <input type="number" step="0.0001" class="form-control text-center"
                                                   wire:model.lazy="rows.{{ $i }}.cost_price" placeholder="0.00">
                                            @error("rows.$i.cost_price") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>

                                        <td>
                                            <input type="number" step="0.0001" class="form-control text-center"
                                                   wire:model.lazy="rows.{{ $i }}.sale_price" placeholder="0.00">
                                            @error("rows.$i.sale_price") <small class="text-danger">{{ $message }}</small> @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save-outline"></i> {{ __('inventory.ds_save') }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm"
                            wire:click="resetForm">
                        <i class="mdi mdi-refresh"></i> {{ __('inventory.ds_reset') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
