<div class="row g-3 mb-4">
    {{-- الوحدات --}}
    <div class="col-12 col-sm-6 col-xl-3">
        @php $uRoute = \Illuminate\Support\Facades\Route::has($unitsRoute) ? route($unitsRoute) : '#'; @endphp
        <a href="{{ $uRoute }}" class="sq-link" aria-label="{{ __('pos.nav_units') }}" style="--accent:#1a5490">
            <div class="sq-card rounded-4 shadow-sm hover-lift">
                <div class="sq-card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="sq-icon-wrap">
                        <i class="mdi mdi-ruler-square fs-2"></i>
                    </div>
                    <div class="fw-bold mt-2">{{ __('pos.nav_units') }}</div>
                    <div class="text-muted xsmall">{{ __('pos.nav_units_sub') }}</div>
                </div>
            </div>
        </a>
    </div>

    {{-- الأقسام --}}
    <div class="col-12 col-sm-6 col-xl-3">
        @php $cRoute = \Illuminate\Support\Facades\Route::has($categoriesRoute) ? route($categoriesRoute) : '#'; @endphp
        <a href="{{ $cRoute }}" class="sq-link" aria-label="{{ __('pos.nav_categories') }}" style="--accent:#6f42c1">
            <div class="sq-card rounded-4 shadow-sm hover-lift">
                <div class="sq-card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="sq-icon-wrap">
                        <i class="mdi mdi-shape-outline fs-2"></i>
                    </div>
                    <div class="fw-bold mt-2">{{ __('pos.nav_categories') }}</div>
                    <div class="text-muted xsmall">{{ __('pos.nav_categories_sub') }}</div>
                </div>
            </div>
        </a>
    </div>

    {{-- بيانات الأصناف --}}
    <div class="col-12 col-sm-6 col-xl-3">
        @php $pRoute = \Illuminate\Support\Facades\Route::has($productsRoute) ? route($productsRoute) : '#'; @endphp
        <a href="{{ $pRoute }}" class="sq-link" aria-label="{{ __('pos.nav_products_data') }}" style="--accent:#0d6efd">
            <div class="sq-card rounded-4 shadow-sm hover-lift">
                <div class="sq-card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="sq-icon-wrap">
                        <i class="mdi mdi-database-cog-outline fs-2"></i>
                    </div>
                    <div class="fw-bold mt-2">{{ __('pos.nav_products_data') }}</div>
                    <div class="text-muted xsmall">{{ __('pos.nav_products_data_sub') }}</div>
                </div>
            </div>
        </a>
    </div>

    {{-- إعدادات عامة --}}
    <div class="col-12 col-sm-6 col-xl-3">
        @php $sRoute = \Illuminate\Support\Facades\Route::has($settingsRoute) ? route($settingsRoute) : '#'; @endphp
        <a href="{{ $sRoute }}" class="sq-link" aria-label="{{ __('pos.nav_general_settings') }}" style="--accent:#20c997">
            <div class="sq-card rounded-4 shadow-sm hover-lift">
                <div class="sq-card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="sq-icon-wrap">
                        <i class="mdi mdi-cog-outline fs-2"></i>
                    </div>
                    <div class="fw-bold mt-2">{{ __('pos.nav_general_settings') }}</div>
                    <div class="text-muted xsmall">{{ __('pos.nav_general_settings_sub') }}</div>
                </div>
            </div>
        </a>
    </div>
</div>

