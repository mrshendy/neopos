{{-- resources/views/livewire/pos/manage.blade.php --}}
<div class="container-fluid px-3" x-data>
    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-2 small">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-2 small">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>{{ __('pos.input_errors') }}</strong>
            <ul class="mb-0 mt-1 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Icons / Font --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.rtl.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg:#f7fafc; --card:#ffffff; --text:#0f172a; --muted:#64748b; --border:#e2e8f0;
            --radius:12px; --radius-sm:8px; --shadow:0 1px 3px rgba(0,0,0,.08); --shadow-md:0 6px 16px rgba(0,0,0,.08);
            --sky:#0ea5e9; --sky-600:#0284c7; --sky-300:#7dd3fc; --accent:#8b5cf6;
        }
        *{box-sizing:border-box; font-family:'Inter',system-ui,-apple-system,"Segoe UI",Roboto,sans-serif}
        body{background:var(--bg); color:var(--text)}

        .hero{position:relative; border-radius:16px; overflow:hidden; margin:10px 0 14px; border:1px solid var(--border);
              background: radial-gradient(800px 400px at 100% -20%, rgba(255,255,255,.35) 0%, transparent 70%),
                         linear-gradient(135deg, var(--sky) 0%, var(--sky-600) 100%); color:#fff; box-shadow:var(--shadow-md)}
        .hero-inner{display:flex; align-items:center; justify-content:space-between; padding:16px 18px}
        .hero-title{display:flex; align-items:center; gap:12px}
        .hero-title i{background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.25); width:40px; height:40px; border-radius:12px; display:inline-flex; align-items:center; justify-content:center}
        .hero h1{margin:0; font-size:1.15rem; font-weight:800; letter-spacing:.2px}
        .hero-sub{opacity:.95; font-size:.85rem; margin-top:2px}
        .hero-actions{display:flex; gap:8px; align-items:center}
        .hero .btn-ghost{background:rgba(255,255,255,.15); color:#fff; border:1px solid rgba(255,255,255,.25); padding:.45rem .8rem; border-radius:10px; font-size:.85rem; font-weight:600; backdrop-filter:blur(6px)}
        .hero .btn-ghost:hover{background:rgba(255,255,255,.22)}
        .hero .stat{display:flex; gap:10px; align-items:center; padding:.35rem .6rem; border-radius:999px; background:rgba(255,255,255,.14); border:1px solid rgba(255,255,255,.25); font-size:.8rem}

        .toolbar{background:var(--card); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow); padding:12px 14px; margin-bottom:12px}
        .toolbar .form-control,.toolbar .form-select{height:36px; padding:.35rem .7rem; font-size:.9rem; border:1px solid var(--border); border-radius:10px}
        .toolbar .form-control:focus,.toolbar .form-select:focus{border-color:var(--sky); box-shadow:0 0 0 3px rgba(14,165,233,.15); outline:none}
        .toolbar .input-group-text{font-size:.85rem; background:#f8fafc; border:1px solid var(--border); color:var(--muted)}
        .small-note{font-size:.75rem; color:var(--muted); margin-top:4px}

        .pos-grid{display:grid; grid-template-columns:1fr 360px; gap:14px}
        @media(min-width:1200px){.pos-grid{grid-template-columns:1.2fr 420px}}
        @media(max-width:992px){.pos-grid{grid-template-columns:1fr}}

        .panel{background:var(--card); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow)}
        .panel-body{padding:14px}

        .chips{display:flex; gap:8px; overflow:auto; padding:4px 0 10px; margin-bottom:4px}
        .chip{border:1px solid var(--border); background:var(--card); border-radius:999px; padding:.35rem .8rem; font-size:.8rem; white-space:nowrap; cursor:pointer; transition:.18s}
        .chip:hover{background:#f8fafc; border-color:var(--sky-300)}
        .chip.active{background:var(--sky); color:#fff; border-color:var(--sky)}

        .products-grid{display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:10px; max-height:55vh; overflow:auto; padding:4px}
        .product-card{border:1px solid var(--border); border-radius:12px; background:var(--card); padding:12px; cursor:pointer; transition:.18s; display:flex; flex-direction:column; position:relative; overflow:hidden}
        .product-card:hover{transform:translateY(-2px); box-shadow:var(--shadow-md); border-color:var(--sky-300)}
        .product-card::before{content:''; position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,var(--sky),var(--accent))}
        .product-thumb{height:88px; border-radius:10px; background:linear-gradient(135deg,#eef7ff,#e6f6ff); display:flex; align-items:center; justify-content:center; color:var(--sky-600); font-size:1.5rem; margin-bottom:8px}
        .product-name{font-size:.92rem; font-weight:700; color:var(--text); margin-bottom:4px; line-height:1.25}
        .product-meta{display:flex; justify-content:space-between; font-size:.8rem; color:var(--muted); margin-top:auto}
        .product-price{font-weight:800; color:var(--sky-600)}

        .cart-items{max-height:52vh; overflow:auto; padding-right:4px}
        .cart-item{display:flex; gap:10px; border-bottom:1px dashed var(--border); padding:10px 0; transition:.18s}
        .cart-item:hover{background:#f8fafc; margin:0 -8px; padding:10px 8px; border-radius:10px}
        .cart-thumb{width:46px; height:46px; border-radius:10px; background:linear-gradient(135deg,#eef7ff,#e6f6ff); display:flex; align-items:center; justify-content:center; color:var(--sky-600)}
        .cart-name{font-weight:700; font-size:.9rem; margin-bottom:2px}
        .price-badge{border:1px solid var(--border); border-radius:999px; padding:.18rem .6rem; font-size:.8rem; background:var(--card); color:var(--sky-600); font-weight:700}
        .qty-btn{width:28px; height:28px; border-radius:50%; border:1px solid var(--border); background:var(--card); color:var(--text); display:flex; align-items:center; justify-content:center}
        .qty-btn:hover{background:var(--sky); color:#fff; border-color:var(--sky)}
        .summary{background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%); border:1px solid var(--border); border-radius:12px; padding:14px; margin-top:12px}
        .summary-row{display:flex; justify-content:space-between; font-size:.9rem; margin-bottom:8px; padding-bottom:6px; border-bottom:1px dashed var(--border)}
        .summary-total{font-weight:900; font-size:1.08rem; color:var(--sky-600); border-bottom:none; padding-top:8px; margin-top:8px; border-top:1px solid var(--border)}

        .btn-primary{background:var(--sky); color:#fff; border:none; border-radius:10px; padding:.5rem 1rem; font-weight:700}
        .btn-primary:hover{background:var(--sky-600)}
        .btn-outline{border:1px solid var(--border); background:#fff; color:var(--text); border-radius:10px; padding:.45rem .9rem; font-weight:600}
        .btn-outline:hover{border-color:var(--sky-300)}

        .table-sm td,.table-sm th{padding:.5rem .75rem}
        ::-webkit-scrollbar{width:6px;height:6px}
        ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:10px}
        ::-webkit-scrollbar-thumb:hover{background:#94a3b8}
    </style>

    {{-- HERO HEADER --}}
    <div class="hero">
        <div class="hero-inner">
            <div class="hero-title">
                <i class="fa-solid fa-cash-register"></i>
                <div>
                    <h1>{{ __('pos.sales_management') }}</h1>
                    <div class="hero-sub">{{ __('pos.modern_fast_interface') }}</div>
                </div>
            </div>
            <div class="hero-actions">
                <div class="stat">
                    <i class="fa-regular fa-calendar"></i>
                    <span class="small">{{ __('pos.today') }}: {{ $pos_date }}</span>
                </div>
                <a class="btn-ghost" href="{{ route('pos.index') }}"><i class="fa-solid fa-list"></i> {{ __('pos.sales_list') }}</a>
                <button class="btn-ghost" type="button" data-bs-toggle="modal" data-bs-target="#orderPreviewModal" {{ empty($rows) || !$warehouse_id ? 'disabled' : '' }}>
                    <i class="fa-regular fa-eye"></i> {{ __('pos.preview_order') }}
                </button>
                <button class="btn-ghost" type="button" onclick="printOrder()" {{ empty($rows) || !$warehouse_id ? 'disabled' : '' }}>
                    <i class="fa-solid fa-print"></i> {{ __('pos.print') }}
                </button>
            </div>
        </div>
    </div>

    {{-- TOP FILTERS --}}
    <div class="toolbar">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-barcode"></i></span>
                    <input type="text" class="form-control" placeholder="{{ __('pos.barcode_search_ph') }}"
                           wire:model.defer="barcode" wire:keydown.enter.prevent="addByBarcode">
                </div>
                <div class="small-note">{{ __('pos.hint_barcode_enter') }}</div>
            </div>
            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" class="form-control" placeholder="{{ __('pos.product_search_ph') }}"
                           wire:model.debounce.350ms="filterProductText">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <input class="form-control" list="dl-customers" placeholder="{{ __('pos.search_customer_ph') }}"
                       wire:model.lazy="customerSearch" wire:change="selectCustomerBySearch">
                <datalist id="dl-customers">
                    @foreach($customers as $c)
                        @php $raw=$c->name; $name=is_array($raw)?($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw)??''))):(string)$raw; @endphp
                        <option value="{{ $name }}"></option>
                    @endforeach
                </datalist>
            </div>
            <div class="col-6 col-md-2">
                <input class="form-control" list="dl-warehouses" placeholder="{{ __('pos.search_warehouse_ph') }}"
                       wire:model.lazy="warehouseSearch" wire:change="selectWarehouseBySearch">
                <datalist id="dl-warehouses">
                    @foreach($warehouses as $w)
                        @php $raw=$w->name; $name=is_array($raw)?($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw)??''))):(string)$raw; @endphp
                        <option value="{{ $name }}"></option>
                    @endforeach
                </datalist>
            </div>
            <div class="col-12 col-md-2">
                <input class="form-control" list="dl-cats" placeholder="{{ __('pos.search_category_ph') }}"
                       wire:model.lazy="categorySearch" wire:change="selectCategoryBySearch">
                <datalist id="dl-cats">
                    @foreach($categories as $cat)
                        @php $raw=$cat->name; $name=is_array($raw)?($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw)??''))):(string)$raw; @endphp
                        <option value="{{ $name }}"></option>
                    @endforeach
                </datalist>
            </div>
        </div>
    </div>

    {{-- MAIN --}}
    <form wire:submit.prevent="save" class="pos-grid">
        {{-- LEFT: Products --}}
        <section class="panel">
            <div class="panel-body">
                {{-- Category chips --}}
                <div class="chips">
                    <div class="chip {{ empty($activeCategoryId) ? 'active' : '' }}" wire:click="selectCategory(null)">{{ __('pos.all') }}</div>
                    @foreach($categories as $cat)
                        @php $raw=$cat->name; $name=is_array($raw)?($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw)??''))):(string)$raw; @endphp
                        <div class="chip {{ (int)$activeCategoryId === (int)$cat->id ? 'active' : '' }}" wire:click="selectCategory({{ $cat->id }})">{{ $name }}</div>
                    @endforeach
                </div>

                {{-- Products grid --}}
                <div class="products-grid">
                    @php $grid = ($catalogProducts ?? $products); @endphp
                    @forelse($grid as $p)
                        @php
                            $passCat = empty($activeCategoryId) || (int)$p->category_id === (int)$activeCategoryId;
                            $txt = trim((string)$filterProductText);
                            $rawName=$p->name;
                            $pname=is_array($rawName)?($rawName[app()->getLocale()] ?? ($rawName['ar'] ?? (reset($rawName)??''))):(string)$rawName;
                            $hay = mb_strtolower($pname.' '.($p->sku??'').' '.($p->barcode??''));
                            $match=!$txt || str_contains($hay, mb_strtolower($txt));
                            $minP = isset($p->min_price) ? (float)$p->min_price : 0.0;
                        @endphp
                        @if($passCat && $match)
                            <div class="product-card" wire:click="addProductToCart({{ $p->id }})" title="{{ __('pos.add_to_cart') }}">
                                <div class="product-thumb"><i class="fa-solid fa-box-open"></i></div>
                                <div class="product-name">{{ $pname }}</div>
                                <div class="product-meta">
                                    <span class="product-price">{{ number_format($minP,2) }}</span>
                                    <span class="text-muted">{{ __('pos.available') }}</span>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-muted small">{{ __('pos.no_products') }}</div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- RIGHT: Cart --}}
        <aside class="panel">
            <div class="panel-body">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <select class="form-select form-select-sm" wire:model="warehouse_id">
                            <option value="">{{ __('pos.warehouse') }}</option>
                            @foreach($warehouses as $w)
                                @php $raw=$w->name; $name=is_array($raw)?($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw)??''))):(string)$raw; @endphp
                                <option value="{{ $w->id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('warehouse_id') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-6">
                        <select class="form-select form-select-sm" wire:model="customer_id">
                            <option value="">{{ __('pos.customer_optional') }}</option>
                            @foreach($customers as $c)
                                @php $raw=$c->name; $name=is_array($raw)?($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw)??''))):(string)$raw; @endphp
                                <option value="{{ $c->id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <input type="date" class="form-control form-control-sm" wire:model="pos_date">
                        @error('pos_date') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control form-control-sm" placeholder="{{ __('pos.note') }}" wire:model.defer="notes_ar">
                    </div>
                </div>

                <div class="cart-items">
                    @forelse($rows as $i=>$r)
                        <div class="cart-item" wire:key="row-{{ $i }}">
                            <div class="cart-thumb"><i class="fa-solid fa-box"></i></div>
                            <div class="flex-fill">
                                <div class="cart-name">{{ $r['preview']['name'] ?? '—' }}</div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <select class="form-select form-select-sm" style="width:auto"
                                            wire:model="rows.{{ $i }}.unit_id" wire:change="rowUnitChanged({{ $i }})">
                                        @if(!empty($r['unit_options']))
                                            @foreach($r['unit_options'] as $uid=>$uname)
                                                <option value="{{ $uid }}">{{ $uname }}</option>
                                            @endforeach
                                        @else
                                            <option value="">{{ __('pos.unit') }}</option>
                                        @endif
                                    </select>
                                    <span class="price-badge">{{ number_format((float)($r['unit_price'] ?? 0),2) }}</span>
                                </div>
                                <div class="small text-muted mt-1">
                                    {{ __('pos.total') }}:
                                    <strong>{{ number_format((float)($r['qty']??0) * (float)($r['unit_price']??0),2) }}</strong>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <button type="button" class="qty-btn" wire:click="decQty({{ $i }})">-</button>
                                <div class="px-2" style="min-width:32px;text-align:center">{{ (float)($r['qty']??0) }}</div>
                                <button type="button" class="qty-btn" wire:click="incQty({{ $i }})">+</button>
                                <button class="btn-outline btn btn-sm" type="button" wire:click="removeRow({{ $i }})" title="{{ __('pos.delete') }}">
