<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .filters .form-label{font-weight:600}
        .table td,.table th{vertical-align:middle}
        .soft-badge{font-size:.75rem}
    </style>

    <div class="card rounded-4 shadow-sm stylish-card">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
            <span><i class="mdi mdi-cash-multiple me-1"></i> {{ __('pos.mov_title_index') ?? 'حركات الخزائن' }}</span>
            <a href="{{ route('finance.movements.manage') }}" class="btn btn-success rounded-pill px-3">
                <i class="mdi mdi-plus"></i> {{ __('pos.btn_new') }}
            </a>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <div class="row g-2 filters mb-3">
                <div class="col-lg-3">
                    <label class="form-label"><i class="mdi mdi-magnify me-1"></i>{{ __('pos.search') ?? 'بحث' }}</label>
                    <input type="text" class="form-control" wire:model.debounce.500ms="q" placeholder="{{ __('pos.search') ?? 'بحث...' }}">
                </div>

                <div class="col-lg-2">
                    <label class="form-label">{{ __('pos.cashbox') ?? 'الخزينة' }}</label>
                    <select class="form-select" wire:model="cashbox_id">
                        <option value="all">{{ __('pos.all') }}</option>
                        @foreach($cashboxes as $cb)
                            @php $name = $cb->getTranslation('name', app()->getLocale()) ?: $cb->getTranslation('name', app()->getLocale()==='ar'?'en':'ar'); @endphp
                            <option value="{{ $cb->id }}">{{ $name }} ({{ $cb->id }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label">{{ __('pos.direction') ?? 'النوع' }}</label>
                    <select class="form-select" wire:model="direction">
                        <option value="all">{{ __('pos.all') }}</option>
                        <option value="in">{{ __('pos.mov_in') ?? 'قبض' }}</option>
                        <option value="out">{{ __('pos.mov_out') ?? 'صرف' }}</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label">{{ __('pos.status') ?? 'الحالة' }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="all">{{ __('pos.all') }}</option>
                        <option value="draft">{{ __('pos.draft') ?? 'مسودة' }}</option>
                        <option value="posted">{{ __('pos.posted') ?? 'مُرحّلة' }}</option>
                        <option value="void">{{ __('pos.void') ?? 'ملغاة' }}</option>
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
                        <input type="number" step="0.01" class="form-control" wire:model="min_amount" placeholder="min">
                        <input type="number" step="0.01" class="form-control" wire:model="max_amount" placeholder="max">
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
                        <option>10</option><option>15</option><option>25</option><option>50</option>
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
                                <a href="#" class="text-decoration-none" wire:click.prevent="setSort('id')">
                                    # @if($sortField==='id')<i class="mdi mdi-chevron-{{$sortDirection==='asc'?'up':'down'}}"></i>@endif
                                </a>
                            </th>
                            <th style="width:130px">
                                <a href="#" class="text-decoration-none" wire:click.prevent="setSort('movement_date')">
                                    {{ __('pos.date') ?? 'التاريخ' }}
                                    @if($sortField==='movement_date')<i class="mdi mdi-chevron-{{$sortDirection==='asc'?'up':'down'}}"></i>@endif
                                </a>
                            </th>
                            <th>{{ __('pos.cashbox') ?? 'الخزينة' }}</th>
                            <th style="width:100px">{{ __('pos.direction') ?? 'النوع' }}</th>
                            <th style="width:130px">
                                <a href="#" class="text-decoration-none" wire:click.prevent="setSort('amount')">
                                    {{ __('pos.amount') ?? 'المبلغ' }}
                                    @if($sortField==='amount')<i class="mdi mdi-chevron-{{$sortDirection==='asc'?'up':'down'}}"></i>@endif
                                </a>
                            </th>
                            <th style="width:110px">{{ __('pos.currency') ?? 'العملة' }}</th>
                            <th style="width:120px">{{ __('pos.status') ?? 'الحالة' }}</th>
                            <th>{{ __('pos.doc_no') ?? 'رقم المستند' }}</th>
                            <th class="text-end" style="width:180px">{{ __('pos.col_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            @php
                                $cbName = $row->cashbox?->getTranslation('name', app()->getLocale()) 
                                        ?: $row->cashbox?->getTranslation('name', app()->getLocale()==='ar'?'en':'ar');
                            @endphp
                            <tr @if($row->trashed()) class="table-danger" @endif>
                                <td>{{ $row->id }}</td>
                                <td>{{ optional($row->movement_date)->format('Y-m-d') }}</td>
                                <td>{{ $cbName ? $cbName.' (#'.$row->finance_settings_id.')' : '—' }}</td>
                                <td>
                                    <span class="badge {{ $row->direction==='in'?'bg-success':'bg-danger' }} soft-badge">
                                        {{ $row->direction==='in' ? __('pos.mov_in') ?? 'قبض' : __('pos.mov_out') ?? 'صرف' }}
                                    </span>
                                </td>
                                <td>{{ number_format($row->amount, 2) }}</td>
                                <td>{{ $row->currency_id ?? '—' }}</td>
                                <td>
                                    @php
                                        $map = ['draft'=>'secondary','posted'=>'primary','void'=>'dark'];
                                        $txt = ['draft'=>__('pos.draft')??'مسودة','posted'=>__('pos.posted')??'مُرحّلة','void'=>__('pos.void')??'ملغاة'];
                                    @endphp
                                    <span class="badge bg-{{ $map[$row->status] }} soft-badge">{{ $txt[$row->status] }}</span>
                                </td>
                                <td>{{ $row->doc_no ?? '—' }}</td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('finance.movements.manage', $row->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </a>

                                        @if(!$row->trashed())
                                            <button class="btn btn-sm btn-outline-success" wire:click="togglePost({{ $row->id }})">
                                                <i class="mdi mdi-checkbox-multiple-marked-outline"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-dark" wire:click="void({{ $row->id }})" title="{{ __('pos.void') ?? 'إلغاء' }}">
                                                <i class="mdi mdi-cancel"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $row->id }})">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" wire:click="restore({{ $row->id }})">
                                                <i class="mdi mdi-restore"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="mdi mdi-information-outline me-1"></i>{{ __('pos.no_data') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    {{ __('pos.pagination_info', ['from'=>$rows->firstItem(), 'to'=>$rows->lastItem(), 'total'=>$rows->total()]) }}
                </div>
                <div>{{ $rows->links() }}</div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 confirm delete --}}
    <script>
        function confirmDelete(id){
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
