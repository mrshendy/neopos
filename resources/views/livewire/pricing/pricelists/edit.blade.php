<div class="page-wrap">
    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="card shadow-sm rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-pencil"></i> {{ __('pos.price_lists_title') }}
        </div>
        <div class="card-body row g-3">
            <div class="col-md-6">
                <label class="form-label"><i class="mdi mdi-translate"></i> {{ __('pos.name_ar') }}</label>
                <input class="form-control" type="text" wire:model.defer="name.ar">
                <small class="text-muted">{{ __('pos.hint_name_ar') }}</small>
                @error('name.ar') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $name['ar'] }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label"><i class="mdi mdi-translate-variant"></i> {{ __('pos.name_en') }}</label>
                <input class="form-control" type="text" wire:model.defer="name.en">
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
                <small class="text-muted">اختياري.</small>
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
                <small class="text-muted">اختياري.</small>
                @error('customer_group_id') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $customer_group_id }}</div>
            </div>

            <div class="col-md-2">
                <label class="form-label"><i class="mdi mdi-calendar-start"></i> من</label>
                <input type="date" class="form-control" wire:model.defer="valid_from">
                <small class="text-muted">تاريخ البداية.</small>
                @error('valid_from') <div class="text-danger small">{{ $message }}</div> @enderror
                <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $valid_from }}</div>
            </div>
            <div class="col-md-2">
                <label class="form-label"><i class="mdi mdi-calendar-end"></i> إلى</label>
                <input type="date" class="form-control" wire:model.defer="valid_to">
                <small class="text-muted">اترك فارغًا لغير محددة.</small>
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
                <button wire:click="saveHeader" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="mdi mdi-content-save"></i> {{ __('pos.btn_save') }}
                </button>
                <a href="{{ route('pricing.lists.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
            <span><i class="mdi mdi-currency-usd"></i> عناصر الأسعار</span>
            <button wire:click="resetItemForm" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="mdi mdi-broom"></i> تفريغ النموذج
            </button>
        </div>
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label"><i class="mdi mdi-package-variant-closed"></i> المنتج</label>
                    <select class="form-select" wire:model.defer="product_id">
                        <option value="">{{ __('pos.choose') }}</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->sku }} — {{ $p->getTranslation('name', app()->getLocale()) }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">اختر المنتج المطلوب تسعيره.</small>
                    @error('product_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $product_id }}</div>
                </div>

                <div class="col-md-2">
                    <label class="form-label"><i class="mdi mdi-currency-usd"></i> السعر</label>
                    <input type="number" step="0.01" class="form-control" wire:model.defer="price">
                    <small class="text-muted">لا يمكن أن يكون أقل من 0.</small>
                    @error('price') <div class="text-danger small">{{ $message }}</div> @enderror
                    <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $price }}</div>
                </div>

                <div class="col-md-2">
                    <label class="form-label"><i class="mdi mdi-counter"></i> أقل كمية</label>
                    <input type="number" class="form-control" wire:model.defer="min_qty">
                    <small class="text-muted">القيمة الافتراضية 1.</small>
                    @error('min_qty') <div class="text-danger small">{{ $message }}</div> @enderror
                    <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $min_qty }}</div>
                </div>

                <div class="col-md-2">
                    <label class="form-label"><i class="mdi mdi-counter"></i> أكبر كمية</label>
                    <input type="number" class="form-control" wire:model.defer="max_qty">
                    <small class="text-muted">اختياري (≥ أقل كمية).</small>
                    @error('max_qty') <div class="text-danger small">{{ $message }}</div> @enderror
                    <div class="preview mt-1"><i class="mdi mdi-eye-outline"></i> {{ $max_qty }}</div>
                </div>

                <div class="col-md-1">
                    <label class="form-label"><i class="mdi mdi-calendar-start"></i> من</label>
                    <input type="date" class="form-control" wire:model.defer="item_valid_from">
                    @error('item_valid_from') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-1">
                    <label class="form-label"><i class="mdi mdi-calendar-end"></i> إلى</label>
                    <input type="date" class="form-control" wire:model.defer="item_valid_to">
                    @error('item_valid_to') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12 text-end">
                    <button wire:click="saveItem" class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save"></i> {{ $price_item_id ? 'تحديث العنصر' : 'إضافة العنصر' }}
                    </button>
                </div>
            </div>

            <div class="divider my-3" style="height:1px;background:rgba(0,0,0,.08)"></div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>المنتج</th>
                            <th>السعر</th>
                            <th>الكمية (من/إلى)</th>
                            <th>الفترة</th>
                            <th class="text-end">{{ __('pos.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $i)
                            <tr>
                                <td>{{ $i['id'] }}</td>
                                <td>{{ $i['product_label'] }}</td>
                                <td>{{ number_format($i['price'],2) }}</td>
                                <td>{{ $i['min_qty'] }} — {{ $i['max_qty'] ?? '∞' }}</td>
                                <td>{{ $i['valid_from'] ?? '—' }} → {{ $i['valid_to'] ?? '—' }}</td>
                                <td class="text-end">
                                    <button class="btn btn-primary btn-sm rounded-pill"
                                            wire:click="editItem({{ $i['id'] }})">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button onclick="confirmDeleteItem({{ $i['id'] }})"
                                            class="btn btn-danger btn-sm rounded-pill">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">{{ __('pos.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 for item delete --}}
<script>
function confirmDeleteItem(id) {
    Swal.fire({
        title: '{{ __("pos.alert_title") }}',
        text: '{{ __("pos.alert_text") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#0d6efd',
        confirmButtonText: '{{ __("pos.alert_confirm") }}',
        cancelButtonText: '{{ __("pos.alert_cancel") }}'
    }).then((r) => {
        if (r.isConfirmed) {
            Livewire.emit('deleteItem', id);
            Swal.fire('{{ __("pos.deleted") }}', '{{ __("pos.msg_deleted_ok") }}', 'success');
        }
    })
}
</script>
