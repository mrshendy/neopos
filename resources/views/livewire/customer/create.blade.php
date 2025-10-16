<div>
    {{-- alerts --}}
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
            <i class="mdi mdi-account-plus-outline me-2"></i>
            {{ __('pos.title_customers_create') }}
        </h4>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
        </a>
    </div>

    <form wire:submit.prevent="save" class="card border-0 shadow-lg rounded-4">
        <div class="card-body">

            {{-- section 1: basic --}}
            <div class="border-bottom pb-3 mb-3">
                <h6 class="text-muted fw-bold mb-3">
                    <i class="mdi mdi-card-account-details-outline me-1"></i> {{ __('pos.section_basic') }}
                </h6>
                <div class="row g-3">

                    {{-- code --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="mdi mdi-shield-key-outline me-1"></i> {{ __('pos.f_code') }}
                        </label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                               wire:model.defer="code" placeholder="{{ __('pos.ph_code') }}" autocomplete="off">
                        <small class="text-muted">{{ __('pos.h_code') }}</small>
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if(!empty($code))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $code }}</span></div>
                        @endif
                    </div>

                    {{-- legal ar --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="mdi mdi-alphabetical me-1"></i> {{ __('pos.f_legal_name_ar') }}
                        </label>
                        <input type="text" class="form-control @error('legal_name.ar') is-invalid @enderror"
                               wire:model.defer="legal_name.ar" placeholder="{{ __('pos.ph_legal_name_ar') }}">
                        <small class="text-muted">{{ __('pos.h_legal_ar') }}</small>
                        @error('legal_name.ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if(!empty($legal_name['ar']))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $legal_name['ar'] }}</span></div>
                        @endif
                    </div>

                    {{-- legal en --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="mdi mdi-alphabetical-variant me-1"></i> {{ __('pos.f_legal_name_en') }}
                        </label>
                        <input type="text" class="form-control @error('legal_name.en') is-invalid @enderror"
                               wire:model.defer="legal_name.en" placeholder="{{ __('pos.ph_legal_name_en') }}">
                        <small class="text-muted">{{ __('pos.h_legal_en') }}</small>
                        @error('legal_name.en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if(!empty($legal_name['en']))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $legal_name['en'] }}</span></div>
                        @endif
                    </div>

                    {{-- type --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="mdi mdi-account-badge-outline me-1"></i> {{ __('pos.f_type') }}
                        </label>
                        <select class="form-select" wire:model="type">
                            <option value="b2b">B2B</option>
                            <option value="b2c">B2C</option>
                            <option value="individual">{{ __('pos.opt_individual') }}</option>
                            <option value="company">{{ __('pos.opt_company') }}</option>
                        </select>
                        <small class="text-muted">{{ __('pos.h_type') }}</small>
                        @if(!empty($type))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $type }}</span></div>
                        @endif
                    </div>

                    {{-- channel --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="mdi mdi-storefront-outline me-1"></i> {{ __('pos.f_channel') }}
                        </label>
                        <select class="form-select" wire:model="channel">
                            <option value="retail">{{ __('pos.opt_retail') }}</option>
                            <option value="wholesale">{{ __('pos.opt_wholesale') }}</option>
                            <option value="online">{{ __('pos.opt_online') }}</option>
                            <option value="pharmacy">{{ __('pos.opt_pharmacy') }}</option>
                        </select>
                        <small class="text-muted">{{ __('pos.h_channel') }}</small>
                        @if(!empty($channel))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $channel }}</span></div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- section 2: location --}}
            <div class="border-bottom pb-3 mb-3">
                <h6 class="text-muted fw-bold mb-3">
                    <i class="mdi mdi-map-outline me-1"></i> {{ __('pos.section_location') }}
                </h6>
                <div class="row g-3">

                    {{-- country --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="mdi mdi-earth me-1"></i> {{ __('pos.f_country') }}
                        </label>
                        <select class="form-select @error('country_id') is-invalid @enderror" wire:model="country_id">
                            <option value="">{{ __('pos.ph_country') }}</option>
                            @foreach ($country as $rg)
                                @php $nm = method_exists($rg,'getTranslation') ? $rg->getTranslation('name', app()->getLocale()) : ($rg->name ?? ''); @endphp
                                <option value="{{ $rg->id }}">{{ $nm }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">{{ __('pos.h_country') }}</small>
                        @error('country_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if(!empty($country_id))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $country_id }}</span></div>
                        @endif
                    </div>

                    {{-- governorate --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="mdi mdi-map me-1"></i> {{ __('pos.f_governorate') }}
                        </label>
                        <select class="form-select @error('governorate_id') is-invalid @enderror" wire:model="governorate_id">
                            <option value="">{{ __('pos.ph_governorate') }}</option>
                            @foreach ($governorates as $go)
                                @php $nm = method_exists($go,'getTranslation') ? $go->getTranslation('name', app()->getLocale()) : ($go->name ?? ''); @endphp
                                <option value="{{ $go->id }}">{{ $nm }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">{{ __('pos.h_governorate') }}</small>
                        @error('governorate_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if(!empty($governorate_id))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $governorate_id }}</span></div>
                        @endif
                    </div>

                    {{-- city select --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="mdi mdi-city-variant-outline me-1"></i> {{ __('pos.f_city_select') }}
                        </label>
                        <select class="form-select @error('city_id') is-invalid @enderror" wire:model="city_id">
                            <option value="">{{ __('pos.ph_city_select') }}</option>
                            @foreach ($cities as $ct)
                                <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">{{ __('pos.h_city_select') }}</small>
                        @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if(!empty($city_id))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $city_id }}</span></div>
                        @endif
                    </div>

                    {{-- area --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="mdi mdi-map-marker-radius-outline me-1"></i> {{ __('pos.f_area') }}
                        </label>
                        <select class="form-select @error('area_id') is-invalid @enderror" wire:model="area_id">
                            <option value="">{{ __('pos.ph_area') }}</option>
                            @foreach ($areas as $ar)
                                <option value="{{ $ar->id }}">{{ $ar->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">{{ __('pos.h_area') }}</small>
                        @error('area_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if(!empty($area_id))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $area_id }}</span></div>
                        @endif
                    </div>

                    {{-- city text --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="mdi mdi-form-textbox me-1"></i> {{ __('pos.f_city_text') }}
                        </label>
                        <input type="text" class="form-control" wire:model.defer="city" placeholder="{{ __('pos.ph_city_text') }}">
                        <small class="text-muted">{{ __('pos.h_city_text') }}</small>
                        @if(!empty($city))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $city }}</span></div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- section 3: contact & finance (no price list) --}}
            <div>
                <h6 class="text-muted fw-bold mb-3">
                    <i class="mdi mdi-phone-outline me-1"></i> {{ __('pos.section_contact_finance') }}
                </h6>
                <div class="row g-3">

                    {{-- phone --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="mdi mdi-phone-outline me-1"></i> {{ __('pos.f_phone') }}</label>
                        <input type="text" class="form-control" wire:model.defer="phone" placeholder="{{ __('pos.ph_phone') }}">
                        <small class="text-muted">{{ __('pos.h_phone') }}</small>
                        @if(!empty($phone))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $phone }}</span></div>
                        @endif
                    </div>

                    {{-- tax number --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="mdi mdi-receipt-text-outline me-1"></i> {{ __('pos.f_tax') }}</label>
                        <input type="text" class="form-control" wire:model.defer="tax_number" placeholder="{{ __('pos.ph_tax') }}">
                        <small class="text-muted">{{ __('pos.h_tax') }}</small>
                        @if(!empty($tax_number))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $tax_number }}</span></div>
                        @endif
                    </div>

                    {{-- credit limit --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="mdi mdi-credit-card-outline me-1"></i> {{ __('pos.f_credit_limit') }}</label>
                        <input type="number" min="0" step="0.01" class="form-control @error('credit_limit') is-invalid @enderror"
                               wire:model.defer="credit_limit" placeholder="0.00">
                        <small class="text-muted">{{ __('pos.h_credit_limit') }}</small>
                        @error('credit_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if(strlen((string)$credit_limit))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $credit_limit }}</span></div>
                        @endif
                    </div>

                    {{-- account status --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="mdi mdi-shield-check-outline me-1"></i> {{ __('pos.f_account_status') }}</label>
                        <select class="form-select" wire:model="account_status">
                            <option value="active">{{ __('pos.status_active') }}</option>
                            <option value="inactive">{{ __('pos.status_inactive') }}</option>
                            <option value="suspended">{{ __('pos.status_suspended') }}</option>
                        </select>
                        <small class="text-muted">{{ __('pos.h_account_status') }}</small>
                        @if(!empty($account_status))
                            <div class="preview-box mt-1"><i class="mdi mdi-eye-outline"></i><span>{{ $account_status }}</span></div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer d-flex gap-2">
            <button class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-content-save"></i> {{ __('pos.btn_save') }}
            </button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-close"></i> {{ __('pos.btn_cancel') }}
            </a>
            <div class="ms-auto d-flex align-items-center">
                <span class="text-muted small" wire:loading>
                    <i class="mdi mdi-loading mdi-spin me-1"></i> {{ __('pos.loading') }}
                </span>
            </div>
        </div>
    </form>
</div>

<style>
    .preview-box{
        border:1px dashed rgba(0,0,0,.15);
        background:#fff;
        border-radius:.65rem;
        padding:.35rem .5rem;
        display:inline-flex; align-items:center; gap:.35rem;
        font-size:.875rem; color:#334155;
        box-shadow:0 1px 2px rgba(16,24,40,.05);
    }
</style>
