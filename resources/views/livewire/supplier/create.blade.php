<form wire:submit.prevent="save" class="row g-3">

    {{-- كود المورد --}}
    <div class="col-md-4">
        <label class="form-label fw-bold"><i class="mdi mdi-identifier me-1 text-primary"></i> {{ __('pos.supplier_code') }}</label>
        <input type="text" class="form-control" wire:model.defer="code" placeholder="{{ __('pos.ph_code') }}">
        <small class="text-muted d-block">{{ __('pos.desc_code') }}</small>
        @if($code)<div class="preview-box mt-1 p-2 border rounded">{{ $code }}</div>@endif
        @error('code') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    {{-- الاسم AR --}}
    <div class="col-md-4">
        <label class="form-label fw-bold"><i class="mdi mdi-alphabetical me-1 text-primary"></i> {{ __('pos.name_ar') }}</label>
        <input type="text" class="form-control" wire:model.defer="name.ar" placeholder="{{ __('pos.ph_name_ar') }}">
        <small class="text-muted d-block">{{ __('pos.desc_name_ar') }}</small>
        @if($name['ar'])<div class="preview-box mt-1 p-2 border rounded">{{ $name['ar'] }}</div>@endif
        @error('name.ar') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    {{-- الاسم EN --}}
    <div class="col-md-4">
        <label class="form-label fw-bold"><i class="mdi mdi-alphabetical-variant me-1 text-primary"></i> {{ __('pos.name_en') }}</label>
        <input type="text" class="form-control" wire:model.defer="name.en" placeholder="{{ __('pos.ph_name_en') }}">
        <small class="text-muted d-block">{{ __('pos.desc_name_en') }}</small>
        @if($name['en'])<div class="preview-box mt-1 p-2 border rounded">{{ $name['en'] }}</div>@endif
        @error('name.en') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    {{-- تجاري/ضريبي --}}
    <div class="col-md-6">
        <label class="form-label fw-bold"><i class="mdi mdi-briefcase me-1 text-primary"></i> {{ __('pos.commercial_register') }}</label>
        <input type="text" class="form-control" wire:model.defer="commercial_register" placeholder="{{ __('pos.ph_cr') }}">
        <small class="text-muted d-block">{{ __('pos.desc_cr') }}</small>
        @if($commercial_register)<div class="preview-box mt-1 p-2 border rounded">{{ $commercial_register }}</div>@endif
    </div>
    <div class="col-md-6">
        <label class="form-label fw-bold"><i class="mdi mdi-receipt-text-outline me-1 text-primary"></i> {{ __('pos.tax_number') }}</label>
        <input type="text" class="form-control" wire:model.defer="tax_number" placeholder="{{ __('pos.ph_tax') }}">
        <small class="text-muted d-block">{{ __('pos.desc_tax') }}</small>
        @if($tax_number)<div class="preview-box mt-1 p-2 border rounded">{{ $tax_number }}</div>@endif
    </div>

    {{-- تصنيف + شرط دفع --}}
    <div class="col-md-4">
        <label class="form-label fw-bold"><i class="mdi mdi-format-list-bulleted me-1 text-primary"></i> {{ __('pos.category') }}</label>
        <select class="form-select" wire:model="supplier_category_id">
            <option value="">{{ __('pos.ph_category') }}</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ app()->getLocale()=='ar'? $cat->getTranslation('name','ar') : $cat->getTranslation('name','en') }}</option>
            @endforeach
        </select>
        <small class="text-muted d-block">{{ __('pos.desc_category') }}</small>
        @error('supplier_category_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold"><i class="mdi mdi-cash-multiple me-1 text-primary"></i> {{ __('pos.payment_term') }}</label>
        <select class="form-select" wire:model="payment_term_id">
            <option value="">{{ __('pos.ph_payment_term') }}</option>
            @foreach($terms as $t)
            <option value="{{ $t->id }}">{{ app()->getLocale()=='ar'? $t->getTranslation('name','ar') : $t->getTranslation('name','en') }}</option>
            @endforeach
        </select>
        <small class="text-muted d-block">{{ __('pos.desc_payment_term') }}</small>
        @error('payment_term_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    {{-- جغرافيا: دولة/محافظة/مدينة/منطقة --}}
    <div class="col-md-3">
        <label class="form-label fw-bold"><i class="mdi mdi-earth me-1 text-primary"></i> {{ __('pos.country') }}</label>
        <select class="form-select" wire:model="country_id">
            <option value="">{{ __('pos.ph_country') }}</option>
            @foreach($countries as $c)
                <option value="{{ $c->id }}">{{ $c->name ?? $c->getTranslation('name','ar') ?? $c->getTranslation('name','en') }}</option>
            @endforeach
        </select>
        <small class="text-muted d-block">{{ __('pos.desc_country') }}</small>
        @error('country_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold"><i class="mdi mdi-map me-1 text-primary"></i> {{ __('pos.governorate') }}</label>
        <select class="form-select" wire:model="governorate_id">
            <option value="">{{ __('pos.ph_governorate') }}</option>
            @foreach($governorates as $g)
                <option value="{{ $g->id }}">{{ $g->name ?? $g->getTranslation('name','ar') ?? $g->getTranslation('name','en') }}</option>
            @endforeach
        </select>
        <small class="text-muted d-block">{{ __('pos.desc_governorate') }}</small>
        @error('governorate_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold"><i class="mdi mdi-home-city-outline me-1 text-primary"></i> {{ __('pos.city') }}</label>
        <select class="form-select" wire:model="city_id" {{ !$governorate_id ? 'disabled' : '' }}>
            <option value="">{{ __('pos.ph_city') }}</option>
            @foreach($cities as $c)
                <option value="{{ $c->id }}">{{ $c->name ?? $c->getTranslation('name','ar') ?? $c->getTranslation('name','en') }}</option>
            @endforeach
        </select>
        <small class="text-muted d-block">{{ __('pos.desc_city') }}</small>
        @error('city_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold"><i class="mdi mdi-map-marker-outline me-1 text-primary"></i> {{ __('pos.area') }}</label>
        <select class="form-select" wire:model="area_id" {{ !$city_id ? 'disabled' : '' }}>
            <option value="">{{ __('pos.ph_area') }}</option>
            @foreach($areas as $a)
                <option value="{{ $a->id }}">{{ $a->name ?? $a->getTranslation('name','ar') ?? $a->getTranslation('name','en') }}</option>
            @endforeach
        </select>
        <small class="text-muted d-block">{{ __('pos.desc_area') }}</small>
        @error('area_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    {{-- الحالة --}}
    <div class="col-md-3">
        <label class="form-label fw-bold"><i class="mdi mdi-toggle-switch me-1 text-primary"></i> {{ __('pos.status') }}</label>
        <select class="form-select" wire:model="status">
            <option value="active">{{ __('pos.status_active') }}</option>
            <option value="inactive">{{ __('pos.status_inactive') }}</option>
        </select>
        <small class="text-muted d-block">{{ __('pos.desc_status') }}</small>
        @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 d-flex gap-2 mt-2">
        <button class="btn btn-success rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-content-save-outline"></i> {{ __('pos.btn_save') }}
        </button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
        </a>
    </div>

    {{-- 🔔 تحذير داخل الكومبوننت --}}
    @if($status==='inactive')
        <div class="alert alert-warning rounded-3 shadow-sm mt-3">
            <i class="mdi mdi-alert-outline"></i> {{ __('pos.warn_inactive_supplier') }}
        </div>
    @endif
</form>
