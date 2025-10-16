<div class="units-index page-wrap">

    {{-- ✅ Flash Messages --}}
    @foreach (['success' => 'check-circle-outline', 'error' => 'alert-circle-outline'] as $type => $icon)
        @if (session()->has($type))
            <div class="alert alert-{{ $type == 'success' ? 'success' : 'danger' }} alert-dismissible fade show shadow-sm mb-3">
                <i class="mdi mdi-{{ $icon }} me-2"></i>{{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    {{-- 🔹 Header --}}
    <div class="d-flex align-products-center justify-content-between mb-3">
        <div>
            <h3 class="mb-1 fw-bold">
                <i class="mdi mdi-scale-balance me-2"></i> {{ __('inventory.units_management_title') ?? 'إدارة الوحدات' }}
            </h3>
            <div class="text-muted small">{{ __('inventory.units_management_sub') ?? 'الوحدات الكبرى مع الوحدات الصغرى المرتبطة بها.' }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('units.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-plus-circle-outline me-1"></i> {{ __('inventory.add_unit') ?? 'إضافة وحدة' }}
            </a>
        </div>
    </div>

    {{-- 🔍 Filters --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="row g-3 align-products-end">
                <div class="col-lg-4">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-magnify"></i> {{ __('inventory.search') ?? 'بحث' }}
                    </label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search"
                           placeholder="{{ __('inventory.search_placeholder') ?? 'كود أو اسم...' }}">
                    <small class="text-muted">{{ __('inventory.search_hint') ?? 'ابحث بالكود أو الاسم (ع/En).' }}</small>
                </div>
                <div class="col-lg-2">
                    <button class="btn btn-light rounded-pill px-3 mt-4"
                            type="button"
                            wire:click="$set('search','')">
                        <i class="mdi mdi-filter-remove-outline"></i> {{ __('inventory.clear_search') ?? 'مسح البحث' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 📦 Majors with Minors --}}
    @forelse($majors as $major)
        <div class="card border-0 shadow-sm rounded-4 stylish-card mb-4">
            <div class="card-body">

                {{-- Major Header --}}
                <div class="d-flex justify-content-between align-products-center border-bottom pb-2 mb-3">
                    <div>
                        <div class="fw-bold fs-6">
                            <span class="text-primary">{{ $major->code }}</span>
                            — {{ $major->getTranslation('name','ar') }}
                            <span class="text-muted">/ {{ $major->getTranslation('name','en') }}</span>
                        </div>
                        <div class="text-muted small mt-1">
                            <span class="badge bg-secondary-subtle text-secondary">وحدة كبرى</span>
                            @if($major->status === 'active')
                                <span class="badge bg-success-subtle text-success ms-1">نشطة</span>
                            @else
                                <span class="badge bg-warning-subtle text-dark ms-1">موقوفة</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('units.edit',$major->id) }}"
                           class="btn btn-outline-primary rounded-pill px-3 shadow-sm">
                            <i class="mdi mdi-pencil-outline"></i> تعديل
                        </a>
                        <button class="btn btn-outline-danger rounded-pill px-3 shadow-sm"
                                type="button"
                                onclick="confirmDelete({{ $major->id }})">
                            <i class="mdi mdi-delete-outline"></i> حذف
                        </button>
                    </div>
                </div>

                {{-- Minor Table --}}
                @if($major->minors->count())
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>الكود</th>
                                    <th>الاسم (ع)</th>
                                    <th>الاسم (En)</th>
                                    <th>الاختصار</th>
                                    <th>النسبة</th>
                                    <th>افتراضي</th>
                                    <th>الحالة</th>
                                    <th class="text-end">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($major->minors as $m)
                                <tr>
                                    <td class="fw-600 font-monospace">{{ $m->code }}</td>
                                    <td>{{ $m->getTranslation('name','ar') }}</td>
                                    <td>{{ $m->getTranslation('name','en') }}</td>
                                    <td>{{ $m->abbreviation ?: '—' }}</td>
                                    <td>{{ number_format($m->ratio_to_parent, 3) }}</td>
                                    <td>
                                        @if($m->is_default_minor)
                                            <span class="badge bg-success-subtle text-success">افتراضي</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">لا</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $m->status==='active'?'bg-primary-subtle text-primary':'bg-warning-subtle text-dark' }}">
                                            {{ $m->status==='active'?'نشط':'موقوف' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('units.edit',$m->id) }}"
                                           class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
                                            <i class="mdi mdi-pencil-outline"></i> تعديل
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm"
                                                type="button"
                                                onclick="confirmDelete({{ $m->id }})">
                                            <i class="mdi mdi-delete-outline"></i> حذف
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-muted small">لا توجد وحدات صغرى مرتبطة بهذه الكبرى.</div>
                @endif

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

    {{-- 📄 Pagination --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $majors->links() }}
    </div>

</div>

{{-- 🔹 Styles --}}
<style>
    .fw-600 { font-weight: 600; }
    .stylish-card { border: 1px solid rgba(0,0,0,.05); transition:.25s; }
    .stylish-card:hover { transform: translateY(-2px); box-shadow: 0 3px 8px rgba(0,0,0,.07); }
    .units-index .table td, .units-index .table th { vertical-align: middle; white-space: nowrap; }
</style>

{{-- 🔹 Delete Confirmation --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'تأكيد الحذف',
            text: 'هل أنت متأكد من حذف هذه الوحدة؟ لا يمكن التراجع بعد ذلك.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((r) => {
            if (r.isConfirmed) {
                Livewire.emit('deleteConfirmed', id);
            }
        });
    }
</script>
