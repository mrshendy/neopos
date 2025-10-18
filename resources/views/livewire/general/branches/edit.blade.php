<div>
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-pencil-outline me-1"></i> تعديل فرع
        </div>
        <div class="card-body p-4">
            <form wire:submit.prevent="update" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">اسم الفرع (عربي)</label>
                    <input type="text" class="form-control" wire:model.defer="name_ar">
                    @error('name_ar') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Branch Name (English)</label>
                    <input type="text" class="form-control" wire:model.defer="name_en">
                    @error('name_en') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-bold">العنوان</label>
                    <input type="text" class="form-control" wire:model.defer="address">
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

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save-outline"></i> حفظ التغييرات
                    </button>
                    <a href="{{ route('branches.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
