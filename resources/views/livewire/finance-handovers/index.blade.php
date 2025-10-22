{{-- resources/views/livewire/finance_handovers/index.blade.php --}}
<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>.stylish-card{border:1px solid rgba(0,0,0,.06)}.soft-badge{font-size:.75rem}.table td,.table th{vertical-align:middle}</style>

    <div class="card rounded-4 shadow-sm stylish-card">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
            <span><i class="mdi mdi-handshake-outline me-1"></i> {{ __('pos.handover_title_index') ?? 'استلام/تسليم الخزينة' }}</span>
            <a href="{{ route('finance.handovers.manage') }}" class="btn btn-success rounded-pill px-3">
                <i class="mdi mdi-plus"></i> {{ __('pos.btn_new') }}
            </a>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <div class="row g-2 mb-3">
                <div class="col-lg-3">
                    <label class="form-label">{{ __('pos.search') ?? 'بحث' }}</label>
                    <input type="text" class="form-control" wire:model.debounce.500ms="q" placeholder="{{ __('pos.search') ?? 'بحث...' }}">
                </div>
                <div class="col-lg-2">
                    <label class="form-label">{{ __('pos.cashbox') ?? 'الخزينة' }}</label>
                    <select class="form-select" wire:model="cashbox_id">
                        <option value="all">{{ __('pos.all') }}</option>
                        @foreach($cashboxes as $cb)
                            @php $n=$cb->getTranslation('name',app()->getLocale()) ?: $cb->getTranslation('name',app()->getLocale()==='ar'?'en':'ar'); @endphp
                            <option value="{{ $cb->id }}">{{ $n }} ({{ $cb->id }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label">{{ __('pos.status') ?? 'الحالة' }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="all">{{ __('pos.all') }}</option>
                        <option value="draft">{{ __('pos.draft') ?? 'مسودة' }}</option>
                        <option value="submitted">{{ __('pos.submitted') ?? 'مُرسلة' }}</option>
                        <option value="received">{{ __('pos.received') ?? 'مستلمة' }}</option>
                        <option value="rejected">{{ __('pos.rejected') ?? 'مرفوضة' }}</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label class="form-label">{{ __('pos.date_range') ?? 'الفترة' }}</label>
                    <div class="d-flex gap-2">
                        <input type="date" class="form-control" wire:model="date_from">
                        <input type="date" class="form-control" wire:model="date_to">
                    </div>
                </div>
                <div class="col-lg-2">
                    <label class="form-label">{{ __('pos.per_page') ?? 'لكل صفحة' }}</label>
                    <select class="form-select" wire:model="perPage">
                        <option>10</option><option>15</option><option>25</option><option>50</option>
                    </select>
                </div>
                <div class="col-lg-2 d-flex align-items-end">
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
                            <th style="width:70px"><a href="#" wire:click.prevent="setSort('id')" class="text-decoration-none"># @if($sortField==='id')<i class="mdi mdi-chevron-{{$sortDirection==='asc'?'up':'down'}}"></i>@endif</a></th>
                            <th>{{ __('pos.cashbox') ?? 'الخزينة' }}</th>
                            <th style="width:150px"><a href="#" wire:click.prevent="setSort('handover_date')" class="text-decoration-none">{{ __('pos.date') ?? 'التاريخ' }} @if($sortField==='handover_date')<i class="mdi mdi-chevron-{{$sortDirection==='asc'?'up':'down'}}"></i>@endif</a></th>
                            <th style="width:130px">{{ __('pos.amount_expected') ?? 'المتوقّع' }}</th>
                            <th style="width:130px">{{ __('pos.amount_counted') ?? 'المحصّل' }}</th>
                            <th style="width:130px">{{ __('pos.difference') ?? 'الفرق' }}</th>
                            <th style="width:130px">{{ __('pos.status') ?? 'الحالة' }}</th>
                            <th style="width:140px">{{ __('pos.doc_no') ?? 'رقم' }}</th>
                            <th class="text-end" style="width:220px">{{ __('pos.col_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            @php
                                $name = $row->cashbox?->getTranslation('name', app()->getLocale()) ?: $row->cashbox?->getTranslation('name', app()->getLocale()==='ar'?'en':'ar');
                                $diff = (float)$row->difference;
                            @endphp
                            <tr @if($row->trashed()) class="table-danger" @endif>
                                <td>{{ $row->id }}</td>
                                <td>{{ $name ? $name.' (#'.$row->finance_settings_id.')' : '—' }}</td>
                                <td>{{ optional($row->handover_date)->format('Y-m-d H:i') }}</td>
                                <td>{{ number_format($row->amount_expected,2) }}</td>
                                <td>{{ number_format($row->amount_counted,2) }}</td>
                                <td>
                                    <span class="badge {{ $diff==0?'bg-success':($diff>0?'bg-primary':'bg-danger') }} soft-badge">{{ number_format($diff,2) }}</span>
                                </td>
                                <td>
                                    @php $map=['draft'=>'secondary','submitted'=>'warning','received'=>'success','rejected'=>'dark']; @endphp
                                    <span class="badge bg-{{ $map[$row->status] }} soft-badge">
                                        {{ __('pos.'.$row->status) ?? $row->status }}
                                    </span>
                                </td>
                                <td>{{ $row->doc_no ?? '—' }}</td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('finance.handovers.manage', $row->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </a>

                                        @if(!$row->trashed())
                                            <button class="btn btn-sm btn-outline-warning" wire:click="setStatus({{ $row->id }}, 'submitted')" title="{{ __('pos.submitted') }}"><i class="mdi mdi-send"></i></button>
                                            <button class="btn btn-sm btn-outline-success" wire:click="setStatus({{ $row->id }}, 'received')"  title="{{ __('pos.received') }}"><i class="mdi mdi-check-all"></i></button>
                                            <button class="btn btn-sm btn-outline-dark"    wire:click="setStatus({{ $row->id }}, 'rejected')"  title="{{ __('pos.rejected') }}"><i class="mdi mdi-cancel"></i></button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $row->id }})"><i class="mdi mdi-trash-can-outline"></i></button>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" wire:click="restore({{ $row->id }})"><i class="mdi mdi-restore"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted py-4"><i class="mdi mdi-information-outline me-1"></i>{{ __('pos.no_data') }}</td></tr>
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

    <script>
        function confirmDelete(id){
            Swal.fire({
                title: '{{ __('pos.alert_title') }}', text: '{{ __('pos.alert_text') }}', icon: 'warning',
                showCancelButton: true, confirmButtonColor:'#198754', cancelButtonColor:'#0d6efd',
                confirmButtonText: '{{ __('pos.alert_confirm') }}', cancelButtonText: '{{ __('pos.alert_cancel') }}'
            }).then((r)=>{ if(r.isConfirmed){ Livewire.emit('deleteConfirmed', id); Swal.fire('{{ __('pos.alert_deleted_title') }}','{{ __('pos.alert_deleted_text') }}','success'); }})
        }
    </script>
</div>
