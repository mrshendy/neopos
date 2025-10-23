<div class="container-fluid px-3">
    {{-- Inline style بسيط لعنصر المعاينة --}}
    <style>
        .preview-chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: #f8f9fa;
            border: 1px solid rgba(0, 0, 0, .06);
            border-radius: 999px;
            padding: .25rem .6rem;
            font-size: .8rem;
            color: #6c757d
        }

        .stylish-card {
            border: 1px solid rgba(0, 0, 0, .06)
        }

        .field-block {
            position: relative
        }

        .field-block label {
            font-weight: 600
        }

        .help {
            font-size: .8rem;
            color: #6c757d
        }
    </style>

    <form wire:submit.prevent="save" class="row g-3">
        <div class="col-12">
            <div class="card rounded-4 shadow-sm stylish-card">
                <div class="card-header bg-light fw-bold">
                    {{ __('branches.create_title') }}
                </div>

                <div class="card-body">
                    {{-- الاسم --}}
                    <div class="mb-3 field-block">
                        <label class="form-label">
                            {{ __('branches.field_name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" wire:model.defer="name"
                            placeholder="{{ __('branches.ph_name') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        @if (strlen($name ?? ''))
                            <div class="mt-2 preview-chip">
                                <i class="mdi mdi-eye-outline"></i>
                                <span class="small">{{ __('branches.preview_value') }}:</span>
                                <strong>{{ $name }}</strong>
                            </div>
                        @endif
                    </div>

                    {{-- العنوان --}}
                    <div class="mb-3 field-block">
                        <label class="form-label">{{ __('branches.field_address') }}</label>
                        <input type="text" class="form-control" wire:model.defer="address"
                            placeholder="{{ __('branches.ph_address') }}">
                        @error('address')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        @if (strlen($address ?? ''))
                            <div class="mt-2 preview-chip">
                                <i class="mdi mdi-eye-outline"></i>
                                <span class="small">{{ __('branches.preview_value') }}:</span>
                                <strong>{{ $address }}</strong>
                            </div>
                        @endif
                    </div>

                    {{-- الحالة --}}
                    <div class="mb-3 field-block">
                        <label class="form-label d-block">{{ __('branches.field_status') }}</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch"
                                wire:model="status">
                            <label class="form-check-label" for="statusSwitch">
                                {{ $status ? __('branches.status_active') : __('branches.status_inactive') }}
                            </label>
                        </div>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        <div class="mt-2 preview-chip">
                            <i class="mdi mdi-eye-outline"></i>
                            <span class="small">{{ __('branches.preview_value') }}:</span>
                            <strong>{{ $status ? __('branches.status_active') : __('branches.status_inactive') }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex gap-2">
                    <button class="btn btn-success rounded-pill px-4 shadow-sm" type="submit">
                        <i class="mdi mdi-content-save"></i> {{ __('branches.btn_save') }}
                    </button>
                    <a href="{{ route('branches.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                        {{ __('branches.btn_back') }}
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
