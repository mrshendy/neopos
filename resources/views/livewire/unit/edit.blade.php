<div>
    <style>
        .preview-chip{
            display:inline-flex;align-items:center;gap:.35rem;
            background:#f8f9fa;border:1px solid rgba(0,0,0,.06);
            border-radius:999px;padding:.25rem .6rem;font-size:.8rem;color:#6c757d
        }
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .help{font-size:.8rem;color:#6c757d}
    </style>

    <div class="card border-0 shadow-lg rounded-4 stylish-card">
        <div class="card-body p-4">
            <form wire:submit.prevent="save" class="row g-4">

                {{-- الاسم (عربي) --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="mdi mdi-alphabetical"></i> {{ __('pos.name_ar') }}
                    </label>
                    <input type="text" class="form-control @error('name.ar') is-invalid @enderror" wire:model.lazy="name.ar">
                    <div class="help">{{ __('pos.hint_name_ar') }}</div>
                    @error('name.ar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    <div class="mt-2 preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $name['ar'] }}</div>
                </div>

                {{-- الاسم (إنجليزي) --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="mdi mdi-alphabetical-variant"></i> {{ __('pos.name_en') }}
                    </label>
                    <input type="text" class="form-control @error('name.en') is-invalid @enderror" wire:model.lazy="name.en">
                    <div class="help">{{ __('pos.hint_name_en') }}</div>
                    @error('name.en') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    <div class="mt-2 preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $name['en'] }}</div>
                </div>

                {{-- الوصف (عربي) --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="mdi mdi-text-long"></i> {{ __('pos.description_ar') }}
                    </label>
                    <textarea class="form-control" rows="3" wire:model.lazy="description.ar"></textarea>
                    <div class="help">{{ __('pos.hint_description_ar') }}</div>
                    <div class="mt-2 preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $description['ar'] }}</div>
                </div>

                {{-- الوصف (إنجليزي) --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="mdi mdi-text-long"></i> {{ __('pos.description_en') }}
                    </label>
                    <textarea class="form-control" rows="3" wire:model.lazy="description.en"></textarea>
                    <div class="help">{{ __('pos.hint_description_en') }}</div>
                    <div class="mt-2 preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $description['en'] }}</div>
                </div>

                {{-- المستوى --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="mdi mdi-view-grid-plus-outline"></i> {{ __('pos.level') }}
                    </label>
                    <select class="form-select @error('level') is-invalid @enderror" wire:model="level">
                        <option value="minor">{{ __('pos.minor') }}</option>
                        <option value="middle">{{ __('pos.middle') }}</option>
                        <option value="major">{{ __('pos.major') }}</option>
                    </select>
                    <div class="help">{{ __('pos.hint_level') }}</div>
                    @error('level') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    <div class="mt-2 preview-chip"><i class="mdi mdi-eye-outline"></i> {{ __('pos.'.$level) }}</div>
                </div>

                {{-- الحالة --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        <i class="mdi mdi-toggle-switch"></i> {{ __('pos.status') }}
                    </label>
                    <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                        <option value="active">{{ __('pos.active') }}</option>
                        <option value="inactive">{{ __('pos.inactive') }}</option>
                    </select>
                    <div class="help">{{ __('pos.hint_status') }}</div>
                    @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    <div class="mt-2 preview-chip"><i class="mdi mdi-eye-outline"></i> {{ __('pos.'.$status) }}</div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save-edit-outline"></i> {{ __('pos.btn_update') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
