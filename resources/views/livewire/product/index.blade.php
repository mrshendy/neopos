<div class="page-wrap">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="mb-1 fw-bold">{{ __('pos.products_title')}}</h3>
            <div class="text-muted small">
                {{ __('pos.products_management_sub')  }}
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary rounded-pill px-3 shadow-sm">
                <i class="mdi mdi-shape-outline"></i> {{ __('pos.category_title') }}
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-success rounded-pill px-3 shadow-sm">
                <i class="mdi mdi-plus"></i> {{ __('pos.btn_new_product') }}
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Toolbar --}}
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
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->getTranslation('name',app()->getLocale()) }}</option>
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
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->getTranslation('name',app()->getLocale()) }}</option>
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
            </div>

            {{-- Mini status line --}}
            <div class="d-flex align-items-center mt-3 small text-muted">
                <i class="mdi mdi-dots-grid me-1"></i>
                <span>{{ __('pos.search') }}: </span>
                <span class="badge bg-light text-dark ms-1">{{ $search ?: '—' }}</span>
                <span class="ms-3">{{ __('pos.filter_status') }}: </span>
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

    {{-- Table --}}
    <div class="card shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle mb-0 pretty-table">
                <thead>
                    <tr>
                        <th class="sticky-col">#</th>
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
                            <td class="font-monospace fw-600">{{ $row->sku }}</td>

                            {{-- Barcode card --}}
                            <td>
                                @if($row->barcode)
                                    <div class="barcode-card text-center">
                                        {!! DNS1D::getBarcodeHTML($row->barcode, 'C128', 1.8, 44) !!}
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

                            <td class="text-truncate name-col" title="{{ $row->getTranslation('name', app()->getLocale()) }}">
                                {{ $row->getTranslation('name', app()->getLocale()) }}
                            </td>

                            <td class="text-truncate">
                                {{ optional($row->unit)->getTranslation('name', app()->getLocale()) ?: '—' }}
                            </td>

                            <td class="text-truncate">
                                {{ optional($row->category)->getTranslation('name', app()->getLocale()) ?: '—' }}
                            </td>

                            <td>
                                <span class="badge rounded-pill px-3 {{ $row->status=='active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ $row->status=='active' ? __('pos.status_active') : __('pos.status_inactive') }}
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="btn-group">
                                    <button class="btn btn-outline-secondary btn-md rounded-pill m-1"
                                            wire:click="toggleStatus({{ $row->id }})"
                                            data-bs-toggle="tooltip" title="{{ __('pos.status') }}">
                                        <i class="mdi mdi-toggle-switch"></i>
                                    </button>
                                    <a href="{{ route('products.edit',$row->id) }}"
                                       class="btn btn-primary btn-md rounded-pill m-1"
                                       data-bs-toggle="tooltip" title="{{ __('pos.btn_edit') ?? 'تعديل' }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <button onclick="confirmDelete({{ $row->id }})"
                                            class="btn btn-danger btn-md rounded-pill m-1"
                                            data-bs-toggle="tooltip" title="{{ __('pos.btn_delete') ?? 'حذف' }}">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
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
</div>

{{-- Barcode Modal --}}
<div class="modal fade" id="barcodeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-sm">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="mdi mdi-barcode me-2"></i> {{ __('pos.barcode') }}
        </h5>
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

{{-- Page styles (scoped) --}}
<style>
    .pretty-table thead th{
        position: sticky; top: 0; z-index: 5;
        background: var(--bs-light,#f8f9fa);
        border-bottom: 1px solid rgba(0,0,0,.06);
    }
    .pretty-table tbody tr:hover{
        background: rgba(13,110,253,.03);
    }
    .sticky-col{ position: sticky; left: 0; background: #fff; }
    .barcode-card{
        border: 1px dashed rgba(0,0,0,.15);
        background: #fff;
        border-radius: .75rem;
        padding: .35rem .5rem;
        box-shadow: 0 1px 2px rgba(16,24,40,.05);
        min-width: 160px;
    }
    .btn-xs{ padding: .15rem .5rem; font-size: .75rem; }
    .name-col{ max-width: 280px; }
    .fw-600{ font-weight:600; }
</style>

{{-- SweetAlert2 + JsBarcode + tooltips --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'تحذير',
            text: '⚠️ هل أنت متأكد أنك تريد حذف هذا الإجراء لا يمكن التراجع عنه!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#0d6efd',
            confirmButtonText: 'نعم، احذفها',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('deleteConfirmed', id);
                Swal.fire('تم الحذف!', '✅ تم الحذف بنجاح.', 'success');
            }
        })
    }

    // Enable Bootstrap tooltips if available
    document.addEventListener('DOMContentLoaded', () => {
        if (window.bootstrap) {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        }
    });

    // Show barcode in modal
    function viewBarcode(code, label) {
        // Fill labels
        document.getElementById('barcodeLabel').textContent = label || '';
        document.getElementById('barcodeCode').textContent  = code || '';

        // Generate barcode into SVG
        const svg = document.getElementById('barcodeSvg');
        while (svg.firstChild) svg.removeChild(svg.firstChild); // clear previous
        try {
            JsBarcode(svg, code, {
                format: "CODE128",
                height: 80,
                fontSize: 14,
                displayValue: false // we show the numeric code separately
            });
        } catch (e) {
            console.error(e);
        }

        // Open modal
        const modalEl = document.getElementById('barcodeModal');
        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else {
            modalEl.style.display = 'block';
        }
    }
</script>
