<div class="inventory-manage">

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
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="mb-1 fw-bold">
                <i class="mdi mdi-view-grid-outline me-2"></i> {{ __('inventory.manage_title') }}
            </h3>
            <div class="text-muted small">{{ __('inventory.manage_subtitle') }}</div>
        </div>
    </div>

    {{-- تحضير كارد "إضافة مباشرة إلى المخزن" وإضافته لو غير موجود --}}
    @php
        $directStoreCard = [
            'key'   => 'direct_store',
            'icon'  => 'mdi-database-plus-outline', // أو 'mdi-warehouse' إن كانت أيقوناتك قديمة
            'route' => 'inv.ds',                    // الراوت القصير
        ];

        if (!isset($modules) || !is_array($modules)) {
            $modules = [$directStoreCard];
        } else {
            $already = collect($modules)->contains(function($m){
                return ($m['key'] ?? null) === 'direct_store';
            });
            if (!$already) {
                array_unshift($modules, $directStoreCard); // يظهر أول عنصر
            }
        }
    @endphp

    {{-- Modules Grid --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <div class="row g-4">
                @foreach($modules as $m)
                    @php
                        $exists = \Illuminate\Support\Facades\Route::has($m['route']);
                        $href   = $exists ? route($m['route']) : 'javascript:void(0)';
                    @endphp

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="{{ $href }}"
                           class="hub-card {{ $exists ? '' : 'is-disabled' }}"
                           @unless($exists) aria-disabled="true" title="{{ __('inventory.route_missing') }}" @endunless>
                            <div class="hub-card__icon">
                                <i class="mdi {{ $m['icon'] }}"></i>
                            </div>
                            <div class="hub-card__title">
                                {{ __('inventory.module_'.$m['key']) }}
                            </div>
                            <div class="hub-card__subtitle">
                                {{ $exists ? __('inventory.open') : __('inventory.soon') }}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Styles — نفس الشكل بالصورة --}}
<style>
/* حاوية عامة */
.inventory-manage { padding: .25rem; }

/* كارت الشبكة */
.hub-card{
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    text-align:center; gap:10px;
    width:100%; min-height:180px; padding:22px;
    background:#fff; border-radius:22px;
    border:1px solid rgba(13,110,253,.06);
    box-shadow:0 6px 18px rgba(13,110,253,.06);
    text-decoration:none;
    transition:.18s ease-in-out;
}
.hub-card:hover{
    transform:translateY(-3px);
    box-shadow:0 12px 28px rgba(13,110,253,.12);
}

/* كبسولة الأيقونة */
.hub-card__icon{
    width:68px; height:68px; border-radius:16px;
    display:flex; align-items:center; justify-content:center;
    background:#f5f8ff;
    border:1px solid rgba(13,110,253,.10);
    box-shadow:0 3px 10px rgba(13,110,253,.08) inset;
}
.hub-card__icon i{ font-size:30px; color:#0d6efd; }

/* العنوان والشرح */
.hub-card__title{
    font-weight:700; font-size:1.05rem;
    color:#0d6efd; line-height:1.2;
}
.hub-card__subtitle{
    font-size:.9rem; color:#6b7280;
}

/* تعطيل عند عدم وجود راوت */
.hub-card.is-disabled{ opacity:.55; pointer-events:none; }
</style>
