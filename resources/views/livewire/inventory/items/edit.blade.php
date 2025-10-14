<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-pencil-outline me-2"></i> {{ __('pos.inventory_items_title') }}
        </div>
        <div class="card-body p-4">
            <form wire:submit.prevent="save" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label fw-bold"><i class="mdi mdi-alphabetical"></i> {{ __('pos.item_name_ar') }}</label>
                    <input type="text" class="form-control" wire:model.defer="name.ar" placeholder="{{ __('pos.ph_item_name_ar') }}">
                    <small class="text-muted d-block">{{ __('pos.hint_item_name') }}</small>
                    <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ $name['ar'] }}</div>
                    @error('name.ar') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold"><i class="mdi mdi-alphabetical-variant"></i> {{ __('pos.item_name_en') }}</label>
                    <input type="text" class="form-control" wire:model.defer="name.en" placeholder="{{ __('pos.ph_item_name_en') }}">
                    <small class="text-muted d-block">{{ __('pos.hint_item_name') }}</small>
                    <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ $name['en'] }}</div>
                    @error('name.en') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="mdi mdi-barcode"></i> {{ __('pos.sku') }}</label>
                    <input type="text" class="form-control" wire:model.defer="sku" placeholder="{{ __('pos.ph_sku') }}">
                    <small class="text-muted d-block">{{ __('pos.hint_sku') }}</small>
                    <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ $sku }}</div>
                    @error('sku') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="mdi mdi-gauge"></i> {{ __('pos.uom') }}</label>
                    <input type="text" class="form-control" wire:model.defer="uom" placeholder="{{ __('pos.ph_uom') }}">
                    <small class="text-muted d-block">{{ __('pos.hint_uom') }}</small>
                    <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ $uom }}</div>
                    @error('uom') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">{{ __('pos.status') }}</label>
                    <select class="form-select" wire:model.defer="status">
                        <option value="active">{{ __('pos.active') }}</option>
                        <option value="inactive">{{ __('pos.inactive') }}</option>
                    </select>
                    <small class="text-muted d-block">{{ __('pos.hint_status') }}</small>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="batchSwitch" wire:model.defer="track_batch">
                        <label class="form-check-label" for="batchSwitch">{{ __('pos.track_batch') }}</label>
                    </div>
                    <small class="text-muted d-block">{{ __('pos.hint_track_batch') }}</small>
                    <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ $track_batch ? __('pos.yes') : __('pos.no') }}</div>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="serialSwitch" wire:model.defer="track_serial">
                        <label class="form-check-label" for="serialSwitch">{{ __('pos.track_serial') }}</label>
                    </div>
                    <small class="text-muted d-block">{{ __('pos.hint_track_serial') }}</small>
                    <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ $track_serial ? __('pos.yes') : __('pos.no') }}</div>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save"></i> {{ __('pos.btn_save') }}
                    </button>
                    <a href="{{ route('inventory.items.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-close"></i> {{ __('pos.btn_cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
