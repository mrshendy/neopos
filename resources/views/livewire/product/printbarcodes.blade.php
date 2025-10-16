<div class="container py-3">

    {{-- شريط أدوات غير قابل للطباعة --}}
    <div class="no-print d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0"><i class="mdi mdi-barcode me-2"></i>{{ __('pos.print_page_title') ?? 'طباعة الباركود' }}</h5>
            <small class="text-muted">{{ __('pos.print_page_hint') ?? 'راجع المعاينة ثم اضغط طباعة' }}</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('product.index') }}" class="btn btn-light rounded-pill">
                <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') ?? 'رجوع' }}
            </a>
            <button onclick="window.print()" class="btn btn-primary rounded-pill px-3">
                <i class="mdi mdi-printer"></i> {{ __('pos.btn_print') ?? 'طباعة' }}
            </button>
        </div>
    </div>

    {{-- شبكة الملصقات --}}
    <div class="grid" id="labels"></div>
</div>

{{-- JsBarcode --}}
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
                JsBarcode(wrap.querySelector('.bc'), p.barcode, {
                    format: 'CODE128', height: 48, margin: 0, displayValue: false
                });
            } catch(e){}

            return wrap;
        }

        items.forEach(p => {
            const count = Math.max(1, parseInt(p.qty || 1));
            for(let i=0; i<count; i++){
                labels.appendChild(makeItem(p));
            }
        });
    })();
</script>

<style>
    @page { size: A4; margin: 10mm; }
    @media print { .no-print { display: none !important; } }

    .grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* 3 أعمدة A4 */
        gap: 8mm;
    }
    .label {
        border: 1px dashed rgba(0,0,0,.25);
        border-radius: 8px;
        padding: 6px 8px;
        text-align: center;
        page-break-inside: avoid;
        break-inside: avoid;
        background: #fff;
    }
    .name {
        font-size: 12px; font-weight: 600;
        margin: 2px 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .sku { font-size: 11px; color:#6c757d; margin-top: 2px; }
    .bc { width: 100%; height: 60px; }
</style>
