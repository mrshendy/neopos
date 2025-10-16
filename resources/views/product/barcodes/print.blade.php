<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('pos.print_page_title') ?? 'طباعة الباركود' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        @page { size: A4; margin: 10mm; }
        @media print { .no-print { display: none !important; } }

        body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans"; }
        .toolbar { margin: 12px 0; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8mm; }
        .label {
            border: 1px dashed rgba(0,0,0,.25);
            border-radius: 8px;
            padding: 6px 8px;
            text-align: center;
            page-break-inside: avoid; break-inside: avoid;
            background: #fff;
        }
        .name { font-size: 12px; font-weight: 600; margin: 2px 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sku { font-size: 11px; color:#6c757d; margin-top: 2px; }
        .bc { width: 100%; height: 60px; }
        .btn { padding: 6px 12px; border-radius: 999px; border: 1px solid transparent; cursor: pointer; }
        .btn-primary { background:#0d6efd; border-color:#0d6efd; color:#fff; }
        .btn-light { background:#f8f9fa; border-color:#dee2e6; color:#212529; }
        .toolbar .btn + .btn { margin-inline-start: 8px; }
    </style>
</head>
<body>

<div class="container" style="padding: 12px 0;">

    <div class="no-print toolbar" style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h5 style="margin:0 0 4px 0;">{{ __('pos.print_page_title') ?? 'طباعة الباركود' }}</h5>
            <small style="color:#6c757d">{{ __('pos.print_page_hint') ?? 'راجِع المعاينة ثم اضغط طباعة' }}</small>
        </div>
        <div>
            <a href="{{ route('product.index') }}" class="btn btn-light">
                {{ __('pos.btn_back') ?? 'رجوع' }}
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                {{ __('pos.btn_print') ?? 'طباعة' }}
            </button>
        </div>
    </div>

    <div class="grid" id="labels"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
    (function(){
        const items = @json($items, JSON_UNESCAPED_UNICODE);
        const labels = document.getElementById('labels');

        function makeItem(p){
            const wrap = document.createElement('div');
            wrap.className = 'label';
            wrap.innerHTML =
                '<div class="name" title="'+(p.label||'')+'">'+(p.label||'')+'</div>'+
                '<svg class="bc"></svg>'+
                '<div class="sku">'+(p.sku||'')+' &middot; '+(p.barcode||'')+'</div>';
            try {
                JsBarcode(wrap.querySelector('.bc'), p.barcode, { format:'CODE128', height: 48, margin: 0, displayValue: false });
            } catch(e){}
            return wrap;
        }

        items.forEach(p => {
            const count = Math.max(1, parseInt(p.qty || 1));
            for (let i=0; i<count; i++) labels.appendChild(makeItem(p));
        });
    })();
</script>
</body>
</html>
