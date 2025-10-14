<div class="page-wrap">
    <h4 class="mb-3"><i class="mdi mdi-format-list-bulleted-square me-2"></i>{{ __('pos.price_lists_create_title') ?? 'إنشاء قائمة أسعار' }}</h4>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @error('save') <div class="alert alert-danger">{{ $message }}</div> @enderror

    <style>
        .preview-chip{display:inline-flex;align-items:center;gap:.35rem;background:#f8f9fa;border:1px solid rgba(0,0,0,.06);border-radius:999px;padding:.25rem .6rem;font-size:.8rem;color:#6c757d}
        .field-block label{font-weight:600}
        .help{font-size:.8rem;color:#6c757d}
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .table td, .table th{vertical-align:middle}
    </style>

    <form wire:submit.prevent="save">
        <div class="card shadow-sm rounded-4 stylish-card">
            <div class="card-header bg-light fw-bold">
                <i class="mdi mdi-information-outline"></i> {{ __('pos.basic_info') ?? 'البيانات الأساسية' }}
            </div>

            <div class="card-body row g-3">
                <div class="col-md-6 field-block">
                    <label class="form-label"><i class="mdi mdi-translate"></i> {{ __('pos.name_ar') }}</label>
                    <input class="form-control" type="text" wire:model.defer="name.ar" placeholder="{{ __('pos.ph_name_ar') }}">
                    <small class="help">{{ __('pos.hint_name_ar') }}</small>
                    @error('name.ar') <div class="text-danger small">{{ $message }}</div> @enderror
                    <div class="mt-1"><span class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $name['ar'] ?? '' }}</span></div>
                </div>

                <div class="col-md-6 field-block">
                    <label class="form-label"><i class="mdi mdi-translate-variant"></i> {{ __('pos.name_en') }}</label>
                    <input class="form-control" type="text" wire:model.defer="name.en" placeholder="{{ __('pos.ph_name_en') }}">
                    <small class="help">{{ __('pos.hint_name_en') }}</small>
                    @error('name.en') <div class="text-danger small">{{ $message }}</div> @enderror
                    <div class="mt-1"><span class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $name['en'] ?? '' }}</span></div>
                </div>

                <div class="col-md-3 field-block">
                    <label class="form-label"><i class="mdi mdi-calendar-start"></i> {{ __('pos.valid_from') ?? 'من تاريخ' }}</label>
                    <input type="date" class="form-control" wire:model.defer="valid_from">
                    <small class="help">{{ __('pos.hint_valid_from') ?? 'تاريخ بداية صلاحية القائمة.' }}</small>
                    @error('valid_from') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 field-block">
                    <label class="form-label"><i class="mdi mdi-calendar-end"></i> {{ __('pos.valid_to') ?? 'إلى تاريخ' }}</label>
                    <input type="date" class="form-control" wire:model.defer="valid_to">
                    <small class="help">{{ __('pos.hint_valid_to') ?? 'اتركه فارغًا لغير محددة.' }}</small>
                    @error('valid_to') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 field-block">
                    <label class="form-label"><i class="mdi mdi-toggle-switch"></i> {{ __('pos.status') }}</label>
                    <select class="form-select" wire:model.defer="status">
                        <option value="active">{{ __('pos.status_active') ?? 'نشط' }}</option>
                        <option value="inactive">{{ __('pos.status_inactive') ?? 'غير نشط' }}</option>
                    </select>
                    <small class="help">{{ __('pos.hint_status') }}</small>
                </div>
            </div>
        </div>

        {{-- بنود الأسعار --}}
        <div class="card shadow-sm rounded-4 stylish-card mt-3">
            <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
                <span><i class="mdi mdi-cash"></i> {{ __('pos.price_items') ?? 'بنود الأسعار' }}</span>
                <button type="button" class="btn btn-sm btn-primary rounded-pill" wire:click="addItem">
                    <i class="mdi mdi-plus"></i> {{ __('pos.add_row') ?? 'إضافة بند' }}
                </button>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:25%">{{ __('pos.product') ?? 'المنتج' }}</th>
                                <th style="width:12%">{{ __('pos.price') ?? 'السعر' }}</th>
                                <th style="width:12%">{{ __('pos.min_qty') ?? 'الحد الأدنى' }}</th>
                                <th style="width:12%">{{ __('pos.max_qty') ?? 'الحد الأقصى' }}</th>
                                <th style="width:14%">{{ __('pos.valid_from') ?? 'من تاريخ' }}</th>
                                <th style="width:14%">{{ __('pos.valid_to') ?? 'إلى تاريخ' }}</th>
                                <th style="width:11%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $idx => $it)
                                <tr>
                                    <td>
                                        <select class="form-select" wire:model.defer="items.{{ $idx }}.product_id">
                                            <option value="">{{ __('pos.choose') ?? 'اختر' }}</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}">
                                                    {{ method_exists($p,'getTranslation') ? $p->getTranslation('name', app()->getLocale()) : ($p->name ?? ('#'.$p->id)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("items.$idx.product_id") <div class="text-danger small">{{ $message }}</div> @enderror
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" class="form-control" wire:model.defer="items.{{ $idx }}.price">
                                        @error("items.$idx.price") <div class="text-danger small">{{ $message }}</div> @enderror
                                    </td>
                                    <td>
                                        <input type="number" min="1" class="form-control" wire:model.defer="items.{{ $idx }}.min_qty">
                                        @error("items.$idx.min_qty") <div class="text-danger small">{{ $message }}</div> @enderror
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" wire:model.defer="items.{{ $idx }}.max_qty">
                                        @error("items.$idx.max_qty") <div class="text-danger small">{{ $message }}</div> @enderror
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" wire:model.defer="items.{{ $idx }}.valid_from">
                                        @error("items.$idx.valid_from") <div class="text-danger small">{{ $message }}</div> @enderror
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" wire:model.defer="items.{{ $idx }}.valid_to">
                                        @error("items.$idx.valid_to") <div class="text-danger small">{{ $message }}</div> @enderror
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" wire:click="removeItem({{ $idx }})">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            @error('items') <tr><td colspan="7"><div class="text-danger small px-3 pb-3">{{ $message }}</div></td></tr> @enderror
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- أزرار --}}
        <div class="mt-3 d-flex justify-content-between">
            <a href="{{ route('pricing.lists.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') ?? 'رجوع' }}
            </a>
            <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm" wire:loading.attr="disabled">
                <i class="mdi mdi-content-save"></i>
                <span wire:loading.remove>{{ __('pos.btn_save') ?? 'حفظ' }}</span>
                <span wire:loading>{{ __('pos.saving') ?? 'جارٍ الحفظ...' }}</span>
            </button>
        </div>
    </form>
</div>
