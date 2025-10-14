<div class="page-wrap">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-plus-box"></i> {{ __('pos.category_create_title') }}
        </div>

        <div class="card-body row g-3">
            <div class="col-md-6">
                <label class="form-label"><i class="mdi mdi-translate"></i> {{ __('pos.name_ar') }}</label>
                <input type="text" class="form-control" wire:model.defer="name.ar">
                <small class="text-muted">{{ __('pos.hint_name_ar') }}</small>
                @error('name.ar') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $name['ar'] }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="mdi mdi-translate-variant"></i> {{ __('pos.name_en') }}</label>
                <input type="text" class="form-control" wire:model.defer="name.en">
                <small class="text-muted">{{ __('pos.hint_name_en') }}</small>
                @error('name.en') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $name['en'] }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="mdi mdi-text-box-outline"></i> {{ __('pos.description_ar') }}</label>
                <textarea class="form-control" rows="2" wire:model.defer="description.ar"></textarea>
                <small class="text-muted">{{ __('pos.hint_description_ar') }}</small>
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $description['ar'] }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="mdi mdi-text-box-outline"></i> {{ __('pos.description_en') }}</label>
                <textarea class="form-control" rows="2" wire:model.defer="description.en"></textarea>
                <small class="text-muted">{{ __('pos.hint_description_en') }}</small>
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $description['en'] }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label"><i class="mdi mdi-toggle-switch"></i> {{ __('pos.status') }}</label>
                <select class="form-select" wire:model.defer="status">
                    <option value="active">{{ __('pos.status_active') }}</option>
                    <option value="inactive">{{ __('pos.status_inactive') }}</option>
                </select>
            </div>

            <div class="col-12 text-end">
                <button wire:click="save" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="mdi mdi-content-save"></i> {{ __('pos.btn_save') }}
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
                </a>
            </div>
        </div>
    </div>
</div>
