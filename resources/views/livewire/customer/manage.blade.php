<div class="page-wrap">

    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-alert-circle-outline me-2"></i><strong>{{ __('pos.input_errors') }}</strong>
            <ul class="mb-0 mt-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .preview-chip{display:flex;align-items:center;gap:.5rem;background:#f8fafc;border:1px dashed rgba(0,0,0,.08);border-radius:12px;padding:.35rem .6rem;margin-top:.4rem;font-size:.85rem;color:#4b5563}
        .preview-chip i{opacity:.7}
        .form-hint{color:#6b7280;font-size:.8rem;margin-top:.2rem}
        .toolbar{display:flex;gap:.5rem;align-items:center}
        .toolbar .btn{border-radius:9999px}
        .badge-soft{background:#f8fafc;border:1px solid rgba(0,0,0,.05);color:#334155}
    </style>

    <div class="card shadow-sm rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
            <div>
                <i class="mdi mdi-account-box-outline me-1"></i>
                {{ $mode === 'edit' ? __('pos.customer_title_edit') : __('pos.customer_title_create') }}
                @if($mode === 'edit')
                    <span class="badge badge-soft ms-2">#{{ $customer_id }}</span>
                @endif
            </div>
            <div class="toolbar">
                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="mdi mdi-arrow-left"></i> {{ __('pos.back') }}
                </a>
                <button type="button" class="btn btn-outline-primary btn-sm px-3" wire:click="$refresh">
                    <i class="mdi mdi-refresh"></i> {{ __('pos.refresh') }}
                </button>
            </div>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="save" class="row g-3">

                {{-- Row 1 --}}
                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('pos.code') }}</label>
                    <input type="text" class="form-control" wire:model.lazy="code" placeholder="CUS-0001">
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $code ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_code') ?? '' }}</div>
                    @error('code') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('pos.type') }}</label>
                    <select class="form-select" wire:model="type">
                        <option value="person">{{ __('pos.person') }}</option>
                        <option value="company">{{ __('pos.company') }}</option>
                    </select>
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i>
                        <span>{{ $type === 'company' ? __('pos.company') : __('pos.person') }}</span>
                    </div>
                    <div class="form-hint">{{ __('pos.hint_type') ?? '' }}</div>
                    @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('pos.status') }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="active">{{ __('pos.status_active') }}</option>
                        <option value="inactive">{{ __('pos.status_inactive') }}</option>
                    </select>
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i>
                        <span>{{ $status === 'inactive' ? __('pos.status_inactive') : __('pos.status_active') }}</span>
                    </div>
                    <div class="form-hint">{{ __('pos.hint_status') ?? '' }}</div>
                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('pos.country') }}</label>
                    <input type="text" class="form-control" wire:model.lazy="country" placeholder="EG / SA / AE">
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $country ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_country') ?? '' }}</div>
                    @error('country') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Row 2 (Names) --}}
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.name_ar') }}</label>
                    <input type="text" class="form-control" wire:model.lazy="name_ar" placeholder="الاسم بالعربية">
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $name_ar ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_name_ar') ?? '' }}</div>
                    @error('name_ar') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.name_en') }}</label>
                    <input type="text" class="form-control" wire:model.lazy="name_en" placeholder="Name in English">
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $name_en ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_name_en') ?? '' }}</div>
                    @error('name_en') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Row 3 (Contact) --}}
                <div class="col-lg-4">
                    <label class="form-label mb-1">{{ __('pos.phone') }}</label>
                    <input type="text" class="form-control" wire:model.lazy="phone">
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $phone ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_phone') ?? '' }}</div>
                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-lg-4">
                    <label class="form-label mb-1">{{ __('pos.mobile') }}</label>
                    <input type="text" class="form-control" wire:model.lazy="mobile">
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $mobile ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_mobile') ?? '' }}</div>
                    @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-lg-4">
                    <label class="form-label mb-1">{{ __('pos.email') }}</label>
                    <input type="email" class="form-control" wire:model.lazy="email">
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $email ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_email') ?? '' }}</div>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Row 4 (City + Address) --}}
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.city_ar') }}</label>
                    <input type="text" class="form-control" wire:model.lazy="city_ar">
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $city_ar ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_city_ar') ?? '' }}</div>
                    @error('city_ar') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.city_en') }}</label>
                    <input type="text" class="form-control" wire:model.lazy="city_en">
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $city_en ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_city_en') ?? '' }}</div>
                    @error('city_en') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.address_ar') }}</label>
                    <textarea class="form-control" rows="2" wire:model.lazy="address_ar"></textarea>
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $address_ar ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_address_ar') ?? '' }}</div>
                    @error('address_ar') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.address_en') }}</label>
                    <textarea class="form-control" rows="2" wire:model.lazy="address_en"></textarea>
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $address_en ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_address_en') ?? '' }}</div>
                    @error('address_en') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Row 5 (Tax / CR) --}}
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.tax_no') }}</label>
                    <input type="text" class="form-control" wire:model.lazy="tax_no">
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $tax_no ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_tax_no') ?? '' }}</div>
                    @error('tax_no') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.commercial_no') }}</label>
                    <input type="text" class="form-control" wire:model.lazy="commercial_no">
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $commercial_no ?: '—' }}</span></div>
                    <div class="form-hint">{{ __('pos.hint_commercial_no') ?? '' }}</div>
                    @error('commercial_no') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Row 6 (Notes) --}}
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.notes_ar') }}</label>
                    <textarea class="form-control" rows="2" wire:model.lazy="notes_ar"></textarea>
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $notes_ar ?: '—' }}</span></div>
                </div>
                <div class="col-lg-6">
                    <label class="form-label mb-1">{{ __('pos.notes_en') }}</label>
                    <textarea class="form-control" rows="2" wire:model.lazy="notes_en"></textarea>
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i><span>{{ $notes_en ?: '—' }}</span></div>
                </div>

                {{-- Actions --}}
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm" wire:loading.attr="disabled">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                        <i class="mdi mdi-content-save-outline"></i>
                        {{ $mode === 'edit' ? __('pos.update') : __('pos.save') }}
                    </button>
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-arrow-left"></i> {{ __('pos.back') }}
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
