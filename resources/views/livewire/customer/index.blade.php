<div>
    {{-- رسائل عامة --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">
            <i class="mdi mdi-account-group-outline me-2"></i> {{ __('pos.title_customers_index') }}
        </h4>
        <a href="{{ route('customers.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-account-plus-outline"></i> {{ __('pos.btn_new_customer') }}
        </a>
    </div>

    {{-- فلاتر البحث --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="mdi mdi-magnify me-1"></i>{{ __('pos.ph_search_name') }}
                    </label>
                    <input type="text" class="form-control" wire:model.debounce.500ms="search"
                           placeholder="{{ __('pos.ph_search_name') }}">
                    <small class="text-muted">{{ __('pos.preview') }}</small>
                    <div class="mt-1 text-muted"><i class="mdi mdi-eye-outline"></i> {{ $search }}</div>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold"><i class="mdi mdi-shield-key-outline me-1"></i>{{ __('pos.th_code') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.500ms="code" placeholder="{{ __('pos.th_code') }}">
                    <small class="text-muted">{{ __('pos.preview') }}</small>
                    <div class="mt-1 text-muted"><i class="mdi mdi-eye-outline"></i> {{ $code }}</div>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold"><i class="mdi mdi-phone-outline me-1"></i>{{ __('pos.th_phone') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.500ms="phone" placeholder="{{ __('pos.ph_phone') }}">
                    <small class="text-muted">{{ __('pos.preview') }}</small>
                    <div class="mt-1 text-muted"><i class="mdi mdi-eye-outline"></i> {{ $phone }}</div>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold"><i class="mdi mdi-receipt-text-outline me-1"></i>{{ __('pos.f_tax') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.500ms="tax" placeholder="{{ __('pos.ph_tax') }}">
                    <small class="text-muted">{{ __('pos.preview') }}</small>
                    <div class="mt-1 text-muted"><i class="mdi mdi-eye-outline"></i> {{ $tax }}</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold"><i class="mdi mdi-storefront-outline me-1"></i>{{ __('pos.f_channel') }}</label>
                    <select class="form-select" wire:model="channel">
                        <option value="">{{ __('pos.none') }}</option>
                        <option value="retail">Retail</option>
                        <option value="wholesale">Wholesale</option>
                        <option value="online">Online</option>
                        <option value="pharmacy">Pharmacy</option>
                    </select>
                    <small class="text-muted">{{ __('pos.preview') }}</small>
                    <div class="mt-1 text-muted"><i class="mdi mdi-eye-outline"></i> {{ $channel }}</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold"><i class="mdi mdi-shield-check-outline me-1"></i>{{ __('pos.f_account_status') }}</label>
                    <select class="form-select" wire:model="account_status">
                        <option value="">{{ __('pos.any_status') }}</option>
                        <option value="active">{{ __('pos.status_active') }}</option>
                        <option value="inactive">{{ __('pos.status_inactive') }}</option>
                        <option value="suspended">{{ __('pos.status_suspended') }}</option>
                    </select>
                    <small class="text-muted">{{ __('pos.preview') }}</small>
                    <div class="mt-1 text-muted"><i class="mdi mdi-eye-outline"></i> {{ $account_status }}</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold"><i class="mdi mdi-cash-multiple me-1"></i>{{ __('pos.f_price_category') }}</label>
                    <input type="number" class="form-control" wire:model="price_category_id"
                           placeholder="{{ __('pos.ph_price_category') }}">
                    <small class="text-muted">{{ __('pos.preview') }}</small>
                    <div class="mt-1 text-muted"><i class="mdi mdi-eye-outline"></i> {{ $price_category_id }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- جدول العملاء --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('pos.th_code') }}</th>
                        <th>{{ __('pos.th_name') }}</th>
                        <th>{{ __('pos.th_phone') }}</th>
                        <th>{{ __('pos.th_city') }}</th>
                        <th>{{ __('pos.th_area') }}</th>
                        <th>{{ __('pos.th_price_category') }}</th>
                        <th>{{ __('pos.th_balance') }}</th>
                        <th>{{ __('pos.th_status') }}</th>
                        <th class="text-end">{{ __('pos.th_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($customers as $row)
                    @php
                        $locale = app()->getLocale();
                        $name = $row->getTranslation('legal_name', $locale);
                    @endphp
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td class="fw-semibold">{{ $row->code }}</td>
                        <td>{{ $name }}</td>
                        <td>{{ $row->phone }}</td>
                        <td>{{ optional($row->cityRel)->name ?? '-' }}</td>
                        <td>{{ optional($row->area)->name ?? '-' }}</td>
                        <td>{{ optional($row->priceCategory)->getTranslation('name', $locale) ?? '-' }}</td>
                        <td>{{ number_format((float)$row->balance, 2) }}</td>
                        <td>
                            @if ($row->account_status === 'active')
                                <span class="badge bg-success">{{ __('pos.status_active') }}</span>
                            @elseif($row->account_status === 'inactive')
                                <span class="badge bg-secondary">{{ __('pos.status_inactive') }}</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ __('pos.status_suspended') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('customers.show', $row->id) }}" class="btn btn-sm btn-outline-info">
                                <i class="mdi mdi-eye-outline"></i>
                            </a>
                            <a href="{{ route('customers.edit', $row->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="mdi mdi-pencil-outline"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                    wire:click="toggleStatus({{ $row->id }})"
                                    title="{{ __('pos.btn_toggle') }}">
                                <i class="mdi mdi-toggle-switch-outline"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete({{ $row->id }})">
                                <i class="mdi mdi-delete-outline"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <i class="mdi mdi-database-off-outline me-1"></i> {{ __('pos.no_data') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $customers->onEachSide(1)->links() }}
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
    </script>

