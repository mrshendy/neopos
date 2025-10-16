<div class="page-wrap">

    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="mb-1 fw-bold">
                <i class="mdi mdi-pencil-box-multiple-outline me-2"></i> تعديل حركة مخزنية
            </h3>
            <div class="text-muted small">رقم الحركة سيتم استبقاؤه كما هو</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('inventory.transactions.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                <i class="mdi mdi-arrow-left"></i> رجوع
            </a>
        </div>
    </div>

    <form wire:submit.prevent="save" class="card shadow-sm rounded-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-3">
                    <label class="form-label fw-bold">نوع الحركة</label>
                    <select class="form-select" wire:model="type">
                        @foreach($types as $k=>$txt)
                            <option value="{{ $k }}">{{ $txt }}</option>
                        @endforeach
                    </select>
                    @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-3">
                    <label class="form-label fw-bold">تاريخ الحركة</label>
                    <input type="datetime-local" class="form-control" wire:model="trx_date">
                    @error('trx_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                @if(in_array($type, ['sales_issue','transfer','adjustment']))
                    <div class="col-lg-3">
                        <label class="form-label fw-bold">من مخزن</label>
                        <select class="form-select" wire:model="warehouse_from_id">
                            <option value="">— اختر —</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        @error('warehouse_from_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                @endif

                @if(in_array($type, ['purchase_receive','transfer']))
                    <div class="col-lg-3">
                        <label class="form-label fw-bold">إلى مخزن</label>
                        <select class="form-select" wire:model="warehouse_to_id">
                            <option value="">— اختر —</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        @error('warehouse_to_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                @endif

                <div class="col-12">
                    <label class="form-label fw-bold">ملاحظات</label>
                    <textarea class="form-control" rows="2" wire:model.defer="notes" placeholder="اختياري"></textarea>
                    @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <hr class="my-4">

            {{-- Lines --}}
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="mb-0"><i class="mdi mdi-format-list-bulleted-type me-2"></i> بنود الحركة</h5>
                <button type="button" class="btn btn-outline-primary rounded-pill px-3 shadow-sm" wire:click="addLine">
                    <i class="mdi mdi-plus"></i> إضافة بند
                </button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:28%">الصنف</th>
                            <th style="width:12%">الباتش</th>
                            <th style="width:12%">السيريال</th>
                            <th style="width:12%">الكمية</th>
                            <th style="width:12%">الوحدة</th>
                            <th>السبب/الوصف</th>
                            <th class="text-end">—</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lines as $i => $ln)
                            <tr>
                                <td>
                                    <select class="form-select" wire:model="lines.{{ $i }}.product_id">
                                        <option value="">— اختر الصنف —</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('lines.'.$i.'.product_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </td>
                                <td><input type="text" class="form-control" wire:model.defer="lines.{{ $i }}.batch_id"></td>
                                <td><input type="text" class="form-control" wire:model.defer="lines.{{ $i }}.serial_id"></td>
                                <td>
                                    <input type="number" step="0.000001" class="form-control" wire:model.defer="lines.{{ $i }}.qty">
                                    @error('lines.'.$i.'.qty') <small class="text-danger">{{ $message }}</small> @enderror
                                </td>
                                <td>
                                    <input type="text" class="form-control" wire:model.defer="lines.{{ $i }}.uom">
                                    @error('lines.'.$i.'.uom') <small class="text-danger">{{ $message }}</small> @enderror
                                </td>
                                <td><input type="text" class="form-control" wire:model.defer="lines.{{ $i }}.reason" placeholder="اختياري"></td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm"
                                            wire:click="removeLine({{ $i }})">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        @if(empty($lines))
                            <tr><td colspan="7" class="text-center text-muted">لا توجد بنود</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('inventory.transactions.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">إلغاء</a>
            <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-content-save-outline"></i> حفظ التعديلات
            </button>
        </div>
    </form>
</div>
