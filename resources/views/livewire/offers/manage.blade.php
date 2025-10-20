<div class="page-wrap">

    {{-- Flash Success/Errors --}}
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
        .section-title{font-weight:700; font-size:1rem; color:#111; border-bottom:1px dashed var(--card-brd); padding-bottom:.5rem; margin-bottom:.75rem}
        .preview-chip{
            display:inline-flex;align-items:center;gap:.35rem;background:var(--chip-bg);
            border:1px solid var(--card-brd);border-radius:999px;padding:.25rem .6rem;
            font-size:.8rem;color:var(--muted)
        }
        .help{font-size:.8rem;color:var(--muted)}
        .side-preview{ position: sticky; top:96px; }
        .badge-soft{ border:1px solid rgba(255,255,255,.35); background:rgba(255,255,255,.15); color:#fff }
        @media (max-width: 991.98px){
            .page-head{ border-radius:.75rem; padding:1rem }
            .side-preview{ position: static; margin-top:.5rem }
        }
        .days-grid{ display:grid; grid-template-columns: repeat(7, minmax(0,1fr)); gap:.35rem; }
        @media (max-width: 767.98px){ .days-grid{ grid-template-columns: repeat(3, minmax(0,1fr)); } }
        .days-grid .form-check{ background:#fafbfc; border:1px solid var(--card-brd); padding:.35rem .5rem; border-radius:.75rem }
    </style>

    {{-- ====== عنوان علوي ثابت + زر حفظ زجاجي ====== --}}
    <div class="page-head">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="title h4 mb-1">
                    <i class="mdi mdi-sale-outline me-1"></i>
                    {{ $offer ? __('pos.edit_offer') : __('pos.create_offer') }}
                </div>
                <div class="subtitle">
                    {{ __('pos.hint_type_offer') }}
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if($offer)
                    <span class="badge badge-soft rounded-pill">
                        <i class="mdi mdi-pound-box-outline"></i> {{ $offer->code }}
                    </span>
                    @php
                        $state = [
                            'active' => ['cls'=>'bg-success-subtle text-success','ico'=>'check-circle-outline'],
                            'paused' => ['cls'=>'bg-warning-subtle text-warning','ico'=>'pause-circle-outline'],
                            'expired'=> ['cls'=>'bg-danger-subtle text-danger','ico'=>'alert-circle-outline'],
                            'draft'  => ['cls'=>'bg-secondary-subtle text-white','ico'=>'circle-outline'],
                        ][$offer->status] ?? ['cls'=>'bg-secondary-subtle text-white','ico'=>'circle-outline'];
                    @endphp
                    <span class="badge {{ $state['cls'] }} rounded-pill d-inline-flex align-items-center gap-1">
                        <i class="mdi mdi-{{ $state['ico'] }}"></i> {{ __('pos.status_'.$offer->status) }}
                    </span>
                @endif

                {{-- زر الحفظ أعلى الصفحة مربوط بنفس النموذج --}}
                <button type="submit" form="offer-form" class="btn btn-glass rounded-pill px-4">
                    <i class="mdi mdi-content-save-outline me-1"></i> {{ __('pos.save') }}
                </button>
            </div>
        </div>
    </div>

    {{-- ====== رسائل تحذيرية ديناميكية ====== --}}
    @if(count($this->warnings))
        <div class="alert alert-warning shadow-sm rounded-3 mb-3">
            <div class="d-flex align-items-start gap-2">
                <i class="mdi mdi-alert-outline fs-4"></i>
                <div>
                    <div class="fw-bold mb-1">{{ __('pos.alert_title') }}</div>
                    <ul class="mb-0 ps-3">
                        @foreach($this->warnings as $w)
                            <li>{{ $w }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== النموذج + معاينة جانبية ====== --}}
    <form id="offer-form" wire:submit.prevent="save" class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card rounded-4 shadow-sm stylish-card">
                <div class="card-body">
                    <div class="row g-3">

                        {{-- ====== الأسماء (AR/EN) ====== --}}
                        <div class="col-12"><div class="section-title">
                            <i class="mdi mdi-alphabetical-circle-outline me-1"></i> {{ __('pos.name') }}
                        </div></div>

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

                        {{-- ====== النوع والقيم ====== --}}
                        <div class="col-12"><div class="section-title">
                            <i class="mdi mdi-tag-multiple-outline me-1"></i> {{ __('pos.type') }}
                        </div></div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="mdi mdi-tag-outline"></i> {{ __('pos.type') }}</label>
                            <select class="form-select" wire:model="type">
                                <option value="percentage">{{ __('pos.type_percentage') }}</option>
                                <option value="fixed">{{ __('pos.type_fixed') }}</option>
                                <option value="bxgy">{{ __('pos.type_bxgy') }}</option>
                                <option value="bundle">{{ __('pos.type_bundle') }}</option>
                            </select>
                            <div class="mt-1 preview-chip"><i class="mdi mdi-shape-outline"></i> {{ __('pos.type_'.$type) }}</div>
                            <small class="help">{{ __('pos.hint_type_offer') }}</small>
                        </div>

                        @if(in_array($type,['percentage','fixed']))
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="mdi mdi-percent"></i> {{ __('pos.discount_value') }}</label>
                                <input type="number" step="0.01" class="form-control @error('discount_value') is-invalid @enderror"
                                       wire:model.lazy="discount_value" placeholder="0">
                                @error('discount_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="mt-1 preview-chip"><i class="mdi mdi-cash"></i> {{ $discount_value ?? 0 }}</div>
                                <small class="help">{{ __('pos.hint_discount_value') }}</small>
                            </div>
                        @elseif($type==='bxgy')
                            <div class="col-md-3">
                                <label class="form-label fw-bold">{{ __('pos.x_qty') }}</label>
                                <input type="number" class="form-control @error('x_qty') is-invalid @enderror" wire:model.lazy="x_qty" min="1">
                                @error('x_qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="mt-1 preview-chip">X = {{ $x_qty ?? 0 }}</div>
                                <small class="help">{{ __('pos.hint_bxgy_x') }}</small>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">{{ __('pos.y_qty') }}</label>
                                <input type="number" class="form-control @error('y_qty') is-invalid @enderror" wire:model.lazy="y_qty" min="1">
                                @error('y_qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="mt-1 preview-chip">Y = {{ $y_qty ?? 0 }}</div>
                                <small class="help">{{ __('pos.hint_bxgy_y') }}</small>
                            </div>
                        @elseif($type==='bundle')
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('pos.bundle_price') }}</label>
                                <input type="number" step="0.01" class="form-control @error('bundle_price') is-invalid @enderror"
                                       wire:model.lazy="bundle_price">
                                @error('bundle_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="mt-1 preview-chip"><i class="mdi mdi-currency-usd"></i> {{ $bundle_price ?? 0 }}</div>
                                <small class="help">{{ __('pos.hint_bundle_price') }}</small>
                            </div>
                        @endif

                        {{-- ====== الإعدادات العامة ====== --}}
                        <div class="col-12"><div class="section-title">
                            <i class="mdi mdi-tune-variant me-1"></i> {{ __('pos.settings') ?? 'الإعدادات' }}
                        </div></div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">{{ __('pos.priority') }}</label>
                            <input type="number" class="form-control" wire:model.lazy="priority" min="1">
                            <div class="mt-1 preview-chip"><i class="mdi mdi-sort-ascending"></i> {{ $priority }}</div>
                            <small class="help">{{ __('pos.hint_priority') }}</small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">{{ __('pos.stackable') }}</label>
                            <select class="form-select" wire:model="is_stackable">
                                <option value="1">{{ __('pos.yes') }}</option>
                                <option value="0">{{ __('pos.no') }}</option>
                            </select>
                            <div class="mt-1 preview-chip">{{ $is_stackable ? __('pos.yes') : __('pos.no') }}</div>
                            <small class="help">{{ __('pos.hint_stackable') }}</small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">{{ __('pos.status') }}</label>
                            <select class="form-select" wire:model="status">
                                <option value="active">{{ __('pos.status_active') }}</option>
                                <option value="paused">{{ __('pos.status_paused') }}</option>
                                <option value="expired">{{ __('pos.status_expired') }}</option>
                                <option value="draft">{{ __('pos.status_draft') }}</option>
                            </select>
                            <div class="mt-1 preview-chip"><i class="mdi mdi-traffic-light"></i> {{ __('pos.status_'.$status) }}</div>
                            <small class="help">{{ __('pos.hint_status') }}</small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Policy</label>
                            <select class="form-select" wire:model="policy">
                                <option value="highest_priority">Highest Priority</option>
                                <option value="largest_discount">Largest Discount</option>
                            </select>
                            <div class="mt-1 preview-chip"><i class="mdi mdi-shield-outline"></i> {{ $policy }}</div>
                            <small class="help">سياسة فضّ التعارض بين العروض.</small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">{{ __('pos.max_discount_per_order') }}</label>
                            <input type="number" step="0.01" class="form-control" wire:model.lazy="max_discount_per_order">
                            <div class="mt-1 preview-chip"><i class="mdi mdi-gauge"></i> {{ $max_discount_per_order ?? 0 }}</div>
                            <small class="help">{{ __('pos.hint_max_discount') }}</small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Sales Channel</label>
                            <input type="text" class="form-control" wire:model.lazy="sales_channel" placeholder="pos / online / ...">
                            <div class="mt-1 preview-chip"><i class="mdi mdi-store-outline"></i> {{ $sales_channel ?: '—' }}</div>
                            <small class="help">قناة البيع المستهدفة (اختياري).</small>
                        </div>

                        {{-- ====== الفترة الزمنية ====== --}}
                        <div class="col-12"><div class="section-title"><i class="mdi mdi-calendar-range-outline me-1"></i> {{ __('pos.period') }}</div></div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('pos.period') }}</label>
                            <div class="row g-2">
                                <div class="col">
                                    <input type="datetime-local" class="form-control" wire:model.lazy="start_at">
                                    <div class="mt-1 preview-chip">{{ $start_at ?: __('pos.no_value') }}</div>
                                </div>
                                <div class="col">
                                    <input type="datetime-local" class="form-control" wire:model.lazy="end_at">
                                    <div class="mt-1 preview-chip">{{ $end_at ?: __('pos.no_value') }}</div>
                                </div>
                            </div>
                            <small class="help">{{ __('pos.hint_period') }}</small>
                            @error('end_at') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">{{ __('pos.hours_from') }}</label>
                            <input type="time" class="form-control" wire:model.lazy="hours_from">
                            <div class="mt-1 preview-chip">{{ $hours_from ?: '—' }}</div>
                            <small class="help">{{ __('pos.hint_hours') }}</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">{{ __('pos.hours_to') }}</label>
                            <input type="time" class="form-control" wire:model.lazy="hours_to">
                            <div class="mt-1 preview-chip">{{ $hours_to ?: '—' }}</div>
                            <small class="help">{{ __('pos.hint_hours') }}</small>
                            @error('hours_to') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- ====== أيام الأسبوع ====== --}}
                        <div class="col-12">
                            <label class="form-label fw-bold"><i class="mdi mdi-calendar-week-outline"></i> أيام الأسبوع</label>
                            @php
                                // ترقيم الإثنين=1 .. الأحد=7 (عدّل الترتيب لو نظامك مختلف)
                                $daysMap = [
                                    1 => 'الإثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء',
                                    4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت', 7 => 'الأحد'
                                ];
                            @endphp
                            <div class="days-grid">
                                @foreach($daysMap as $num => $label)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="d{{$num}}"
                                               value="{{ $num }}" wire:model="days_of_week">
                                        <label class="form-check-label small" for="d{{$num}}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-1 preview-chip">
                                <i class="mdi mdi-calendar-week"></i>
                                @if(empty($days_of_week)) {{ __('pos.all') ?? 'الكل' }} @else {{ implode(', ', $days_of_week) }} @endif
                            </div>
                            <small class="help">اتركها فارغة لتُطبَّق كل أيام الأسبوع.</small>
                            @error('days_of_week.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- ====== نطاق التطبيق: فروع/منتجات/فئات ====== --}}
                        <div class="col-12"><div class="section-title"><i class="mdi mdi-crosshairs-gps me-1"></i> النطاق</div></div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">الفروع</label>
                            <select class="form-select" multiple wire:model="branch_ids">
                                {{-- مرّر $branches من الكومبوننت/الكنترولر إن توفر --}}
                                @foreach(($branches ?? []) as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                            <div class="mt-1 preview-chip">
                                <i class="mdi mdi-store-outline"></i>
                                @if(empty($branch_ids)) {{ __('pos.all') ?? 'الكل' }} @else {{ count($branch_ids) }} مختار @endif
                            </div>
                            <small class="help">اتركه فارغًا لتطبيق العرض على كل الفروع.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">المنتجات</label>
                            <select class="form-select" multiple wire:model="product_ids">
                                @foreach(($products ?? []) as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <div class="mt-1 preview-chip">
                                <i class="mdi mdi-cube-outline"></i>
                                @if(empty($product_ids)) {{ __('pos.all') ?? 'الكل' }} @else {{ count($product_ids) }} مختار @endif
                            </div>
                            <small class="help">اتركه فارغًا لتطبيق العرض على كل المنتجات أو استخدم الفئات.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">الفئات</label>
                            <select class="form-select" multiple wire:model="category_ids">
                                @foreach(($categories ?? []) as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            <div class="mt-1 preview-chip">
                                <i class="mdi mdi-shape-outline"></i>
                                @if(empty($category_ids)) {{ __('pos.all') ?? 'الكل' }} @else {{ count($category_ids) }} مختار @endif
                            </div>
                            <small class="help">اتركه فارغًا لتطبيق العرض على كل الفئات.</small>
                        </div>

                        {{-- ====== الوصف ====== --}}
                        <div class="col-12">
                            <label class="form-label fw-bold"><i class="mdi mdi-text-long"></i> {{ __('pos.description') }}</label>
                            <textarea class="form-control mb-1" wire:model.lazy="description.{{ app()->getLocale() }}" rows="3" placeholder="{{ __('pos.ph_description') }}"></textarea>
                            <div class="mt-1 preview-chip">
                                <i class="mdi mdi-eye-outline"></i> {{ $description[app()->getLocale()] ?? __('pos.no_value') }}
                            </div>
                            <small class="help">{{ __('pos.hint_description') }}</small>
                        </div>

                    </div>
                </div>

                <div class="card-footer d-flex gap-2">
                    <a href="{{ route('offers.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-arrow-left"></i> {{ __('pos.back') }}
                    </a>
                    <button class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save-outline"></i> {{ __('pos.save') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- ====== بطاقة معاينة جانبية (Live Summary) ====== --}}
        <div class="col-12 col-lg-4">
            <div class="card rounded-4 shadow-sm stylish-card side-preview">
                <div class="card-header bg-light fw-bold">
                    <i class="mdi mdi-eye-outline me-1"></i> {{ __('Preview') ?? 'معاينة' }}
                </div>
                <div class="card-body">
                    <div class="mb-2"><span class="text-muted small">{{ __('pos.name') }}</span>
                        <div class="fw-semibold">{{ $name[app()->getLocale()] ?: '—' }}</div>
                    </div>

                    <div class="mb-2"><span class="text-muted small">{{ __('pos.type') }}</span>
                        <div>{{ __('pos.type_'.$type) }}</div>
                    </div>

                    @if(in_array($type,['percentage','fixed']))
                        <div class="mb-2"><span class="text-muted small">{{ __('pos.discount_value') }}</span>
                            <div class="preview-chip"><i class="mdi mdi-cash"></i> {{ $discount_value ?? 0 }}</div>
                        </div>
                    @elseif($type==='bxgy')
                        <div class="mb-2"><span class="text-muted small">BxGy</span>
                            <div class="preview-chip">X={{ $x_qty ?? 0 }} | Y={{ $y_qty ?? 0 }}</div>
                        </div>
                    @elseif($type==='bundle')
                        <div class="mb-2"><span class="text-muted small">{{ __('pos.bundle_price') }}</span>
                            <div class="preview-chip"><i class="mdi mdi-currency-usd"></i> {{ $bundle_price ?? 0 }}</div>
                        </div>
                    @endif

                    <div class="mb-2"><span class="text-muted small">{{ __('pos.priority') }}</span>
                        <div class="preview-chip"><i class="mdi mdi-sort-ascending"></i> {{ $priority }}</div>
                    </div>

                    <div class="mb-2"><span class="text-muted small">Policy</span>
                        <div class="preview-chip"><i class="mdi mdi-shield-outline"></i> {{ $policy }}</div>
                    </div>

                    <div class="mb-2"><span class="text-muted small">{{ __('pos.stackable') }}</span>
                        <div class="preview-chip"><i class="mdi mdi-layers-triple-outline"></i> {{ $is_stackable ? __('pos.yes') : __('pos.no') }}</div>
                    </div>

                    <div class="mb-2"><span class="text-muted small">{{ __('pos.period') }}</span>
                        <div class="preview-chip">
                            <i class="mdi mdi-calendar-range"></i> {{ $start_at ?: '—' }} → {{ $end_at ?: '—' }}
                        </div>
                    </div>

                    <div class="mb-2"><span class="text-muted small">{{ __('pos.hours_from') }} / {{ __('pos.hours_to') }}</span>
                        <div class="preview-chip"><i class="mdi mdi-clock-outline"></i> {{ $hours_from ?: '—' }} → {{ $hours_to ?: '—' }}</div>
                    </div>

                    <div class="mb-2"><span class="text-muted small">أيام الأسبوع</span>
                        <div class="preview-chip"><i class="mdi mdi-calendar-week"></i>
                            @if(empty($days_of_week)) {{ __('pos.all') ?? 'الكل' }} @else {{ implode(', ', $days_of_week) }} @endif
                        </div>
                    </div>

                    <div class="mb-2"><span class="text-muted small">القناة</span>
                        <div class="preview-chip"><i class="mdi mdi-store-outline"></i> {{ $sales_channel ?: '—' }}</div>
                    </div>

                    <div class="mb-2"><span class="text-muted small">الفروع/المنتجات/الفئات</span>
                        <div class="preview-chip"><i class="mdi mdi-store-outline"></i> {{ empty($branch_ids)?'الكل':(count($branch_ids).' فرع') }}</div>
                        <div class="preview-chip"><i class="mdi mdi-cube-outline"></i> {{ empty($product_ids)?'الكل':(count($product_ids).' منتج') }}</div>
                        <div class="preview-chip"><i class="mdi mdi-shape-outline"></i> {{ empty($category_ids)?'الكل':(count($category_ids).' فئة') }}</div>
                    </div>

                    <div class="mt-3">
                        <span class="text-muted small">{{ __('pos.description') }}</span>
                        <div class="border rounded-3 p-2" style="min-height:68px; background:#fcfcfd">
                            {{ $description[app()->getLocale()] ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