<style>
    /* إعدادات عامة */
    .sq-link{
        text-decoration:none; outline:none;
    }
    .sq-link:focus-visible .sq-card{
        box-shadow: 0 0 0 .25rem rgba(13,110,253,.15), 0 .5rem 1rem rgba(0,0,0,.08);
        border-color: var(--accent, #0d6efd);
    }

    /* كارت مربّع أصغر + أنظف */
    .sq-card{
        --accent: #0d6efd;            /* لون الأكسنت لكل كارت (قابل للتغيير inline) */
        position:relative; width:100%; background: var(--bs-body-bg,#fff);
        border:1px solid rgba(0,0,0,.06);
        transition:transform .2s ease, box-shadow .2s ease, border-color .2s ease, background .2s ease;
        aspect-ratio: 1 / 1;          /* مربع حقيقي */
        max-height: 220px;            /* تصغير الارتفاع */
        min-height: 170px;            /* ومنع الصِغَر الزائد */
    }
    /* fallback للمتصفحات القديمة */
    @supports not (aspect-ratio: 1 / 1){
        .sq-card::before{content:"";display:block;padding-top:100%;}
        .sq-card .sq-card-body{position:absolute;inset:0;}
    }

    .sq-card-body{ position:absolute; inset:0; padding: .85rem 1rem; }

    .sq-icon-wrap{
        width: 64px; height: 64px;    /* أصغر */
        display:inline-flex; align-items:center; justify-content:center;
        border-radius: 1rem;
        background: #f6f8fa; border:1px solid rgba(0,0,0,.06);
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease, border-color .2s ease;
    }

    .hover-lift:hover{
        transform: translateY(-3px);
        box-shadow: 0 .6rem 1.2rem rgba(0,0,0,.08);
        border-color: var(--accent);
        background: linear-gradient(180deg, rgba(0,0,0,.00), rgba(0,0,0,.02));
    }
    .hover-lift:hover .sq-icon-wrap{
        border-color: var(--accent);
        background: rgba(13,110,253,.04); /* لمسة خفيفة */
    }

    /* أحجام خط أدق */
    .fs-2{ font-size: calc(1.25rem + .9vw) !important; } /* أيقونة كبيرة بدون مبالغة */
    @media (min-width: 1200px){
        .fs-2{ font-size: 1.75rem !important; }
    }
    .xsmall{ font-size: .8rem; }

    /* دعم تباين داكن/فاتح أفضل */
    @media (prefers-color-scheme: dark){
        .sq-card{ border-color: rgba(255,255,255,.08); background: rgba(255,255,255,.02); }
        .sq-icon-wrap{ background: rgba(255,255,255,.04); border-color: rgba(255,255,255,.08); }
        .hover-lift:hover{ background: rgba(255,255,255,.03); }
        /* خلفية بيضاء + حركة خفيفة */
.sq-card{
    position: relative;
    background: #fff;                 /* خلفية بيضاء صريحة */
    border: 1px solid rgba(0,0,0,.06);
    overflow: hidden;                  /* لإخفاء طبقة الحركة */
    aspect-ratio: 1 / 1;
    max-height: 220px;
    min-height: 170px;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}

/* طبقة الحركة: لمعان أبيض شفاف يمر ببطء */
.sq-card::after{
    content: "";
    position: absolute; inset: -35%;
    background: linear-gradient(120deg,
        rgba(255,255,255,0) 0%,
        rgba(255,255,255,.75) 15%,
        rgba(255,255,255,0) 30%,
        rgba(255,255,255,0) 100%);
    transform: translateX(-120%) rotate(0.001deg);
    animation: sqSweep 10s linear infinite; /* حركة دائمة هادئة */
    pointer-events: none;                    /* لا تعيق النقر */
    mix-blend-mode: screen;                  /* اندماج ناعم فوق الأبيض */
    opacity: .35;                            /* تأكد أنها subtle */
}

/* تأثير hover اختياري (مكمل) */
.sq-card.hover-lift:hover{
    transform: translateY(-3px);
    box-shadow: 0 .6rem 1.2rem rgba(0,0,0,.08);
}

/* حجم الأيقونة والحاوية */
.sq-icon-wrap{
    width: 64px; height: 64px;
    display:inline-flex; align-items:center; justify-content:center;
    border-radius: 1rem;
    background: #fff;                        /* تبقى بيضاء أيضاً */
    border: 1px solid rgba(0,0,0,.06);
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease, background .2s ease;
}

/* حركة اللمعان */
@keyframes sqSweep{
    0%   { transform: translateX(-120%) rotate(0.001deg); }
    60%  { transform: translateX(180%) rotate(0.001deg); }
    100% { transform: translateX(180%) rotate(0.001deg); }
}

/* احترام تفضيل تقليل الحركة لدى المستخدم */
@media (prefers-reduced-motion: reduce){
    .sq-card::after{ animation: none; }
}

    }
</style>
