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
    <div class="d-flex align-products-center justify-content-between mb-3">
        <div>
            <h3 class="mb-1 fw-bold">
                <i class="mdi mdi-view-grid-outline me-2"></i> {{ __('inventory.manage_title') }}
            </h3>
            <div class="text-muted small">{{ __('inventory.manage_subtitle') }}</div>
        </div>
    </div>

    {{-- Modules Grid --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <div class="row g-3">
                @foreach($modules as $m)
                    @php
                        // احسب وجود الراوت هنا لتفادي أي مفاتيح ناقصة في المصفوفة
                        $exists = \Illuminate\Support\Facades\Route::has($m['route']);
                        $href   = $exists ? route($m['route']) : 'javascript:void(0)';
                    @endphp

                    <div class="col-6 col-md-4 col-xl-3">
                        <a href="{{ $href }}" class="inv-card {{ $exists ? '' : 'inv-card--disabled' }}"
                           @unless($exists) aria-disabled="true" title="{{ __('inventory.route_missing') }}" @endunless>
                            <div class="inv-card__icon">
                                <i class="mdi {{ $m['icon'] }}"></i>
                            </div>

                            <div class="inv-card__text">
                                <div class="inv-card__title">{{ __('inventory.module_'.$m['key']) }}</div>
                                <div class="inv-card__subtitle">
                                    {{ $exists ? __('inventory.open') : __('inventory.soon') }}
                                </div>
                            </div>

                            <div class="inv-card__chevron">
                                <i class="mdi mdi-chevron-{{ app()->getLocale()==='ar' ? 'left' : 'right' }}"></i>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Styles (Modern cards) --}}
<style>
.inventory-manage .inv-card{
    display:flex; align-products:center; gap:14px;
    width:100%; padding:16px 18px; border-radius:18px;
    background: linear-gradient(180deg,#ffffff 0%,#f9fbff 100%);
    border:1px solid rgba(13,110,253,.08);
    box-shadow: 0 6px 18px rgba(13,110,253,.06);
    text-decoration:none; transition:.18s ease; position:relative; overflow:hidden;
}
.inventory-manage .inv-card:hover{
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(13,110,253,.12);
}
.inventory-manage .inv-card__icon{
    width:54px; height:54px; border-radius:14px;
    display:flex; align-products:center; justify-content:center;
    background: radial-gradient(120% 120% at 0% 0%, #e8f1ff 0%, #f3f7ff 100%);
    border:1px solid rgba(13,110,253,.10);
    flex: 0 0 54px;
}
.inventory-manage .inv-card__icon i{ font-size:26px; color:#0d6efd; }

.inventory-manage .inv-card__text{ flex:1 1 auto; min-width:0; }
.inventory-manage .inv-card__title{
    font-weight:700; font-size:1.02rem; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.inventory-manage .inv-card__subtitle{ font-size:.84rem; color:#6b7280; }

.inventory-manage .inv-card__chevron{
    color:#94a3b8; font-size:22px; margin-{{ app()->getLocale()==='ar' ? 'right' : 'left' }}: auto;
}

.inventory-manage .inv-card--disabled{ opacity:.55; pointer-events:none; }
</style>
