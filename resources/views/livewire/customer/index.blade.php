<div class="page-wrap">

    {{-- alerts --}}
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

    {{-- header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="mdi mdi-account-group-outline me-2"></i> {{ __('pos.title_customers_index') ?? 'إدارة العملاء' }}
            </h4>
            <div class="text-muted small">{{ __('pos.subtitle_customers_index') ?? 'بحث، تصفية، تعديل، وحذف العملاء' }}</div>
        </div>
        <a href="{{ route('customers.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-account-plus"></i> {{ __('pos.btn_new_customer') ?? 'عميل جديد' }}
        </a>
    </div>

    {{-- filters --}}
    <div class="card shadow-sm rounded-4 mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label mb-1"><i class="mdi mdi-magnify"></i> {{ __('pos.search') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search"
                           placeholder="{{ __('pos.ph_search_customer') ?? 'ابحث بالاسم/الكود/الهاتف' }}">
                    <small class="text-muted">{{ __('pos.hint_search_customer') ?? 'اكتب جزءًا من الاسم أو الكود أو الهاتف' }}</small>
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-shield-check-outline"></i> {{ __('pos.filter_status') }}</label>
                    <select class="form-select" wire:model="account_status">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="active">{{ __('pos.status_active') }}</option>
                        <option value="inactive">{{ __('pos.status_inactive') }}</option>
                        <option value="suspended">{{ __('pos.status_suspended') ?? 'معلّق' }}</option>
                    </select>
                    <small class="text-muted">{{ __('pos.h_account_status') ?? 'حالة حساب العميل' }}</small>
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-storefront-outline"></i> {{ __('pos.f_channel') }}</label>
                    <select class="form-select" wire:model="channel">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="retail">{{ __('pos.opt_retail') }}</option>
                        <option value="wholesale">{{ __('pos.opt_wholesale') }}</option>
                        <option value="online">{{ __('pos.opt_online') }}</option>
                        <option value="pharmacy">{{ __('pos.opt_pharmacy') }}</option>
                    </select>
                    <small class="text-muted">{{ __('pos.h_channel') }}</small>
                </div>

                <div class="col-lg-2 text-end">
                    <button class="btn btn-light rounded-pill px-3 shadow-sm"
                        wire:click="$set('search','');$set('account_status','');$set('channel','')">
                        <i class="mdi mdi-filter-remove-outline"></i> {{ __('pos.clear_filters') ?? 'مسح الفلاتر' }}
                    </button>
                </div>
            </div>

            {{-- summary --}}
            <div class="d-flex align-items-center mt-3 small text-muted">
                <i class="mdi mdi-dots-grid me-1"></i>
                <span>{{ __('pos.search') }}:</span>
                <span class="badge bg-light text-dark ms-1">{{ $search ?: '—' }}</span>
                <span class="ms-3">{{ __('pos.f_channel') }}:</span>
                <span class="badge bg-light text-dark ms-1">{{ $channel ?: __('pos.all') }}</span>
                <span class="ms-3">{{ __('pos.filter_status') }}:</span>
                <span class="badge bg-light text-dark ms-1">
                    {{ $account_status ? __($account_status==='active'?'pos.status_active':($account_status==='inactive'?'pos.status_inactive':'pos.status_suspended')) : __('pos.all') }}
                </span>
                <span class="ms-auto">
                    <span class="badge bg-primary-subtle text-primary rounded-pill">
                        <i class="mdi mdi-counter me-1"></i>{{ $rows->total() }}
                    </span>
                </span>
            </div>
        </div>
    </div>

    {{-- table --}}
    <div class="card shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle mb-0 pretty-table">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('pos.f_code') }}</th>
                        <th>{{ __('pos.f_legal_name_ar') }}</th>
                        <th>{{ __('pos.f_phone') }}</th>
                        <th>{{ __('pos.f_channel') }}</th>
                        <th>{{ __('pos.f_account_status') }}</th>
                        <th class="text-end">{{ __('pos.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td class="text-muted">{{ $row->id }}</td>
                            <td class="font-monospace fw-600">{{ $row->code }}</td>
                            <td class="text-truncate" style="max-width:280px"
                                title="{{ $row->getTranslation('legal_name', app()->getLocale()) }}">
                                {{ $row->getTranslation('legal_name', app()->getLocale()) }}
                            </td>
                            <td>{{ $row->phone ?: '—' }}</td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ __(
                                        $row->channel==='retail'?'pos.opt_retail':
                                        ($row->channel==='wholesale'?'pos.opt_wholesale':
                                        ($row->channel==='online'?'pos.opt_online':'pos.opt_pharmacy'))
                                    ) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 {{ $row->account_status=='active' ? 'bg-success-subtle text-success' : ($row->account_status=='inactive' ? 'bg-secondary-subtle text-secondary' : 'bg-warning-subtle text-warning') }}">
                                    {{
                                        __(
                                            $row->account_status==='active'?'pos.status_active':
                                            ($row->account_status==='inactive'?'pos.status_inactive':'pos.status_suspended')
                                        )
                                    }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('customers.edit',$row->id) }}" class="btn btn-primary btn-sm rounded-pill m-1" data-bs-toggle="tooltip" title="{{ __('pos.btn_edit') }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <button onclick="confirmDelete({{ $row->id }})" class="btn btn-danger btn-sm rounded-pill m-1" data-bs-toggle="tooltip" title="{{ __('pos.btn_delete') }}">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="py-5 text-center text-muted">
                                    <i class="mdi mdi-account-off fs-1 d-block mb-2"></i>
                                    {{ __('pos.no_data') }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                <i class="mdi mdi-information-outline"></i>
                {{ $rows->firstItem() }}–{{ $rows->lastItem() }} / {{ $rows->total() }}
            </div>
            <div>{{ $rows->onEachSide(1)->links() }}</div>
        </div>
    </div>
</div>

{{-- sweetalert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id){
        Swal.fire({
            title: '{{ __("pos.alert_title") }}',
            text: '{{ __("pos.alert_text") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#0d6efd',
            confirmButtonText: '{{ __("pos.alert_confirm") }}',
            cancelButtonText: '{{ __("pos.alert_cancel") }}'
        }).then((r)=>{
            if(r.isConfirmed){
                Livewire.emit('deleteConfirmed', id);
                Swal.fire('{{ __("pos.deleted") }}', '{{ __("pos.msg_deleted_ok") }}', 'success');
            }
        })
    }
</script>

<style>
    .pretty-table thead th{ position: sticky; top: 0; z-index: 1; background: var(--bs-light,#f8f9fa); }
    .table-hover tbody tr:hover{ background: rgba(13,110,253,.03); }
    .fw-600{ font-weight:600; }
    .bg-success-subtle{ background:#d1e7dd!important; color:#0f5132!important; }
    .bg-secondary-subtle{ background:#e2e3e5!important; color:#41464b!important; }
    .bg-warning-subtle{ background:#fff3cd!important; color:#664d03!important; }
</style>
