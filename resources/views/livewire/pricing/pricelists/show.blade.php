<div class="page-wrap">
    {{-- ====== Header ====== --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div class="d-flex align-items-center gap-3">
            <h4 class="mb-0 d-flex align-items-center gap-2">
                <i class="mdi mdi-eye-outline"></i>
                {{ __('pos.price_lists_title') ?? 'قوائم الأسعار' }} — {{ __('pos.show') ?? 'عرض' }} #{{ $row->id }}
            </h4>
            @if ($row->status === 'active')
                <span class="badge bg-success d-inline-flex align-items-center gap-1">
                    <i class="mdi mdi-check-circle-outline"></i>{{ __('pos.status_active') ?? 'نشط' }}
                </span>
            @else
                <span class="badge bg-secondary d-inline-flex align-items-center gap-1">
                    <i class="mdi mdi-close-circle-outline"></i>{{ __('pos.status_inactive') ?? 'غير نشط' }}
                </span>
            @endif
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('pricing.lists.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') ?? 'رجوع' }}
            </a>
            <a href="{{ route('pricing.lists.edit', $row->id) }}" class="btn btn-primary rounded-pill px-4">
                <i class="mdi mdi-pencil-outline"></i> {{ __('pos.btn_edit') ?? 'تعديل' }}
            </a>
            <button type="button" class="btn btn-light rounded-pill px-4" onclick="window.print()">
                <i class="mdi mdi-printer"></i> طباعة
            </button>
            <button type="button" class="btn btn-success rounded-pill px-4" onclick="exportItemsCsv()">
                <i class="mdi mdi-file-delimited-outline"></i> تصدير CSV
            </button>
        </div>
    </div>

    {{-- ====== Styles ====== --}}
    <style>
        .card-modern{border:1px solid rgba(0,0,0,.06);border-radius:1rem}
        .table td, .table th{vertical-align:middle}
        .thead-sticky thead th{position:sticky;top:0;background:#f8f9fa;z-index:5}
        .chip{display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .6rem;border-radius:999px;border:1px solid rgba(0,0,0,.08);background:#fff;font-size:.8rem;color:#6c757d}
        .stat-card{background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:1rem;padding:1rem}
        .stat-card .label{font-size:.8rem;color:#6c757d}
        .stat-card .value{font-weight:700;font-size:1.05rem}
        .timeline{display:flex;align-items:center;gap:.5rem}
        .timeline .dot{width:10px;height:10px;border-radius:999px;background:#0d6efd}
        .timeline .line{height:2px;background:#e9ecef;flex:1}
        .soft-muted{color:#6c757d}
        .items-toolbar{padding:.75rem}
        .items-toolbar .form-control{max-width:320px}
        @media print{
            .btn,.items-toolbar,.page-actions{display:none!important}
            .card-modern{box-shadow:none!important}
        }
    </style>

    {{-- ====== Basic Info ====== --}}
    <div class="card card-modern mb-3">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
            <span><i class="mdi mdi-information-outline me-1"></i> {{ __('pos.basic_info') ?? 'البيانات الأساسية' }}</span>
            @php
                $from = $row->valid_from ? \Illuminate\Support\Carbon::parse($row->valid_from)->format('Y-m-d') : '—';
                $to   = $row->valid_to   ? \Illuminate\Support\Carbon::parse($row->valid_to)->format('Y-m-d')   : '—';
            @endphp
            <div class="d-none d-md-flex align-items-center gap-2">
                <span class="chip"><i class="mdi mdi-calendar-start"></i> {{ $from }}</span>
                <span class="chip"><i class="mdi mdi-calendar-end"></i> {{ $to }}</span>
            </div>
        </div>

        @php
            $displayAr = null; $displayEn = null;
            try {
                $decoded = is_string($row->name) ? json_decode($row->name, true) : $row->name;
                if (is_array($decoded)) {
                    $displayAr = $decoded['ar'] ?? null;
                    $displayEn = $decoded['en'] ?? null;
                }
            } catch (\Throwable $e) {}
        @endphp

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="label">{{ __('pos.name_ar') }}</div>
                        <div class="value">{{ $displayAr ?: (is_array($row->name)?($row->name['ar']??''): (string)$row->name) }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="label">{{ __('pos.name_en') }}</div>
                        <div class="value">{{ $displayEn ?: (is_array($row->name)?($row->name['en']??''): (string)$row->name) }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="label">{{ __('pos.validity') ?? 'الصلاحية' }}</div>
                        <div class="value d-flex flex-column gap-2">
                            <div class="timeline">
                                <span class="dot"></span>
                                <span class="line"></span>
                                <span class="dot" style="background:#198754"></span>
                            </div>
                            <div class="soft-muted small">
                                {{ $from }} <span class="mx-1">→</span> {{ $to }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="label">{{ __('pos.items_count') ?? 'عدد البنود' }}</div>
                        <div class="value">{{ $items->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== Items ====== --}}
    <div class="card card-modern">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
            <span><i class="mdi mdi-cash me-1"></i> {{ __('pos.price_items') ?? 'بنود الأسعار' }}</span>
            <div class="d-flex align-items-center gap-2 items-toolbar">
                <input type="text" id="itemsSearch" class="form-control form-control-sm" placeholder="بحث داخل البنود..." oninput="filterItemsTable()">
                <span class="chip d-none d-md-inline-flex"><i class="mdi mdi-counter"></i> {{ $items->count() }}</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive thead-sticky" style="max-height:65vh">
                <table class="table table-hover align-middle mb-0" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="width:34%">{{ __('pos.product') ?? 'المنتج' }}</th>
                            <th style="width:12%">{{ __('pos.price') ?? 'السعر' }}</th>
                            <th style="width:12%">{{ __('pos.min_qty') ?? 'الحد الأدنى' }}</th>
                            <th style="width:12%">{{ __('pos.max_qty') ?? 'الحد الأقصى' }}</th>
                            <th style="width:15%">{{ __('pos.valid_from') ?? 'من تاريخ' }}</th>
                            <th style="width:15%">{{ __('pos.valid_to') ?? 'إلى تاريخ' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $it)
                            @php
                                $p = $it->product;
                                $pname = null;
                                if ($p) {
                                    $pname = method_exists($p,'getTranslation')
                                        ? $p->getTranslation('name', app()->getLocale())
                                        : ($p->name ?? ('#'.$p->id));
                                }
                                $vf = $it->valid_from ? \Illuminate\Support\Carbon::parse($it->valid_from)->format('Y-m-d') : '—';
                                $vt = $it->valid_to   ? \Illuminate\Support\Carbon::parse($it->valid_to)->format('Y-m-d')   : '—';
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $pname ?: '—' }}</div>
                                    <div class="soft-muted small">
                                        <span class="chip"><i class="mdi mdi-pound-box-outline"></i>ID: {{ $it->product_id }}</span>
                                        @if($it->min_qty)
                                            <span class="chip"><i class="mdi mdi-arrow-up-bold-outline"></i>{{ __('pos.min_qty') ?? 'الحد الأدنى' }}: {{ $it->min_qty }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ number_format((float)$it->price, 2) }}</td>
                                <td>{{ $it->min_qty }}</td>
                                <td>{{ $it->max_qty ?? '—' }}</td>
                                <td>{{ $vf }}</td>
                                <td>{{ $vt }}</td>
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
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('pricing.lists.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') ?? 'رجوع' }}
            </a>
            <a href="{{ route('pricing.lists.edit', $row->id) }}" class="btn btn-primary rounded-pill px-4">
                <i class="mdi mdi-pencil-outline"></i> {{ __('pos.btn_edit') ?? 'تعديل' }}
            </a>
        </div>
    </div>

    {{-- ====== Helpers (CSV + Search) ====== --}}
    <script>
        function filterItemsTable(){
            const q = (document.getElementById('itemsSearch').value || '').toLowerCase().trim();
            const rows = document.querySelectorAll('#itemsTable tbody tr');
            rows.forEach(r => {
                const text = r.innerText.toLowerCase();
                r.style.display = text.includes(q) ? '' : 'none';
            });
        }

        function exportItemsCsv(){
            // جمع البيانات من الجدول
            const rows = Array.from(document.querySelectorAll('#itemsTable tr'));
            const data = rows.map(row => Array.from(row.querySelectorAll('th,td')).map(cell => {
                let v = cell.innerText.replace(/\s+/g,' ').trim();
                // هروب فاصلة CSV
                if (v.includes(',') || v.includes('"')) v = `"${v.replace(/"/g,'""')}"`;
                return v;
            }).join(',')).join('\n');

            const blob = new Blob([data], {type: 'text/csv;charset=utf-8;'});
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `price_list_{{ $row->id }}_items.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
    </script>
</div>
