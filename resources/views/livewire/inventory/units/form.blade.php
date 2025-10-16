<div class="units-form-pro" x-data>
    {{-- ✨ Flash --}}
    @foreach (['success' => 'check-circle-outline', 'error' => 'alert-circle-outline'] as $type => $icon)
        @if (session()->has($type))
            <div class="alert alert-{{ $type == 'success' ? 'success' : 'danger' }} alert-dismissible fade show shadow-sm mb-3">
                <i class="mdi mdi-{{ $icon }} me-2"></i>{{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    {{-- 🧭 Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="mb-1 fw-bold">
                <i class="mdi mdi-scale me-2"></i>
                {{ $unitId ? 'تعديل وحدة' : 'إضافة وحدة' }}
            </h3>
            <div class="text-muted small">إدارة وحدات القياس (كبرى/صغرى) مع التحويل الافتراضي والاختصار والحالة.</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('units.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="mdi mdi-arrow-left"></i> رجوع
            </a>
            <button form="unitForm"
                    type="submit"
                    class="btn btn-success rounded-pill px-4"
                    wire:loading.attr="disabled">
                <span wire:loading.remove><i class="mdi mdi-content-save-outline me-1"></i> حفظ</span>
                <span wire:loading class="d-inline-flex align-items-center">
                    <span class="spinner-border spinner-border-sm me-2"></span> جارِ الحفظ...
                </span>
            </button>
        </div>
    </div>

    {{-- 🚨 Error summary --}}
    @if ($errors->any())
        <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-3">
            <div class="d-flex">
                <i class="mdi mdi-alert-outline fs-4 me-2"></i>
                <div>
                    <div class="fw-600 mb-1">يوجد بعض الحقول تحتاج مراجعة:</div>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- 📝 Form --}}
    <form id="unitForm" wire:submit.prevent="save">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="row g-0">
                    {{-- العمود الأيسر: الحقول الأساسية --}}
                    <div class="col-12 col-lg-8 p-4">
                        <div class="row g-3">

                            {{-- Code --}}
                            <div class="col-md-4">
                                <label class="form-label">الكود <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('code') is-invalid @enderror"
                                       wire:model.defer="code"
                                       placeholder="مثال: KG / BOX / PCS"
                                       autocomplete="off">
                                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">كود فريد للوحدة.</small>
                            </div>

                            {{-- Kind (Toggle) --}}
                            <div class="col-md-4">
                                <label class="form-label d-block">النوع <span class="text-danger">*</span></label>
                                <div class="btn-group w-100" role="group" aria-label="kind">
                                    <input type="radio" class="btn-check" id="kindMinor" value="minor" wire:model="kind">
                                    <label class="btn btn-outline-primary" for="kindMinor">
                                        <i class="mdi mdi-arrow-collapse-down me-1"></i> صغرى
                                    </label>

                                    <input type="radio" class="btn-check" id="kindMajor" value="major" wire:model="kind">
                                    <label class="btn btn-outline-primary" for="kindMajor">
                                        <i class="mdi mdi-arrow-expand-up me-1"></i> كبرى
                                    </label>
                                </div>
                                @error('kind')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            {{-- Status --}}
                            <div class="col-md-4">
                                <label class="form-label">الحالة <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                                    <option value="active">نشط</option>
                                    <option value="inactive">موقوف</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Name AR / EN --}}
                            <div class="col-md-6">
                                <label class="form-label">الاسم (ع) <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('name.ar') is-invalid @enderror"
                                       wire:model.defer="name.ar"
                                       placeholder="كيلوجرام">
                                @error('name.ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الاسم (En) <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('name.en') is-invalid @enderror"
                                       wire:model.defer="name.en"
                                       placeholder="Kilogram">
                                @error('name.en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Abbreviation --}}
                            <div class="col-md-4">
                                <label class="form-label">الاختصار</label>
                                <input type="text"
                                       class="form-control @error('abbreviation') is-invalid @enderror"
                                       wire:model.defer="abbreviation"
                                       placeholder="مثل: KG / PCS">
                                @error('abbreviation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">اختياري للعرض في القوائم والفواتير.</small>
                            </div>

                            {{-- Minor-only --}}
                            @if($kind === 'minor')
                                <div class="col-md-4">
                                    <label class="form-label">الوحدة الكبرى <span class="text-danger">*</span></label>
                                    <select class="form-select @error('parent_id') is-invalid @enderror"
                                            wire:model="parent_id">
                                        <option value="">— اختر —</option>
                                        @foreach($majors as $m)
                                            <option value="{{ $m->id }}">{{ $m->code }} — {{ $m->getTranslation('name','ar') }}</option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">النسبة إلى الكبرى <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number"
                                               step="0.000001" min="0.000001"
                                               class="form-control @error('ratio_to_parent') is-invalid @enderror"
                                               wire:model.defer="ratio_to_parent"
                                               placeholder="مثال: 12">
                                        <span class="input-group-text">×</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        1 كبرى = <b class="text-primary">{{ $ratio_to_parent ?: '—' }}</b> صغرى
                                    </small>
                                    @error('ratio_to_parent')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" id="isDefaultMinor" wire:model="is_default_minor">
                                        <label for="isDefaultMinor" class="form-check-label">
                                            اجعلها الوحدة الصغرى الافتراضية لهذه الكبرى
                                        </label>
                                    </div>
                                    @error('is_default_minor')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- العمود الأيمن: بطاقة مساعدة/معاينة --}}
                    <div class="col-12 col-lg-4 p-4 border-start bg-light-subtle">
                        <div class="sticky-aside">
                            <div class="card border-0 shadow-sm rounded-4 mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="mdi mdi-information-outline fs-4 me-2 text-primary"></i>
                                        <div class="fw-600">نصائح سريعة</div>
                                    </div>
                                    <ul class="small text-muted mb-0">
                                        <li>الوحدة <b>الكبرى</b> لا تحتاج أبًا أو نسبة.</li>
                                        <li>الوحدة <b>الصغرى</b> يجب ربطها بكبرى مع تحديد النسبة.</li>
                                        <li>لا يمكن اختيار أكثر من صغرى افتراضية لكل كبرى (نحرص على ذلك تلقائيًا).</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-body">
                                    <div class="fw-600 mb-2"><i class="mdi mdi-eye-outline me-1"></i> معاينة سريعة</div>
                                    <div class="small">
                                        <div class="mb-1">
                                            <span class="text-muted">الحالة:</span>
                                            <span class="badge {{ $status==='active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                                {{ $status==='active' ? 'نشط' : 'موقوف' }}
                                            </span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="text-muted">النوع:</span>
                                            <span class="badge bg-primary-subtle text-primary">{{ $kind==='major'?'كبرى':'صغرى' }}</span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="text-muted">الكود:</span> <span class="font-monospace">{{ $code ?: '—' }}</span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="text-muted">الاسم:</span> {{ $name['ar'] ?: '—' }} <span class="text-muted">/ {{ $name['en'] ?: '—' }}</span>
                                        </div>
                                        @if($kind==='minor')
                                            <div class="mb-1">
                                                <span class="text-muted">الكبرى:</span>
                                                {{ optional(collect($majors)->firstWhere('id',$parent_id))->getTranslation('name','ar') ?: '—' }}
                                            </div>
                                            <div class="mb-1">
                                                <span class="text-muted">النسبة:</span>
                                                {{ $ratio_to_parent ?: '—' }}
                                            </div>
                                            <div class="mb-1">
                                                <span class="text-muted">افتراضية:</span>
                                                {!! $is_default_minor ? '<span class="badge bg-success-subtle text-success">نعم</span>' : '<span class="badge bg-secondary-subtle text-secondary">لا</span>' !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div> {{-- sticky --}}
                    </div>
                </div>
            </div>

            {{-- ✅ Footer (actions) --}}
            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    اختصار: <kbd>Ctrl</kbd> + <kbd>S</kbd> للحفظ
                </small>
                <div class="d-flex gap-2">
                    <a href="{{ route('units.index') }}" class="btn btn-light rounded-pill px-4">إلغاء</a>
                    <button type="submit" class="btn btn-success rounded-pill px-4" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="mdi mdi-content-save-outline me-1"></i> حفظ</span>
                        <span wire:loading class="d-inline-flex align-items-center">
                            <span class="spinner-border spinner-border-sm me-2"></span> جارِ الحفظ...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- 🎨 Styles --}}
<style>
    .units-form-pro .fw-600{font-weight:600;}
    .units-form-pro .sticky-aside{position: sticky; top: 1rem;}
    /* تحسينات عامة */
    .units-form-pro .card{border:1px solid rgba(0,0,0,.05);}
    .units-form-pro .card:hover{transition:.2s; }
</style>

{{-- ⌨️ Ctrl+S to submit --}}
<script>
document.addEventListener('keydown', function(e){
    if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.keyCode === 83)) {
        e.preventDefault();
        const form = document.getElementById('unitForm');
        if(form){ form.dispatchEvent(new Event('submit', {bubbles:true, cancelable:true})); }
    }
});
</script>
