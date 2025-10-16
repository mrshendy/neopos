<div class="page-wrap">

    {{-- ✅ Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 🏷️ Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="mb-1 fw-bold">
                <i class="mdi mdi-package-variant-closed me-2"></i> {{ __('pos.products_title') ?? 'إدارة المنتجات' }}
            </h3>
            <div class="text-muted small">{{ __('pos.products_management_sub') ?? 'بحث، تصفية، تعديل، وحذف المنتجات' }}
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary rounded-pill px-3 shadow-sm">
                <i class="mdi mdi-shape-outline"></i> {{ __('pos.category_title') ?? 'الأقسام' }}
            </a>
            <a href="{{ route('product.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-plus"></i> {{ __('pos.btn_new_product') ?? 'منتج جديد' }}
            </a>
        </div>
    </div>

    {{-- 🔎 Filters --}}
    <div class="card shadow-sm rounded-4 mb-2">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-magnify"></i> {{ __('pos.search') ?? 'بحث' }}
                    </label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search"
                        placeholder="{{ __('pos.ph_search_sku_barcode_name') ?? 'ابحث بـ SKU/باركود/اسم' }}">
                    <small
                        class="text-muted">{{ __('pos.hint_search_products') ?? 'اكتب جزء من الكود أو الاسم' }}</small>
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-shape-outline"></i> {{ __('pos.filter_category') ?? 'القسم' }}
                    </label>
                    <select class="form-select" wire:model="category_id">
                        <option value="">{{ __('pos.all') ?? 'الكل' }}</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->getTranslation('name', app()->getLocale()) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-weight-kilogram"></i> {{ __('pos.filter_unit') ?? 'الوحدة' }}
                    </label>
                    <select class="form-select" wire:model="unit_id">
                        <option value="">{{ __('pos.all') ?? 'الكل' }}</option>
                        @foreach ($units as $u)
                            <option value="{{ $u->id }}">{{ $u->getTranslation('name', app()->getLocale()) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-toggle-switch"></i> {{ __('pos.filter_status') ?? 'الحالة' }}
                    </label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('pos.all') ?? 'الكل' }}</option>
                        <option value="active">{{ __('pos.status_active') ?? 'نشط' }}</option>
                        <option value="inactive">{{ __('pos.status_inactive') ?? 'غير نشط' }}</option>
                    </select>
                </div>

                <div class="col-lg-2 text-end">
                    <button class="btn btn-light rounded-pill px-3 shadow-sm"
                        wire:click="$set('search','');$set('status','');$set('category_id','');$set('unit_id','')">
                        <i class="mdi mdi-filter-remove-outline"></i> {{ __('pos.clear_filters') ?? 'مسح الفلاتر' }}
                    </button>
                </div>
            </div>

            {{-- Summary --}}
            <div class="d-flex align-items-center mt-3 small text-muted">
                <i class="mdi mdi-dots-grid me-1"></i>
                <span>{{ __('pos.search') ?? 'بحث' }}:</span>
                <span class="badge bg-light text-dark ms-1">{{ $search ?: '—' }}</span>
                <span class="ms-3">{{ __('pos.filter_status') ?? 'الحالة' }}:</span>
                <span class="badge bg-light text-dark ms-1">
                    {{ $status ? __($status == 'active' ? 'pos.status_active' : 'pos.status_inactive') : __('pos.all') }}
                </span>
                <span class="ms-auto">
                    <span class="badge bg-primary-subtle text-primary rounded-pill">
                        <i class="mdi mdi-counter me-1"></i>{{ $rows->total() }}
                    </span>
                </span>
            </div>
        </div>
    </div>

    {{-- ✅ Bulk actions bar: زر طباعة الباركود --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="small text-muted">
            @if (!empty($selected))
                <i class="mdi mdi-check-all me-1"></i>
                <strong>{{ count($selected) }}</strong> {{ __('pos.selected_rows') ?? 'سجل/سجلات محددة' }}
            @endif
        </div>
        <div class="d-flex gap-2">
            @if (!empty($selected))
                <button class="btn btn-outline-secondary rounded-pill px-3"
                    wire:click="$set('selected', []); $set('select_all_current_page', false)">
                    <i class="mdi mdi-close"></i> {{ __('pos.clear_selection') ?? 'إلغاء التحديد' }}
                </button>
            @endif

            {{-- زر الطباعة (يعتمد على goPrintSelected في الكومبوننت) --}}
            <button class="btn btn-primary rounded-pill px-3" wire:click="goPrintSelected"
                {{ empty($selected) ? 'disabled' : '' }}>
                <i class="mdi mdi-printer"></i> {{ __('pos.print_selected') ?? 'طباعة الباركود' }}
            </button>
        </div>
    </div>

    {{-- 🧾 Table --}}
    <div class="card shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle mb-0 pretty-table">
                <thead class="table-light">
                    <tr>
                        {{-- تحديد الصفحة الحالية --}}
                        <th class="w-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll"
                                    wire:click="toggleSelectAllCurrentPage" @checked($select_all_current_page)>
                            </div>
                        </th>

                        <th class="sticky-col">#</th>
                        <th>{{ __('pos.image') ?? 'الصورة' }}</th>
                        <th>{{ __('pos.sku') ?? 'كود' }}</th>
                        <th>{{ __('pos.barcode') ?? 'الباركود' }}</th>
                        <th>{{ __('pos.name') ?? 'الاسم' }}</th>
                        <th>{{ __('pos.category') ?? 'القسم' }}</th>
                        <th>{{ __('pos.status') ?? 'الحالة' }}</th>
                        <th class="text-end">{{ __('pos.actions') ?? 'إجراءات' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            {{-- checkbox + qty --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="checkbox" class="form-check-input" value="{{ $row->id }}"
                                        wire:model="selected">
                                    @if (in_array($row->id, $selected))
                                        <input type="number" min="1" class="form-control form-control-sm"
                                            style="width:80px" wire:model.lazy="qty.{{ $row->id }}"
                                            placeholder="1" title="{{ __('pos.print_qty') ?? 'كمية الطباعة' }}">
                                    @endif
                                </div>
                            </td>

                            <td class="sticky-col text-muted">{{ $row->id }}</td>

                            {{-- الصورة --}}
                            <td>
                                @php
                                    $img = $row->image_path ? asset('attachments/' . ltrim($row->image_path, '/')) : null;
                                @endphp
                                @if ($img)
                                    <img src="{{ $img }}" alt="" class="rounded-3 border"
                                        width="42" height="42" style="object-fit:cover">
                                @else
                                    <div class="rounded-3 border bg-light d-inline-flex align-items-center justify-content-center"
                                        style="width:42px;height:42px;">
                                        <i class="mdi mdi-image-off-outline text-muted"></i>
                                    </div>
                                @endif
                            </td>

                            <td class="font-monospace fw-600">{{ $row->sku }}</td>

                            {{-- Barcode --}}
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

                            {{-- الاسم --}}
                            <td class="text-truncate" style="max-width: 260px;"
                                title="{{ $row->getTranslation('name', app()->getLocale()) }}">
                                {{ $row->getTranslation('name', app()->getLocale()) }}
                            </td>

                            {{-- القسم --}}
                            <td class="text-truncate" style="max-width: 220px;">
                                {{ optional($row->category)->getTranslation('name', app()->getLocale()) ?: '—' }}
                            </td>

                            {{-- الحالة --}}
                            <td>
                                <span
                                    class="badge rounded-pill px-3 {{ $row->status == 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ $row->status == 'active' ? __('pos.status_active') ?? 'نشط' : __('pos.status_inactive') ?? 'غير نشط' }}
                                </span>
                            </td>

                            {{-- الإجراءات --}}
                            <td class="text-end">
                                <div class="btn-group">
                                    <button class="btn btn-outline-secondary btn-sm rounded-pill m-1"
                                        wire:click="toggleStatus({{ $row->id }})" data-bs-toggle="tooltip"
                                        title="{{ __('pos.toggle_status') ?? 'تغيير الحالة' }}">
                                        <i class="mdi mdi-toggle-switch"></i>
                                    </button>
                                    <a href="{{ route('product.edit', $row->id) }}"
                                        class="btn btn-primary btn-sm rounded-pill m-1" data-bs-toggle="tooltip"
                                        title="{{ __('pos.btn_edit') ?? 'تعديل' }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <button onclick="confirmDelete({{ $row->id }})"
                                        class="btn btn-danger btn-sm rounded-pill m-1" data-bs-toggle="tooltip"
                                        title="{{ __('pos.btn_delete') ?? 'حذف' }}">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="py-5 text-center text-muted">
                                    <i class="mdi mdi-package-variant-closed fs-1 d-block mb-2"></i>
                                    {{ __('pos.no_data') ?? 'لا توجد بيانات' }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                <i class="mdi mdi-information-outline"></i>
                {{ $rows->firstItem() }}–{{ $rows->lastItem() }} / {{ $rows->total() }}
            </div>
            <div>{{ $rows->onEachSide(1)->links() }}</div>
        </div>
    </div>
</div>

{{-- ✅ Modal لعرض الباركود --}}
<div class="modal fade" id="barcodeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-sm">
            <div class="modal-header">
                <h5 class="modal-title"><i class="mdi mdi-barcode me-2"></i> {{ __('pos.barcode') ?? 'الباركود' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="{{ __('pos.close') ?? 'إغلاق' }}"></button>
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

{{-- ✅ SweetAlert2 + JsBarcode + Tooltips --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '{{ __('pos.alert_title') ?? 'تحذير' }}',
            text: '{{ __('pos.alert_text') ?? 'هل أنت متأكد من الحذف؟ لا يمكن التراجع!' }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#0d6efd',
            confirmButtonText: '{{ __('pos.alert_confirm') ?? 'نعم، احذف' }}',
            cancelButtonText: '{{ __('pos.alert_cancel') ?? 'إلغاء' }}'
        }).then((r) => {
            if (r.isConfirmed) {
                Livewire.emit('deleteConfirmed', id);
                Swal.fire('{{ __('pos.deleted') ?? 'تم الحذف' }}',
                    '{{ __('pos.deleted_success') ?? 'تم الحذف بنجاح' }}', 'success');
            }
        })
    }

    function viewBarcode(code, label) {
        document.getElementById('barcodeLabel').textContent = label || '';
        document.getElementById('barcodeCode').textContent = code || '';
        const svg = document.getElementById('barcodeSvg');
        while (svg.firstChild) svg.removeChild(svg.firstChild);
        try {
            JsBarcode(svg, code, {
                format: "CODE128",
                height: 80,
                fontSize: 14,
                displayValue: false
            });
        } catch (e) {
            console.error(e);
        }
        const modalEl = document.getElementById('barcodeModal');
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else {
            modalEl.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.bootstrap) {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        }
    });
</script>

<style>
    .pretty-table thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        background: var(--bs-light, #f8f9fa);
    }

    .sticky-col {
        position: sticky;
        left: 0;
        background: #fff;
    }

    .table-hover tbody tr:hover {
        background: rgba(13, 110, 253, .03);
    }

    .fw-600 {
        font-weight: 600;
    }

    .w-1 {
        width: 1%;
        white-space: nowrap;
    }

    .barcode-card {
        border: 1px dashed rgba(0, 0, 0, .15);
        background: #fff;
        border-radius: .75rem;
        padding: .35rem .5rem;
        min-width: 140px;
        box-shadow: 0 1px 2px rgba(16, 24, 40, .05);
    }

    .btn-xs {
        padding: .15rem .5rem;
        font-size: .75rem;
    }
</style>
