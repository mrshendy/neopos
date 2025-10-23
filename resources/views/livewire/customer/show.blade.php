<div class="container-fluid">

    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .badge-soft{background:#f8fafc;border:1px solid rgba(0,0,0,.05);color:#334155}
        .avatar{width:72px;height:72px;border-radius:22px;display:flex;align-items:center;justify-content:center;background:#eef2ff;border:1px solid rgba(55,48,163,.18);color:#3730a3;font-weight:800;font-size:1.25rem}
        .section-title{font-weight:800;color:#0d6efd}
        .mini-title{font-weight:700;color:#334155}
        .info-line{display:flex;align-items:center;gap:.55rem;margin:.25rem 0}
        .info-line i{opacity:.7}
        .kpi{display:flex;align-items:center;gap:.75rem}
        .kpi .ico{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:#f5f8ff;border:1px solid rgba(13,110,253,.10)}
        .kpi .val{font-weight:800;font-size:1.1rem}
        .table thead th{background:#f8f9fc;white-space:nowrap;position:sticky;top:0;z-index:1}
    </style>

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar">
                {{ mb_strtoupper(mb_substr( \Illuminate\Support\Str::of(is_string($row->name) && \Illuminate\Support\Str::startsWith(trim($row->name),'{') ? (json_decode($row->name,true)[app()->getLocale()] ?? json_decode($row->name,true)['ar'] ?? '') : ($row->name ?? '' ))->value(), 0, 2)) }}
            </div>
            <div>
                <h3 class="mb-1 fw-bold">
                    <i class="mdi mdi-account-badge-outline me-1"></i>
                    @php
                        $displayName = $row->name;
                        if (is_string($displayName) && \Illuminate\Support\Str::startsWith(trim($displayName), '{')) {
                            $a = json_decode($displayName,true) ?: [];
                            $displayName = $a[app()->getLocale()] ?? $a['ar'] ?? $displayName;
                        }
                    @endphp
                    {{ $displayName }}
                    @if($row->code)
                        <span class="badge bg-light text-dark border ms-2">{{ $row->code }}</span>
                    @endif
                </h3>
                <div class="text-muted small">
                    {{ __('pos.status') }}:
                    <span class="badge badge-soft">
                        {{ $row->status === 'active' ? __('pos.status_active') : __('pos.status_inactive') }}
                    </span>
                    @if($row->type)
                        <span class="ms-2 badge bg-secondary-subtle text-body">
                            {{ $row->type === 'company' ? __('pos.company') : __('pos.person') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left"></i> {{ __('pos.back') }}
            </a>
            <a href="{{ route('customers.edit', $row->id) }}" class="btn btn-primary">
                <i class="mdi mdi-pencil-outline"></i> {{ __('pos.edit') }}
            </a>
            <button type="button" class="btn btn-warning" wire:click="toggleStatus">
                <i class="mdi mdi-rotate-3d"></i> {{ __('pos.change_status') }}
            </button>
            <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $row->id }})">
                <i class="mdi mdi-trash-can-outline"></i> {{ __('pos.delete') }}
            </button>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <div class="card shadow-sm rounded-4 stylish-card p-3">
                <div class="kpi">
                    <div class="ico"><i class="mdi mdi-file-chart-outline fs-4 text-primary"></i></div>
                    <div>
                        <div class="mini-title">{{ __('pos.records_total') }}</div>
                        <div class="val">{{ number_format($stats['sales_count']) }}</div>
                    </div>
                </div>
                <div class="text-muted small mt-2">{{ __('pos.title_customers_index') }} → POS</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-sm rounded-4 stylish-card p-3">
                <div class="kpi">
                    <div class="ico"><i class="mdi mdi-currency-usd fs-4 text-success"></i></div>
                    <div>
                        <div class="mini-title">{{ __('pos.grand_total') }}</div>
                        <div class="val">{{ number_format($stats['sales_total'], 2) }}</div>
                    </div>
                </div>
                <div class="text-muted small mt-2">{{ __('pos.purchases_index_subtitle') }}</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-sm rounded-4 stylish-card p-3">
                <div class="kpi">
                    <div class="ico"><i class="mdi mdi-calendar-clock fs-4 text-info"></i></div>
                    <div>
                        <div class="mini-title">{{ __('pos.delivery_date') }}</div>
                        <div class="val">
                            {{ $stats['last_sale_at'] ? \Illuminate\Support\Carbon::parse($stats['last_sale_at'])->format('Y-m-d') : '—' }}
                        </div>
                    </div>
                </div>
                <div class="text-muted small mt-2">{{ __('pos.records_showing') }}</div>
            </div>
        </div>
    </div>

    {{-- Details --}}
    <div class="row g-3">
        {{-- Contact --}}
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm rounded-4 stylish-card">
                <div class="card-header bg-light section-title">
                    <i class="mdi mdi-card-account-phone-outline me-1"></i> {{ __('pos.contact') ?? 'بيانات التواصل' }}
                </div>
                <div class="card-body">
                    <div class="info-line"><i class="mdi mdi-email-outline"></i> {{ $row->email ?: '—' }}</div>
                    <div class="info-line"><i class="mdi mdi-phone-outline"></i> {{ $row->phone ?: '—' }}</div>
                    <div class="info-line"><i class="mdi mdi-cellphone"></i> {{ $row->mobile ?: '—' }}</div>
                    <div class="info-line"><i class="mdi mdi-earth"></i> {{ $row->country ?: '—' }}</div>
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm rounded-4 stylish-card">
                <div class="card-header bg-light section-title">
                    <i class="mdi mdi-map-marker-outline me-1"></i> {{ __('pos.address_ar') }}
                </div>
                <div class="card-body">
                    @php
                        $city = is_string($row->city) && \Illuminate\Support\Str::startsWith(trim($row->city),'{')
                            ? (json_decode($row->city,true)[app()->getLocale()] ?? json_decode($row->city,true)['ar'] ?? '')
                            : ($row->city ?? '');
                        $addr = is_string($row->address) && \Illuminate\Support\Str::startsWith(trim($row->address),'{')
                            ? (json_decode($row->address,true)[app()->getLocale()] ?? json_decode($row->address,true)['ar'] ?? '')
                            : ($row->address ?? '');
                    @endphp
                    <div class="info-line"><i class="mdi mdi-city"></i> {{ $city ?: '—' }}</div>
                    <div class="info-line"><i class="mdi mdi-home-outline"></i> {{ $addr ?: '—' }}</div>
                    <div class="info-line"><i class="mdi mdi-numeric"></i> {{ $row->postal_code ?: '—' }}</div>
                </div>
            </div>
        </div>

        {{-- Company / Tax --}}
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm rounded-4 stylish-card">
                <div class="card-header bg-light section-title">
                    <i class="mdi mdi-briefcase-outline me-1"></i> {{ __('pos.company') ?? 'بيانات الشركة' }}
                </div>
                <div class="card-body">
                    <div class="info-line">
                        <i class="mdi mdi-tag-outline"></i>
                        {{ $row->type === 'company' ? __('pos.company') : __('pos.person') }}
                    </div>
                    <div class="info-line"><i class="mdi mdi-file-certificate-outline"></i> {{ __('pos.tax_no') }}: {{ $row->tax_no ?: '—' }}</div>
                    <div class="info-line"><i class="mdi mdi-clipboard-text-outline"></i> {{ __('pos.commercial_no') }}: {{ $row->commercial_no ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    <div class="card shadow-sm rounded-4 stylish-card my-3">
        <div class="card-header bg-light section-title"><i class="mdi mdi-note-text-outline me-1"></i> {{ __('pos.notes_ar') }}</div>
        <div class="card-body">
            @php
                $notes = is_string($row->notes) && \Illuminate\Support\Str::startsWith(trim($row->notes),'{')
                    ? (json_decode($row->notes,true)[app()->getLocale()] ?? json_decode($row->notes,true)['ar'] ?? '')
                    : ($row->notes ?? '');
            @endphp
            <div class="text-muted">{{ $notes ?: '—' }}</div>
        </div>
    </div>

    {{-- Latest POS invoices --}}
    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="card-header bg-light d-flex align-items-center justify-content-between">
            <div class="section-title"><i class="mdi mdi-receipt-text-outline me-1"></i> {{ __('pos.purchases_index_title') }}</div>
            <div class="text-muted small">{{ __('pos.records_showing') }}: {{ number_format(count($invoices)) }}</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:180px">{{ __('pos.purchase_no') }}</th>
                            <th style="width:140px">{{ __('pos.purchase_date') }}</th>
                            <th>{{ __('pos.status') }}</th>
                            <th class="text-end" style="width:160px">{{ __('pos.grand_total') }}</th>
                            <th style="width:160px">{{ __('pos.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $i)
                            @php $statusKey = 'pos.status_'.($i['status'] ?? 'draft'); @endphp
                            <tr>
                                <td class="fw-semibold">{{ $i['sale_no'] }}</td>
                                <td>{{ $i['date'] ? \Illuminate\Support\Carbon::parse($i['date'])->format('Y-m-d') : '—' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ \Illuminate\Support\Facades\Lang::has($statusKey) ? __($statusKey) : strtoupper($i['status'] ?? 'draft') }}
                                    </span>
                                </td>
                                <td class="text-end">{{ number_format((float)($i['grand_total'] ?? 0), 2) }}</td>
                                <td class="d-flex gap-1">
                                    @if (Route::has('pos.show'))
                                        <a href="{{ route('pos.show', $i['id']) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>
                                    @endif
                                    @if (Route::has('pos.edit'))
                                        <a href="{{ route('pos.edit', $i['id']) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-file-document-edit-outline"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">{{ __('pos.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '{{ __("pos.confirm_delete_title") }}',
                text: '{{ __("pos.confirm_delete_text") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#0d6efd',
                confirmButtonText: '{{ __("pos.confirm") }}',
                cancelButtonText: '{{ __("pos.cancel") }}'
            }).then((r) => {
                if (r.isConfirmed) {
                    Livewire.emit('deleteConfirmed', id);
                    Swal.fire('{{ __("pos.deleted") }}', '{{ __("pos.deleted_ok") }}', 'success');
                }
            })
        }
    </script>
</div>
