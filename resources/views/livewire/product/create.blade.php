<div class="page-wrap">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="card-header bg-light fw-bold"><i class="mdi mdi-plus-box"></i> {{ __('pos.product_create_title') }}</div>
        <div class="card-body row g-3">

            <div class="col-md-4">
                <label class="form-label"><i class="mdi mdi-identifier"></i> {{ __('pos.sku') }}</label>
                <input type="text" class="form-control" wire:model.defer="sku" placeholder="{{ __('pos.ph_sku') }}">
                <small class="text-muted">{{ __('pos.hint_sku') }}</small>
                @error('sku') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $sku }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label"><i class="mdi mdi-barcode"></i> {{ __('pos.barcode') }}</label>
                <input type="text" class="form-control" wire:model.defer="barcode" placeholder="{{ __('pos.ph_barcode') }}">
                <small class="text-muted">{{ __('pos.hint_barcode') }}</small>
                @error('barcode') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $barcode }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label"><i class="mdi mdi-translate"></i> {{ __('pos.name_ar') }}</label>
                <input type="text" class="form-control" wire:model.defer="name.ar" placeholder="{{ __('pos.ph_name_ar') }}">
                <small class="text-muted">{{ __('pos.hint_name_ar') }}</small>
                @error('name.ar') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $name['ar'] }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label"><i class="mdi mdi-translate-variant"></i> {{ __('pos.name_en') }}</label>
                <input type="text" class="form-control" wire:model.defer="name.en" placeholder="{{ __('pos.ph_name_en') }}">
                <small class="text-muted">{{ __('pos.hint_name_en') }}</small>
                @error('name.en') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $name['en'] }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label"><i class="mdi mdi-weight-kilogram"></i> {{ __('pos.unit') }}</label>
                <select class="form-select" wire:model.defer="unit_id">
                    <option value="">{{ __('pos.choose') }}</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}">{{ $u->getTranslation('name', app()->getLocale()) }}</option>
                    @endforeach
                </select>
                <small class="text-muted">{{ __('pos.hint_unit') }}</small>
                @error('unit_id') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $unit_id }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label"><i class="mdi mdi-shape"></i> {{ __('pos.category') }}</label>
                <select class="form-select" wire:model.defer="category_id">
                    <option value="">{{ __('pos.choose') }}</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->getTranslation('name', app()->getLocale()) }}</option>
                    @endforeach
                </select>
                <small class="text-muted">{{ __('pos.hint_category') }}</small>
                @error('category_id') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $category_id }}</div>
            </div>

            <div class="col-md-3">
                <label class="form-label"><i class="mdi mdi-percent"></i> {{ __('pos.tax_rate') }}</label>
                <input type="number" step="0.001" class="form-control" wire:model.defer="tax_rate">
                <small class="text-muted">{{ __('pos.hint_tax_rate') }}</small>
                @error('tax_rate') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $tax_rate }}</div>
            </div>

            <div class="col-md-3">
                <label class="form-label"><i class="mdi mdi-warehouse"></i> {{ __('pos.opening_stock') }}</label>
                <input type="number" class="form-control" wire:model.defer="opening_stock">
                <small class="text-muted">{{ __('pos.hint_opening_stock') }}</small>
                @error('opening_stock') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $opening_stock }}</div>
            </div>

            <div class="col-md-3">
                <label class="form-label"><i class="mdi mdi-toggle-switch"></i> {{ __('pos.status') }}</label>
                <select class="form-select" wire:model.defer="status">
                    <option value="active">{{ __('pos.status_active') }}</option>
                    <option value="inactive">{{ __('pos.status_inactive') }}</option>
                </select>
                <small class="text-muted">{{ __('pos.hint_status') }}</small>
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $status }}</div>
            </div>

            <div class="col-12">
                <label class="form-label"><i class="mdi mdi-text-box-outline"></i> {{ __('pos.description_ar') }}</label>
                <textarea class="form-control" rows="2" wire:model.defer="description.ar" placeholder="{{ __('pos.ph_desc_ar') }}"></textarea>
                <small class="text-muted">{{ __('pos.hint_desc_ar') }}</small>
                @error('description.ar') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $description['ar'] }}</div>
            </div>

            <div class="col-12">
                <label class="form-label"><i class="mdi mdi-text-box-outline"></i> {{ __('pos.description_en') }}</label>
                <textarea class="form-control" rows="2" wire:model.defer="description.en" placeholder="{{ __('pos.ph_desc_en') }}"></textarea>
                <small class="text-muted">{{ __('pos.hint_desc_en') }}</small>
                @error('description.en') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $description['en'] }}</div>
            </div>

            <div class="col-12 text-end">
                <button wire:click="save" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="mdi mdi-content-save"></i> {{ __('pos.btn_save') }}
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
                </a>
            </div>

        </div>
    </div>
</div>
