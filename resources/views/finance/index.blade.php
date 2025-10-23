{{-- resources/views/finance/index.blade.php --}}
@extends('layouts.master')

@section('title', __('pos.cashboxes_hub_title'))

@section('content')
    <div class="page-wrap" dir="rtl">
        <style>
            :root {
                --panel-border: #eef2ff;
                --panel-shadow: 0 12px 36px rgba(17, 24, 39, .06);
                --tile-border: #eef2ff;
                --tile-shadow: 0 10px 26px rgba(17, 24, 39, .06);
                --tile-hover: 0 14px 40px rgba(17, 24, 39, .10);
                --accent: #1971ff;
                /* غامق أزرق زي الصورة */
                --accent-soft-1: #f2f7ff;
                --accent-soft-2: #e9f1ff;
                --title-muted: #8a9ab5;
            }

            .hub-panel {
                background: #fff;
                border: 1px solid var(--panel-border);
                border-radius: 22px;
                padding: 18px 18px 22px;
                box-shadow: var(--panel-shadow)
            }

            .hub-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 8px
            }

            .hub-heading {
                display: flex;
                align-items: center;
                gap: 8px
            }

            .hub-heading i {
                color: var(--accent);
                font-size: 22px
            }

            .hub-title {
                font-weight: 800;
                font-size: 22px;
                margin: 0
            }

            .hub-sub {
                margin-top: 2px;
                color: #94a3b8;
                font-size: .92rem
            }

            .tile {
                position: relative;
                background: #fff;
                border: 1px solid var(--tile-border);
                border-radius: 22px;
                padding: 18px 18px 14px;
                box-shadow: var(--tile-shadow);
                transition: transform .12s ease, box-shadow .12s ease
            }

            .tile:hover {
                transform: translateY(-2px);
                box-shadow: var(--tile-hover)
            }

            .tile-icon {
                width: 62px;
                height: 62px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(180deg, var(--accent-soft-1), #fff);
                border: 1px solid var(--accent-soft-2);
                box-shadow: inset 0 0 0 8px #f8fbff;
            }

            .tile-icon i {
                font-size: 28px;
                color: var(--accent)
            }

            .tile-title {
                margin: .85rem 0 .2rem;
                font-weight: 800;
                font-size: 1.08rem;
                color: var(--accent);
                text-decoration: none;
                display: inline-block
            }

            .tile-open {
                color: var(--title-muted);
                font-size: .95rem
            }

            .stretched-link::after {
                position: absolute;
                inset: 0;
                content: ""
            }
        </style>

        <div class="hub-panel">
            <div class="hub-header">
                <div>
                    <div class="hub-heading">
                        <i class="mdi mdi-apps"></i>
                        <h4 class="hub-title">{{ __('pos.cashboxes_hub_title') }}</h4>
                    </div>
                    <div class="hub-sub">{{ __('pos.cashboxes_hub_subtitle') }}</div>
                </div>
            </div>

            <div class="row g-3 mt-2">
                {{-- 1) إعدادات الخزائن --}}
                <div class="col-xxl-3 col-lg-4 col-md-6">
                    <div class="tile h-100">
                        <div class="tile-icon mb-2"><i class="mdi mdi-cog-outline"></i></div>
                        <a href="{{ route('finance_settings.index') }}" class="tile-title stretched-link">
                            {{ __('pos.cashboxes_settings') }}
                        </a>
                        <div class="tile-open">{{ __('pos.open') }}</div>
                    </div>
                </div>

                {{-- 2) حركات الخزائن --}}
                <div class="col-xxl-3 col-lg-4 col-md-6">
                    <div class="tile h-100">
                        <div class="tile-icon mb-2"><i class="mdi mdi-swap-horizontal-bold"></i></div>
                        <a href="{{ route('finance.movements') }}" class="tile-title stretched-link">
                            {{ __('pos.cashboxes_movements') }}
                        </a>
                        <div class="tile-open">{{ __('pos.open') }}</div>
                    </div>
                </div>

                {{-- 3) استلام/تسليم الخزينة --}}
                <div class="col-xxl-3 col-lg-4 col-md-6">
                    <div class="tile h-100">
                        <div class="tile-icon mb-2"><i class="mdi mdi-handshake-outline"></i></div>
                        <a href="{{ route('finance.handovers') }}" class="tile-title stretched-link">
                            {{ __('pos.cashboxes_shifts') }}
                        </a>
                        <div class="tile-open">{{ __('pos.open') }}</div>
                    </div>
                </div>

                {{-- 4) الإيصالات --}}
                <div class="col-xxl-3 col-lg-4 col-md-6">
                    <div class="tile h-100">
                        <div class="tile-icon mb-2"><i class="mdi mdi-clipboard-text-outline"></i></div>
                        <a href="{{ route('finance.receipts') }}" class="tile-title stretched-link">
                            {{ __('pos.cashboxes_receipts') }}
                        </a>
                        <div class="tile-open">{{ __('pos.open') }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
