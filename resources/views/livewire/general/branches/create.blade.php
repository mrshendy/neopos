<div>
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-office-building-outline me-1"></i> إضافة فرع
        </div>
        <div class="card-body p-4">
            <form wire:submit.prevent="save" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">اسم الفرع (عربي)</label>
                    <input type="text" class="form-control" wire:model.defer="name_ar" placeholder="مثال: فرع القاهرة">
                    @error('name_ar') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Branch Name (English)</label>
                    <input type="text" class="form-control" wire:model.defer="name_en" placeholder="e.g., Cairo Branch">
                    @error('name_en') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-bold">العنوان</label>
                    <input type="text" class="form-control" wire:model.defer="address" placeholder="العنوان التفصيلي (اختياري)">
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">الحالة</label>
                    <select class="form-select" wire:model.defer="status">
                        <option value="active">نشط</option>
                        <option value="inactive">متوقف</option>
                    </select>
                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="create-wh" wire:model="create_default_warehouse">
                        <label class="form-check-label" for="create-wh">إنشاء مستودع افتراضي مربوط بالفرع</label>
                    </div>
                </div>

                @if($create_default_warehouse)
                    <div class="col-md-6">
                        <label class="form-label fw-bold">اسم المستودع (عربي)</label>
                        <input type="text" class="form-control" wire:model.defer="warehouse_name_ar">
                        @error('warehouse_name_ar') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Warehouse Name (English)</label>
                        <input type="text" class="form-control" wire:model.defer="warehouse_name_en">
                        @error('warehouse_name_en') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                @endif

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save-outline"></i> حفظ
                    </button>
                    <a href="{{ route('branches.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
