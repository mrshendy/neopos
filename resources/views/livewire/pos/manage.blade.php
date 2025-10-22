<div class="container" style="max-width:1600px;margin:0 auto" x-data>
    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>{{ __('pos.input_errors') }}</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Fonts & Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.rtl.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #0ea5e9;
            --primary-light: #38bdf8;
            --primary-dark: #0284c7;
            --secondary: #06b6d4;
            --accent: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --light: #f8fafc;
            --dark: #0f172a;
            --gray: #64748b;
            --gray-light: #cbd5e1;
            --border: #e2e8f0;
            --card-bg: #ffffff;
            --sidebar-bg: #0f172a;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, .1), 0 2px 4px -1px rgba(0, 0, 0, .06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, .1), 0 4px 6px -2px rgba(0, 0, 0, .05);
            --radius: 12px;
            --radius-sm: 8px;
            --transition: all .25s ease;
        }

        * {
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif
        }

        body {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            color: var(--dark)
        }

        .pos-system {
            display: grid;
            grid-template-columns: 280px 1fr 380px;
            gap: 20px;
            min-height: calc(100vh - 40px)
        }

        @media (max-width: 1200px) {
            .pos-system {
                grid-template-columns: 250px 1fr 350px
            }
        }

        @media (max-width: 992px) {
            .pos-system {
                grid-template-columns: 1fr;
                grid-template-rows: auto 1fr auto
            }
        }

        .header {
            grid-column: 1/-1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: #fff;
            border-radius: var(--radius);
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
            margin: 20px 0 10px
        }

        .header h1 {
            font-size: 1.6rem;
            font-weight: 800;
            margin: 0 0 4px
        }

        .header p {
            opacity: .92;
            font-size: .95rem
        }

        .btn {
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition)
        }

        .btn-primary {
            background: rgba(255, 255, 255, .18);
            color: #fff
        }

        .btn-primary:hover {
            background: rgba(255, 255, 255, .28)
        }

        .btn-light {
            background: #fff;
            color: var(--primary);
            border-color: #fff
        }

        .btn-light:hover {
            background: var(--light)
        }

        .btn-grad {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: #fff;
            border: none
        }

        .btn-grad:hover {
            opacity: .95
        }

        .sidebar {
            background: var(--sidebar-bg);
            border-radius: var(--radius);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            box-shadow: var(--shadow);
            overflow: auto
        }

        .section-title {
            color: #fff;
            font-size: 1.05rem;
            font-weight: 800;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .categories-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px
        }

        .category-card {
            background: rgba(255, 255, 255, .08);
            border-radius: var(--radius-sm);
            padding: 14px 10px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, .1)
        }

        .category-card:hover {
            background: rgba(255, 255, 255, .14);
            transform: translateY(-2px)
        }

        .category-card.active {
            background: var(--primary);
            box-shadow: 0 4px 12px rgba(14, 165, 233, .4)
        }

        .category-icon {
            font-size: 1.4rem;
            margin-bottom: 6px;
            color: var(--primary-light)
        }

        .category-card.active .category-icon {
            color: #fff
        }

        .category-name {
            color: #fff;
            font-size: .95rem;
            font-weight: 700
        }

        .filters-section {
            background: rgba(255, 255, 255, .06);
            border-radius: var(--radius-sm);
            padding: 16px
        }

        .filter-group {
            margin-bottom: 12px
        }

        .filter-label {
            color: var(--gray-light);
            font-size: .85rem;
            margin-bottom: 6px;
            display: block
        }

        .filter-select,
        .filter-input {
            width: 100%;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(255, 255, 255, .16);
            background: rgba(255, 255, 255, .1);
            color: #fff;
            font-size: .95rem
        }

        .filter-select:focus,
        .filter-input:focus {
            outline: none;
            border-color: var(--primary-light)
        }

        .products-section {
            background: #fff;
            border-radius: var(--radius);
            padding: 18px;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow);
            overflow: hidden
        }

        .search-box {
            position: relative;
            margin-bottom: 12px
        }

        .search-input {
            width: 100%;
            padding: 12px 46px 12px 14px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: var(--light)
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray)
        }

        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 6px 0 12px
        }

        .products-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--dark)
        }

        .view-btn {
            padding: 8px 10px;
            border-radius: 10px;
            background: var(--light);
            border: 1px solid var(--border);
            cursor: pointer
        }

        .view-btn.active {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary)
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 14px;
            overflow: auto;
            padding: 4px;
            flex: 1
        }

        .product-card {
            background: #fff;
            border-radius: 12px;
            padding: 14px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            transition: var(--transition);
            cursor: pointer;
            display: flex;
            flex-direction: column
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg)
        }

        .product-image {
            height: 110px;
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            color: var(--primary);
            font-size: 1.8rem
        }

        .product-name {
            font-weight: 800;
            margin-bottom: 6px;
            font-size: 1rem
        }

        .product-info {
            display: flex;
            justify-content: space-between;
            margin-top: auto
        }

        .product-price {
            font-weight: 900;
            color: var(--primary)
        }

        .product-stock {
            font-size: .82rem;
            color: var(--gray)
        }

        .cart-section {
            background: #fff;
            border-radius: var(--radius);
            padding: 18px;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow);
            overflow: hidden
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border)
        }

        .cart-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--dark)
        }

        .cart-items {
            flex: 1;
            overflow: auto;
            margin-bottom: 10px
        }

        .cart-item {
            display: flex;
            gap: 10px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border)
        }

        .cart-item-image {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary)
        }

        .cart-item-details {
            flex: 1
        }

        .cart-item-name {
            font-weight: 800;
            margin-bottom: 2px
        }

        .cart-item-price {
            color: var(--primary);
            font-weight: 900
        }

        .cart-item-controls {
            display: flex;
            align-items: center;
            gap: 8px
        }

        .quantity-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: var(--light);
            color: var(--dark);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer
        }

        .quantity-btn:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary)
        }

        .cart-item-quantity {
            font-weight: 800;
            min-width: 26px;
            text-align: center
        }

        .cart-summary {
            background: var(--light);
            border-radius: 10px;
            padding: 14px;
            margin-top: auto
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px
        }

        .summary-total {
            font-weight: 900;
            font-size: 1.15rem;
            color: var(--primary);
            border-top: 1px solid var(--border);
            padding-top: 10px;
            margin-top: 4px
        }

        .checkout-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 900;
            font-size: 1.05rem;
            cursor: pointer;
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(14, 165, 233, .3)
        }

        .badge-soft {
            background: #f8fafc;
            border: 1px solid rgba(0, 0, 0, .06);
            color: #334155;
            border-radius: 999px;
            padding: .15rem .5rem;
            display: inline-block
        }

        .price-badge {
            background: #ecfeff;
            border: 1px solid #a5f3fc;
            color: #155e75;
            border-radius: 999px;
            padding: .2rem .55rem;
            display: inline-block;
            min-width: 80px;
            text-align: center;
            font-weight: 800
        }

        .fade-in {
            animation: fadeIn .3s ease
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }
    </style>

    {{-- Header --}}
    <div class="header">
        <div>
            <h1><i class="fa-solid fa-cash-register"></i> {{ __('إدارة المبيعات') }}</h1>
            <p>{{ __('إدارة فواتير البيع الخاصة بك') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-light" href="{{ route('pos.index') }}"><i class="fa-solid fa-list"></i>
                {{ __('قائمة المبيعات') }}</a>
        </div>
    </div>

    <form wire:submit.prevent="save" class="pos-system">
        {{-- Sidebar --}}
        <aside class="sidebar">
            <div>
                <div class="section-title"><i class="fa-solid fa-th-large"></i> {{ __('الأقسام') }}</div>
                <div class="categories-grid">
                    @foreach ($categories as $cat)
                        @php
                            $raw = $cat->name;
                            $cname = is_array($raw)
                                ? $raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw) ?? ''))
                                : (is_string($raw) &&
                                (str_starts_with(trim($raw), '{') || str_starts_with(trim($raw), '['))
                                    ? json_decode($raw, true)[app()->getLocale()] ??
                                        (json_decode($raw, true)['ar'] ?? $raw)
                                    : $raw);
                        @endphp
                        <div class="category-card {{ isset($activeCategoryId) && (int) $activeCategoryId === (int) $cat->id ? 'active' : '' }}"
                            wire:click="selectCategory({{ $cat->id }})">
                            <div class="category-icon"><i class="fa-solid fa-folder-tree"></i></div>
                            <div class="category-name">{{ $cname }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="filters-section">
                <div class="section-title"><i class="fa-solid fa-filter"></i> {{ __('الفلاتر والبحث') }}</div>

                <div class="filter-group">
                    <label class="filter-label">{{ __('بحث بالمنتج / الباركود / SKU') }}</label>
                    <input type="text" class="filter-input" placeholder="{{ __('اكتب للبحث') }}"
                        wire:model.debounce.400ms="filterProductText">
                </div>

                <div class="filter-group">
                    <label class="filter-label">{{ __('المخزن') }}</label>
                    <select class="filter-select" wire:model="warehouse_id">
                        <option value="">{{ __('اختر') }}</option>
                        @foreach ($warehouses as $w)
                            @php
                                $raw = $w->name;
                                $wname = is_array($raw)
                                    ? $raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw) ?? ''))
                                    : (is_string($raw) &&
                                    (str_starts_with(trim($raw), '{') || str_starts_with(trim($raw), '['))
                                        ? json_decode($raw, true)[app()->getLocale()] ??
                                            (json_decode($raw, true)['ar'] ?? $raw)
                                        : $raw);
                            @endphp
                            <option value="{{ $w->id }}">{{ $wname }}</option>
                        @endforeach
                    </select>
                    @error('warehouse_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="filter-group">
                    <label class="filter-label">{{ __('العميل (اختياري)') }}</label>
                    <select class="filter-select" wire:model="customer_id">
                        <option value="">{{ __('اختيار') }}</option>
                        @foreach ($customers as $c)
                            @php
                                $raw = $c->name;
                                $cname = is_array($raw)
                                    ? $raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw) ?? ''))
                                    : (is_string($raw) &&
                                    (str_starts_with(trim($raw), '{') || str_starts_with(trim($raw), '['))
                                        ? json_decode($raw, true)[app()->getLocale()] ??
                                            (json_decode($raw, true)['ar'] ?? $raw)
                                        : $raw);
                            @endphp
                            <option value="{{ $c->id }}">{{ $cname }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">{{ __('تاريخ الفاتورة') }}</label>
                    <input type="date" class="filter-input" wire:model="pos_date">
                    @error('pos_date')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="filter-group">
                    <label class="filter-label">{{ __('ملاحظات (اختياري)') }}</label>
                    <input type="text" class="filter-input" placeholder="{{ __('ملاحظات تظهر في الطباعة') }}"
                        wire:model.defer="notes_ar">
                </div>
            </div>
        </aside>

        {{-- Products --}}
        <section class="products-section">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="{{ __('ابحث عن منتج...') }}"
                    wire:model.debounce.300ms="filterProductText">
                <div class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
            </div>

            <div class="products-header">
                <div class="products-title">{{ __('المنتجات المتاحة') }}</div>
                <div class="view-options">
                    <button class="view-btn active" type="button"><i class="fa-solid fa-grip"></i></button>
                    <button class="view-btn" type="button"><i class="fa-solid fa-list"></i></button>
                </div>
            </div>

            <div class="products-grid">
                @php $grid = ($catalogProducts ?? $products); @endphp
                @forelse($grid as $p)
                    @php
                        $passCat = empty($activeCategoryId) || (int) $p->category_id === (int) $activeCategoryId;
                        $txt = trim((string) $filterProductText);
                        $rawName = $p->name;
                        $pname = is_array($rawName)
                            ? $rawName[app()->getLocale()] ?? ($rawName['ar'] ?? (reset($rawName) ?? ''))
                            : (is_string($rawName) &&
                            (str_starts_with(trim($rawName), '{') || str_starts_with(trim($rawName), '['))
                                ? json_decode($rawName, true)[app()->getLocale()] ??
                                    (json_decode($rawName, true)['ar'] ?? $rawName)
                                : $rawName);
                        $haystack = mb_strtolower(trim($pname . ' ' . ($p->sku ?? '') . ' ' . ($p->barcode ?? '')));
                        $match = !$txt || str_contains($haystack, mb_strtolower($txt));
                        $minP = isset($p->min_price) ? (float) $p->min_price : 0.0;
                    @endphp
                    @if ($passCat && $match)
                        <div class="product-card fade-in" wire:click="addProductToCart({{ $p->id }})"
                            title="{{ __('إضافة للسلة') }}">
                            <div class="product-image"><i class="fa-solid fa-box-open"></i></div>
                            <div class="product-name">{{ $pname }}</div>
                            <div class="product-info">
                                <div class="product-price">{{ number_format($minP, 2) }} <span
                                        style="color:#64748b;font-weight:600">{{ __('ر.س') }}</span></div>
                                <div class="product-stock">{{ __('متاح') }}</div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-muted">{{ __('لا توجد منتجات') }}</div>
                @endforelse
            </div>
        </section>

        {{-- Cart --}}
        <aside class="cart-section">
            <div class="cart-header">
                <div class="cart-title"><i class="fa-solid fa-bag-shopping me-1"></i> {{ __('سلة المشتريات') }}</div>
                <div class="cart-actions d-flex gap-2">
                    <button class="btn btn-light" type="button" wire:click="clearCart"><i
                            class="fa-solid fa-trash"></i> {{ __('إفراغ السلة') }}</button>
                    <button class="btn btn-light" type="button" data-bs-toggle="modal"
                        data-bs-target="#orderPreviewModal" {{ empty($rows) || !$warehouse_id ? 'disabled' : '' }}>
                        <i class="fa-solid fa-eye"></i> {{ __('عرض الطلب') }}
                    </button>
                    <button class="btn btn-light" type="button" onclick="printOrder()"
                        {{ empty($rows) || !$warehouse_id ? 'disabled' : '' }}>
                        <i class="fa-solid fa-print"></i> {{ __('طباعة') }}
                    </button>
                </div>
            </div>

            <div class="cart-items">
                @forelse($rows as $i => $r)
                    @php $pname = $r['preview']['name'] ?? '—'; @endphp
                    <div class="cart-item fade-in" wire:key="row-{{ $i }}">
                        <div class="cart-item-image"><i class="fa-solid fa-box"></i></div>

                        <div class="cart-item-details">
                            <div class="cart-item-name">{{ $pname }}</div>

                            <div class="d-flex align-items-center gap-2 mb-1">
                                {{-- Unit select --}}
                                <select class="form-select form-select-sm" style="width:auto"
                                    wire:model="rows.{{ $i }}.unit_id"
                                    wire:change="rowUnitChanged({{ $i }})">
                                    @if (!empty($r['unit_options']))
                                        @foreach ($r['unit_options'] as $uid => $uname)
                                            <option value="{{ $uid }}">{{ $uname }}</option>
                                        @endforeach
                                    @else
                                        <option value="">{{ __('الوحدة') }}</option>
                                    @endif
                                </select>

                                {{-- Price (readonly) --}}
                                <span class="price-badge">{{ number_format((float) ($r['unit_price'] ?? 0), 2) }}</span>
                            </div>

                            {{-- Line total --}}
                            <div class="cart-item-price">{{ __('الإجمالي:') }}
                                <strong>{{ number_format((float) ($r['qty'] ?? 0) * (float) ($r['unit_price'] ?? 0), 2) }}</strong>
                                {{ __('ر.س') }}
                            </div>
                        </div>

                        <div class="cart-item-controls">
                            <button class="quantity-btn" type="button"
                                wire:click="decQty({{ $i }})">-</button>
                            <div class="cart-item-quantity">{{ (float) ($r['qty'] ?? 0) }}</div>
                            <button class="quantity-btn" type="button"
                                wire:click="incQty({{ $i }})">+</button>

                            <button class="btn btn-light" type="button" title="{{ __('حذف السطر') }}"
                                wire:click="removeRow({{ $i }})">
                                <i class="fa-solid fa-trash-can" style="color:#ef4444"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">{{ __('السلة فارغة - اختر منتجًا من الوسط') }}</div>
                @endforelse
            </div>

            {{-- Totals --}}
            <div class="cart-summary">
                <div class="summary-row">
                    <span>{{ __('الإجمالي الفرعي') }}</span><span>{{ number_format((float) $subtotal, 2) }}
                        {{ __('ر.س') }}</span></div>
                <div class="summary-row align-items-center gap-2">
                    <span>{{ __('الخصم') }}</span>
                    <input type="number" step="0.01" class="form-control form-control-sm" style="width:120px"
                        wire:model.lazy="discount">
                </div>
                <div class="summary-row align-items-center gap-2">
                    <span>{{ __('الضريبة') }}</span>
                    <input type="number" step="0.01" class="form-control form-control-sm" style="width:120px"
                        wire:model.lazy="tax">
                </div>
                <div class="summary-row summary-total">
                    <span>{{ __('الإجمالي النهائي') }}</span><span>{{ number_format((float) $grand, 2) }}
                        {{ __('ر.س') }}</span></div>

                <button class="checkout-btn" type="submit" wire:loading.attr="disabled">
                    <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                    <i class="fa-solid fa-credit-card"></i>
                    {{ $pos_id ? __('تحديث') : __('إتمام عملية الدفع / الحفظ') }}
                </button>
            </div>
        </aside>
    </form>

    {{-- Order Preview Modal --}}
    <div class="modal fade" id="orderPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" id="printArea">
                <div class="modal-header">
                    <h6 class="modal-title"><i class="fa-solid fa-receipt me-1"></i> {{ __('معاينة الطلب') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('إغلاق') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-bold">{{ __('فاتورة بيع') }}</div>
                            <div class="small text-muted">{{ __('التاريخ') }}: {{ $pos_date }}</div>
                            <div class="small text-muted">{{ __('المخزن') }}:
                                @php
                                    $w = $warehouses->firstWhere('id', $warehouse_id);
                                    $raw = $w->name ?? null;
                                    $wname = is_array($raw)
                                        ? $raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw) ?? ''))
                                        : (is_string($raw) &&
                                        (str_starts_with(trim($raw), '{') || str_starts_with(trim($raw), '['))
                                            ? json_decode($raw, true)[app()->getLocale()] ??
                                                (json_decode($raw, true)['ar'] ?? $raw)
                                            : $raw);
                                @endphp
                                {{ $wname ?? '—' }}
                            </div>
                            <div class="small text-muted">{{ __('العميل') }}:
                                @php
                                    $c = $customers->firstWhere('id', $customer_id);
                                    $raw = $c->name ?? null;
                                    $cname = is_array($raw)
                                        ? $raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw) ?? ''))
                                        : (is_string($raw) &&
                                        (str_starts_with(trim($raw), '{') || str_starts_with(trim($raw), '['))
                                            ? json_decode($raw, true)[app()->getLocale()] ??
                                                (json_decode($raw, true)['ar'] ?? $raw)
                                            : $raw);
                                @endphp
                                {{ $cname ?? '—' }}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">{{ $pos_id ? '#' . $pos_id : __('(غير محفوظ)') }}</div>
                            @if (!empty($notes_ar))
                                <div class="small">{{ $notes_ar }}</div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('المنتج') }}</th>
                                    <th class="text-center">{{ __('الوحدة') }}</th>
                                    <th class="text-center">{{ __('الكمية') }}</th>
                                    <th class="text-center">{{ __('سعر الوحدة') }}</th>
                                    <th class="text-end">{{ __('الإجمالي') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $r)
                                    <tr>
                                        <td>{{ $r['preview']['name'] ?? '—' }}</td>
                                        <td class="text-center">{{ $r['preview']['uom'] ?? ($r['uom_text'] ?? '—') }}
                                        </td>
                                        <td class="text-center">{{ (float) ($r['qty'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format((float) ($r['unit_price'] ?? 0), 2) }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format((float) ($r['qty'] ?? 0) * (float) ($r['unit_price'] ?? 0), 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">{{ __('الإجمالي الفرعي') }}</th>
                                    <th class="text-end">{{ number_format((float) $subtotal, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">{{ __('الخصم') }}</th>
                                    <th class="text-end">{{ number_format((float) $discount, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">{{ __('الضريبة') }}</th>
                                    <th class="text-end">{{ number_format((float) $tax, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">{{ __('الإجمالي النهائي') }}</th>
                                    <th class="text-end">{{ number_format((float) $grand, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">{{ __('إغلاق') }}</button>
                    <button class="btn btn-grad" onclick="printOrder()"><i class="fa-solid fa-print"></i>
                        {{ __('طباعة') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Print helper --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function printOrder() {
            const node = document.getElementById('printArea');
            if (!node) {
                return window.print();
            }
            const w = window.open('', '_blank', 'width=900,height=700');
            w.document.open();
            w.document.write(`
                <html dir="rtl" lang="ar">
                <head>
                    <meta charset="utf-8" />
                    <title>{{ __('فاتورة بيع') }}</title>
                    <style>
                        body{font-family: system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans"; padding:16px}
                        table{width:100%;border-collapse:collapse}
                        th,td{border:1px solid #ddd;padding:6px}
                        .text-end{text-align:right}
                        .text-center{text-align:center}
                        .table-light th{background:#f5f5f5}
                    </style>
                </head>
                <body>${node.innerHTML}</body>
                </html>
            `);
            w.document.close();
            w.focus();
            w.print();
            setTimeout(() => w.close(), 300);
        }
    </script>
</div>
