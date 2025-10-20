<div class="page-wrap">

    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        :root {
            --accent: #0d6efd;
            /* Bootstrap primary */
            --accent-2: #20c997;
            /* teal */
            --muted: #6c757d;
            --chip-bg: #f8f9fa;
            --card-brd: rgba(0, 0, 0, .06);
        }

        .page-head {
            position: sticky;
            top: 0;
            z-index: 15;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%);
            border-radius: 1rem;
            padding: 1.25rem 1.25rem;
            box-shadow: 0 10px 30px rgba(13, 110, 253, .12);
            color: #fff;
            margin-bottom: 1rem;
        }

        .page-head .title {
            font-weight: 800;
            letter-spacing: .2px;
        }

        .page-head .subtitle {
            opacity: .9;
            font-size: .9rem
        }

        .btn-glass {
            backdrop-filter: blur(6px);
            background: rgba(255, 255, 255, .15);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .25);
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, .25);
            color: #fff
        }

        .stylish-card {
            border: 1px solid var(--card-brd);
            border-radius: 1rem
        }

        .preview-chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: var(--chip-bg);
            border: 1px solid var(--card-brd);
            border-radius: 999px;
            padding: .25rem .6rem;
            font-size: .8rem;
            color: var(--muted)
        }

        .sticky-filters {
            position: sticky;
            top: 90px;
            z-index: 10
        }

        .table thead th {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 5;
            box-shadow: inset 0 -1px 0 var(--card-brd);
        }

        .table tbody tr:hover {
            background: #fbfcff
        }

        .badge-soft {
            border: 1px solid var(--card-brd);
            background: #f6f7f9;
            color: #111;
            font-weight: 600
        }

        .toolbar {
            display: flex;
            gap: .75rem;
            align-items: center;
            justify-content: space-between;
            margin: .25rem 0 1rem 0;
        }

        .toolbar h4 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: .5rem
        }

        .filters-help {
            font-size: .8rem;
            color: var(--muted)
        }

        .btn-reset {
            border: 1px dashed var(--card-brd);
            background: #fff;
        }

        @media (max-width: 575.98px) {
            .page-head {
                border-radius: .75rem;
                padding: 1rem
            }

            .sticky-filters {
                top: 86px
            }
        }
    </style>

    {{-- ====== عنوان علوي + زر إضافة ====== --}}
    <div class="page-head">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="title h4 mb-0">
                    <i class="mdi mdi-ticket-percent-outline me-1"></i> {{ __('pos.coupons_title') }}
                </div>
                <div class="subtitle mt-1">
                    {{ __('pos.hint_search_coupon') ?? __('pos.hint_search_offer') }}
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('coupons.create') }}" class="btn btn-glass rounded-pill px-4">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> {{ __('pos.btn_new_coupon') }}
                </a>
            </div>
        </div>
    </div>

    {{-- ====== فلاتر البحث (أفقي ومنسّق) ====== --}}
    <div class="card rounded-4 shadow-sm mb-3 stylish-card sticky-filters">
        <div class="card-body pb-2">
            <div class="row row-cols-1 row-cols-lg-5 g-3 align-items-end">

                {{-- بحث --}}
                <div class="col">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-magnify"></i> {{ __('pos.search') }}
                    </label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search"
                        placeholder="{{ __('pos.ph_search_coupon') ?? __('pos.ph_search_offer') }}">
                    <div class="mt-1 preview-chip">
                        <i class="mdi mdi-text-box-search-outline"></i>
                        {{ $search ?: __('pos.no_value') }}
                    </div>
                    <small
                        class="filters-help">{{ __('pos.hint_search_coupon') ?? __('pos.hint_search_offer') }}</small>
                </div>

                {{-- الحالة --}}
                <div class="col">
                    <label class="form-label mb-1">{{ __('pos.status') }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="active">{{ __('pos.status_active') }}</option>
                        <option value="paused">{{ __('pos.status_paused') }}</option>
                        <option value="expired">{{ __('pos.status_expired') }}</option>
                    </select>
                    <div class="mt-1 preview-chip">
                        <i class="mdi mdi-traffic-light"></i> {{ $status ?: __('pos.all') }}
                    </div>
                    <small class="filters-help">{{ __('pos.hint_status') }}</small>
                </div>

                {{-- النوع --}}
                <div class="col">
                    <label class="form-label mb-1">{{ __('pos.type') }}</label>
                    <select class="form-select" wire:model="type">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="percentage">{{ __('pos.type_percentage') }}</option>
                        <option value="fixed">{{ __('pos.type_fixed') }}</option>
                    </select>
                    <div class="mt-1 preview-chip">
                        <i class="mdi mdi-tag-outline"></i> {{ $type ?: __('pos.all') }}
                    </div>
                    <small class="filters-help">{{ __('pos.hint_type') }}</small>
                </div>

                {{-- مدى التاريخ (input-group واحد) --}}
                <div class="col">
                    <label class="form-label mb-1">{{ __('pos.date_from') }} → {{ __('pos.date_to') }}</label>
                    <div class="input-group">
                        <input type="date" class="form-control" wire:model="date_from">
                        <span class="input-group-text">→</span>
                        <input type="date" class="form-control" wire:model="date_to">
                    </div>
                    <div class="mt-1 preview-chip">
                        <i class="mdi mdi-calendar-range"></i>
                        {{ $date_from ?: __('pos.no_value') }} → {{ $date_to ?: __('pos.no_value') }}
                    </div>
                    <small class="filters-help">{{ __('pos.hint_date_range') }}</small>
                </div>

                {{-- إعادة تعيين --}}
                <div class="col">
                    <label class="form-label mb-1 d-block">&nbsp;</label>
                    <button type="button" class="btn btn-reset rounded-pill w-100"
                        wire:click="$set('search','');$set('status','');$set('type','');$set('date_from',null);$set('date_to',null)">
                        <i class="mdi mdi-backup-restore"></i> {{ __('Reset') ?? 'إعادة تعيين' }}
                    </button>
                </div>

            </div>
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
                        <th>{{ __('pos.period') }}</th>
                        <th>{{ __('pos.usage') ?? 'Usage' }}</th>
                        <th>{{ __('pos.status') }}</th>
                        <th class="text-end" style="width:240px">{{ __('pos.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $i => $c)
                        <tr>
                            <td class="text-muted">{{ $coupons->firstItem() + $i }}</td>
                            <td><span class="badge badge-soft rounded-pill">{{ $c->code }}</span></td>
                            <td class="fw-semibold">{{ $c->getTranslation('name', app()->getLocale()) }}</td>
                            <td>{{ __('pos.type_' . $c->type) }}</td>
                            <td class="text-nowrap">
                                @if ($c->start_at || $c->end_at)
                                    <i class="mdi mdi-calendar-range-outline me-1"></i>
                                    {{ optional($c->start_at)->format('Y-m-d') }} →
                                    {{ optional($c->end_at)->format('Y-m-d') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="preview-chip">
                                    <i class="mdi mdi-counter"></i> {{ $c->used_count }} /
                                    {{ $c->max_total_uses ?? '∞' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $state = [
                                        'active' => [
                                            'cls' => 'bg-success-subtle text-success',
                                            'ico' => 'check-circle-outline',
                                        ],
                                        'paused' => [
                                            'cls' => 'bg-warning-subtle text-warning',
                                            'ico' => 'pause-circle-outline',
                                        ],
                                        'expired' => [
                                            'cls' => 'bg-danger-subtle text-danger',
                                            'ico' => 'alert-circle-outline',
                                        ],
                                    ][$c->status] ?? [
                                        'cls' => 'bg-secondary-subtle text-secondary',
                                        'ico' => 'circle-outline',
                                    ];
                                @endphp
                                <span class="badge {{ $state['cls'] }} d-inline-flex align-items-center gap-1">
                                    <i class="mdi mdi-{{ $state['ico'] }}"></i> {{ __('pos.status_' . $c->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('coupons.edit', $c->id) }}"
                                        class="btn btn-outline-primary btn-sm rounded-pill">
                                        <i class="mdi mdi-pencil-outline"></i> {{ __('pos.edit') }}
                                    </a>
                                    <button class="btn btn-outline-warning btn-sm rounded-pill"
                                        wire:click="toggleStatus({{ $c->id }})">
                                        <i class="mdi mdi-toggle-switch-outline"></i> {{ __('pos.toggle_status') }}
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm rounded-pill"
                                        onclick="confirmDelete({{ $c->id }})">
                                        <i class="mdi mdi-delete-outline"></i> {{ __('pos.delete') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">{{ __('pos.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted small">
                <i class="mdi mdi-information-outline me-1"></i>
                {{ __('Showing') ?? 'عرض' }} {{ $coupons->firstItem() }}–{{ $coupons->lastItem() }}
                {{ __('of') ?? 'من' }} {{ $coupons->total() }}
            </div>
            {{ $coupons->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- ✅ SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '{{ __('pos.alert_title') }}',
            text: '{{ __('pos.alert_text') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#0d6efd',
            confirmButtonText: '{{ __('pos.alert_yes_delete') }}',
            cancelButtonText: '{{ __('pos.alert_cancel') }}'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('deleteConfirmed', id);
                Swal.fire('{{ __('pos.deleted') }}', '{{ __('pos.msg_deleted_ok') }}', 'success');
            }
        })
    }
</script>
