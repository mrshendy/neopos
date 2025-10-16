<div class="page-wrap">
    {{-- العنوان --}}
    <div class="d-flex align-products-center justify-content-between flex-wrap gap-2 mb-3">
        <h4 class="mb-0 d-flex align-products-center gap-2">
            <i class="mdi mdi-view-list-outline"></i>
            {{ __('pos.price_lists_title') ?? 'قوائم الأسعار' }}
        </h4>
        <a href="{{ route('pricing.lists.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-plus"></i> {{ __('pos.btn_new') ?? 'جديد' }}
        </a>
    </div>

    {{-- تنبيهات --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- أنماط --}}
    <style>
        .card-modern{border:1px solid rgba(0,0,0,.06);border-radius:1rem}
        .toolbar .form-label{font-weight:600}
        .table thead th{white-space:nowrap}
        .table td,.table th{vertical-align:middle}
        .thead-sticky thead th{position:sticky;top:0;background:#f8f9fa;z-index:5}
        .chip{display:inline-flex;align-products:center;gap:.35rem;padding:.2rem .6rem;border-radius:999px;border:1px solid rgba(0,0,0,.08);background:#fff;font-size:.8rem;color:#6c757d}
        .badge-status{font-size:.75rem}
        .row-actions .btn{min-width:34px}
        .name-col strong{font-weight:700}
        .soft-muted{color:#6c757d}
        @media (max-width: 768px){
            .hide-sm{display:none!important}
        }
    </style>

    {{-- شريط الأدوات --}}
    <div class="card card-modern mb-3">
        <div class="card-body toolbar">
            <div class="row g-3 align-products-end">
                <div class="col-lg-4">
                    <label class="form-label"><i class="mdi mdi-magnify"></i> {{ __('pos.search') ?? 'بحث' }}</label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search" placeholder="{{ __('pos.ph_search') ?? 'ابحث بالاسم...' }}">
                </div>

                <div class="col-lg-2">
                    <label class="form-label"><i class="mdi mdi-toggle-switch"></i> {{ __('pos.status') ?? 'الحالة' }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('pos.all') ?? 'الكل' }}</option>
                        <option value="active">{{ __('pos.status_active') ?? 'نشط' }}</option>
                        <option value="inactive">{{ __('pos.status_inactive') ?? 'غير نشط' }}</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label"><i class="mdi mdi-calendar-start"></i> {{ __('pos.valid_from') ?? 'من تاريخ' }}</label>
                    <input type="date" class="form-control" wire:model="date_from">
                </div>

                <div class="col-lg-2">
                    <label class="form-label"><i class="mdi mdi-calendar-end"></i> {{ __('pos.valid_to') ?? 'إلى تاريخ' }}</label>
                    <input type="date" class="form-control" wire:model="date_to">
                </div>

                <div class="col-lg-2">
                    <label class="form-label"><i class="mdi mdi-format-list-numbered"></i> {{ __('pos.per_page') ?? 'عدد الصفوف' }}</label>
                    <select class="form-select" wire:model="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                {{-- شرائط حالة سريعة --}}
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 mt-1">
                        @if($search)
                            <span class="chip"><i class="mdi mdi-text-search-variant"></i> {{ $search }}</span>
                        @endif
                        @if($status)
                            <span class="chip">
                                <i class="mdi mdi-filter-outline"></i>
                                {{ $status === 'active' ? (__('pos.status_active') ?? 'نشط') : (__('pos.status_inactive') ?? 'غير نشط') }}
                            </span>
                        @endif
                        @if($date_from)
                            <span class="chip"><i class="mdi mdi-calendar-start"></i> {{ $date_from }}</span>
                        @endif
                        @if($date_to)
                            <span class="chip"><i class="mdi mdi-calendar-end"></i> {{ $date_to }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- الجدول --}}
    <div class="card card-modern">
        <div class="card-body p-0">
            <div class="table-responsive thead-sticky" style="max-height:65vh">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:70px">#</th>
                            <th>{{ __('pos.name') ?? 'الاسم' }}</th>
                            <th class="hide-sm" style="width:150px">{{ __('pos.products_count') ?? 'عدد البنود' }}</th>
                            <th class="hide-sm">{{ __('pos.validity') ?? 'الصلاحية' }}</th>
                            <th style="width:120px">{{ __('pos.status') ?? 'الحالة' }}</th>
                            <th style="width:220px" class="text-end">{{ __('pos.actions') ?? 'الإجراءات' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lists as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td class="name-col">
                                    @php
                                        $displayName = null;
                                        try {
                                            $decoded = json_decode($row->name, true);
                                            if (is_array($decoded)) {
                                                $displayName = $decoded[app()->getLocale()] ?? ($decoded['ar'] ?? ($decoded['en'] ?? null));
                                            }
                                        } catch (\Throwable $e) {}
                                        $displayName = $displayName ?: (string)$row->name;
                                    @endphp
                                    <strong>{{ $displayName }}</strong>
                                    <div class="soft-muted small">
                                        @php
                                            $from = $row->valid_from ? \Illuminate\Support\Carbon::parse($row->valid_from)->format('Y-m-d') : '—';
                                            $to   = $row->valid_to   ? \Illuminate\Support\Carbon::parse($row->valid_to)->format('Y-m-d')   : '—';
                                        @endphp
                                        <i class="mdi mdi-calendar-range-outline"></i> {{ $from }} <span class="mx-1">→</span> {{ $to }}
                                    </div>
                                </td>

                                <td class="hide-sm">
                                    <span class="badge bg-info-subtle text-dark border">{{ $counts[$row->id] ?? 0 }}</span>
                                </td>

                                <td class="hide-sm soft-muted">
                                    @if($row->valid_from || $row->valid_to)
                                        <span class="small">{{ $from }} <span class="mx-1">→</span> {{ $to }}</span>
                                    @else
                                        <span class="small">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($row->status === 'active')
                                        <span class="badge bg-success badge-status d-inline-flex align-products-center gap-1">
                                            <i class="mdi mdi-check-circle-outline"></i>{{ __('pos.status_active') ?? 'نشط' }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary badge-status d-inline-flex align-products-center gap-1">
                                            <i class="mdi mdi-close-circle-outline"></i>{{ __('pos.status_inactive') ?? 'غير نشط' }}
                                        </span>
                                    @endif
                                </td>

                                {{-- العمليات --}}
                                <td class="text-end">
                                    <div class="d-none d-md-inline-flex gap-1 row-actions">
                                        <a href="{{ route('pricing.lists.show', $row->id) }}"
                                           class="btn btn-sm btn-info text-white rounded-pill">
                                            <i class="mdi mdi-eye-outline"></i> {{ __('pos.show') ?? 'عرض' }}
                                        </a>
                                        <a href="{{ route('pricing.lists.edit', $row->id) }}"
                                           class="btn btn-sm btn-light rounded-pill">
                                            <i class="mdi mdi-pencil-outline"></i> {{ __('pos.btn_edit') ?? 'تعديل' }}
                                        </a>
                                        <button type="button" onclick="confirmDelete({{ $row->id }})"
                                                class="btn btn-sm btn-outline-danger rounded-pill">
                                            <i class="mdi mdi-trash-can-outline"></i> {{ __('pos.btn_delete') ?? 'حذف' }}
                                        </button>
                                    </div>

                                    {{-- قائمة منسدلة للموبايل --}}
                                    <div class="dropdown d-inline-block d-md-none">
                                        <button class="btn btn-sm btn-light rounded-pill" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('pricing.lists.show', $row->id) }}">
                                                    <i class="mdi mdi-eye-outline me-1"></i>{{ __('pos.show') ?? 'عرض' }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('pricing.lists.edit', $row->id) }}">
                                                    <i class="mdi mdi-pencil-outline me-1"></i>{{ __('pos.btn_edit') ?? 'تعديل' }}
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="confirmDelete({{ $row->id }})">
                                                    <i class="mdi mdi-trash-can-outline me-1"></i>{{ __('pos.btn_delete') ?? 'حذف' }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <div class="mb-2"><i class="mdi mdi-database-off" style="font-size:30px"></i></div>
                                    {{ __('pos.no_data') ?? 'لا توجد بيانات' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 d-flex align-products-center justify-content-between flex-wrap gap-2">
                <div class="small text-muted">
                    {{ __('pos.showing') ?? 'عرض' }} {{ $lists->firstItem() }}–{{ $lists->lastItem() }}
                    {{ __('pos.of') ?? 'من' }} {{ $lists->total() }}
                </div>
                <div>{{ $lists->onEachSide(1)->links() }}</div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
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
