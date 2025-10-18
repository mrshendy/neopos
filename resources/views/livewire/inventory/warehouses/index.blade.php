<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .help{font-size:.85rem;color:#6c757d}
        .badge-soft{background:#f5f7fb;border:1px solid #e9eef5;color:#5b6b79}
    </style>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="mdi mdi-warehouse me-2"></i> {{ __('pos.warehouses_title') }}</h4>
        @if (\Illuminate\Support\Facades\Route::has('inventory.warehouses.create'))
            <a href="{{ route('inventory.warehouses.create') }}"
               class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-plus"></i> {{ __('pos.btn_new_warehouse') }}
            </a>
        @endif
    </div>

    <div class="card shadow-sm rounded-4 stylish-card mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-magnify"></i> {{ __('pos.search') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="q"
                           placeholder="{{ __('pos.ph_search_wh') }}">
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-home-city-outline"></i> {{ __('pos.branch') }}</label>
                    <select class="form-select" wire:model="branch_id">
                        <option value="">{{ __('pos.all_branches') }}</option>
                        @foreach($branches as $b)
                            @php
                                $bName = is_array($b->name ?? null) ? ($b->name['ar'] ?? $b->name['en'] ?? '') : ($b->name ?? '');
                            @endphp
                            <option value="{{ $b->id }}">{{ $bName ?: ('#'.$b->id) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1"><i class="mdi mdi-office-building-outline"></i> {{ __('pos.warehouse_type') }}</label>
                    <select class="form-select" wire:model="warehouse_type">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="main">{{ __('pos.main') }}</option>
                        <option value="sub">{{ __('pos.sub') }}</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1"><i class="mdi mdi-toggle-switch"></i> {{ __('pos.status') }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="active">{{ __('pos.active') }}</option>
                        <option value="inactive">{{ __('pos.inactive') }}</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1"><i class="mdi mdi-format-list-numbered"></i> {{ __('pos.per_page') ?? 'Per Page' }}</label>
                    <select class="form-select" wire:model="perPage">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button type="button" wire:click="clearFilters" class="btn btn-light rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-refresh"></i> {{ __('pos.btn_reset') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('pos.code') }}</th>
                        <th>{{ __('pos.name') }}</th>
                        <th>{{ __('pos.branch') }}</th>
                        <th>{{ __('pos.warehouse_type_short') }}</th>
                        <th>{{ __('pos.status') }}</th>
                        <th>{{ __('pos.managers_count') }}</th>
                        <th class="text-end">{{ __('pos.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouses as $i => $w)
                        @php
                            $wName = is_array($w->name ?? null) ? ($w->name['ar'] ?? $w->name['en'] ?? '') : ($w->name ?? '');
                            $branchName = optional($w->branch)->name;
                            $branchName = is_array($branchName) ? ($branchName['ar'] ?? $branchName['en'] ?? '') : ($branchName ?? '');
                            $managersCount = method_exists($w, 'managers')
                                ? ($w->managers->count() ?? 0)
                                : (is_array($w->manager_ids ?? null) ? count($w->manager_ids) : 0);
                        @endphp
                        <tr>
                            <td>{{ ($warehouses->firstItem() ?? 1) + $i }}</td>
                            <td><span class="fw-bold">{{ $w->code }}</span></td>
                            <td>{{ $wName }}</td>
                            <td>{{ $branchName ?: '—' }}</td>
                            <td>
                                @if($w->warehouse_type === 'main')
                                    <span class="badge badge-soft">{{ __('pos.main') }}</span>
                                @elseif($w->warehouse_type === 'sub')
                                    <span class="badge badge-soft">{{ __('pos.sub') }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($w->status === 'active')
                                    <span class="badge bg-success-subtle text-success">{{ __('pos.active') }}</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">{{ __('pos.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($managersCount)
                                    <i class="mdi mdi-account-multiple-outline"></i> {{ $managersCount }}
                                @else
                                    <span class="text-muted">{{ __('pos.none') }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if (\Illuminate\Support\Facades\Route::has('inventory.warehouses.edit'))
                                    <a href="{{ route('inventory.warehouses.edit', $w->id) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="mdi mdi-pencil-outline"></i> {{ __('pos.btn_edit') }}
                                    </a>
                                @endif

                                {{-- زر الحذف: يستدعي SweetAlert --}}
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                        onclick="confirmDelete({{ $w->id }})">
                                    <i class="mdi mdi-delete-outline"></i> {{ __('pos.btn_delete') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">{{ __('pos.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    {{ $warehouses->total() }} {{ __('pos.warehouses_title') }}
                </div>
                <div>
                    {{ $warehouses->onEachSide(1)->links() }}
                </div>
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
                    // أرسل أيفنت إلى الكومبوننت
                    Livewire.emit('deleteConfirmed', id);
                    Swal.fire('تم الحذف!', '✅ تم الحذف  بنجاح.', 'success');
                }
            })
        }
    </script>
</div>
