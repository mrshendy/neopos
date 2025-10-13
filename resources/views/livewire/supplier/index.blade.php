<div class="supplier-index">

    {{-- ===== تحسينات شكلية خفيفة فقط ===== --}}
    <style>
        .k-card{border:1px solid rgba(0,0,0,.06);border-radius:1rem;box-shadow:0 8px 20px rgba(0,0,0,.04)}
        .k-header{gap:.5rem}
        .k-badge{background:#f8f9fa;border:1px solid rgba(0,0,0,.08);color:#495057}
        .table thead th{position:sticky;top:0;background:#f8f9fa;z-index:1}
        .table-hover tbody tr:hover{background:rgba(13,110,253,.03)}
        .td-actions{white-space:nowrap}
        .btn-icon-sm{padding:.25rem .5rem;border-radius:999px}
        .btn-icon-sm i{font-size:16px;line-height:1}
        .cell-muted{color:#6c757d}
        .fw-600{font-weight:600}
    </style>

    {{-- هيدر الصفحة + زر إنشاء --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between k-header mb-3">
        <div class="d-flex align-items-center gap-2">
            <h4 class="mb-0">
                <i class="mdi mdi-truck-outline me-1 text-primary"></i>
                {{ __('pos.supplier_list') }}
            </h4>
            <span class="badge k-badge rounded-pill">
                {{ $suppliers->total() }} {{ __('pos.supplier_title') }}
            </span>
        </div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-plus me-1"></i> {{ __('pos.supplier_create') }}
        </a>
    </div>

    {{-- تنبيهات --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- شريط الفلاتر --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3 k-card">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small mb-1">
                        <i class="mdi mdi-magnify text-primary me-1"></i>{{ __('pos.search_supplier') }}
                    </label>
                    <input type="text" wire:model.debounce.500ms="search" class="form-control" placeholder="{{ __('pos.search_supplier') }}">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small mb-1">
                        <i class="mdi mdi-format-list-bulleted text-primary me-1"></i>{{ __('pos.filter_category') }}
                    </label>
                    <select wire:model="filter_category" class="form-select">
                        <option value="">{{ __('pos.filter_category') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->getTranslation('name', app()->getLocale()) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small mb-1">
                        <i class="mdi mdi-map text-primary me-1"></i>{{ __('pos.filter_governorate') }}
                    </label>
                    <select wire:model="filter_governorate" class="form-select">
                        <option value="">{{ __('pos.filter_governorate') }}</option>
                        @foreach ($governorates as $g)
                            <option value="{{ $g->id }}">{{ $g->name ?? ($g->getTranslation('name','ar') ?? $g->getTranslation('name','en')) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small mb-1">
                        <i class="mdi mdi-home-city-outline text-primary me-1"></i>{{ __('pos.filter_city') }}
                    </label>
                    <select wire:model="filter_city" class="form-select" {{ !$filter_governorate ? 'disabled' : '' }}>
                        <option value="">{{ __('pos.filter_city') }}</option>
                        @foreach ($cities as $c)
                            <option value="{{ $c->id }}">{{ $c->name ?? ($c->getTranslation('name','ar') ?? $c->getTranslation('name','en')) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small mb-1">
                        <i class="mdi mdi-toggle-switch text-primary me-1"></i>{{ __('pos.filter_status') }}
                    </label>
                    <select wire:model="filter_status" class="form-select">
                        <option value="">{{ __('pos.filter_status') }}</option>
                        <option value="active">{{ __('pos.status_active') }}</option>
                        <option value="inactive">{{ __('pos.status_inactive') }}</option>
                    </select>
                </div>
                <div class="col-lg-1 col-md-12 d-grid">
                    <button type="button" class="btn btn-outline-secondary rounded-pill"
                            wire:click="$set('search','');$set('filter_category','');$set('filter_governorate','');$set('filter_city','');$set('filter_status','')">
                        <i class="mdi mdi-broom"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- الجدول --}}
    <div class="card border-0 shadow-lg rounded-4 k-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:70px">#</th>
                            <th>{{ __('pos.supplier_code') }}</th>
                            <th>{{ __('pos.supplier_name') }}</th>
                            <th>{{ __('pos.category') }}</th>
                            <th>{{ __('pos.governorate') }}</th>
                            <th>{{ __('pos.city') }}</th>
                            <th>{{ __('pos.status') }}</th>
                            <th class="text-end td-actions" style="width:220px">{{ __('pos.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $row)
                            <tr>
                                <td class="cell-muted">#{{ $row->id }}</td>
                                <td class="fw-600">{{ $row->code }}</td>
                                <td>
                                    <a href="{{ route('suppliers.show', $row->id) }}" class="text-decoration-none fw-600">
                                        {{ $row->getTranslation('name', app()->getLocale()) }}
                                    </a>
                                </td>
                                <td>{{ optional($row->category)?->getTranslation('name', app()->getLocale()) ?? '-' }}</td>
                                <td>{{ optional($row->governorate)->name ?? '-' }}</td>
                                <td>{{ optional($row->city)->name ?? '-' }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $row->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $row->status == 'active' ? __('pos.status_active') : __('pos.status_inactive') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group" aria-label="actions">
                                        {{-- أزرار صغيرة (أيقونات فقط) --}}
                                        <a href="{{ route('suppliers.show', $row->id) }}"
                                           class="btn btn-outline-secondary btn-icon-sm"
                                           title="{{ __('pos.btn_show') }}">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $row->id) }}"
                                           class="btn btn-primary btn-icon-sm"
                                           title="{{ __('pos.btn_edit') }}">
                                            <i class="mdi mdi-square-edit-outline"></i>
                                        </a>
                                        <button wire:click="toggleStatus({{ $row->id }})"
                                                class="btn btn-success btn-icon-sm"
                                                title="{{ __('pos.btn_toggle') }}">
                                            <i class="mdi mdi-toggle-switch"></i>
                                        </button>
                                        <button onclick="confirmDelete({{ $row->id }})"
                                                class="btn btn-danger btn-icon-sm"
                                                title="{{ __('pos.btn_delete') }}">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="text-center py-5">
                                        <i class="mdi mdi-package-variant-closed text-muted" style="font-size:48px"></i>
                                        <div class="mt-2 text-muted">{{ __('pos.no_data') }}</div>
                                        <div class="mt-3">
                                            <a href="{{ route('suppliers.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                                                <i class="mdi mdi-plus"></i> {{ __('pos.supplier_create') }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center p-3">
                <small class="text-muted">
                    {{ $suppliers->firstItem() }}–{{ $suppliers->lastItem() }} / {{ $suppliers->total() }}
                </small>
                {{ $suppliers->links() }}
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


</div>
