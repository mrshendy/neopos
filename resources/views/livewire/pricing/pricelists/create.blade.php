<div class="page-wrap">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-plus-box"></i> {{ __('pos.price_lists_title') }}
        </div>

        <div class="card-body row g-3">
            <div class="col-md-6">
                <label class="form-label"><i class="mdi mdi-translate"></i> {{ __('pos.name_ar') }}</label>
                <input class="form-control" type="text" wire:model.defer="name.ar" placeholder="{{ __('pos.ph_name_ar') }}">
                <small class="text-muted">{{ __('pos.hint_name_ar') }}</small>
                @error('name.ar') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $name['ar'] }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="mdi mdi-translate-variant"></i> {{ __('pos.name_en') }}</label>
                <input class="form-control" type="text" wire:model.defer="name.en" placeholder="{{ __('pos.ph_name_en') }}">
                <small class="text-muted">{{ __('pos.hint_name_en') }}</small>
                @error('name.en') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $name['en'] }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label"><i class="mdi mdi-store"></i> قناة البيع</label>
                <select class="form-select" wire:model.defer="sales_channel_id">
                    <option value="">{{ __('pos.choose') }}</option>
                    @foreach($channels as $ch)
                        <option value="{{ $ch->id }}">{{ $ch->getTranslation('name', app()->getLocale()) }}</option>
                    @endforeach
                </select>
                <small class="text-muted">اختياري: ربط بالقناة.</small>
                @error('sales_channel_id') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $sales_channel_id }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label"><i class="mdi mdi-account-group-outline"></i> مجموعة العملاء</label>
                <select class="form-select" wire:model.defer="customer_group_id">
                    <option value="">{{ __('pos.choose') }}</option>
                    @foreach($groups as $gr)
                        <option value="{{ $gr->id }}">{{ $gr->getTranslation('name', app()->getLocale()) }}</option>
                    @endforeach
                </select>
                <small class="text-muted">اختياري: ربط بمجموعة عملاء.</small>
                @error('customer_group_id') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $customer_group_id }}</div>
            </div>

            <div class="col-md-2">
                <label class="form-label"><i class="mdi mdi-calendar-start"></i> من تاريخ</label>
                <input type="date" class="form-control" wire:model.defer="valid_from">
                <small class="text-muted">تاريخ بداية صلاحية القائمة.</small>
                @error('valid_from') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $valid_from }}</div>
            </div>

            <div class="col-md-2">
                <label class="form-label"><i class="mdi mdi-calendar-end"></i> إلى تاريخ</label>
                <input type="date" class="form-control" wire:model.defer="valid_to">
                <small class="text-muted">اتركه فارغًا لغير محددة.</small>
                @error('valid_to') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $valid_to }}</div>
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

            <div class="col-12 text-end">
                <button wire:click="save" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="mdi mdi-content-save"></i> {{ __('pos.btn_save') }}
                </button>
                <a href="{{ route('pricing.lists.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
                </a>
            </div>
        </div>
    </div>
</div>
