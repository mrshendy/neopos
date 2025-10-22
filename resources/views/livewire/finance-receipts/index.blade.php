<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .stylish-card {
            border: 1px solid rgba(0, 0, 0, .06)
        }

        .soft-badge {
            font-size: .75rem
        }

        .summary-cards .card {
            border: 1px solid rgba(0, 0, 0, .06)
        }

        .table td,
        .table th {
            vertical-align: middle
        }
    </style>

    {{-- كروت الإجماليات --}}
    <div class="row g-3 summary-cards mb-3">
        <div class="col-md-4">
            <div class="card rounded-4 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">{{ __('pos.total_cash') ?? 'إجمالي النقدي' }}</div>
                        <div class="fs-4 fw-bold">{{ number_format($total_cash, 2) }}</div>
                    </div>
                    <i class="mdi mdi-cash fs-1 text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card rounded-4 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">{{ __('pos.total_return') ?? 'إجمالي المرتجع' }}</div>
                        <div class="fs-4 fw-bold">{{ number_format($total_return, 2) }}</div>
                    </div>
                    <i class="mdi mdi-undo-variant fs-1 text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card rounded-4 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">{{ __('pos.total_net') ?? 'إجمالي الصافي' }}</div>
                        <div class="fs-4 fw-bold">{{ number_format($total_net, 2) }}</div>
                    </div>
                    <i class="mdi mdi-scale-balance fs-1 text-info"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card rounded-4 shadow-sm stylish-card">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
            <span><i class="mdi mdi-receipt-outline me-1"></i>
                {{ __('pos.receipts_title_index') ?? 'الإيصالات' }}</span>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-dark rounded-pill" wire:click="showCanceledOnly">
                    <i class="mdi mdi-cancel"></i> {{ __('pos.btn_canceled_report') ?? 'تقرير الملغاة' }}
                </button>
                <a href="{{ route('finance.receipts.manage') }}" class="btn btn-success rounded-pill px-3">
                    <i class="mdi mdi-plus"></i> {{ __('pos.btn_new') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <div class="row g-2 mb-3">
                <div class="col-lg-3">
                    <label class="form-label">{{ __('pos.search') ?? 'بحث' }}</label>
                    <input type="text" class="form-control" wire:model.debounce.500ms="q"
                        placeholder="{{ __('pos.search') ?? 'بحث...' }}">
                </div>
                <div class="col-lg-2">
                    <label class="form-label">{{ __('pos.cashbox') ?? 'الخزينة' }}</label>
                    <select class="form-select" wire:model="cashbox_id">
                        <option value="all">{{ __('pos.all') }}</option>
                        @foreach ($cashboxes as $cb)
                            @php $n=$cb->getTranslation('name',app()->getLocale()) ?: $cb->getTranslation('name',app()->getLocale()==='ar'?'en':'ar'); @endphp
                            <option value="{{ $cb->id }}">{{ $n }} ({{ $cb->id }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label">{{ __('pos.status') ?? 'الحالة' }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="all">{{ __('pos.all') }}</option>
                        <option value="active">{{ __('pos.active') ?? 'نشط' }}</option>
                        <option value="canceled">{{ __('pos.canceled') ?? 'ملغي' }}</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label">{{ __('pos.method') ?? 'طريقة الدفع' }}</label>
                    <select class="form-select" wire:model="method">
                        <option value="all">{{ __('pos.all') }}</option>
                        <option value="cash">{{ __('pos.method_cash') ?? 'نقدي' }}</option>
                        <option value="bank">{{ __('pos.method_bank') ?? 'بنكي' }}</option>
                        <option value="pos">{{ __('pos.method_pos') ?? 'نقطة بيع' }}</option>
                        <option value="transfer">{{ __('pos.method_transfer') ?? 'تحويل' }}</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label class="form-label">{{ __('pos.date_range') ?? 'الفترة' }}</label>
                    <div class="d-flex gap-2">
                        <input type="date" class="form-control" wire:model="date_from">
                        <input type="date" class="form-control" wire:model="date_to">
                    </div>
                </div>
                <div class="col-lg-3">
                    <label class="form-label">{{ __('pos.amount_range') ?? 'نطاق المبلغ' }}</label>
                    <div class="d-flex gap-2">
                        <input type="number" step="0.01" class="form-control" wire:model="min_amount"
                            placeholder="min">
                        <input type="number" step="0.01" class="form-control" wire:model="max_amount"
                            placeholder="max">
                    </div>
                </div>
                <div class="col-lg-2">
                    <label class="form-label">{{ __('pos.trashed') ?? 'المحذوف' }}</label>
                    <select class="form-select" wire:model="trashed">
                        <option value="active">{{ __('pos.active_only') ?? 'النشطة فقط' }}</option>
                        <option value="with">{{ __('pos.with_trashed') ?? 'مع المحذوف' }}</option>
                        <option value="only">{{ __('pos.only_trashed') ?? 'المحذوف فقط' }}</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label">{{ __('pos.per_page') ?? 'لكل صفحة' }}</label>
                    <select class="form-select" wire:model="perPage">
                        <option>10</option>
                        <option>15</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                </div>
                <div class="col-lg-1 d-flex align-items-end">
                    <button class="btn btn-outline-secondary w-100" wire:click="clearFilters">
                        <i class="mdi mdi-broom"></i>
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:70px">
                                <a href="#" class="text-decoration-none" wire:click.prevent="setSort('id')">#
                                    @if ($sortField === 'id')
                                        <i class="mdi mdi-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th>{{ __('pos.cashbox') ?? 'الخزينة' }}</th>
                            <th style="width:160px">
                                <a href="#" class="text-decoration-none"
                                    wire:click.prevent="setSort('receipt_date')">
                                    {{ __('pos.date') ?? 'التاريخ' }}
                                    @if ($sortField === 'receipt_date')
                                        <i class="mdi mdi-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th style="width:120px">{{ __('pos.amount') ?? 'المبلغ' }}</th>
                            <th style="width:120px">{{ __('pos.return_amount') ?? 'المرتجع' }}</th>
                            <th style="width:120px">{{ __('pos.method') ?? 'الطريقة' }}</th>
                            <th style="width:120px">{{ __('pos.status') ?? 'الحالة' }}</th>
                            <th style="width:140px">{{ __('pos.doc_no') ?? 'رقم' }}</th>
                            <th class="text-end" style="width:220px">{{ __('pos.col_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            @php
                                $name =
                                    $row->cashbox?->getTranslation('name', app()->getLocale()) ?:
                                    $row->cashbox?->getTranslation('name', app()->getLocale() === 'ar' ? 'en' : 'ar');
                            @endphp
                            <tr @if ($row->trashed()) class="table-danger" @endif>
                                <td>{{ $row->id }}</td>
                                <td>{{ $name ? $name . ' (#' . $row->finance_settings_id . ')' : '—' }}</td>
                                <td>{{ optional($row->receipt_date)->format('Y-m-d H:i') }}</td>
                                <td>{{ number_format($row->amount_total, 2) }}</td>
                                <td>{{ number_format($row->return_amount, 2) }}</td>
                                <td>{{ __('pos.method_' . $row->method) ?? $row->method }}</td>
                                <td>
                                    @php $map=['active'=>'success','canceled'=>'dark']; @endphp
                                    <span class="badge bg-{{ $map[$row->status] }} soft-badge">
                                        {{ __('pos.' . $row->status) ?? $row->status }}
                                    </span>
                                </td>
                                <td>{{ $row->doc_no ?? '—' }}</td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('finance.receipts.manage', $row->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </a>

                                        @if ($row->status === 'active' && !$row->trashed())
                                            <button class="btn btn-sm btn-outline-dark"
                                                onclick="confirmCancel({{ $row->id }})"
                                                title="{{ __('pos.canceled') }}">
                                                <i class="mdi mdi-cancel"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDelete({{ $row->id }})">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled
                                                title="{{ __('pos.canceled') }}"><i
                                                    class="mdi mdi-cancel"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4"><i
                                        class="mdi mdi-information-outline me-1"></i>{{ __('pos.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    {{ __('pos.pagination_info', ['from' => $rows->firstItem(), 'to' => $rows->lastItem(), 'total' => $rows->total()]) }}
                </div>
                <div>{{ $rows->links() }}</div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 🔴 حذف
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
                    // ✅ نداء مباشر لدالة المكوّن (يعمل v2/v3)
                    @this.call('delete', id);

                    Swal.fire('تم الحذف!', '✅ تم الحذف بنجاح.', 'success');
                }
            });
        }

        // 🟡 إلغاء بإدخال سبب
        function confirmCancel(id) {
            Swal.fire({
                title: 'إلغاء إيصال',
                input: 'text',
                inputLabel: 'سبب الإلغاء',
                inputPlaceholder: 'اكتب سبب الإلغاء...',
                showCancelButton: true,
                confirmButtonText: 'تأكيد الإلغاء',
                cancelButtonText: 'إلغاء',
            }).then((result) => {
                if (result.isConfirmed) {
                    // ✅ نداء مباشر للدالة مع payload (object)
                    @this.call('cancel', {
                        id: id,
                        reason: result.value || ''
                    });

                    Swal.fire('ملغي', 'تم إلغاء الإيصال.', 'success');
                }
            });
        }
    </script>

</div>
