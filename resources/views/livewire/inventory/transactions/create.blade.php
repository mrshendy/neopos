<div>
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

    <div class="card border-0 shadow-lg rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-swap-horizontal-bold me-2"></i> {{ __('pos.add_new_transaction') }}
        </div>
        <div class="card-body p-4">
            <form wire:submit.prevent="save" class="row g-3">

                <div class="col-md-3">
                    <label class="form-label fw-bold">{{ __('pos.type') }}</label>
                    <select class="form-select" wire:model="type">
                        <option value="adjustment">{{ __('pos.trx_adjustment') }}</option>
                        <option value="transfer">{{ __('pos.trx_transfer') }}</option>
                        <option value="sales_issue">{{ __('pos.trx_sales_issue') }}</option>
                        <option value="sales_return">{{ __('pos.trx_sales_return') }}</option>
                        <option value="purchase_receive">{{ __('pos.trx_purchase_receive') }}</option>
                    </select>
                    <small class="text-muted d-block">{{ __('pos.hint_trx_type') }}</small>
                    <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ __('pos.trx_'.$type) }}</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">{{ __('pos.trx_date') }}</label>
                    <input type="datetime-local" class="form-control" wire:model="trx_date">
                    <small class="text-muted d-block">{{ __('pos.hint_trx_date') }}</small>
                    <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ $trx_date }}</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">{{ __('pos.from_warehouse') }}</label>
                    <select class="form-select" wire:model="warehouse_from_id">
                        <option value="">{{ __('pos.none') }}</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}">{{ app()->getLocale()=='ar'?($w->name['ar']??''):($w->name['en']??'') }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block">{{ __('pos.hint_from_wh') }}</small>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">{{ __('pos.to_warehouse') }}</label>
                    <select class="form-select" wire:model="warehouse_to_id">
                        <option value="">{{ __('pos.none') }}</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}">{{ app()->getLocale()=='ar'?($w->name['ar']??''):($w->name['en']??'') }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block">{{ __('pos.hint_to_wh') }}</small>
                </div>

                <div class="col-12"><hr class="divider"></div>

                {{-- Lines --}}
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0"><i class="mdi mdi-format-list-bulleted"></i> {{ __('pos.lines') }}</h6>
                        <button type="button" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" wire:click="addLine">
                            <i class="mdi mdi-plus"></i> {{ __('pos.add_line') }}
                        </button>
                    </div>

                    @foreach($lines as $idx => $ln)
                        <div class="row g-2 align-items-end mb-2">
                            <div class="col-md-4">
                                <label class="form-label mb-1">{{ __('pos.item') }}</label>
                                <select class="form-select" wire:model="lines.{{ $idx }}.item_id">
                                    <option value="">{{ __('pos.choose') }}</option>
                                    @foreach($items as $it)
                                        <option value="{{ $it->id }}">
                                            {{ app()->getLocale()=='ar'?($it->name['ar']??''):($it->name['en']??'') }} ({{ $it->sku }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block">{{ __('pos.hint_item') }}</small>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label mb-1">{{ __('pos.qty') }}</label>
                                <input type="number" step="0.000001" class="form-control" wire:model="lines.{{ $idx }}.qty">
                                <small class="text-muted d-block">{{ __('pos.hint_qty') }}</small>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label mb-1">{{ __('pos.uom') }}</label>
                                <input type="text" class="form-control" wire:model="lines.{{ $idx }}.uom">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label mb-1">{{ __('pos.reason') }}</label>
                                <input type="text" class="form-control" wire:model="lines.{{ $idx }}.reason" placeholder="">
                            </div>

                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-sm btn-danger rounded-pill shadow-sm" wire:click="removeLine({{ $idx }})">
                                    <i class="mdi mdi-delete-outline"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">{{ __('pos.notes') }}</label>
                    <textarea class="form-control" rows="2" wire:model.defer="notes" placeholder="{{ __('pos.hint_notes') }}"></textarea>
                    <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ $notes }}</div>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save"></i> {{ __('pos.btn_save') }}
                    </button>
                    <a href="{{ route('inventory.transactions.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-close"></i> {{ __('pos.btn_cancel') }}
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
