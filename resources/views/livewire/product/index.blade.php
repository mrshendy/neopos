{{-- resources/views/livewire/product/index.blade.php --}}
<div class="page-wrap">

    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="mb-1 fw-bold"><i class="mdi mdi-package-variant-closed me-2"></i> {{ __('pos.products_title') }}</h3>
            <div class="text-muted small">{{ __('pos.products_management_sub') }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary rounded-pill px-3 shadow-sm">
                <i class="mdi mdi-shape-outline"></i> {{ __('pos.category_title') }}
            </a>
            <a href="{{ route('product.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-plus"></i> {{ __('pos.btn_new_product') }}
            </a>
        </div>
    </div>

    {{-- Toolbar / Filters --}}
    <div class="card shadow-sm rounded-4 mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-magnify"></i> {{ __('pos.search') }}
                    </label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search"
                           placeholder="{{ __('pos.ph_search_sku_barcode_name') }}">
                    <small class="text-muted">{{ __('pos.hint_search_products') }}</small>
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-shape-outline"></i> {{ __('pos.filter_category') }}
                    </label>
                    <select class="form-select" wire:model="category_id">
                        <option value="">{{ __('pos.all') }}</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->getTranslation('name', app()->getLocale()) }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">{{ __('pos.hint_category') }}</small>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-weight-kilogram"></i> {{ __('pos.filter_unit') }}
                    </label>
                    <select class="form-select" wire:model="unit_id">
                        <option value="">{{ __('pos.all') }}</option>
                        @foreach ($units as $u)
                            <option value="{{ $u->id }}">{{ $u->getTranslation('name', app()->getLocale()) }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">{{ __('pos.hint_unit') }}</small>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-toggle-switch"></i> {{ __('pos.filter_status') }}
                    </label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="active">{{ __('pos.status_active') }}</option>
                        <option value="inactive">{{ __('pos.status_inactive') }}</option>
                    </select>
                    <small class="text-muted">{{ __('pos.hint_status') }}</small>
                </div>

                <div class="col-lg-2 text-end">
                    <button class="btn btn-light rounded-pill px-3 shadow-sm"
                            wire:click="$set('search','');$set('status','');$set('category_id','');$set('unit_id','')">
                        <i class="mdi mdi-filter-remove-outline"></i> {{ __('pos.clear_filters') ?? 'مسح الفلاتر' }}
                    </button>
                </div>
            </div>

            {{-- Mini status line + Print barcodes --}}
            <div class="d-flex align-items-center mt-3 small text-muted">
                <i class="mdi mdi-dots-grid me-1"></i>
                <span>{{ __('pos.search') }}:</span>
                <span class="badge bg-light text-dark ms-1">{{ $search ?: '—' }}</span>

                <span class="ms-3">{{ __('pos.filter_status') }}:</span>
                <span class="badge bg-light text-dark ms-1">
                    {{ $status ? __($status == 'active' ? 'pos.status_active' : 'pos.status_inactive') : __('pos.all') }}
                </span>

                <span class="ms-3">
                    <span class="badge bg-info-subtle text-info rounded-pill">
                        <i class="mdi mdi-printer me-1"></i>
                        {{ is_array($selected ?? null) ? count($selected) : 0 }} {{ __('pos.selected') ?? 'مختار' }}
                    </span>
                </span>

                <span class="ms-auto d-flex align-items-center gap-2">
                    <button class="btn btn-outline-dark rounded-pill btn-sm"
                            wire:click="printSelected"
                            {{ (is_array($selected ?? null) && count($selected)) ? '' : 'disabled' }}>
                        <i class="mdi mdi-printer"></i>
                        {{ __('pos.print_barcodes') ?? 'طباعة الباركود' }}
                    </button>

                    <span class="badge bg-primary-subtle text-primary rounded-pill">
                        <i class="mdi mdi-counter me-1"></i>{{ $rows->total() }}
                    </span>
                </span>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle mb-0 pretty-table">
                <thead>
                    <tr>
                        <th class="sticky-col">#</th>
                        <th>{{ __('pos.select') ?? 'اختيار' }}</th>
                        <th>{{ __('pos.qty') ?? 'الكمية' }}</th>
                        <th>{{ __('pos.image') ?? 'الصورة' }}</th>
                        <th>{{ __('pos.sku') }}</th>
                        <th>{{ __('pos.barcode') }}</th>
                        <th>{{ __('pos.name') }}</th>
                        <th>{{ __('pos.unit') }}</th>
                        <th>{{ __('pos.category') }}</th>
                        <th>{{ __('pos.status') }}</th>
                        <th class="text-end">{{ __('pos.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td class="sticky-col text-muted">{{ $row->id }}</td>

                            {{-- Select --}}
                            <td>
                                <input type="checkbox" class="form-check-input"
                                       value="{{ $row->id }}" wire:model="selected"
                                       @disabled(!$row->barcode)>
                            </td>

                            {{-- Qty --}}
                            <td style="max-width:90px;">
                                <input type="number" min="1"
                                       class="form-control form-control-sm text-center"
                                       wire:model.lazy="qty.{{ $row->id }}"
                                       @disabled(! (is_array($selected ?? null) && in_array($row->id, $selected)))>
                            </td>

                            {{-- View Image --}}
                            <td>
                                @php $thumb = $row->thumb_url; @endphp
                                <button class="btn btn-outline-primary btn-sm rounded-pill"
                                        onclick="showProductImage('{{ $thumb }}','{{ addslashes($row->getTranslation('name', app()->getLocale())) }}')"
                                        {{ $thumb ? '' : 'disabled' }}
                                        data-bs-toggle="tooltip"
                                        title="{{ $thumb ? __('pos.view_image') ?? 'عرض الصورة' : __('pos.no_image') ?? 'لا توجد صورة' }}">
                                    <i class="mdi mdi-image-search-outline"></i>
                                    {{ __('pos.view_image') ?? 'عرض الصورة' }}
                                </button>
                            </td>

                            {{-- SKU --}}
                            <td class="font-monospace fw-600">{{ $row->sku }}</td>

                            {{-- Barcode (card + modal) --}}
                            <td>
                                @if ($row->barcode)
                                    <div class="barcode-card text-center">
                                        @if (class_exists('\DNS1D'))
                                            {!! DNS1D::getBarcodeHTML($row->barcode, 'C128', 1.6, 38) !!}
                                        @endif
                                        <div class="small text-muted mt-1">{{ $row->barcode }}</div>
                                        <button class="btn btn-outline-primary btn-xs rounded-pill mt-1"
                                                onclick="viewBarcode('{{ $row->barcode }}','{{ addslashes($row->getTranslation('name', app()->getLocale())) }}')"
                                                data-bs-toggle="tooltip" title="{{ __('pos.view') ?? 'عرض' }}">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Name --}}
                            <td class="text-truncate name-col"
                                title="{{ $row->getTranslation('name', app()->getLocale()) }}">
                                {{ $row->getTranslation('name', app()->getLocale()) }}
                            </td>

                            {{-- Unit --}}
                            <td class="text-truncate">
                                {{ optional($row->unit)->getTranslation('name', app()->getLocale()) ?: '—' }}
                            </td>

                            {{-- Category --}}
                            <td class="text-truncate">
                                {{ optional($row->category)->getTranslation('name', app()->getLocale()) ?: '—' }}
                            </td>

                            {{-- Status --}}
                            <td>
                                <span class="badge rounded-pill px-3 {{ $row->status == 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ $row->status == 'active' ? __('pos.status_active') : __('pos.status_inactive') }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="text-end">
                                <div class="btn-group">
                                    <button class="btn btn-outline-secondary btn-sm rounded-pill m-1"
                                            wire:click="toggleStatus({{ $row->id }})"
                                            data-bs-toggle="tooltip" title="{{ __('pos.status') }}">
                                        <i class="mdi mdi-toggle-switch"></i>
                                    </button>
                                    <a href="{{ route('product.edit', $row->id) }}"
                                       class="btn btn-primary btn-sm rounded-pill m-1"
                                       data-bs-toggle="tooltip" title="{{ __('pos.btn_edit') ?? 'تعديل' }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <button onclick="confirmDelete({{ $row->id }})"
                                            class="btn btn-danger btn-sm rounded-pill m-1"
                                            data-bs-toggle="tooltip" title="{{ __('pos.btn_delete') ?? 'حذف' }}">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11">
                                <div class="py-5 text-center text-muted">
                                    <i class="mdi mdi-package-variant-closed fs-1 d-block mb-2"></i>
                                    {{ __('pos.no_data') }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                <i class="mdi mdi-information-outline"></i>
                {{ $rows->firstItem() }}–{{ $rows->lastItem() }} / {{ $rows->total() }}
            </div>
            <div>
                {{ $rows->onEachSide(1)->links() }}
            </div>
        </div>
    </div>

    {{-- Image Modal --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 shadow-sm">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="mdi mdi-image-outline me-2"></i>
                        <span id="imageTitle">{{ __('pos.image') ?? 'الصورة' }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('pos.close') ?? 'إغلاق' }}"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="productLargeImg" src="" alt="" class="img-fluid rounded-3 border"
                         style="max-height:70vh;object-fit:contain"
                         onerror="this.onerror=null;this.src='{{ asset('assets/img/placeholder-product-lg.png') }}';">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">
                        <i class="mdi mdi-close"></i> {{ __('pos.close') ?? 'إغلاق' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Barcode Modal --}}
    <div class="modal fade" id="barcodeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-sm">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="mdi mdi-barcode me-2"></i> {{ __('pos.barcode') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('pos.close') ?? 'إغلاق' }}"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="barcodeLabel" class="fw-600 mb-2"></div>
                    <svg id="barcodeSvg"></svg>
                    <div id="barcodeCode" class="small text-muted mt-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">
                        <i class="mdi mdi-close"></i> {{ __('pos.close') ?? 'إغلاق' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Styles --}}
<style>
    .pretty-table thead th{
        position: sticky; top: 0; z-index: 5;
        background: var(--bs-light,#f8f9fa);
        border-bottom: 1px solid rgba(0,0,0,.06);
    }
    .pretty-table tbody tr:hover{ background: rgba(13,110,253,.03); }
    .sticky-col{ position: sticky; left: 0; background: #fff; }
    .barcode-card{
        border: 1px dashed rgba(0,0,0,.15);
        background: #fff; border-radius: .75rem;
        padding: .35rem .5rem; min-width: 140px;
        box-shadow: 0 1px 2px rgba(16,24,40,.05);
    }
    .btn-xs{ padding: .15rem .5rem; font-size: .75rem; }
    .name-col{ max-width: 260px; }
    .fw-600{ font-weight:600; }
</style>

{{-- JS: SweetAlert2 + JsBarcode + tooltips --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '{{ __("pos.alert_title") ?? "تحذير" }}',
            text: '⚠️ {{ __("pos.confirm_delete_text") ?? "هل أنت متأكد من الحذف؟ لا يمكن التراجع!" }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor:  '#0d6efd',
            confirmButtonText:  '{{ __("pos.btn_yes_delete") ?? "نعم، احذف" }}',
            cancelButtonText:   '{{ __("pos.btn_cancel") ?? "إلغاء" }}'
        }).then((r) => {
            if (r.isConfirmed) {
                Livewire.emit("deleteConfirmed", id);
                Swal.fire('{{ __("pos.deleted") ?? "تم الحذف" }}', '✅ {{ __("pos.deleted_success") ?? "تم الحذف بنجاح." }}', 'success');
            }
        })
    }

    // عرض باركود في مودال
    function viewBarcode(code, label) {
        document.getElementById('barcodeLabel').textContent = label || '';
        document.getElementById('barcodeCode').textContent  = code || '';
        const svg = document.getElementById('barcodeSvg');
        while (svg.firstChild) svg.removeChild(svg.firstChild);
        try { JsBarcode(svg, code, { format: "CODE128", height: 80, fontSize: 14, displayValue: false }); }
        catch (e) { console.error(e); }
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('barcodeModal')).show();
        }
    }

    // عرض صورة المنتج
    function showProductImage(url, label){
        if(!url) return;
        const img = document.getElementById('productLargeImg');
        const titleEl = document.getElementById('imageTitle');
        img.src = url;
        img.alt = label || '';
        titleEl.textContent = label || '';
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('imageModal')).show();
        }
    }

    // طباعة باركودات العناصر المختارة
    window.addEventListener('print-barcodes', (e) => {
        const items = e.detail.items || [];
        if (!items.length) return;

        const html = `<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <title>{{ __('pos.print_barcodes') ?? 'طباعة الباركود' }}</title>
  <style>
    @page { margin: 10mm; }
    body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .card { border: 1px solid #ddd; border-radius: 8px; padding: 6px; text-align: center; }
    .name { font-size: 11px; color:#333; margin: 4px 0 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .sku  { font-size: 10px; color:#666; margin-bottom: 4px; }
    .barcode { width: 100%; height: 58px; }
  </style>
</head>
<body>
  <div class="grid" id="printGrid"></div>

  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"><\/script>
  <script>
    const DATA = ${JSON.stringify(items)};
    const container = document.getElementById('printGrid');
    DATA.forEach(it => {
      const count = Math.max(1, parseInt(it.qty || 1, 10));
      for (let i=0; i<count; i++){
        const card = document.createElement('div');
        card.className = 'card';
        const name = document.createElement('div');
        name.className = 'name';
        name.textContent = it.label || '';
        const sku = document.createElement('div');
        sku.className = 'sku';
        sku.textContent = it.sku ? ('SKU: ' + it.sku) : '';
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.classList.add('barcode');
        card.appendChild(name);
        if (it.sku) card.appendChild(sku);
        card.appendChild(svg);
        container.appendChild(card);
        try { JsBarcode(svg, it.barcode, { format: "CODE128", height: 58, displayValue: false, margin: 0 }); }
        catch (err) { console.error(err); }
      }
    });
    setTimeout(()=> window.print(), 350);
  <\/script>
</body>
</html>`;

        const w = window.open('', '_blank');
        w.document.open();
        w.document.write(html);
        w.document.close();
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (window.bootstrap) {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        }
    });
</script>
