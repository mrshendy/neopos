<div>
    {{-- Alerts --}}
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

    <style>
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .table thead th{background:#f8f9fc; white-space:nowrap}
        .table td,.table th{vertical-align:middle}
        .badge-status{font-weight:600}
        .badge-status.draft{background:#eef2ff;color:#3730a3;border:1px solid rgba(55,48,163,.15)}
        .badge-status.posted{background:#eff6ff;color:#1e40af;border:1px solid rgba(30,64,175,.15)}
        .badge-status.cancelled{background:#fef2f2;color:#991b1b;border:1px solid rgba(153,27,27,.15)}
    </style>

    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
            <div><i class="mdi mdi-swap-horizontal-bold me-1"></i> حركات المخزون</div>
            <div class="d-flex align-items-center gap-2">
                <div class="text-muted small">لكل صفحة</div>
                <select class="form-select form-select-sm" style="width:auto" wire:model="perPage">
                    <option>10</option><option>20</option><option>30</option><option>50</option>
                </select>
            </div>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">بحث</label>
                    <input type="text" class="form-control" placeholder="ابحث برقم الحركة/النوع/ملاحظات" wire:model.debounce.400ms="search">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">النوع</label>
                    <select class="form-select" wire:model="type">
                        <option value="">— الكل —</option>
                        <option value="in">إدخال</option>
                        <option value="out">صرف</option>
                        <option value="transfer">تحويل</option>
                        <option value="direct_add">إضافة مباشرة</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">الحالة</label>
                    <select class="form-select" wire:model="status">
                        <option value="">— الكل —</option>
                        <option value="draft">مسودة</option>
                        <option value="posted">مرحّلة</option>
                        <option value="cancelled">ملغاة</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">من تاريخ</label>
                    <input type="date" class="form-control" wire:model="date_from">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">إلى تاريخ</label>
                    <input type="date" class="form-control" wire:model="date_to">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">المخزن (مصدر/وجهة)</label>
                    <select class="form-select" wire:model="warehouse_id">
                        <option value="">— الكل —</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width:120px">رقم الحركة</th>
                            <th style="width:120px">تاريخ</th>
                            <th style="width:120px">النوع</th>
                            <th>من مخزن</th>
                            <th>إلى مخزن</th>
                            <th style="width:130px" class="text-center">الحالة</th>
                            <th style="width:130px">المستخدم</th>
                            <th style="width:150px">عمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $r)
                            <tr>
                                <td class="fw-semibold">{{ $r->trx_no }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($r->trx_date)->format('Y-m-d') }}</td>
                                <td>{{ $r->type }}</td>
                                <td>{{ $r->warehouseFrom->name ?? '—' }}</td>
                                <td>{{ $r->warehouseTo->name ?? '—' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-status {{ $r->status }}">{{ $r->status }}</span>
                                </td>
                                <td>{{ $r->user->name ?? '—' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('inv.trx.create') }}?copy={{ $r->id }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="mdi mdi-content-copy"></i>
                                        </a>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="mdi mdi-flag-variant-outline"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus({{ $r->id }}, 'draft')">مسودة</a></li>
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus({{ $r->id }}, 'posted')">مرحّلة</a></li>
                                                <li><a class="dropdown-item text-danger" href="#" wire:click.prevent="changeStatus({{ $r->id }}, 'cancelled')">ملغاة</a></li>
                                            </ul>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $r->id }})">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">لا توجد بيانات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $rows->links() }}
            </div>
        </div>
    </div>

    {{-- SweetAlert2 تأكيد الحذف --}}
    <script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'تحذير',
            text: '⚠️ هل أنت متأكد أنك تريد الحذف؟ لا يمكن التراجع!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#0d6efd',
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('deleteConfirmed', id);
                Swal.fire('تم الحذف!', '✅ تم الحذف بنجاح.', 'success');
            }
        })
    }
    </script>
</div>
