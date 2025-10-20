<div class="page-wrap">

    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        :root{
            --accent:#0d6efd;      /* primary */
            --accent-2:#20c997;    /* teal */
            --muted:#6c757d;
            --chip-bg:#f8f9fa;
            --card-brd:rgba(0,0,0,.06);
        }
        .page-head{
            position:sticky; top:0; z-index:15;
            background: linear-gradient(135deg,var(--accent) 0%,var(--accent-2) 100%);
            border-radius:1rem; padding:1.25rem 1.25rem; color:#fff;
            box-shadow:0 10px 30px rgba(13,110,253,.12); margin-bottom:1rem;
        }
        .page-head .title{ font-weight:800; letter-spacing:.2px }
        .page-head .subtitle{ opacity:.9; font-size:.9rem }
        .btn-glass{
            backdrop-filter: blur(6px);
            background: rgba(255,255,255,.15); color:#fff; border:1px solid rgba(255,255,255,.25);
        }
        .btn-glass:hover{ background: rgba(255,255,255,.25); color:#fff }
        .stylish-card{border:1px solid var(--card-brd); border-radius:1rem}
        .preview-chip{
            display:inline-flex;align-items:center;gap:.35rem;background:var(--chip-bg);
            border:1px solid var(--card-brd);border-radius:999px;padding:.25rem .6rem;
            font-size:.8rem;color:var(--muted)
        }
        .sticky-filters{position:sticky;top:90px;z-index:10}
        .table thead th{
            position: sticky; top: 0; background: #fff; z-index: 5;
            box-shadow: inset 0 -1px 0 var(--card-brd);
        }
        .table tbody tr:hover{ background:#fbfcff }
        .badge-soft{ border:1px solid var(--card-brd); background:#f6f7f9; color:#111; font-weight:600 }
        .btn-reset{ border:1px dashed var(--card-brd); background:#fff }
        @media (max-width: 575.98px){
            .page-head{ border-radius:.75rem; padding:1rem }
            .sticky-filters{ top: 86px }
        }
    </style>

    {{-- ====== عنوان علوي + زر إضافة ====== --}}
    <div class="page-head">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="title h4 mb-0">
                    <i class="mdi mdi-sale-outline me-1"></i> {{ __('pos.offers_title') }}
                </div>
                <div class="subtitle mt-1">
                    {{ __('pos.hint_type_offer') }}
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('offers.create') }}" class="btn btn-glass rounded-pill px-4">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> {{ __('pos.btn_new_offer') }}
                </a>
            </div>
        </div>
    </div>

   {{-- ====== فلاتر البحث (منسّقة أفقيًا) ====== --}}
<div class="card rounded-4 shadow-sm mb-3 stylish-card sticky-filters">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            {{-- بحث --}}
            <div class="col-12 col-md-6 col-lg-3 col-xl-3">
                <label class="form-label mb-1">
                    <i class="mdi mdi-magnify"></i> {{ __('pos.search') }}
                </label>
                <input type="text" class="form-control"
                       wire:model.debounce.400ms="search"
                       placeholder="{{ __('pos.ph_search_offer') }}">
                <div class="mt-1 preview-chip">
                    <i class="mdi mdi-text-box-search-outline"></i>
                    {{ $search ?: __('pos.no_value') }}
                </div>
                <small class="text-muted">{{ __('pos.hint_search_offer') }}</small>
            </div>

            {{-- الحالة --}}
            <div class="col-6 col-md-4 col-lg-2 col-xl-2">
                <label class="form-label mb-1">{{ __('pos.status') }}</label>
                <select class="form-select" wire:model="status">
                    <option value="">{{ __('pos.all') }}</option>
                    <option value="active">{{ __('pos.status_active') }}</option>
                    <option value="paused">{{ __('pos.status_paused') }}</option>
                    <option value="expired">{{ __('pos.status_expired') }}</option>
                    <option value="draft">{{ __('pos.status_draft') }}</option>
                </select>
                <div class="mt-1 preview-chip">
                    <i class="mdi mdi-traffic-light"></i> {{ $status ?: __('pos.all') }}
                </div>
                <small class="text-muted">{{ __('pos.hint_status') }}</small>
            </div>

            {{-- النوع --}}
            <div class="col-6 col-md-4 col-lg-2 col-xl-2">
                <label class="form-label mb-1">{{ __('pos.type') }}</label>
                <select class="form-select" wire:model="type">
                    <option value="">{{ __('pos.all') }}</option>
                    <option value="percentage">{{ __('pos.type_percentage') }}</option>
                    <option value="fixed">{{ __('pos.type_fixed') }}</option>
                    <option value="bxgy">{{ __('pos.type_bxgy') }}</option>
                    <option value="bundle">{{ __('pos.type_bundle') }}</option>
                </select>
                <div class="mt-1 preview-chip">
                    <i class="mdi mdi-tag-outline"></i> {{ $type ?: __('pos.all') }}
                </div>
                <small class="text-muted">{{ __('pos.hint_type') }}</small>
            </div>

            {{-- قابل للدمج --}}
            <div class="col-6 col-md-4 col-lg-2 col-xl-2">
                <label class="form-label mb-1">{{ __('pos.stackable') }}</label>
                <select class="form-select" wire:model="stackable">
                    <option value="">{{ __('pos.all') }}</option>
                    <option value="1">{{ __('pos.yes') }}</option>
                    <option value="0">{{ __('pos.no') }}</option>
                </select>
                <div class="mt-1 preview-chip">
                    <i class="mdi mdi-layers-triple-outline"></i>
                    @if($stackable==='') {{ __('pos.all') }} @else {{ $stackable ? __('pos.yes') : __('pos.no') }} @endif
                </div>
                <small class="text-muted">{{ __('pos.hint_stackable') }}</small>
            </div>

            {{-- مدى التاريخ (حقل واحد بعنصرين) --}}
            <div class="col-12 col-md-6 col-lg-3 col-xl-3">
                <label class="form-label mb-1">{{ __('pos.date_from') }} → {{ __('pos.date_to') }}</label>
                <div class="d-flex gap-2">
                    <input type="date" class="form-control" wire:model="date_from">
                    <input type="date" class="form-control" wire:model="date_to">
                </div>
                <div class="mt-1 preview-chip">
                    <i class="mdi mdi-calendar-range"></i>
                    {{ $date_from ?: __('pos.no_value') }} → {{ $date_to ?: __('pos.no_value') }}
                </div>
                <small class="text-muted">{{ __('pos.hint_date_range') }}</small>
            </div>
        </div>
    </div>

    {{-- فوتر الفلاتر: زر إعادة تعيين فقط لترك الصف العلوي نضيف --}}
    <div class="card-footer d-flex justify-content-end">
        <button type="button" class="btn btn-reset rounded-pill"
                wire:click="$set('search','');$set('status','');$set('type','');$set('stackable','');$set('date_from',null);$set('date_to',null)">
            <i class="mdi mdi-backup-restore"></i> {{ __('Reset') ?? 'إعادة تعيين' }}
        </button>
    </div>
</div>


    {{-- ====== الجدول ====== --}}
    <div class="card rounded-4 shadow-sm stylish-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th>{{ __('pos.code') }}</th>
                        <th>{{ __('pos.name') }}</th>
                        <th>{{ __('pos.type') }}</th>
                        <th>{{ __('pos.priority') }}</th>
                        <th>{{ __('pos.stackable') }}</th>
                        <th>{{ __('pos.period') }}</th>
                        <th class="text-end" style="width:260px">{{ __('pos.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offers as $i => $off)
                        <tr>
                            <td class="text-muted">{{ $offers->firstItem() + $i }}</td>
                            <td><span class="badge badge-soft rounded-pill">{{ $off->code }}</span></td>
                            <td class="fw-semibold">{{ $off->getTranslation('name', app()->getLocale()) }}</td>
                            <td>{{ __('pos.type_'.$off->type) }}</td>
                            <td>{{ $off->priority }}</td>
                            <td>{!! $off->is_stackable ? '<i class="mdi mdi-check text-success"></i>' : '<i class="mdi mdi-close text-danger"></i>' !!}</td>
                            <td class="text-nowrap">
                                @if($off->start_at || $off->end_at)
                                    <i class="mdi mdi-calendar-range-outline me-1"></i>
                                    {{ optional($off->start_at)->format('Y-m-d') }} → {{ optional($off->end_at)->format('Y-m-d') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                                <div class="mt-1">
                                    @php
                                        $state = [
                                            'active' => ['cls'=>'bg-success-subtle text-success','ico'=>'check-circle-outline'],
                                            'paused' => ['cls'=>'bg-warning-subtle text-warning','ico'=>'pause-circle-outline'],
                                            'expired'=> ['cls'=>'bg-danger-subtle text-danger','ico'=>'alert-circle-outline'],
                                            'draft'  => ['cls'=>'bg-secondary-subtle text-secondary','ico'=>'circle-outline'],
                                        ][$off->status] ?? ['cls'=>'bg-secondary-subtle text-secondary','ico'=>'circle-outline'];
                                    @endphp
                                    <span class="badge {{ $state['cls'] }} d-inline-flex align-items-center gap-1">
                                        <i class="mdi mdi-{{ $state['ico'] }}"></i> {{ __('pos.status_'.$off->status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('offers.edit',$off->id) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                        <i class="mdi mdi-pencil-outline"></i> {{ __('pos.edit') }}
                                    </a>
                                    <button class="btn btn-outline-warning btn-sm rounded-pill" wire:click="toggleStatus({{ $off->id }})">
                                        <i class="mdi mdi-toggle-switch-outline"></i> {{ __('pos.toggle_status') }}
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm rounded-pill" onclick="confirmDelete({{ $off->id }})">
                                        <i class="mdi mdi-delete-outline"></i> {{ __('pos.delete') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">{{ __('pos.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted small">
                <i class="mdi mdi-information-outline me-1"></i>
                عرض {{ $offers->firstItem() }}–{{ $offers->lastItem() }} من {{ $offers->total() }}
            </div>
            {{ $offers->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- ✅ SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '{{ __("pos.alert_title") }}',
            text: '{{ __("pos.alert_text") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#0d6efd',
            confirmButtonText: '{{ __("pos.alert_yes_delete") }}',
            cancelButtonText: '{{ __("pos.alert_cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('deleteConfirmed', id);
                Swal.fire('{{ __("pos.deleted") }}', '{{ __("pos.msg_deleted_ok") }}', 'success');
            }
        })
    }
</script>
