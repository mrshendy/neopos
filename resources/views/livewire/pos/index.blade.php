{{-- resources/views/livewire/pos/index.blade.php --}}
<div class="container-fluid px-3">
    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-2 small">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

   
    <style>
        :root{
            --bg:#f7fafc; --card:#ffffff; --text:#0f172a; --muted:#64748b; --border:#e2e8f0;
            --radius:12px; --radius-sm:8px; --shadow:0 1px 3px rgba(0,0,0,.08); --shadow-md:0 6px 16px rgba(0,0,0,.08);
            --sky:#0ea5e9; --sky-600:#0284c7; --sky-300:#7dd3fc; --accent:#8b5cf6;
        }
        *{box-sizing:border-box;}
        body{background:var(--bg); color:var(--text)}

        .hero{position:relative; border-radius:16px; overflow:hidden; margin:10px 0 14px; border:1px solid var(--border);
              background: radial-gradient(800px 400px at 100% -20%, rgba(255,255,255,.35) 0%, transparent 70%),
                         linear-gradient(135deg, var(--sky) 0%, var(--sky-600) 100%); color:#fff; box-shadow:var(--shadow-md)}
        .hero-inner{display:flex; align-items:center; justify-content:space-between; padding:16px 18px}
        .hero-title{display:flex; align-items:center; gap:12px}
        .hero-title i{background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.25); width:40px; height:40px; border-radius:12px; display:inline-flex; align-items:center; justify-content:center}
        .hero h1{margin:0; font-size:1.15rem; font-weight:800; letter-spacing:.2px}
        .hero-sub{opacity:.95; font-size:.85rem; margin-top:2px}
        .hero-actions{display:flex; gap:8px; align-items:center}
        .hero .btn-ghost{background:rgba(255,255,255,.15); color:#fff; border:1px solid rgba(255,255,255,.25); padding:.45rem .8rem; border-radius:10px; font-size:.85rem; font-weight:600; backdrop-filter:blur(6px)}
        .hero .btn-ghost:hover{background:rgba(255,255,255,.22)}
        .hero .stat{display:flex; gap:10px; align-items:center; padding:.35rem .6rem; border-radius:999px; background:rgba(255,255,255,.14); border:1px solid rgba(255,255,255,.25); font-size:.8rem}

        .toolbar{background:var(--card); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow); padding:12px 14px; margin-bottom:12px}
        .toolbar .form-control,.toolbar .form-select{height:36px; padding:.35rem .7rem; font-size:.9rem; border:1px solid var(--border); border-radius:10px}
        .toolbar .form-control:focus,.toolbar .form-select:focus{border-color:var(--sky); box-shadow:0 0 0 3px rgba(14,165,233,.15); outline:none}
        .toolbar .input-group-text{font-size:.85rem; background:#f8fafc; border:1px solid var(--border); color:var(--muted)}
        .small-note{font-size:.75rem; color:var(--muted); margin-top:4px}

        .panel{background:var(--card); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow)}
        .panel-body{padding:14px}

        .btn-primary{background:var(--sky); color:#fff; border:none; border-radius:10px; padding:.5rem 1rem; font-weight:700}
        .btn-primary:hover{background:var(--sky-600)}
        .btn-outline{border:1px solid var(--border); background:#fff; color:var(--text); border-radius:10px; padding:.45rem .9rem; font-weight:600}
        .btn-outline:hover{border-color:var(--sky-300)}

        .table-sm td,.table-sm th{padding:.5rem .75rem}
        ::-webkit-scrollbar{width:6px;height:6px}
        ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:10px}
        ::-webkit-scrollbar-thumb:hover{background:#94a3b8}
        .cursor-pointer{cursor:pointer}
    </style>

    {{-- HERO HEADER (مطابق لإحساس manage) --}}
    <div class="hero">
        <div class="hero-inner">
            <div class="hero-title">
<i class="mdi mdi-clipboard-list-outline"></i>        <!-- نسخة أنيقة وأقرب للـ fa -->
                <div>
                    <h1>{{ __('pos.title_pos_index') }}</h1>
                    <div class="hero-sub">{{ __('pos.modern_fast_interface') }}</div>
                </div>
            </div>
            <div class="hero-actions">
                <div class="stat">
                    <i class="fa-regular fa-calendar"></i>
                    <span class="small">
                        {{ __('pos.date_from') }}: {{ $date_from ?? '—' }} &nbsp;|&nbsp; {{ __('pos.date_to') }}: {{ $date_to ?? '—' }}
                    </span>
                </div>
                <a href="{{ route('pos.create') }}" class="btn-ghost">
                    <i class="fa-solid fa-plus"></i> {{ __('pos.btn_new_sale') }}
                </a>
                <button type="button" class="btn-ghost" wire:click="clearFilters">
                    <i class="fa-solid fa-filter-circle-xmark"></i> {{ __('pos.clear_filters') }}
                </button>
            </div>
        </div>
    </div>

    {{-- FILTERS TOOLBAR (نفس ستايل manage) --}}
    <div class="toolbar">
        <div class="row g-2 align-items-end">
            <div class="col-lg-3">
                <label class="form-label mb-1">{{ __('pos.search') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" class="form-control" wire:model.debounce.400ms="q" placeholder="{{ __('pos.ph_search_sale') }}">
                </div>
                <div class="small-note">{{ __('pos.hint_search_sale') }}</div>
            </div>

            <div class="col-lg-2">
                <label class="form-label mb-1">{{ __('pos.date_from') }}</label>
                <input type="date" class="form-control" wire:model.lazy="date_from">
            </div>
            <div class="col-lg-2">
                <label class="form-label mb-1">{{ __('pos.date_to') }}</label>
                <input type="date" class="form-control" wire:model.lazy="date_to">
            </div>

            <div class="col-lg-2">
                <label class="form-label mb-1">{{ __('pos.warehouse') }}</label>
                <select class="form-select" wire:model="warehouse_id">
                    <option value="">{{ __('pos.all') }}</option>
                    @foreach($warehouses as $w)
                        @php
                            $raw = $w->name;
                            $name = is_array($raw) ? ($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw)??''))) : (string)$raw;
                        @endphp
                        <option value="{{ $w->id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2">
                <label class="form-label mb-1">{{ __('pos.customer') }}</label>
                <select class="form-select" wire:model="customer_id">
                    <option value="">{{ __('pos.all') }}</option>
                    @foreach($customers as $c)
                        @php
                            $raw = $c->name;
                            $name = is_array($raw) ? ($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw)??''))) : (string)$raw;
                        @endphp
                        <option value="{{ $c->id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-1">
                <label class="form-label mb-1">{{ __('pos.status') }}</label>
                <select class="form-select" wire:model="status">
                    <option value="">{{ __('pos.all') }}</option>
                    <option value="draft">{{ __('pos.status_draft') }}</option>
                    <option value="approved">{{ __('pos.status_approved') }}</option>
                    <option value="posted">{{ __('pos.status_posted') }}</option>
                    <option value="cancelled">{{ __('pos.status_cancelled') }}</option>
                </select>
            </div>

            <div class="col-lg-12 d-flex justify-content-between align-items-center mt-2">
                <div>
                    <label class="small me-2">{{ __('pos.per_page') }}</label>
                    <select class="form-select d-inline-block" style="width:auto" wire:model="perPage">
                        <option>10</option><option>25</option><option>50</option><option>100</option>
                    </select>
                </div>
                <div class="text-muted small">
                    <i class="fa-regular fa-circle-question"></i> {{ __('pos.hint_click_to_sort') }}
                </div>
            </div>
        </div>
    </div>

    {{-- LIST PANEL --}}
    <section class="panel">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th wire:click="sort('id')" class="cursor-pointer">#</th>
                        <th wire:click="sort('pos_no')" class="cursor-pointer">{{ __('pos.sale_no') }}</th>
                        <th wire:click="sort('pos_date')" class="cursor-pointer">{{ __('pos.sale_date') }}</th>
                        <th>{{ __('pos.customer') }}</th>
                        <th>{{ __('pos.warehouse') }}</th>
                        <th wire:click="sort('status')" class="cursor-pointer">{{ __('pos.status') }}</th>
                        <th class="text-end cursor-pointer" wire:click="sort('subtotal')">{{ __('pos.subtotal') }}</th>
                        <th class="text-end cursor-pointer" wire:click="sort('grand_total')">{{ __('pos.grand_total') }}</th>
                        <th class="text-end">{{ __('pos.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($rows as $r)
                        @php
                            $wRaw = $r->warehouse->name ?? null;
                            $wName = is_array($wRaw) ? ($wRaw[app()->getLocale()] ?? ($wRaw['ar'] ?? (reset($wRaw)??''))) : $wRaw;
                            $cRaw = $r->customer->name ?? null;
                            $cName = is_array($cRaw) ? ($cRaw[app()->getLocale()] ?? ($cRaw['ar'] ?? (reset($cRaw)??''))) : $cRaw;
                            $badge = $r->status === 'posted' ? 'success'
                                   : ($r->status === 'approved' ? 'primary'
                                   : ($r->status === 'draft' ? 'secondary' : 'danger'));
                        @endphp
                        <tr>
                            <td>{{ $r->id }}</td>
                            <td>{{ $r->pos_no ?? '—' }}</td>
                            <td>{{ $r->pos_date }}</td>
                            <td>{{ $cName ?? '—' }}</td>
                            <td>{{ $wName ?? '—' }}</td>
                            <td><span class="badge rounded-pill text-bg-{{ $badge }}">{{ __('pos.status_'.$r->status) }}</span></td>
                            <td class="text-end">{{ number_format((float)$r->subtotal,2) }}</td>
                            <td class="text-end">{{ number_format((float)$r->grand_total,2) }}</td>
                            <td class="text-end">
                                <a href="{{ route('pos.show',$r->id) }}" class="btn btn-outline btn-sm rounded-pill px-3">
                                    <i class="fa-regular fa-eye"></i> {{ __('pos.btn_show') }}
                                </a>
                                <a href="{{ route('pos.edit',$r->id) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                    <i class="fa-regular fa-pen-to-square"></i> {{ __('pos.btn_edit') }}
                                </a>
                                {{-- ✅ زر الحذف مع SweetAlert (كما طلبت) --}}
                                <button type="button" class="btn btn-danger btn-sm rounded-pill px-3"
                                        onclick="confirmDelete({{ $r->id }})">
                                    <i class="fa-regular fa-trash-can"></i> {{ __('pos.btn_delete') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">{{ __('pos.no_data') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-2">
                <div class="small text-muted">{{ __('pos.pagination_info', ['from'=>$rows->firstItem(),'to'=>$rows->lastItem(),'total'=>$rows->total()]) }}</div>
                <div>{{ $rows->links() }}</div>
            </div>
        </div>
    </section>
</div>

{{-- ✅ SweetAlert2 (الحذف كما هو) --}}
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
