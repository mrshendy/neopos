<div class="units-form">

    {{-- Flash --}}
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
                <i class="mdi mdi-scale me-2"></i>
                {{ $unit ? 'تعديل وحدة' : 'إضافة وحدة' }}
            </h3>
            <div class="text-muted small">إدارة وحدات القياس (كبرى/صغرى) وربط التحويلات الافتراضية.</div>
        </div>
        <a href="{{ route('units.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-arrow-left"></i> رجوع
        </a>
    </div>

    {{-- Card --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <div class="row g-3">

                {{-- Code --}}
                <div class="col-md-3">
                    <label class="form-label">الكود <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model.defer="code" placeholder="مثال: KG / BOX / PCS">
                    @error('code')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                {{-- Kind --}}
                <div class="col-md-3">
                    <label class="form-label d-block">النوع <span class="text-danger">*</span></label>
                    <div class="btn-group" role="group" aria-label="kind">
                        <input type="radio" class="btn-check" id="kindMinor" autocomplete="off" value="minor" wire:model="kind">
                        <label class="btn btn-outline-primary" for="kindMinor">
                            <i class="mdi mdi-arrow-collapse-down me-1"></i> صغرى (افتراضي)
                        </label>

                        <input type="radio" class="btn-check" id="kindMajor" autocomplete="off" value="major" wire:model="kind">
                        <label class="btn btn-outline-primary" for="kindMajor">
                            <i class="mdi mdi-arrow-expand-up me-1"></i> كبرى
                        </label>
                    </div>
                    @error('kind')<small class="text-danger d-block">{{ $message }}</small>@enderror
                </div>

                {{-- Name AR / EN --}}
                <div class="col-md-3">
                    <label class="form-label">الاسم (ع) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model.defer="name.ar" placeholder="كيلوجرام">
                    @error('name.ar')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">الاسم (En) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model.defer="name.en" placeholder="Kilogram">
                    @error('name.en')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                {{-- Abbreviation --}}
                <div class="col-md-3">
                    <label class="form-label">الاختصار</label>
                    <input type="text" class="form-control" wire:model.defer="abbreviation" placeholder="مثل: KG / PCS">
                    @error('abbreviation')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                {{-- Minor-only fields --}}
                @if($kind === 'minor')
                    <div class="col-md-3">
                        <label class="form-label">الوحدة الكبرى <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="parent_id">
                            <option value="">— اختر —</option>
                            @foreach($majors as $m)
                                <option value="{{ $m->id }}">{{ $m->code }} — {{ $m->getTranslation('name','ar') }}</option>
                            @endforeach
                        </select>
                        @error('parent_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">النسبة إلى الكبرى <span class="text-danger">*</span></label>
                        <input type="number" step="0.000001" class="form-control" wire:model.defer="ratio_to_parent" placeholder="مثال: 12">
                        <small class="text-muted">1 من الصغرى = (1 / النسبة) من الكبرى.</small>
                        @error('ratio_to_parent')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-md-3 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input type="checkbox" class="form-check-input" id="isDefaultMinor" wire:model="is_default_minor">
                            <label for="isDefaultMinor" class="form-check-label">الوحدة الصغرى الافتراضية لهذه الكبرى</label>
                        </div>
                        @error('is_default_minor')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                @endif

                {{-- Status --}}
                <div class="col-md-3">
                    <label class="form-label">الحالة <span class="text-danger">*</span></label>
                    <select class="form-select" wire:model="status">
                        <option value="active">نشط</option>
                        <option value="inactive">موقوف</option>
                    </select>
                    @error('status')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('units.index') }}" class="btn btn-light rounded-pill px-4">إلغاء</a>
            <button wire:click="save" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-content-save-outline me-1"></i> حفظ
            </button>
        </div>
    </div>

</div>

{{-- Local styles (خفيفة) --}}
<style>
    .units-form .btn-check:checked + .btn { box-shadow: 0 0 0 .2rem rgba(13,110,253,.15); }
</style>
