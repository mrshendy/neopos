<div class="page-wrap">
    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm rounded-4 stylish-card mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-magnify"></i> {{ __('pos.search') }}</label>
                    <input type="text" class="form-control" placeholder="كود/اسم/هاتف/ضريبة"
                           wire:model.debounce.400ms="search">
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.f_account_status') }}</label>
                    <select class="form-select" wire:model="account_status">
                        <option value="">{{ __('pos.opt_all') }}</option>
                        <option value="active">{{ __('pos.status_active') }}</option>
                        <option value="inactive">{{ __('pos.status_inactive') }}</option>
                        <option value="suspended">{{ __('pos.status_suspended') }}</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.f_type') }}</label>
                    <select class="form-select" wire:model="type">
                        <option value="">{{ __('pos.opt_all') }}</option>
                        <option value="individual">{{ __('pos.opt_individual') }}</option>
                        <option value="company">{{ __('pos.opt_company') }}</option>
                        <option value="b2b">B2B</option>
                        <option value="b2c">B2C</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.per_page') }}</label>
                    <select class="form-select" wire:model="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <div class="col-lg-3 d-flex gap-2 justify-content-end">
                    <a href="{{ route('customers.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-account-plus-outline"></i> {{ __('pos.btn_new_customer') }}
                    </a>
                    <button class="btn btn-light rounded-pill px-4 shadow-sm" wire:click="clearFilters">
                        <i class="mdi mdi-filter-remove"></i> {{ __('pos.btn_reset_filters') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>{{ __('pos.f_code') }}</th>
                    <th>{{ __('pos.f_legal_name_ar') }}</th>
                    <th>{{ __('pos.f_legal_name_en') }}</th>
                    <th>{{ __('pos.f_type') }}</th>
                    <th>{{ __('pos.f_channel') }}</th>
                    <th>{{ __('pos.f_phone') }}</th>
                    <th>{{ __('pos.f_account_status') }}</th>
                    <th class="text-end">{{ __('pos.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($customers as $i => $row)
                    <tr>
                        <td>{{ $customers->firstItem() + $i }}</td>
                        <td>{{ $row->code }}</td>
                        <td>{{ $row->getTranslation('legal_name','ar') }}</td>
                        <td>{{ $row->getTranslation('legal_name','en') }}</td>
                        <td>{{ $row->type }}</td>
                        <td>{{ $row->channel }}</td>
                        <td>{{ $row->phone }}</td>
                        <td>
                            @if($row->account_status === 'active')
                                <span class="badge bg-success-subtle text-success">{{ __('pos.status_active') }}</span>
                            @elseif($row->account_status === 'inactive')
                                <span class="badge bg-secondary-subtle text-secondary">{{ __('pos.status_inactive') }}</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning">{{ __('pos.status_suspended') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('customers.show',$row->id) }}" class="btn btn-light btn-sm rounded-pill px-3">
                                <i class="mdi mdi-eye-outline"></i> {{ __('pos.btn_show') }}
                            </a>
                            <a href="{{ route('customers.edit',$row->id) }}" class="btn btn-success btn-sm rounded-pill px-3">
                                <i class="mdi mdi-square-edit-outline"></i> {{ __('pos.btn_edit') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">{{ __('pos.no_data') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">{{ __('pos.total') }}: {{ $customers->total() }}</small>
            {{ $customers->links() }}
        </div>
    </div>
</div>

<style>
    .stylish-card{border:1px solid rgba(0,0,0,.06)}
</style>
