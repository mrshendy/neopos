<div class="page-wrap">

    {{-- Alerts --}}
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
                <i class="mdi mdi-swap-horizontal me-2"></i> {{ __('pos.inventory_transactions_title') ?? 'حركات المخازن' }}
            </h3>
            <div class="text-muted small">{{ __('pos.inventory_transactions_sub') ?? 'إدارة وفلترة الحركات' }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('inventory.transactions.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-plus-circle-outline"></i> {{ __('pos.btn_new') ?? 'جديد' }}
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm rounded-4 mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-magnify"></i> بحث</label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search" placeholder="رقم الحركة/ملاحظات">
                </div>
                <div class="col-lg-2">
                    <label class="form-label mb-1">النوع</label>
                    <select class="form-select" wire:model="type">
                        <option value="">الكل</option>
                        @foreach($types as $key=>$txt)
                            <option value="{{ $key }}">{{ $txt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <label class="form-label mb-1">المخزن</label>
                    <select class="form-select" wire:model="warehouse_id">
                        <option value="">الكل</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label mb-1">من تاريخ</label>
                    <input type="date" class="form-control" wire:model="date_from">
                </div>
                <div class="col-lg-2">
                    <label class="form-label mb-1">إلى تاريخ</label>
                    <input type="date" class="form-control" wire:model="date_to">
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-outline-secondary rounded-pill px-3 shadow-sm" wire:click="clearFilters">
                        <i class="mdi mdi-broom"></i> إعادة تعيين
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>رقم الحركة</th>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>من/إلى</th>
                        <th>الملاحظات</th>
                        <th>الحالة</th>
                        <th class="text-end">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $i=>$row)
                        <tr>
                            <td>{{ $rows->firstItem() + $i }}</td>
                            <td class="fw-bold">{{ $row->trx_no }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->trx_date)->format('Y-m-d H:i') }}</td>
                            <td>
                                @php
                                    $labels = [
                                        'sales_issue'=>'صرف مبيعات','sales_return'=>'مرتجع مبيعات',
                                        'purchase_receive'=>'استلام مشتريات','transfer'=>'تحويل','adjustment'=>'تسوية'
                                    ];
                                @endphp
                                <span class="badge bg-light text-dark border">{{ $labels[$row->type] ?? $row->type }}</span>
                            </td>
                            <td>
                                <div class="small text-muted">
                                    من: {{ optional($row->warehouseFrom)->name ?? '-' }} /
                                    إلى: {{ optional($row->warehouseTo)->name ?? '-' }}
                                </div>
                            </td>
                            <td class="text-truncate" style="max-width:220px">{{ $row->notes }}</td>
                            <td>
                                <span class="badge {{ $row->status === 'draft' ? 'bg-warning' : 'bg-success' }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('inventory.transactions.edit', $row->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                    <i class="mdi mdi-pencil-outline"></i> تعديل
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">لا توجد بيانات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex align-items-center justify-content-between">
            <div class="small text-muted">عرض {{ $rows->firstItem() }}–{{ $rows->lastItem() }} من {{ $rows->total() }}</div>
            {{ $rows->links() }}
        </div>
    </div>
</div>
