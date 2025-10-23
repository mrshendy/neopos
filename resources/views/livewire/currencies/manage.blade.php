<div class="page-wrap container-fluid px-3" x-data>
    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-2 small">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($warning)
        <div class="alert alert-warning alert-dismissible fade show shadow-sm mb-2 small">
            <i class="mdi mdi-alert-outline me-2"></i>{{ $warning }}
            <button type="button" class="btn-close" wire:click="$set('warning', null)"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-2 small">
            <i class="mdi mdi-alert-circle-outline me-2"></i><strong>{{ __('pos.input_errors') }}</strong>
            <ul class="mb-0 mt-1 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Toolbar / Filters --}}
    <div class="card shadow-sm rounded-4 mb-3 stylish-card">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-magnify"></i> {{ __('pos.search') }}
                    </label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search"
                           placeholder="{{ __('pos.ph_search_code_or_name') }}">
                    <small class="text-muted">{{ __('pos.hint_search_currencies') }}</small>
                </div>

                <div class="col-md-3">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-filter-outline"></i> {{ __('pos.status') }}
                    </label>
                    <select class="form-select" wire:model="filter_status">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="active">{{ __('pos.active') }}</option>
                        <option value="inactive">{{ __('pos.inactive') }}</option>
                    </select>
                    <div class="preview-chip mt-1">
                        <i class="mdi mdi-eye-outline"></i>
                        <span>{{ $filter_status === '' ? __('pos.all') : __(
                            $filter_status==='active'?'pos.active':'pos.inactive'
                        ) }}</span>
                    </div>
                    <small class="text-muted d-block">{{ __('pos.hint_filter_status') }}</small>
                </div>

                <div class="col-md-2">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-format-list-numbered"></i> {{ __('pos.per_page') }}
                    </label>
                    <select class="form-select" wire:model="per_page">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <div class="preview-chip mt-1"><i class="mdi mdi-page-next-outline"></i> {{ $per_page }}</div>
                </div>

                <div class="col-md-3 text-md-end">
                    <label class="form-label mb-1 d-block">&nbsp;</label>
                    <button type="button" class="btn btn-light rounded-pill px-4 shadow-sm"
                            wire:click="$refresh">
                        <i class="mdi mdi-refresh"></i> {{ __('pos.reload') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- ======= Form ======= --}}
        <div class="col-lg-4">
            <div class="card rounded-4 shadow-sm stylish-card">
                <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
                    <div><i class="mdi mdi-cash-multiple"></i> {{ $currency_id ? __('pos.edit_currency') : __('pos.add_currency') }}</div>
                    @if($currency_id)
                        <span class="badge bg-secondary">{{ __('pos.editing_id') }}: {{ $currency_id }}</span>
                    @endif
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save" class="row g-3">
                        <div class="col-12 field-block">
                            <label class="form-label">{{ __('pos.code') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.lazy="code" placeholder="EGP / USD" style="text-transform:uppercase">
                            <div class="preview-chip mt-1"><i class="mdi mdi-eye-outline"></i> {{ $code ?: '-' }}</div>
                            <small class="help">{{ __('pos.h_code') }}</small>
                        </div>

                        <div class="col-12 field-block">
                            <label class="form-label">{{ __('pos.name_ar') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="name_ar" placeholder="{{ __('pos.ph_ar') }}">
                            <div class="preview-chip mt-1"><i class="mdi mdi-translate"></i> {{ $name_ar ?: '-' }}</div>
                            <small class="help">{{ __('pos.h_name_ar') }}</small>
                        </div>

                        <div class="col-12 field-block">
                            <label class="form-label">{{ __('pos.name_en') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="name_en" placeholder="{{ __('pos.ph_en') }}">
                            <div class="preview-chip mt-1"><i class="mdi mdi-translate-variant"></i> {{ $name_en ?: '-' }}</div>
                            <small class="help">{{ __('pos.h_name_en') }}</small>
                        </div>

                        <div class="col-md-6 field-block">
                            <label class="form-label">{{ __('pos.symbol') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="symbol" placeholder="E£ / $ / SAR">
                            <div class="preview-chip mt-1"><i class="mdi mdi-currency-usd"></i> {{ $symbol ?: '-' }}</div>
                            <small class="help">{{ __('pos.h_symbol') }}</small>
                        </div>

                        <div class="col-md-6 field-block">
                            <label class="form-label">{{ __('pos.minor_unit') }} <span class="text-danger">*</span></label>
                            <input type="number" min="0" max="6" class="form-control" wire:model.defer="minor_unit">
                            <div class="preview-chip mt-1"><i class="mdi mdi-decimal"></i> {{ $minor_unit }}</div>
                            <small class="help">{{ __('pos.h_minor_unit') }}</small>
                        </div>

                        <div class="col-md-6 field-block">
                            <label class="form-label">{{ __('pos.exchange_rate') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.000001" class="form-control" wire:model.defer="exchange_rate">
                            <div class="preview-chip mt-1"><i class="mdi mdi-swap-horizontal"></i> {{ $exchange_rate }}</div>
                            <small class="help">{{ __('pos.h_exchange_rate') }}</small>
                        </div>

                        <div class="col-md-6 field-block">
                            <label class="form-label">{{ __('pos.status') }} <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model.defer="status">
                                <option value="active">{{ __('pos.active') }}</option>
                                <option value="inactive">{{ __('pos.inactive') }}</option>
                            </select>
                            <div class="preview-chip mt-1">
                                <i class="mdi mdi-toggle-switch-outline"></i>
                                {{ __($status==='active'?'pos.active':'pos.inactive') }}
                            </div>
                            <small class="help">{{ __('pos.h_status') }}</small>
                        </div>

                        <div class="col-12 field-block">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_default" wire:model.defer="is_default">
                                <label class="form-check-label" for="is_default">
                                    <i class="mdi mdi-star-outline"></i> {{ __('pos.is_default') }}
                                </label>
                            </div>
                            <div class="preview-chip mt-1">
                                <i class="mdi mdi-star"></i> {{ $is_default ? __('pos.yes') : __('pos.no') }}
                            </div>
                            <small class="help">{{ __('pos.h_is_default') }}</small>
                        </div>

                        <div class="col-12 field-block">
                            <label class="form-label">{{ __('pos.notes') }}</label>
                            <textarea class="form-control mb-1" rows="2" wire:model.defer="notes_ar" placeholder="{{ __('pos.notes_ar') }}"></textarea>
                            <textarea class="form-control" rows="2" wire:model.defer="notes_en" placeholder="{{ __('pos.notes_en') }}"></textarea>
                            <div class="preview-chip mt-1"><i class="mdi mdi-text-long"></i> {{ Str::limit($notes_ar ?: $notes_en, 30, '...') }}</div>
                            <small class="help">{{ __('pos.h_notes') }}</small>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                                <i class="mdi mdi-content-save-outline"></i> {{ __('pos.save') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm" wire:click="resetForm">
                                <i class="mdi mdi-restore"></i> {{ __('pos.reset') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ======= Table ======= --}}
        <div class="col-lg-8">
            <div class="card rounded-4 shadow-sm stylish-card">
                <div class="card-header bg-light fw-bold">
                    <i class="mdi mdi-table"></i> {{ __('pos.currencies_list') }}
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:90px">{{ __('pos.code') }}</th>
                                    <th>{{ __('pos.name') }}</th>
                                    <th>{{ __('pos.symbol') }}</th>
                                    <th>{{ __('pos.exchange_rate') }}</th>
                                    <th>{{ __('pos.status') }}</th>
                                    <th style="width:160px">{{ __('pos.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rows as $r)
                                    @php
                                        $loc = app()->getLocale();
                                        $name = $r->getTranslation('name', $loc) ?? ($loc==='ar' ? $r->getTranslation('name','en') : $r->getTranslation('name','ar'));
                                    @endphp
                                    <tr>
                                        <td class="fw-bold">
                                            {{ $r->code }}
                                            @if($r->is_default)
                                                <span class="badge bg-warning text-dark ms-1"><i class="mdi mdi-star"></i> {{ __('pos.default') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $name }}</td>
                                        <td>{{ $r->symbol }}</td>
                                        <td>{{ number_format($r->exchange_rate, 6) }}</td>
                                        <td>
                                            <span class="badge {{ $r->status==='active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ __($r->status==='active'?'pos.active':'pos.inactive') }}
                                            </span>
                                        </td>
                                        <td class="text-nowrap">
                                            <button class="btn btn-sm btn-light rounded-pill shadow-sm me-1"
                                                    wire:click="edit({{ $r->id }})" title="{{ __('pos.edit') }}">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </button>

                                            <button class="btn btn-sm btn-outline-primary rounded-pill shadow-sm me-1"
                                                    wire:click="changeStatus({{ $r->id }})" title="{{ __('pos.change_status') }}">
                                                <i class="mdi mdi-toggle-switch"></i>
                                            </button>

                                            <button class="btn btn-sm btn-outline-danger rounded-pill shadow-sm"
                                                    onclick="confirmDelete({{ $r->id }})" title="{{ __('pos.delete') }}">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="mdi mdi-database-off mdi-24px d-block mb-1"></i>
                                            {{ __('pos.no_data') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            {{ __('pos.showing') }} {{ $rows->firstItem() }}–{{ $rows->lastItem() }} {{ __('pos.of') }} {{ $rows->total() }}
                        </div>
                        <div>{{ $rows->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ SweetAlert2 --}}
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

        window.addEventListener('focusSearch', () => {
            const el = document.querySelector('input[wire\\:model*="search"]');
            if (el) el.focus();
        });

        window.addEventListener('scrollToForm', () => {
            document.querySelector('.page-wrap').scrollIntoView({behavior:'smooth', block:'start'});
        });
    </script>

    <style>
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .field-block{position:relative}
        .field-block label{font-weight:600}
        .help{font-size:.8rem;color:#6c757d}
        .preview-chip{display:inline-flex;align-items:center;gap:.35rem;background:#f8f9fa;border:1px solid rgba(0,0,0,.06);border-radius:999px;padding:.25rem .6rem;font-size:.8rem;color:#6c757d}
    </style>
</div>
