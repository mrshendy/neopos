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

                {{-- 🧱 products --}}
                <div class="col-6 col-md-4 col-xl-2">
                    <a href="{{ route('product.index') }}" class="module-btn">
                        <i class="mdi mdi-package-variant-closed"></i>
                        <span>{{ __('pos.inventory_product_title') ?? 'Product' }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    