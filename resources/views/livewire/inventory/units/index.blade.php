<div class="units-index">

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
                <i class="mdi mdi-scale-balance me-2"></i> إدارة الوحدات
            </h3>
            <div class="text-muted small">الوحدات الكبرى مع الوحدات الصغرى المرتبطة بها.</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('units.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-plus-circle-outline me-1"></i> إضافة وحدة
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-magnify"></i> بحث
                    </label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search" placeholder="كود/اسم...">
                    <small class="text-muted">ابحث بالكود أو الاسم (ع/En).</small>
                </div>
                <div class="col-lg-2">
                    <button class="btn btn-light rounded-pill px-3 mt-4"
                            type="button"
                            wire:click="$set('search','')">
                        <i class="mdi mdi-filter-remove-outline"></i> مسح البحث
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Majors List --}}
    @forelse($majors as $major)
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body">

                {{-- Major header --}}
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold fs-6">
                            {{ $major->code }}
                            — {{ $major->getTranslation('name','ar') }}
                            <span class="text-muted">/ {{ $major->getTranslation('name','en') }}</span>
                        </div>
                        <div class="text-muted small mt-1">
                            <span class="badge bg-secondary-subtle text-secondary">وحدة كبرى</span>
                            @if($major->status === 'active')
                                <span class="badge bg-primary-subtle text-primary ms-1">نشطة</span>
                            @else
                                <span class="badge bg-warning text-dark ms-1">موقوفة</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('units.edit',$major->id) }}"
                           class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                            <i class="mdi mdi-pencil-outline"></i> تعديل
                        </a>
                        <button class="btn btn-outline-danger rounded-pill px-3 shadow-sm"
                                type="button"
                                onclick="if(confirm('تأكيد الحذف؟')) Livewire.emit('deleteConfirmed', {{ $major->id }})">
                            <i class="mdi mdi-delete-outline"></i> حذف
                        </button>
                    </div>
                </div>

                {{-- Minors table --}}
                <div class="mt-3">
                    @if($major->minors->count())
                        <div class="table-responsive">
                            <table class="table table-sm align-middle table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:110px">الكود</th>
                                        <th>الاسم (ع)</th>
                                        <th>الاسم (En)</th>
                                        <th style="width:140px">الاختصار</th>
                                        <th style="width:120px">النسبة</th>
                                        <th style="width:110px">افتراضي</th>
                                        <th style="width:120px">الحالة</th>
                                        <th class="text-end" style="width:170px">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($major->minors as $m)
                                    <tr>
                                        <td class="font-monospace">{{ $m->code }}</td>
                                        <td class="text-truncate" title="{{ $m->getTranslation('name','ar') }}">
                                            {{ $m->getTranslation('name','ar') }}
                                        </td>
                                        <td class="text-truncate" title="{{ $m->getTranslation('name','en') }}">
                                            {{ $m->getTranslation('name','en') }}
                                        </td>
                                        <td>{{ $m->abbreviation ?: '—' }}</td>
                                        <td>{{ rtrim(rtrim(number_format($m->ratio_to_parent,6,'.',''), '0'),'.') }}</td>
                                        <td>
                                            @if($m->is_default_minor)
                                                <span class="badge bg-success">افتراضي</span>
                                            @else
                                                <span class="badge bg-secondary">لا</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $m->status==='active'?'bg-primary-subtle text-primary':'bg-warning text-dark' }}">
                                                {{ $m->status==='active'?'نشط':'موقوف' }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('units.edit',$m->id) }}"
                                               class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                                                <i class="mdi mdi-pencil-outline"></i> تعديل
                                            </a>
                                            <button class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm"
                                                    type="button"
                                                    onclick="if(confirm('تأكيد الحذف؟')) Livewire.emit('deleteConfirmed', {{ $m->id }})">
                                                <i class="mdi mdi-delete-outline"></i> حذف
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-muted small">لا توجد وحدات صغرى مرتبطة.</div>
                    @endif
                </div>

            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center text-muted py-5">
                <i class="mdi mdi-scale-off fs-1 d-block mb-2"></i>
                لا توجد وحدات حتى الآن.
            </div>
        </div>
    @endforelse

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $majors->links() }}
    </div>

</div>

{{-- Local styles --}}
<style>
    .units-index .table td, .units-index .table th { vertical-align: middle; }
</style>
