        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('assets/images/logo.png') }}" alt=""
                            style="height: 70px;width: 100% !important;">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('assets/images/logo.png') }}" alt="" height="17"
                            style="height: 70px;width:100% !important;">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link font  @if (Route::currentRouteName() == 'dashbord.create' || Route::currentRouteName() == 'dashbord.index') active @endif"
                                href="{{ url('/' . ($page = 'dashbord')) }}">
                                <i class="mdi mdi-speedometer"></i> <span data-key="t-widgets">
                                    {{ trans('main_trans.dashboards') }}</span>
                            </a>
                        </li>

                        {{-- العملاء --}}
                        <li class="nav-item">
                            <a class="nav-link menu-link font @if (Str::startsWith(Route::currentRouteName(), 'customers.')) active @endif"
                                href="{{ route('customers.index') }}">
                                <i class="mdi mdi-account-group-outline"></i>
                                <span data-key="t-customers">{{ __('pos.title_customers_index') }}</span>
                            </a>
                        </li>
                        {{-- المورّدون --}}
                        <li class="nav-item">
                            <a class="nav-link menu-link font @if (Str::startsWith(Route::currentRouteName(), 'suppliers.')) active @endif"
                                href="{{ route('suppliers.index') }}">
                                <i class="mdi mdi-truck-outline"></i>
                                <span data-key="t-suppliers">{{ __('pos.supplier_title') }}</span>
                            </a>
                        </li>
                        {{-- إدارة المبيعات (POS) --}}
                        <li class="nav-item">
                            <a class="nav-link menu-link font @if (Str::startsWith(Route::currentRouteName(), 'pos.')) active @endif"
                                href="{{ route('pos.index') }}">
                                <i class="mdi mdi-point-of-sale"></i>
                                <span data-key="t-pos">{{ __('pos.sales_title') ?? 'إدارة المبيعات' }}</span>
                            </a>
                        </li>

                        {{-- المنتجات --}}
                        <li class="nav-item">
                            <a class="nav-link menu-link font @if (Str::startsWith(Route::currentRouteName(), 'products.')) active @endif"
                                href="{{ route('product.manage') }}">
                                <i class="mdi mdi-cube-outline"></i>
                                <span data-key="t-products">{{ __('pos.products_index_title') }}</span>
                            </a>
                        </li>
                        {{-- المشتريات --}}
                        <li class="nav-item">
                            <a class="nav-link menu-link font @if (Str::startsWith(Route::currentRouteName(), 'purchases.')) active @endif"
                                href="{{ route('purchases.index') }}">
                                <i class="mdi mdi-cart-outline"></i>
                                <span data-key="t-purchases">{{ __('pos.purchases_title') ?? 'المشتريات' }}</span>
                            </a>
                        </li>

                        {{-- العروض والكوبونات --}}
                        @php
                            $promoOpen =
                                Str::startsWith(Route::currentRouteName(), 'offers.') ||
                                Str::startsWith(Route::currentRouteName(), 'coupons.');
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link menu-link font {{ $promoOpen ? 'active' : '' }}" href="#sidebarpromos"
                                data-bs-toggle="collapse" role="button"
                                aria-expanded="{{ $promoOpen ? 'true' : 'false' }}" aria-controls="sidebarpromos">
                                <i class="mdi mdi-ticket-percent-outline me-1"></i>
                                <span data-key="t-promos">{{ __('pos.offers_title') }} &
                                    {{ __('pos.coupons_title') }}</span>
                            </a>
                            <div class="collapse menu-dropdown {{ $promoOpen ? 'show' : '' }}" id="sidebarpromos">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'offers.') ? 'active' : '' }}"
                                            href="{{ route('offers.index') }}">
                                            {{ __('pos.offers_title') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'coupons.') ? 'active' : '' }}"
                                            href="{{ route('coupons.index') }}">
                                            {{ __('pos.coupons_title') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        {{-- المخزون --}}
                        <li class="nav-item">
                            <a class="nav-link menu-link font 
        @if (Str::startsWith(Route::currentRouteName(), 'inventory.')) active @endif"
                                href="{{ route('inventory.manage') }}">
                                <i class="mdi mdi-warehouse"></i>
                                <span data-key="t-inventory">{{ __('pos.inventory_title') ?? 'المخزون' }}</span>
                            </a>
                        </li>
                        {{-- الخزائن --}}
                        <li class="nav-item">
                            <a class="nav-link menu-link font @if (Str::startsWith(Route::currentRouteName(), 'finance.')) active @endif"
                                href="{{ route('finance.index') }}">
                                <i class="mdi mdi-safe"></i>
                                <span data-key="t-cashboxes">{{ __('pos.finance_title_index') }}</span>
                            </a>
                        </li>


                        <!--user management-->
                        <li class="menu-title"><i class="ri-more-fill"></i> <span
                                data-key="t-components">{{ trans('main_trans.user_management') }}</span></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link font" href="#sidebaruser_management" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="sidebarAuth">
                                <i class="mdi mdi-account-lock-outline"></i> <span
                                    data-key="t-authentication">{{ trans('main_trans.users') }}</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebaruser_management">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="collapse" role="button"
                                            aria-expanded="false" aria-controls="sidebarSignIn"
                                            data-key="t-signin">{{ trans('main_trans.user_add') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="collapse" role="button"
                                            aria-expanded="false" aria-controls="sidebarSignUp"
                                            data-key="t-signup">{{ trans('main_trans.user_management') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!--reports-->
                        <li class="menu-title"><i class="ri-more-fill"></i> <span
                                data-key="t-components">{{ trans('main_trans.reports') }}</span></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link font" href="#sidebarreports" data-bs-toggle="collapse"
                                role="button" aria-expanded="false" aria-controls="sidebarAuth">
                                <i class="mdi mdi-book-search-outline"></i> <span
                                    data-key="t-authentication">{{ trans('main_trans.reports') }}</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarreports">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="collapse" role="button"
                                            aria-expanded="false" aria-controls="sidebarSignIn"
                                            data-key="t-signin">{{ trans('main_trans.collections_expenses') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="collapse" role="button"
                                            aria-expanded="false" aria-controls="sidebarSignUp"
                                            data-key="t-signup">{{ trans('main_trans.claims_management') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>


                        <!--settings-->
                        <li class="menu-title"><i class="ri-more-fill"></i> <span
                                data-key="t-components">{{ trans('main_trans.settings') }}</span></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link font @if (Route::currentRouteName() == 'settings.create' || Route::currentRouteName() == 'settings.index') active @endif"
                                href="{{ url('/' . ($page = 'settings')) }}">
                                <i class="mdi mdi-cog-outline"></i> <span
                                    data-key="t-widgets">{{ trans('main_trans.settings') }}</span>
                            </a>
                        </li>

                    </ul>
                </div>
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>
