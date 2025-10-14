<div>
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

    {{-- ======= Modules Toolbar (Modern Buttons) ======= --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="row g-3 modules-toolbar">

                {{-- ⚙️ Settings --}}
                <div class="col-6 col-md-4 col-xl-2">
                    <a href="{{ route('inventory.settings.index') }}" class="module-btn">
                        <i class="mdi mdi-cog-outline"></i>
                        <span>{{ __('pos.inventory_settings_title') ?? 'Settings' }}</span>
                    </a>
                </div>

                {{-- 📦 Counts --}}
                <div class="col-6 col-md-4 col-xl-2">
                    <a href="{{ route('inventory.counts.index') }}" class="module-btn">
                        <i class="mdi mdi-clipboard-list-outline"></i>
                        <span>{{ __('pos.inventory_counts_title') ?? 'Counts' }}</span>
                    </a>
                </div>

                {{-- 🚨 Alerts --}}
                <div class="col-6 col-md-4 col-xl-2">
                    <a href="{{ route('inventory.alerts.index') }}" class="module-btn">
                        <i class="mdi mdi-alert-decagram-outline"></i>
                        <span>{{ __('pos.inventory_alerts_title') ?? 'Alerts' }}</span>
                    </a>
                </div>

                {{-- 🔄 Transactions --}}
                <div class="col-6 col-md-4 col-xl-2">
                    <a href="{{ route('inventory.transactions.index') }}" class="module-btn">
                        <i class="mdi mdi-swap-horizontal-bold"></i>
                        <span>{{ __('pos.inventory_transactions_title') ?? 'Transactions' }}</span>
                    </a>
                </div>

                {{-- 🏠 Warehouses --}}
                <div class="col-6 col-md-4 col-xl-2">
                    <a href="{{ route('inventory.warehouses.index') }}" class="module-btn">
                        <i class="mdi mdi-warehouse"></i>
                        <span>{{ __('pos.inventory_warehouses_title') ?? 'Warehouses' }}</span>
                    </a>
                </div>

                {{-- 🧱 Items --}}
                <div class="col-6 col-md-4 col-xl-2">
                    <a href="{{ route('inventory.items.index') }}" class="module-btn">
                        <i class="mdi mdi-package-variant-closed"></i>
                        <span>{{ __('pos.inventory_items_title') ?? 'Items' }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 💅 Styles for modules toolbar --}}
    <style>
        .modules-toolbar .module-btn{
            display:flex;flex-direction:column;align-items:center;justify-content:center;
            gap:.4rem;text-decoration:none;width:100%;height:100%;
            border-radius:1rem;padding:1rem 0.75rem;text-align:center;
            background:linear-gradient(135deg,#198754 0%,#1a5490 100%);
            color:#fff;border:none;box-shadow:0 .5rem 1rem rgba(0,0,0,.06);
            transition:transform .25s ease, box-shadow .25s ease, filter .25s ease, background .25s ease;
            min-height:84px;
        }
        .modules-toolbar .module-btn i{
            font-size:1.65rem;line-height:1;
        }
        .modules-toolbar .module-btn span{
            font-weight:600;font-size:.95rem;letter-spacing:.2px;
        }
        .modules-toolbar .module-btn:hover{
            transform:translateY(-3px);
            box-shadow:0 .85rem 1.6rem rgba(0,0,0,.10);
            filter:saturate(1.08);
            background:linear-gradient(135deg,#1a5490 0%,#198754 100%);
            color:#fff;
        }
        @media (max-width: 575.98px){
            .modules-toolbar .module-btn span{ font-size:.85rem; }
            .modules-toolbar .module-btn{ min-height:76px; }
        }
    </style>

    {{-- Filters --}}
    <div class="card shadow-sm rounded-4 mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-magnify"></i> {{ __('pos.search') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search" placeholder="{{ __('pos.ph_search_sku_name') }}">
                    <small class="text-muted">{{ __('pos.hint_search_items') }}</small>
                </div>
                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.status') }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="active">{{ __('pos.active') }}</option>
                        <option value="inactive">{{ __('pos.inactive') }}</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.track_batch') }}</label>
                    <select class="form-select" wire:model="track_batch">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="1">{{ __('pos.yes') }}</option>
                        <option value="0">{{ __('pos.no') }}</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.track_serial') }}</label>
                    <select class="form-select" wire:model="track_serial">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="1">{{ __('pos.yes') }}</option>
                        <option value="0">{{ __('pos.no') }}</option>
                    </select>
                </div>
                <div class="col-lg-3 text-end">
                    <a href="{{ route('inventory.items.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-plus-circle-outline"></i> {{ __('pos.btn_new_item') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('pos.item_name') }}</th>
                            <th>{{ __('pos.sku') }}</th>
                            <th>{{ __('pos.uom') }}</th>
                            <th>{{ __('pos.track_batch') }}</th>
                            <th>{{ __('pos.track_serial') }}</th>
                            <th>{{ __('pos.status') }}</th>
                            <th class="text-end">{{ __('pos.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $i)
                            <tr>
                                <td>{{ $i->id }}</td>
                                <td>{{ app()->getLocale()=='ar' ? ($i->name['ar'] ?? '') : ($i->name['en'] ?? '') }}</td>
                                <td>{{ $i->sku }}</td>
                                <td>{{ $i->uom }}</td>
                                <td><span class="badge bg-{{ $i->track_batch?'success':'secondary' }}">{{ $i->track_batch?__('pos.yes'):__('pos.no') }}</span></td>
                                <td><span class="badge bg-{{ $i->track_serial?'success':'secondary' }}">{{ $i->track_serial?__('pos.yes'):__('pos.no') }}</span></td>
                                <td><span class="badge bg-{{ $i->status=='active'?'success':'secondary' }}">{{ __('pos.'.$i->status) }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('inventory.items.edit',$i->id) }}" class="btn btn-sm btn-primary rounded-pill shadow-sm">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning rounded-pill shadow-sm" wire:click="toggleStatus({{ $i->id }})">
                                        <i class="mdi mdi-toggle-switch"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger rounded-pill shadow-sm" wire:click="confirmDelete({{ $i->id }})">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted">{{ __('pos.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $items->onEachSide(1)->links() }}
            </div>
        </div>
    </div>

    {{-- ✅ SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    Swal.fire('تم الحذف!', '✅ تم الحذف  بنجاح.', 'success');
                }
            })
        }
    </script>
</div>
