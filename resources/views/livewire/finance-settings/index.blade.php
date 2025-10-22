{{-- resources/views/livewire/finance_settings/index.blade.php --}}
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
        .soft-badge{font-size:.75rem}
        .filters .form-label{font-weight:600}
        .table td, .table th{vertical-align:middle}
    </style>

    <div class="card rounded-4 shadow-sm stylish-card">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
            <span><i class="mdi mdi-warehouse-cog me-1"></i> {{ __('pos.finset_title') }}</span>
            <a href="{{ route('finance_settings.create') }}" class="btn btn-success rounded-pill px-3">
                <i class="mdi mdi-plus"></i> {{ __('pos.btn_new') }}
            </a>
        </div>

        <div class="card-body">

            {{-- Filters --}}
            <div class="row g-2 filters mb-3">
                <div class="col-lg-3">
                    <label class="form-label"><i class="mdi mdi-magnify me-1"></i>{{ __('pos.search') ?? 'بحث' }}</label>
                    <input type="text" class="form-control" placeholder="{{ __('pos.search') ?? 'بحث...' }}"
                           wire:model.debounce.500ms="q">
                </div>

                <div class="col-lg-2">
                    <label class="form-label">{{ __('pos.finset_cashbox_type') }}</label>
                    <select class="form-select" wire:model="type">
                        <option value="all">{{ __('pos.all') }}</option>
                        <option value="main">{{ __('pos.finset_cashbox_type_main') }}</option>
                        <option value="sub">{{ __('pos.finset_cashbox_type_sub') }}</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label">{{ __('pos.finset_is_available') }}</label>
                    <select class="form-select" wire:model="available">
                        <option value="all">{{ __('pos.all') }}</option>
                        <option value="1">{{ __('pos.enabled') }}</option>
                        <option value="0">{{ __('pos.disabled') }}</option>
                    </select>
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
                        <option>10</option><option>15</option><option>25</option><option>50</option>
                    </select>
                </div>

                <div class="col-lg-1 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-secondary w-100" wire:click="clearFilters">
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
                                <a href="#" wire:click.prevent="setSort('id')" class="text-decoration-none">
                                    # @if($sortField==='id') <i class="mdi mdi-chevron-{{$sortDirection==='asc'?'up':'down'}}"></i> @endif
                                </a>
                            </th>
                            <th>
                                <a href="#" wire:click.prevent="setSort('name')" class="text-decoration-none">
                                    {{ __('pos.finset_name_ar') }} / {{ __('pos.finset_name_en') }}
                                    @if($sortField==='name') <i class="mdi mdi-chevron-{{$sortDirection==='asc'?'up':'down'}}"></i> @endif
                                </a>
                            </th>
                            <th style="width:120px">{{ __('pos.finset_currency') }}</th>
                            <th style="width:120px">{{ __('pos.finset_cashbox_type') }}</th>
                            <th style="width:140px">{{ __('pos.finset_is_available') }}</th>
                            <th style="width:140px">{{ __('pos.finset_return_window_days') }}</th>
                            <th style="width:140px">{{ __('pos.finset_next_return_number') }}</th>
                            <th style="width:160px">
                                <a href="#" wire:click.prevent="setSort('updated_at')" class="text-decoration-none">
                                    {{ __('Updated') ?? 'آخر تحديث' }}
                                    @if($sortField==='updated_at') <i class="mdi mdi-chevron-{{$sortDirection==='asc'?'up':'down'}}"></i> @endif
                                </a>
                            </th>
                            <th class="text-end" style="width:160px">{{ __('pos.col_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $row)
                            @php
                                $locale = app()->getLocale();
                                $displayName = $row->getTranslation('name', $locale) ?: ($locale==='ar' ? $row->getTranslation('name','en') : $row->getTranslation('name','ar'));
                            @endphp
                            <tr @if($row->trashed()) class="table-danger" @endif>
                                <td>{{ $row->id }}</td>
                                <td>
                                    <div class="fw-bold">{{ $displayName }}</div>
                                    <div class="text-muted small">
                                        <span class="me-2"><i class="mdi mdi-format-letter-matches"></i> {{ $row->receipt_prefix }}</span>
                                    </div>
                                </td>
                                <td>{{ $row->currency_id ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $row->cashbox_type==='main' ? 'bg-primary' : 'bg-info' }} soft-badge">
                                        {{ $row->cashbox_type==='main' ? __('pos.finset_cashbox_type_main') : __('pos.finset_cashbox_type_sub') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge {{ $row->is_available ? 'bg-success' : 'bg-secondary' }} soft-badge">
                                            {{ $row->is_available ? __('pos.enabled') : __('pos.disabled') }}
                                        </span>
                                        @if(!$row->trashed())
                                            <button class="btn btn-sm btn-outline-success rounded-pill"
                                                    wire:click="toggleStatus({{ $row->id }})">
                                                <i class="mdi mdi-toggle-switch"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $row->return_window_days }}</td>
                                <td>{{ $row->next_return_number }}</td>
                                <td>{{ $row->updated_at?->format('Y-m-d H:i') }}</td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        @if($row->trashed())
                                            <button class="btn btn-sm btn-outline-secondary" wire:click="restore({{ $row->id }})">
                                                <i class="mdi mdi-restore"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('finance_settings.edit', $row->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="mdi mdi-pencil-outline"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete({{ $row->id }})">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="mdi mdi-information-outline me-1"></i> {{ __('pos.no_data') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    {{ __('pos.pagination_info', ['from'=>$rows->firstItem(), 'to'=>$rows->lastItem(), 'total'=>$rows->total()]) }}
                </div>
                <div>
                    {{ $rows->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 confirm delete --}}
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '{{ __('pos.alert_title') }}',
                text:  '{{ __('pos.alert_text') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#0d6efd',
                confirmButtonText: '{{ __('pos.alert_confirm') }}',
                cancelButtonText: '{{ __('pos.alert_cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('deleteConfirmed', id);
                    Swal.fire('{{ __('pos.alert_deleted_title') }}', '{{ __('pos.alert_deleted_text') }}', 'success');
                }
            })
        }
    </script>
</div>
