<div class="page-wrap">

    {{-- Flash --}}
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        :root{
            --accent:#0d6efd;      /* primary */
            --accent-2:#20c997;    /* teal */
            --muted:#6c757d;
            --chip-bg:#f8f9fa;
            --card-brd:rgba(0,0,0,.06);
        }
        .page-head{
            position:sticky; top:0; z-index:15;
            background: linear-gradient(135deg,var(--accent) 0%,var(--accent-2) 100%);
            border-radius:1rem; padding:1.25rem 1.25rem; color:#fff;
            box-shadow:0 10px 30px rgba(13,110,253,.12); margin-bottom:1rem;
        }
        .page-head .title{ font-weight:800; letter-spacing:.2px }
        .page-head .subtitle{ opacity:.9; font-size:.9rem }
        .btn-glass{
            backdrop-filter: blur(6px);
            background: rgba(255,255,255,.15); color:#fff; border:1px solid rgba(255,255,255,.25);
        }
        .btn-glass:hover{ background: rgba(255,255,255,.25); color:#fff }

        .stylish-card{border:1px solid var(--card-brd); border-radius:1rem}
        .preview-chip{
            display:inline-flex;align-items:center;gap:.35rem;background:var(--chip-bg);
            border:1px solid var(--card-brd);border-radius:999px;padding:.25rem .6rem;
            font-size:.8rem;color:var(--muted)
        }
        .help{font-size:.8rem;color:var(--muted)}
        .badge-soft{ border:1px solid rgba(255,255,255,.35); background:rgba(255,255,255,.15); color:#fff }
        .input-group-text{ background:#fff; border-color: var(--card-brd) }
    </style>

    {{-- ====== عنوان علوي ثابت + زر حفظ زجاجي ====== --}}
    <div class="page-head">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="title h4 mb-1">
                    <i class="mdi mdi-ticket-percent-outline me-1"></i>
                    {{ $coupon ? __('pos.edit_coupon') : __('pos.create_coupon') }}
                </div>
                <div class="subtitle">
                    {{ __('pos.hint_type_offer') ?? 'أنشئ كوبون خصم وحدد نوعه ومدته وحدود الاستخدام.' }}
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if($coupon)
                    <span class="badge badge-soft rounded-pill">
                        <i class="mdi mdi-pound-box-outline"></i> {{ $coupon->code ?? '—' }}
                    </span>
                    @php
                        $state = [
                            'active' => ['cls'=>'bg-success-subtle text-success','ico'=>'check-circle-outline'],
                            'paused' => ['cls'=>'bg-warning-subtle text-warning','ico'=>'pause-circle-outline'],
                            'expired'=> ['cls'=>'bg-danger-subtle text-danger','ico'=>'alert-circle-outline'],
                        ][$coupon->status] ?? ['cls'=>'bg-secondary-subtle text-white','ico'=>'circle-outline'];
                    @endphp
                    <span class="badge {{ $state['cls'] }} rounded-pill d-inline-flex align-items-center gap-1">
                        <i class="mdi mdi-{{ $state['ico'] }}"></i> {{ __('pos.status_'.($coupon->status ?? 'active')) }}
                    </span>
                @endif
                <button type="submit" form="coupon-form" class="btn btn-glass rounded-pill px-4">
                    <i class="mdi mdi-content-save-outline me-1"></i> {{ __('pos.save') }}
                </button>
            </div>
        </div>
    </div>

    {{-- ====== تحذيرات ديناميكية (من الكومبوننت) ====== --}}
    @if(method_exists($this,'getWarningsProperty') && count($this->warnings))
        <div class="alert alert-warning shadow-sm rounded-3 mb-3">
            <div class="d-flex align-items-start gap-2">
                <i class="mdi mdi-alert-outline fs-4"></i>
                <div>
                    <div class="fw-bold mb-1">{{ __('pos.alert_title') ?? 'تنبيه' }}</div>
                    <ul class="mb-0 ps-3">
                        @foreach($this->warnings as $w)
                            <li>{{ $w }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== النموذج ====== --}}
    <form id="coupon-form" wire:submit.prevent="save" class="row g-3">
        <div class="col-12">
            <div class="card rounded-4 shadow-sm stylish-card">
                <div class="card-body">
                    <div class="row g-3">

                        {{-- الأسماء --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="mdi mdi-alphabetical"></i> {{ __('pos.name_ar') }}</label>
                            <input type="text" class="form-control @error('name.ar') is-invalid @enderror"
                                   wire:model.lazy="name.ar" placeholder="{{ __('pos.ph_name_ar') }}">
                            @error('name.ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="mt-1 preview-chip"><i class="mdi mdi-translate"></i> {{ $name['ar'] ?: __('pos.no_value') }}</div>
                            <small class="help">{{ __('pos.hint_name') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="mdi mdi-alphabetical-variant"></i> {{ __('pos.name_en') }}</label>
                            <input type="text" class="form-control @error('name.en') is-invalid @enderror"
                                   wire:model.lazy="name.en" placeholder="{{ __('pos.ph_name_en') }}">
                            @error('name.en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="mt-1 preview-chip"><i class="mdi mdi-translate-variant"></i> {{ $name['en'] ?: __('pos.no_value') }}</div>
                            <small class="help">{{ __('pos.hint_name') }}</small>
                        </div>

                        {{-- النوع + قيمة الخصم --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="mdi mdi-tag-outline"></i> {{ __('pos.type') }}</label>
                            <select class="form-select" wire:model="type">
                                <option value="percentage">{{ __('pos.type_percentage') }}</option>
                                <option value="fixed">{{ __('pos.type_fixed') }}</option>
                            </select>
                            <div class="mt-1 preview-chip"><i class="mdi mdi-shape-outline"></i> {{ __('pos.type_'.$type) }}</div>
                            <small class="help">{{ __('pos.hint_type_offer') }}</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="mdi mdi-percent"></i> {{ __('pos.discount_value') }}</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('discount_value') is-invalid @enderror"
                                       wire:model.lazy="discount_value" placeholder="0">
                                <span class="input-group-text">
                                    @if($type==='percentage') % @else <i class="mdi mdi-currency-usd"></i> @endif
                                </span>
                            </div>
                            @error('discount_value') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            <div class="mt-1 preview-chip"><i class="mdi mdi-cash"></i> {{ $discount_value ?? 0 }}</div>
                            <small class="help">{{ __('pos.hint_discount_value') }}</small>
                        </div>

                        {{-- الحالة + المدة --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('pos.status') }}</label>
                            <select class="form-select" wire:model="status">
                                <option value="active">{{ __('pos.status_active') }}</option>
                                <option value="paused">{{ __('pos.status_paused') }}</option>
                                <option value="expired">{{ __('pos.status_expired') }}</option>
                            </select>
                            <div class="mt-1 preview-chip"><i class="mdi mdi-traffic-light"></i> {{ __('pos.status_'.$status) }}</div>
                            <small class="help">{{ __('pos.hint_status') }}</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('pos.date_from') }}</label>
                            <input type="datetime-local" class="form-control" wire:model.lazy="start_at">
                            <div class="mt-1 preview-chip">{{ $start_at ?: __('pos.no_value') }}</div>
                            <small class="help">{{ __('pos.hint_period') }}</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('pos.date_to') }}</label>
                            <input type="datetime-local" class="form-control @error('end_at') is-invalid @enderror" wire:model.lazy="end_at">
                            @error('end_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="mt-1 preview-chip">{{ $end_at ?: __('pos.no_value') }}</div>
                            <small class="help">{{ __('pos.hint_period') }}</small>
                        </div>

                        {{-- حدود الاستخدام + الدمج --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('pos.max_uses_per_customer') ?? 'Max Uses / Customer' }}</label>
                            <input type="number" class="form-control @error('max_uses_per_customer') is-invalid @enderror"
                                   wire:model.lazy="max_uses_per_customer" min="1">
                            @error('max_uses_per_customer') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="mt-1 preview-chip"><i class="mdi mdi-counter"></i> {{ $max_uses_per_customer }}</div>
                            <small class="help">الحد الأقصى لاستخدام العميل الواحد.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('pos.max_total_uses') ?? 'Max Total Uses' }}</label>
                            <input type="number" class="form-control @error('max_total_uses') is-invalid @enderror"
                                   wire:model.lazy="max_total_uses" min="1">
                            @error('max_total_uses') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="mt-1 preview-chip"><i class="mdi mdi-sigma"></i> {{ $max_total_uses ?? '∞' }}</div>
                            <small class="help">اتركها فارغة للسماح بعدد غير محدود.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('pos.stackable') }}</label>
                            <select class="form-select" wire:model="is_stackable">
                                <option value="0">{{ __('pos.no') }}</option>
                                <option value="1">{{ __('pos.yes') }}</option>
                            </select>
                            <div class="mt-1 preview-chip"><i class="mdi mdi-layers-triple-outline"></i> {{ $is_stackable ? __('pos.yes') : __('pos.no') }}</div>
                            <small class="help">{{ __('pos.hint_stackable') }}</small>
                        </div>

                        {{-- نطاق التطبيق (اختياري) --}}
                        <div class="col-12">
                            <hr class="text-muted" />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('pos.branches') ?? 'الفروع' }}</label>
                            <select class="form-select" multiple wire:model="branch_ids">
                                @foreach(($branches ?? []) as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                            <div class="mt-1 preview-chip"><i class="mdi mdi-store-outline"></i>
                                @if(empty($branch_ids)) {{ __('pos.all') ?? 'الكل' }} @else {{ count($branch_ids) }} مختار @endif
                            </div>
                            <small class="help">اتركه فارغًا لتطبيق الكوبون على كل الفروع.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('pos.customers') ?? 'العملاء' }}</label>
                            <select class="form-select" multiple wire:model="customer_ids">
                                @foreach(($customers ?? []) as $c)
                                    <option value="{{ $c->id }}">{{ $c->name ?? $c->email }}</option>
                                @endforeach
                            </select>
                            <div class="mt-1 preview-chip"><i class="mdi mdi-account-group-outline"></i>
                                @if(empty($customer_ids)) {{ __('pos.all') ?? 'الكل' }} @else {{ count($customer_ids) }} مختار @endif
                            </div>
                            <small class="help">اتركه فارغًا ليكون متاحًا لجميع العملاء.</small>
                        </div>

                        {{-- الوصف --}}
                        <div class="col-12">
                            <label class="form-label fw-bold"><i class="mdi mdi-text-long"></i> {{ __('pos.description') }}</label>
                            <textarea class="form-control mb-1" wire:model.lazy="description.{{ app()->getLocale() }}"
                                      rows="3" placeholder="{{ __('pos.ph_description') }}"></textarea>
                            <div class="mt-1 preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $description[app()->getLocale()] ?? __('pos.no_value') }}</div>
                            <small class="help">{{ __('pos.hint_description') }}</small>
                        </div>

                    </div>
                </div>
                <div class="card-footer d-flex gap-2">
                    <a href="{{ route('coupons.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-arrow-left"></i> {{ __('pos.back') }}
                    </a>
                    <button class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save-outline"></i> {{ __('pos.save') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
