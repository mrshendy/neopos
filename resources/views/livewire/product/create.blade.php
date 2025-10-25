<div class="page-wrap">

    {{-- alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-1 fw-bold"><i class="mdi mdi-plus-circle-outline me-2"></i> {{ __('pos.product_create_title') ?? 'إنشاء منتج' }}</h4>
            <div class="text-muted small">{{ __('pos.product_create_sub') ?? 'أدخل البيانات الأساسية، الوحدات، والإعدادات' }}</div>
        </div>
        <a href="{{ route('product.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') ?? 'رجوع' }}
        </a>
    </div>

    {{-- basic info fancy header --}}
    <div class="basic-head rounded-4 p-3 p-lg-4 mb-3 shadow-sm position-relative overflow-hidden">
        <div class="d-flex align-items-center gap-3">
            <div class="head-icon d-flex align-items-center justify-content-center rounded-4">
                <i class="mdi mdi-clipboard-text-outline"></i>
            </div>

            <div class="flex-grow-1">
                <h5 class="mb-1 fw-bold">{{ __('pos.product_create_title') ?? 'البيانات الأساسية' }}</h5>
                <div class="text-muted small">
                    {{ __('pos.product_create_sub') ?? 'أدخل اسم المنتج، الكود، الباركود، والوصف — مع دعم عربي/إنجليزي' }}
                </div>
            </div>

            <span class="badge rounded-pill px-3 py-2 d-none d-sm-inline-flex
                {{ ($status ?? 'active')==='active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                <i class="mdi mdi-toggle-switch-outline me-1"></i>
                {{ ($status ?? 'active')==='active' ? __('pos.status_active') ?? 'نشط' : __('pos.status_inactive') ?? 'غير نشط' }}
            </span>
        </div>

        <div class="d-flex gap-2 mt-3 small steps-wrap">
            <div class="step active">
                <span class="dot"></span><span class="label">{{ __('pos.basic_info_title') ?? 'البيانات الأساسية' }}</span>
            </div>
            <div class="sep"></div>
            <div class="step">
                <span class="dot"></span><span class="label">{{ __('pos.units_matrix') ?? 'بيانات الوحدات' }}</span>
            </div>
            <div class="sep"></div>
            <div class="step">
                <span class="dot"></span><span class="label">{{ __('pos.expiry_settings') ?? 'الصلاحية' }}</span>
            </div>
        </div>

        <span class="bg-bubble one"></span>
        <span class="bg-bubble two"></span>
    </div>

    @php
        // قيَم مختارة للوحدات بعد تبديل الترتيب
        $selMajor  = $units_matrix['major']['unit_id']  ?? null;
        $selMiddle = $units_matrix['middle']['unit_id'] ?? null;
        $selMinor  = $units_matrix['minor']['unit_id']  ?? null;

        // التصفية: الكبرى = الكل، الوسطى تستبعد الكبرى، الصغرى تستبعد الكبرى والوسطى
        $optsMajor  = $units;
        $optsMiddle = $units->when($selMajor, fn($q) => $q->where('id', '!=', $selMajor));
        $optsMinor  = $units->when($selMajor, fn($q) => $q->where('id', '!=', $selMajor))
                            ->when($selMiddle, fn($q) => $q->where('id', '!=', $selMiddle));
    @endphp

    <form wire:submit.prevent="save" class="row g-3">

        {{-- صورة المنتج --}}
        <div class="col-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div>
                        @if($image)
                            <img src="{{ $image->temporaryUrl() }}" class="rounded border" width="88" height="88" style="object-fit:cover">
                        @elseif($image_path)
                            <img src="{{ asset('attachments/'.ltrim($image_path,'/')) }}" class="rounded border" width="88" height="88" style="object-fit:cover">
                        @else
                            <div class="rounded border bg-light d-flex align-items-center justify-content-center" style="width:88px;height:88px">
                                <i class="mdi mdi-image-plus text-muted fs-2"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <label class="form-label mb-1">{{ __('pos.product_image') ?? 'صورة المنتج' }}</label>
                        <input type="file" class="form-control" wire:model="image" accept="image/*">
                        <small class="text-muted d-block mb-1">{{ __('pos.hint_product_image') ?? 'PNG/JPG/WEBP حتى 2MB' }}</small>

                        @if($image || $image_path)
                            <div class="preview-box">
                                <i class="mdi mdi-image-outline"></i>
                                <span>
                                    @if($image) {{ __('pos.preview_selected_file') ?? 'تم تحديد ملف للصورة' }}
                                    @elseif($image_path) {{ __('pos.preview_existing_image') ?? 'صورة محفوظة مسبقًا' }}
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-danger rounded-pill" wire:click="removeImage">
                            <i class="mdi mdi-close"></i> {{ __('pos.remove') ?? 'إزالة' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- البيانات الأساسية (4 أعمدة) --}}
        <div class="col-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <div class="row g-3 row-cols-1 row-cols-lg-4">

                        {{-- sku --}}
                        <div class="col">
                            <label class="form-label">{{ __('pos.sku') ?? 'كود الصنف' }}</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                   wire:model.debounce.400ms="sku" placeholder="{{ __('pos.ph_sku') ?? 'مثال: PRD-0001' }}">
                            <small class="text-muted d-block mb-1">{{ __('pos.hint_sku') ?? 'يُستخدم كمعرّف داخلي فريد' }}</small>
                            @error('sku') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                            @if(!empty($sku))
                                <div class="preview-box"><i class="mdi mdi-tag-outline"></i><span>{{ $sku }}</span></div>
                            @endif
                        </div>

                        {{-- barcode --}}
                        <div class="col">
                            <label class="form-label">{{ __('pos.barcode') ?? 'الباركود' }}</label>
                            <input type="text" class="form-control @error('barcode') is-invalid @enderror"
                                   wire:model.debounce.400ms="barcode" placeholder="{{ __('pos.ph_barcode') ?? 'مثال: 6221234567890' }}">
                            <small class="text-muted d-block mb-1">{{ __('pos.hint_barcode') ?? 'اختياري — لو متاح على الصنف' }}</small>
                            @error('barcode') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                            @if(!empty($barcode))
                                <div class="preview-box"><i class="mdi mdi-barcode"></i><span>{{ $barcode }}</span></div>
                            @endif
                        </div>

                        {{-- name ar --}}
                        <div class="col">
                            <label class="form-label">{{ __('pos.name_ar') ?? 'الاسم (عربي)' }}</label>
                            <input type="text" class="form-control @error('name.ar') is-invalid @enderror"
                                   wire:model.debounce.400ms="name.ar" placeholder="{{ __('pos.ph_name_ar') ?? 'ادخل الاسم بالعربية' }}">
                            <small class="text-muted d-block mb-1">{{ __('pos.hint_name_ar') ?? 'سيظهر للواجهة العربية' }}</small>
                            @error('name.ar') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                            @if(!empty($name['ar']))
                                <div class="preview-box"><i class="mdi mdi-alphabet-arabic"></i><span>{{ $name['ar'] }}</span></div>
                            @endif
                        </div>

                        {{-- name en --}}
                        <div class="col">
                            <label class="form-label">{{ __('pos.name_en') ?? 'الاسم (إنجليزي)' }}</label>
                            <input type="text" class="form-control @error('name.en') is-invalid @enderror"
                                   wire:model.debounce.400ms="name.en" placeholder="{{ __('pos.ph_name_en') ?? 'Enter English name' }}">
                            <small class="text-muted d-block mb-1">{{ __('pos.hint_name_en') ?? 'سيظهر للواجهة الإنجليزية' }}</small>
                            @error('name.en') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                            @if(!empty($name['en']))
                                <div class="preview-box"><i class="mdi mdi-alphabet-latin"></i><span>{{ $name['en'] }}</span></div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3 mt-1 row-cols-1 row-cols-lg-4">
                        {{-- description ar --}}
                        <div class="col">
                            <label class="form-label">{{ __('pos.description_ar') ?? 'الوصف (عربي)' }}</label>
                            <textarea class="form-control" rows="2" wire:model.debounce.400ms="description.ar"
                                      placeholder="{{ __('pos.ph_description_ar') ?? 'وصف مختصر بالعربية' }}"></textarea>
                            <small class="text-muted d-block mb-1">{{ __('pos.hint_description') ?? 'اختياري' }}</small>
                            @if(!empty($description['ar']))
                                <div class="preview-box"><i class="mdi mdi-text"></i><span>{{ $description['ar'] }}</span></div>
                            @endif
                        </div>

                        {{-- description en --}}
                        <div class="col">
                            <label class="form-label">{{ __('pos.description_en') ?? 'الوصف (إنجليزي)' }}</label>
                            <textarea class="form-control" rows="2" wire:model.debounce.400ms="description.en"
                                      placeholder="{{ __('pos.ph_description_en') ?? 'Short English description' }}"></textarea>
                            <small class="text-muted d-block mb-1">{{ __('pos.hint_description') ?? 'Optional' }}</small>
                            @if(!empty($description['en']))
                                <div class="preview-box"><i class="mdi mdi-text-long"></i><span>{{ $description['en'] }}</span></div>
                            @endif
                        </div>

                        {{-- category --}}
                        <div class="col">
                            <label class="form-label">{{ __('pos.category') ?? 'القسم' }}</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" wire:model="category_id">
                                <option value="">{{ __('pos.choose') ?? 'اختر' }}</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->getTranslation('name', app()->getLocale()) }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mb-1">{{ __('pos.hint_category') ?? 'اختر القسم المناسب' }}</small>
                            @error('category_id') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                            @if(!empty($category_id))
                                <div class="preview-box"><i class="mdi mdi-shape-outline"></i>
                                    <span>{{ optional($categories->firstWhere('id',$category_id))->getTranslation('name', app()->getLocale()) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- supplier --}}
                        <div class="col">
                            <label class="form-label">{{ __('pos.supplier') ?? 'المورّد' }}</label>
                            <select class="form-select @error('supplier_id') is-invalid @enderror" wire:model="supplier_id">
                                <option value="">{{ __('pos.choose') ?? 'اختر' }}</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->getTranslation('name', app()->getLocale()) }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mb-1">{{ __('pos.hint_supplier') ?? 'اختر المورّد الأساسي للصنف' }}</small>
                            @error('supplier_id') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                            @if(!empty($supplier_id))
                                <div class="preview-box"><i class="mdi mdi-truck-outline"></i>
                                    <span>{{ optional($suppliers->firstWhere('id',$supplier_id))->getTranslation('name', app()->getLocale()) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-12 col-lg-3">
                            <label class="form-label">{{ __('pos.status') ?? 'الحالة' }}</label>
                            <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                                <option value="active">{{ __('pos.status_active') ?? 'نشط' }}</option>
                                <option value="inactive">{{ __('pos.status_inactive') ?? 'غير نشط' }}</option>
                            </select>
                            <small class="text-muted d-block mb-1">{{ __('pos.hint_status') ?? 'حدد حالة ظهور وبيع الصنف' }}</small>
                            @error('status') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                            @if(!empty($status))
                                <div class="preview-box">
                                    <i class="mdi mdi-toggle-switch"></i>
                                    <span>{{ $status=='active' ? __('pos.status_active') ?? 'نشط' : __('pos.status_inactive') ?? 'غير نشط' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- جدول الوحدات (ترتيب: كبرى، وسطى، صغرى) --}}
        <div class="col-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="mdi mdi-ruler me-1"></i> {{ __('pos.units_matrix') ?? 'بيانات الوحدات' }}</h6>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('pos.field') ?? 'الحقل' }}</th>
                                    <th>{{ __('pos.major') ?? 'الوحدة الكبرى' }}</th>
                                    <th>{{ __('pos.middle') ?? 'الوحدة الوسطى' }}</th>
                                    <th>{{ __('pos.minor') ?? 'الوحدة الصغرى' }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                {{-- اختيار الوحدة --}}
                                <tr>
                                    <td class="text-muted">{{ __('pos.unit') ?? 'الوحدة' }}</td>

                                    {{-- major --}}
                                    <td>
                                        <select class="form-select @error('units_matrix.major.unit_id') is-invalid @enderror"
                                                wire:model="units_matrix.major.unit_id">
                                            <option value="">{{ __('pos.choose') ?? 'اختر' }}</option>
                                            @foreach($optsMajor as $u)
                                                <option value="{{ $u->id }}">{{ $u->getTranslation('name', app()->getLocale()) }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted d-block mb-1">{{ __('pos.hint_pick_unit') ?? 'اختر وحدة لهذا المستوى' }}</small>
                                        @error('units_matrix.major.unit_id') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                                        @if(!empty($units_matrix['major']['unit_id']))
                                            <div class="preview-box">
                                                <i class="mdi mdi-ruler-square"></i>
                                                <span>{{ optional($units->firstWhere('id',$units_matrix['major']['unit_id']))->getTranslation('name', app()->getLocale()) }}</span>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- middle --}}
                                    <td>
                                        <select class="form-select @error('units_matrix.middle.unit_id') is-invalid @enderror"
                                                wire:model="units_matrix.middle.unit_id">
                                            <option value="">{{ __('pos.choose') ?? 'اختر' }}</option>
                                            @foreach($optsMiddle as $u)
                                                <option value="{{ $u->id }}">{{ $u->getTranslation('name', app()->getLocale()) }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted d-block mb-1">{{ __('pos.hint_pick_unit') ?? 'اختر وحدة لهذا المستوى' }}</small>
                                        @error('units_matrix.middle.unit_id') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                                        @if(!empty($units_matrix['middle']['unit_id']))
                                            <div class="preview-box">
                                                <i class="mdi mdi-ruler-square"></i>
                                                <span>{{ optional($units->firstWhere('id',$units_matrix['middle']['unit_id']))->getTranslation('name', app()->getLocale()) }}</span>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- minor --}}
                                    <td>
                                        <select class="form-select @error('units_matrix.minor.unit_id') is-invalid @enderror"
                                                wire:model="units_matrix.minor.unit_id">
                                            <option value="">{{ __('pos.choose') ?? 'اختر' }}</option>
                                            @foreach($optsMinor as $u)
                                                <option value="{{ $u->id }}">{{ $u->getTranslation('name', app()->getLocale()) }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted d-block mb-1">{{ __('pos.hint_pick_unit') ?? 'اختر وحدة لهذا المستوى' }}</small>
                                        @error('units_matrix.minor.unit_id') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                                        @if(!empty($units_matrix['minor']['unit_id']))
                                            <div class="preview-box">
                                                <i class="mdi mdi-ruler-square"></i>
                                                <span>{{ optional($units->firstWhere('id',$units_matrix['minor']['unit_id']))->getTranslation('name', app()->getLocale()) }}</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>

                                {{-- cost --}}
                                <tr>
                                    <td class="text-muted">{{ __('pos.cost_price') ?? 'سعر التكلفة' }}</td>
                                    @foreach (['major','middle','minor'] as $lvl)
                                        <td>
                                            <input type="number" step="0.0001" class="form-control"
                                                   wire:model.debounce.400ms="units_matrix.{{ $lvl }}.cost" placeholder="0.00">
                                            <small class="text-muted d-block mb-1">{{ __('pos.hint_cost') ?? 'أدخل التكلفة' }}</small>
                                            @if(!empty($units_matrix[$lvl]['cost']) || $units_matrix[$lvl]['cost'] === 0)
                                                <div class="preview-box"><i class="mdi mdi-cash"></i><span>{{ $units_matrix[$lvl]['cost'] }}</span></div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>

                                {{-- price --}}
                                <tr>
                                    <td class="text-muted">{{ __('pos.sale_price') ?? 'سعر البيع' }}</td>
                                    @foreach (['major','middle','minor'] as $lvl)
                                        <td>
                                            <input type="number" step="0.0001" class="form-control"
                                                   wire:model.debounce.400ms="units_matrix.{{ $lvl }}.price" placeholder="0.00">
                                            <small class="text-muted d-block mb-1">{{ __('pos.hint_price') ?? 'أدخل سعر البيع' }}</small>
                                            @if(!empty($units_matrix[$lvl]['price']) || $units_matrix[$lvl]['price'] === 0)
                                                <div class="preview-box"><i class="mdi mdi-cash-multiple"></i><span>{{ $units_matrix[$lvl]['price'] }}</span></div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>

                                {{-- factor --}}
                                <tr>
                                    <td class="text-muted">{{ __('pos.conv_factor') ?? 'معامل التحويل' }}</td>
                                    @foreach (['major','middle','minor'] as $lvl)
                                        <td>
                                            <input type="number" step="0.0001" class="form-control @error('units_matrix.'.$lvl.'.factor') is-invalid @enderror"
                                                   wire:model.debounce.400ms="units_matrix.{{ $lvl }}.factor" placeholder="1">
                                            <small class="text-muted d-block mb-1">{{ __('pos.hint_factor') ?? 'الافتراضي 1' }}</small>
                                            @error('units_matrix.'.$lvl.'.factor') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                                            @if(!empty($units_matrix[$lvl]['factor']))
                                                <div class="preview-box"><i class="mdi mdi-swap-horizontal"></i><span>{{ $units_matrix[$lvl]['factor'] }}</span></div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- إعدادات الوحدات --}}
                    <div class="row g-3 mt-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('pos.sale_unit') ?? 'وحدة البيع' }}</label>
                            <select class="form-select @error('sale_unit_key') is-invalid @enderror" wire:model="sale_unit_key">
                                <option value="major"  @disabled(empty($selMajor))>{{ __('pos.major') ?? 'الكبرى' }}</option>
                                <option value="middle" @disabled(empty($selMiddle))>{{ __('pos.middle') ?? 'الوسطى' }}</option>
                                <option value="minor"  @disabled(empty($selMinor))>{{ __('pos.minor') ?? 'الصغرى' }}</option>
                            </select>
                            <small class="text-muted d-block mb-1">{{ __('pos.hint_sale_unit') ?? 'يجب أن تكون من الثلاث وحدات المختارة أعلاه' }}</small>
                            @error('sale_unit_key') <div class="text-danger small mb-1">{{ $message }}</div> @enderror

                            @php
                                $k = $sale_unit_key;
                                $uid = $units_matrix[$k]['unit_id'] ?? null;
                            @endphp
                            @if(!empty($uid))
                                <div class="preview-box">
                                    <i class="mdi mdi-cart-outline"></i>
                                    <span>{{ optional($units->firstWhere('id',$uid))->getTranslation('name', app()->getLocale()) }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">{{ __('pos.purchase_unit') ?? 'وحدة الشراء' }}</label>
                            <select class="form-select @error('purchase_unit_key') is-invalid @enderror" wire:model="purchase_unit_key">
                                <option value="major"  @disabled(empty($selMajor))>{{ __('pos.major') ?? 'الكبرى' }}</option>
                                <option value="middle" @disabled(empty($selMiddle))>{{ __('pos.middle') ?? 'الوسطى' }}</option>
                                <option value="minor"  @disabled(empty($selMinor))>{{ __('pos.minor') ?? 'الصغرى' }}</option>
                            </select>
                            <small class="text-muted d-block mb-1">{{ __('pos.hint_purchase_unit') ?? 'يجب أن تكون من الثلاث وحدات المختارة أعلاه' }}</small>
                            @error('purchase_unit_key') <div class="text-danger small mb-1">{{ $message }}</div> @enderror

                            @php
                                $k2 = $purchase_unit_key;
                                $uid2 = $units_matrix[$k2]['unit_id'] ?? null;
                            @endphp
                            @if(!empty($uid2))
                                <div class="preview-box">
                                    <i class="mdi mdi-basket-outline"></i>
                                    <span>{{ optional($units->firstWhere('id',$uid2))->getTranslation('name', app()->getLocale()) }}</span>
                                </div>
                            @endif
                        </div>

                        @error('sale_purchase_mismatch')
                            <div class="col-12">
                                <div class="alert alert-warning py-2 px-3 mb-0">
                                    <i class="mdi mdi-alert-outline me-1"></i>{{ $message }}
                                </div>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- صلاحية الصنف --}}
        <div class="col-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="mdi mdi-timer-outline me-1"></i> {{ __('pos.expiry_settings') ?? 'صلاحية الصنف' }}</h6>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="expirySwitch" wire:model="expiry_enabled">
                        <label class="form-check-label" for="expirySwitch">{{ __('pos.has_expiry') ?? 'له صلاحية؟' }}</label>
                        @if($expiry_enabled)
                            <div class="preview-box mt-2">
                                <i class="mdi mdi-check-circle-outline"></i>
                                <span>{{ __('pos.yes') ?? 'نعم' }}</span>
                            </div>
                        @endif
                    </div>

                    @if($expiry_enabled)
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label">{{ __('pos.expiry_unit') ?? 'وحدة الصلاحية' }}</label>
                                <select class="form-select" wire:model="expiry_unit">
                                    <option value="day">{{ __('pos.day') ?? 'يوم' }}</option>
                                    <option value="month">{{ __('pos.month') ?? 'شهر' }}</option>
                                    <option value="year">{{ __('pos.year') ?? 'سنة' }}</option>
                                </select>
                                <small class="text-muted d-block mb-1">{{ __('pos.hint_expiry_unit') ?? 'اختر وحدة زمنية' }}</small>
                                @if(!empty($expiry_unit))
                                    <div class="preview-box"><i class="mdi mdi-timelapse"></i><span>{{ $expiry_unit }}</span></div>
                                @endif
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label">{{ __('pos.expiry_value') ?? 'القيمة' }}</label>
                                <input type="number" min="1" class="form-control" wire:model.debounce.400ms="expiry_value" placeholder="1">
                                <small class="text-muted d-block mb-1">{{ __('pos.hint_expiry_value') ?? 'عدد الأيام/الأشهر/السنوات' }}</small>
                                @if(!empty($expiry_value))
                                    <div class="preview-box"><i class="mdi mdi-numeric"></i><span>{{ $expiry_value }}</span></div>
                                @endif
                            </div>
                            {{-- تم حذف اختيار أيام الأسبوع كما طلبت --}}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- أزرار --}}
        <div class="col-12 d-flex justify-content-end gap-2">
            <a href="{{ route('product.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-close"></i> {{ __('pos.btn_cancel') ?? 'إلغاء' }}
            </a>
            <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-content-save-outline"></i> {{ __('pos.btn_save') ?? 'حفظ' }}
            </button>
        </div>
    </form>
