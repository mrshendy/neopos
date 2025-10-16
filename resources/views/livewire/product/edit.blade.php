<div class="page-wrap">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .preview-chip{display:inline-flex;align-products:center;gap:.35rem;background:#f8f9fa;border:1px solid rgba(0,0,0,.06);border-radius:999px;padding:.25rem .6rem;font-size:.8rem;color:#6c757d}
        .field-block{position:relative}
        .field-block label{font-weight:600}
        .help{font-size:.8rem;color:#6c757d}
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
    </style>

    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-pencil"></i> {{ __('pos.product_edit_title') ?? 'تعديل المنتج' }}
        </div>

        <div class="card-body row g-3">
            {{-- صورة المنتج --}}
            <div class="col-12 field-block">
                <label class="form-label"><i class="mdi mdi-image-multiple-outline"></i> {{ __('pos.product_image') ?? 'صورة المنتج' }}</label>
                <div class="d-flex flex-wrap gap-3 align-products-start">
                    <div>
                        @php
                            $preview = null;
                            if ($image ?? null) { $preview = $image->temporaryUrl(); }
                            elseif (!empty($image_path)) { $preview = \Illuminate\Support\Facades\Storage::disk('public')->exists($image_path) ? asset('attachments/'.$image_path) : null; }
                        @endphp
                        @if($preview)
                            <img src="{{ $preview }}" alt="preview" class="rounded-4 border" style="width:160px;height:160px;object-fit:cover;">
                        @else
                            <div class="d-flex align-products-center justify-content-center rounded-4 border bg-light"
                                 style="width:160px;height:160px;">
                                <i class="mdi mdi-image-off-outline fs-1 text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <input type="file" class="form-control" wire:model="image" accept="image/*">
                        <small class="help">{{ __('pos.hint_image') ?? 'الحد الأقصى 2MB. الامتدادات: jpg, jpeg, png, webp.' }}</small>
                        <div class="mt-2 d-flex gap-2">
                            @if($image || $image_path)
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill" wire:click="removeImage">
                                    <i class="mdi mdi-trash-can-outline"></i> {{ __('pos.remove_image') ?? 'إزالة الصورة' }}
                                </button>
                            @endif
                        </div>
                        @error('image') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                    </div>
                </div>
                <hr class="my-2">
            </div>

            {{-- SKU --}}
            <div class="col-md-4 field-block">
                <label class="form-label"><i class="mdi mdi-identifier"></i> {{ __('pos.sku') }}</label>
                <input type="text" class="form-control" wire:model.live="sku" placeholder="{{ __('pos.ph_sku') }}">
                <div class="help">{{ __('pos.hint_sku') }}</div>
                @error('sku') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="mt-1"><span class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $sku ?? '' }}</span></div>
            </div>

            {{-- Barcode --}}
            <div class="col-md-4 field-block">
                <label class="form-label"><i class="mdi mdi-barcode"></i> {{ __('pos.barcode') }}</label>
                <input type="text" class="form-control" wire:model.live="barcode" placeholder="{{ __('pos.ph_barcode') }}">
                <div class="help">{{ __('pos.hint_barcode') }}</div>
                @error('barcode') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="mt-1"><span class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $barcode ?? '' }}</span></div>
            </div>

            {{-- Name AR --}}
            <div class="col-md-4 field-block">
                <label class="form-label"><i class="mdi mdi-translate"></i> {{ __('pos.name_ar') }}</label>
                <input type="text" class="form-control" wire:model.live="name.ar" placeholder="{{ __('pos.ph_name_ar') }}">
                <div class="help">{{ __('pos.hint_name_ar') }}</div>
                @error('name.ar') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="mt-1"><span class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $name['ar'] ?? '' }}</span></div>
            </div>

            {{-- Name EN --}}
            <div class="col-md-4 field-block">
                <label class="form-label"><i class="mdi mdi-translate-variant"></i> {{ __('pos.name_en') }}</label>
                <input type="text" class="form-control" wire:model.live="name.en" placeholder="{{ __('pos.ph_name_en') }}">
                <div class="help">{{ __('pos.hint_name_en') }}</div>
                @error('name.en') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="mt-1"><span class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $name['en'] ?? '' }}</span></div>
            </div>

            {{-- Unit --}}
            <div class="col-md-4 field-block">
                <label class="form-label"><i class="mdi mdi-weight-kilogram"></i> {{ __('pos.unit') }}</label>
                <select class="form-select" wire:model.live="unit_id">
                    <option value="">{{ __('pos.choose') }}</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}">{{ $u->getTranslation('name', app()->getLocale()) }}</option>
                    @endforeach
                </select>
                <div class="help">{{ __('pos.hint_unit') }}</div>
                @error('unit_id') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="mt-1"><span class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $unit_id ?? '' }}</span></div>
            </div>

            {{-- Category --}}
            <div class="col-md-4 field-block">
                <label class="form-label"><i class="mdi mdi-shape"></i> {{ __('pos.category') }}</label>
                <select class="form-select" wire:model.live="category_id">
                    <option value="">{{ __('pos.choose') }}</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->getTranslation('name', app()->getLocale()) }}</option>
                    @endforeach
                </select>
                <div class="help">{{ __('pos.hint_category') }}</div>
                @error('category_id') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="mt-1"><span class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $category_id ?? '' }}</span></div>
            </div>

            {{-- Tax --}}
            <div class="col-md-3 field-block">
                <label class="form-label"><i class="mdi mdi-percent"></i> {{ __('pos.tax_rate') }}</label>
                <input type="number" step="0.001" class="form-control" wire:model.live="tax_rate">
                <div class="help">{{ __('pos.hint_tax_rate') }}</div>
                @error('tax_rate') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="mt-1"><span class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $tax_rate ?? '' }}</span></div>
            </div>

            {{-- Status --}}
            <div class="col-md-3 field-block">
                <label class="form-label"><i class="mdi mdi-toggle-switch"></i> {{ __('pos.status') }}</label>
                <select class="form-select" wire:model.live="status">
                    <option value="active">{{ __('pos.status_active') }}</option>
                    <option value="inactive">{{ __('pos.status_inactive') }}</option>
                </select>
                <div class="help">{{ __('pos.hint_status') }}</div>
                <div class="mt-1"><span class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $status ?? '' }}</span></div>
            </div>

            {{-- Flags --}}
            <div class="col-md-3 field-block">
                <div class="form-check form-switch mt-4">
                    <input class="form-check-input" type="checkbox" role="switch" id="batchSwitch" wire:model.live="track_batch">
                    <label class="form-check-label" for="batchSwitch">{{ __('pos.track_batch') }}</label>
                </div>
                <div class="help">{{ __('pos.hint_track_batch') ?? 'فعّلها للأصناف ذات الدُفعات.' }}</div>
            </div>

            <div class="col-md-3 field-block">
                <div class="form-check form-switch mt-4">
                    <input class="form-check-input" type="checkbox" role="switch" id="serialSwitch" wire:model.live="track_serial">
                    <label class="form-check-label" for="serialSwitch">{{ __('pos.track_serial') }}</label>
                </div>
                <div class="help">{{ __('pos.hint_track_serial') ?? 'فعّلها للأجهزة التي تتطلب رقم تسلسلي.' }}</div>
            </div>

            {{-- Reorder --}}
            <div class="col-md-3 field-block">
                <label class="form-label"><i class="mdi mdi-bell-outline"></i> {{ __('pos.reorder_level') }}</label>
                <input type="number" step="1" class="form-control" wire:model.live="reorder_level" placeholder="مثال: 10">
                @error('reorder_level') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="help">{{ __('pos.hint_reorder') ?? 'يصدر النظام تنبيهًا عند نزول الرصيد دونه.' }}</div>
            </div>

            {{-- Description AR --}}
            <div class="col-12 field-block">
                <label class="form-label"><i class="mdi mdi-text-box-outline"></i> {{ __('pos.description_ar') }}</label>
                <textarea class="form-control" rows="2" wire:model.live="description.ar" placeholder="{{ __('pos.ph_desc_ar') }}"></textarea>
                <div class="help">{{ __('pos.hint_desc_ar') }}</div>
                @error('description.ar') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="mt-1"><span class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $description['ar'] ?? '' }}</span></div>
            </div>

            {{-- Description EN --}}
            <div class="col-12 field-block">
                <label class="form-label"><i class="mdi mdi-text-box-outline"></i> {{ __('pos.description_en') }}</label>
                <textarea class="form-control" rows="2" wire:model.live="description.en" placeholder="{{ __('pos.ph_desc_en') }}"></textarea>
                <div class="help">{{ __('pos.hint_desc_en') }}</div>
                @error('description.en') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="mt-1"><span class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $description['en'] ?? '' }}</span></div>
            </div>

            <div class="col-12 text-end">
                <button type="button" wire:click="save" class="btn btn-success rounded-pill px-4 shadow-sm" wire:loading.attr="disabled">
                    <i class="mdi mdi-content-save"></i> {{ __('pos.btn_save') }}
                </button>
                <a href="{{ route('product.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
                </a>
            </div>
        </div>
    </div>
</div>
