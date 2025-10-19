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
        .table-hover tbody tr:hover{background:#fbfbfd}
        .code-chip{font-weight:700;background:#f6f8fb;border:1px solid #e9eef5;padding:.25rem .5rem;border-radius:8px}
        .status-dot{display:inline-block;width:.5rem;height:.5rem;border-radius:999px;margin-inline-end:.4rem}
        .dot-green{background:#22c55e}.dot-gray{background:#9ca3af}

        /* إجراءات 3 أزرار جنب بعض */
        .actions-stack{
            display:inline-flex;align-items:center;gap:.4rem;flex-wrap:nowrap;
        }
        .action-btn{
            border-radius:999px; display:inline-flex; align-items:center; gap:.35rem;
            padding:.35rem .75rem; box-shadow:0 1px 2px rgba(16,24,40,.04);
        }
        .action-btn i{font-size:1rem}
        /* على الشاشات الصغيرة أخفي النص وأبقي الأيقونة */
        @media (max-width: 768px){
            .action-text{display:none}
            .action-btn{padding:.35rem .5rem}
        }
    </style>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="mdi mdi-warehouse me-2"></i> {{ __('pos.warehouses_title') }}</h4>
        @if (\Illuminate\Support\Facades\Route::has('inventory.warehouses.create'))
            <a href="{{ route('inventory.warehouses.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-plus"></i> {{ __('pos.btn_new_warehouse') }}
            </a>
        @endif
    </div>

    {{-- فلاتر --}}
    <div class="card shadow-sm rounded-4 stylish-card mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-magnify"></i> {{ __('pos.search') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="q" placeholder="{{ __('pos.ph_search_wh') }}">
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-home-city-outline"></i> {{ __('pos.branch') }}</label>
                    <select class="form-select" wire:model="branch_id">
                        <option value="">{{ __('pos.all_branches') }}</option>
                        @foreach ($branches as $b)
                            @php
                                $bName = is_array($b->name ?? null)
                                    ? ($b->name['ar'] ?? ($b->name['en'] ?? ''))
                                    : ($b->name ?? '');
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

    {{-- الجدول --}}
    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px">#</th>
                        <th>{{ __('pos.code') }}</th>
                        <th>{{ __('pos.name') }}</th>
                        <th>{{ __('pos.branch') }}</th>
                        <th>{{ __('pos.warehouse_type_short') }}</th>
                        <th>{{ __('pos.status') }}</th>
                        <th>{{ __('pos.managers_count') }}</th>
                        <th class="text-end" style="width:220px">{{ __('pos.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouses as $i => $w)
                        @php
                            $wName = is_array($w->name ?? null) ? ($w->name['ar'] ?? ($w->name['en'] ?? '')) : ($w->name ?? '');
                            $branchName = optional($w->branch)->name;
                            $branchName = is_array($branchName) ? ($branchName['ar'] ?? ($branchName['en'] ?? '')) : ($branchName ?? '');
                            $managersCount = method_exists($w, 'managers') ? ($w->managers->count() ?? 0) : (is_array($w->manager_ids ?? null) ? count($w->manager_ids) : 0);
                        @endphp
                        <tr>
                            <td class="text-muted">{{ ($warehouses->firstItem() ?? 1) + $i }}</td>
                            <td><span class="code-chip">{{ $w->code }}</span></td>
                            <td class="fw-medium">{{ $wName }}</td>
                            <td>{{ $branchName ?: '—' }}</td>
                            <td>
                                @if ($w->warehouse_type === 'main')
                                    <span class="badge badge-soft"><i class="mdi mdi-source-branch me-1"></i>{{ __('pos.main') }}</span>
                                @elseif($w->warehouse_type === 'sub')
                                    <span class="badge badge-soft"><i class="mdi mdi-source-branch-check me-1"></i>{{ __('pos.sub') }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($w->status === 'active')
                                    <span><span class="status-dot dot-green"></span>{{ __('pos.active') }}</span>
                                @else
                                    <span><span class="status-dot dot-gray"></span>{{ __('pos.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($managersCount)
                                    <span class="text-body"><i class="mdi mdi-account-multiple-outline"></i> {{ $managersCount }}</span>
                                @else
                                    <span class="text-muted">{{ __('pos.none') }}</span>
                                @endif
                            </td>

                            {{-- الإجراءات: 3 أزرار جنب بعض --}}
                            <td class="text-end">
                                <div class="actions-stack">
                                    {{-- عرض --}}
                                    @if (Route::has('inventory.warehouses.show'))
                                        <a href="{{ route('inventory.warehouses.show', $w->id) }}"
                                           class="btn btn-outline-info btn-sm action-btn"
                                           data-bs-toggle="tooltip" data-bs-title="{{ __('pos.btn_show') ?? 'عرض' }}">
                                            <i class="mdi mdi-eye-outline"></i>
                                            <span class="action-text">{{ __('pos.btn_show') ?? 'عرض' }}</span>
                                        </a>
                                    @endif

                                    {{-- تعديل --}}
                                    @if (Route::has('inventory.warehouses.edit'))
                                        <a href="{{ route('inventory.warehouses.edit', $w->id) }}"
                                           class="btn btn-outline-primary btn-sm action-btn"
                                           data-bs-toggle="tooltip" data-bs-title="{{ __('pos.btn_edit') }}">
                                            <i class="mdi mdi-pencil-outline"></i>
                                            <span class="action-text">{{ __('pos.btn_edit') }}</span>
                                        </a>
                                    @endif

                                    {{-- حذف --}}
                                    <button type="button"
                                            class="btn btn-outline-danger btn-sm action-btn"
                                            onclick="confirmDelete({{ $w->id }})"
                                            data-bs-toggle="tooltip" data-bs-title="{{ __('pos.btn_delete') }}">
                                        <i class="mdi mdi-delete-outline"></i>
                                        <span class="action-text">{{ __('pos.btn_delete') }}</span>
                                    </button>
                                </div>
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

    {{-- ✅ SweetAlert2 + تفعيل Tooltips --}}
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

        // تفعيل التولتيب (Bootstrap) + إعادة تفعيله بعد تحديثات Livewire
        function initTooltips(){
            if (window.bootstrap) {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                    new bootstrap.Tooltip(el);
                });
            }
        }
        document.addEventListener('DOMContentLoaded', initTooltips);
        document.addEventListener('livewire:load', () => {
            if (window.Livewire) {
                Livewire.hook('message.processed', initTooltips);
            }
        });
    </script>
</div>