</div>

{{-- styles --}}
<style>
    .preview-box{
        margin-top:.35rem;
        border:1px dashed rgba(0,0,0,.15);
        background:#fff;
        border-radius:.65rem;
        padding:.35rem .5rem;
        display:flex; align-items:center; gap:.35rem;
        font-size:.875rem; color:#334155;
        box-shadow:0 1px 2px rgba(16,24,40,.05);
    }
    .preview-box i{ opacity:.75; }

    .basic-head{
        background: linear-gradient(180deg, #ffffff, #fbfcff);
        border: 1px solid rgba(0,0,0,.06);
    }
    .basic-head .head-icon{
        width: 64px; height: 64px; min-width:64px;
        border: 1px solid rgba(0,0,0,.06);
        background: #f6f8fb;
        box-shadow: 0 2px 6px rgba(16,24,40,.06) inset;
    }
    .basic-head .head-icon i{
        font-size: 1.9rem; color: #0d6efd; opacity:.9;
    }
    .steps-wrap .sep{
        width: 24px; height: 2px; background: rgba(13,110,253,.25); align-self: center; border-radius: 1rem;
    }
    .steps-wrap .step{ display: inline-flex; align-items: center; gap:.4rem; color:#64748b; }
    .steps-wrap .step .dot{
        width: 10px; height: 10px; border-radius: 999px; background: #cbd5e1; display:inline-block;
    }
    .steps-wrap .step.active .dot{ background:#0d6efd; }
    .steps-wrap .step.active .label{ color:#0d6efd; font-weight:600; }
    .basic-head .bg-bubble{
        position:absolute; border-radius: 999px; pointer-events:none; opacity:.28; filter: blur(2px);
    }
    .basic-head .bg-bubble.one{
        width: 160px; height: 160px; background: radial-gradient(closest-side, #cfe2ff, transparent);
        top: -40px; {{ app()->getLocale()==='ar' ? 'right' : 'left' }}: -40px;
    }
    .basic-head .bg-bubble.two{
        width: 220px; height: 220px; background: radial-gradient(closest-side, #e2e8f0, transparent);
        bottom: -60px; {{ app()->getLocale()==='ar' ? 'left' : 'right' }}: -60px;
    }
    .bg-success-subtle{ background: #d1e7dd !important; color:#0f5132 !important; }
    .bg-secondary-subtle{ background: #e2e3e5 !important; color:#41464b !important; }

    @media (max-width: 576px){
        .basic-head .head-icon{ width: 54px; height: 54px; min-width:54px; }
        .steps-wrap{ display:none; }
    }
</style>