<i class="mdi mdi-delete-outline text-danger"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small text-center py-3">{{ __('pos.cart_empty') }}</div>
                    @endforelse
                </div>

                <div class="summary">
                    <div class="summary-row"><span>{{ __('pos.subtotal') }}</span><span>{{ number_format((float)$subtotal,2) }}</span></div>
                    <div class="summary-row align-items-center">
                        <span>{{ __('pos.discount') }}</span>
                        <input type="number" step="0.01" class="form-control form-control-sm" style="width:120px" wire:model.lazy="discount">
                    </div>
                    <div class="summary-row align-items-center">
                        <span>{{ __('pos.tax') }}</span>
                        <input type="number" step="0.01" class="form-control form-control-sm" style="width:120px" wire:model.lazy="tax">
                    </div>
                    <div class="summary-row summary-total">
                        <span>{{ __('pos.grand_total') }}</span><span>{{ number_format((float)$grand,2) }}</span>
                    </div>
                    <div class="d-grid mt-3">
                        <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                            <i class="fa-regular fa-floppy-disk me-1"></i>{{ $pos_id ? __('pos.update_btn') : __('pos.save_finish') }}
                        </button>
                    </div>
                    <div class="d-grid mt-2">
                        <button class="btn-outline" type="button" wire:click="clearCart">
                            <i class="fa-solid fa-trash me-1"></i>{{ __('pos.clear_cart') }}
                        </button>
                    </div>
                </div>
            </div>
        </aside>
    </form>

    {{-- Order Preview Modal --}}
    <div class="modal fade" id="orderPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" id="printArea">
                <div class="modal-header">
                    <h6 class="modal-title"><i class="fa-solid fa-receipt me-1"></i>{{ __('pos.order_preview') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('pos.close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between small">
                        <div>
                            <div class="fw-bold">{{ __('pos.invoice_sale') }}</div>
                            <div class="text-muted">{{ __('pos.date') }}: {{ $pos_date }}</div>
                            <div class="text-muted">{{ __('pos.warehouse') }}:
                                @php
                                    $w=$warehouses->firstWhere('id',$warehouse_id);
                                    $raw=$w->name ?? null; $wname=is_array($raw)?($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw)??''))):$raw;
                                @endphp
                                {{ $wname ?? '—' }}
                            </div>
                            <div class="text-muted">{{ __('pos.customer') }}:
                                @php
                                    $c=$customers->firstWhere('id',$customer_id);
                                    $raw=$c->name ?? null; $cname=is_array($raw)?($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw)??''))):$raw;
                                @endphp
                                {{ $cname ?? '—' }}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">{{ $pos_id ? '#'.$pos_id : __('pos.unsaved') }}</div>
                            @if(!empty($notes_ar))<div>{{ $notes_ar }}</div>@endif
                        </div>
                    </div>

                    <hr class="my-2">

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>{{ __('pos.product') }}</th>
                                <th class="text-center">{{ __('pos.unit') }}</th>
                                <th class="text-center">{{ __('pos.qty') }}</th>
                                <th class="text-center">{{ __('pos.unit_price') }}</th>
                                <th class="text-end">{{ __('pos.total') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rows as $r)
                                <tr>
                                    <td>{{ $r['preview']['name'] ?? '—' }}</td>
                                    <td class="text-center">{{ $r['preview']['uom'] ?? ($r['uom_text'] ?? '—') }}</td>
                                    <td class="text-center">{{ (float)($r['qty'] ?? 0) }}</td>
                                    <td class="text-center">{{ number_format((float)($r['unit_price'] ?? 0),2) }}</td>
                                    <td class="text-end">{{ number_format((float)($r['qty']??0)*(float)($r['unit_price']??0),2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr><th colspan="4" class="text-end">{{ __('pos.subtotal') }}</th><th class="text-end">{{ number_format((float)$subtotal,2) }}</th></tr>
                            <tr><th colspan="4" class="text-end">{{ __('pos.discount') }}</th><th class="text-end">{{ number_format((float)$discount,2) }}</th></tr>
                            <tr><th colspan="4" class="text-end">{{ __('pos.tax') }}</th><th class="text-end">{{ number_format((float)$tax,2) }}</th></tr>
                            <tr><th colspan="4" class="text-end">{{ __('pos.grand_total') }}</th><th class="text-end">{{ number_format((float)$grand,2) }}</th></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-outline" data-bs-dismiss="modal">{{ __('pos.close') }}</button>
                    <button class="btn btn-primary" onclick="printOrder()"><i class="fa-solid fa-print me-1"></i>{{ __('pos.print') }}</button>
                </div>
            </div>
        </div>
    </div>
<script>
    function printOrder(){
        let printContents = document.getElementById('printArea').innerHTML;
        let originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
</div>
