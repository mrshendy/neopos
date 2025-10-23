{{-- resources/views/livewire/pos/show.blade.php --}}
<div class="container-fluid px-3" x-data>
    {{-- Icons / Font (نفس manage) --}}
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

        .panel{background:var(--card); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow)}
        .panel-body{padding:14px}
        .btn-primary{background:var(--sky); color:#fff; border:none; border-radius:10px; padding:.5rem 1rem; font-weight:700}
        .btn-primary:hover{background:var(--sky-600)}
        .btn-outline{border:1px solid var(--border); background:#fff; color:var(--text); border-radius:10px; padding:.45rem .9rem; font-weight:600}
        .btn-outline:hover{border-color:var(--sky-300)}
        .table-sm td,.table-sm th{padding:.5rem .75rem}
    </style>

    {{-- HERO HEADER (مطابق لإحساس manage) --}}
    <div class="hero">
        <div class="hero-inner">
            <div class="hero-title">
<i class="mdi mdi-clipboard-list-outline"></i>        <!-- نسخة أنيقة وأقرب للـ fa -->
                <div>
                    <h1>{{ __('pos.title_pos_show') }}</h1>
                    <div class="hero-sub">
                        {{ __('pos.sale_no') }}: {{ $sale->pos_no ?? '—' }} · {{ __('pos.sale_date') }}: {{ $sale->pos_date }}
                    </div>
                </div>
            </div>
            <div class="hero-actions">
                @php
                    $badge = $sale->status === 'posted' ? 'success' :
                             ($sale->status === 'approved' ? 'primary' :
                             ($sale->status === 'draft' ? 'secondary' : 'danger'));
                @endphp
                <div class="stat">
                    <i class="fa-regular fa-rectangle-list"></i>
                    <span class="small">{{ __('pos.status') }}:
                        <span class="badge rounded-pill text-bg-{{ $badge }}">{{ __('pos.status_'.$sale->status) }}</span>
                    </span>
                </div>
                <a href="{{ route('pos.index') }}" class="btn-ghost">
                    <i class="fa-solid fa-arrow-left-long"></i> {{ __('pos.back_to_list') }}
                </a>
                <a href="{{ route('pos.edit',$sale->id) }}" class="btn-ghost">
                    <i class="fa-regular fa-pen-to-square"></i> {{ __('pos.btn_edit') }}
                </a>
                <button type="button" class="btn-ghost" onclick="printArea()">
                    <i class="fa-solid fa-print"></i> {{ __('pos.print') }}
                </button>
                {{-- حذف بـ SweetAlert2 --}}
                <button type="button" class="btn-ghost" onclick="confirmDelete({{ $sale->id }})">
                    <i class="fa-regular fa-trash-can"></i> {{ __('pos.btn_delete') }}
                </button>
            </div>
        </div>
    </div>

    {{-- بطاقة التفاصيل + جدول السطور --}}
    <section class="panel" id="printArea">
        <div class="panel-body">
            <div class="row g-3 mb-3">
                <div class="col-lg-6">
                    <div class="p-3 border rounded-3">
                        <div class="fw-bold mb-2">{{ __('pos.invoice_info') }}</div>
                        <div class="small text-muted">{{ __('pos.sale_no') }}: <strong>{{ $sale->pos_no ?? '—' }}</strong></div>
                        <div class="small text-muted">{{ __('pos.sale_date') }}: <strong>{{ $sale->pos_date }}</strong></div>
                        <div class="small text-muted">{{ __('pos.delivery_date') }}: <strong>{{ $sale->delivery_date ?? '—' }}</strong></div>
                        @if($sale->notes)
                            <div class="small text-muted">{{ __('pos.note') }}: <strong>{{ $sale->notes }}</strong></div>
                        @endif
                        <div class="small text-muted">ID: <strong>#{{ $sale->id }}</strong></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    @php
                        $wRaw = $sale->warehouse->name ?? null;
                        $wName = is_array($wRaw) ? ($wRaw[app()->getLocale()] ?? ($wRaw['ar'] ?? (reset($wRaw)??''))) : $wRaw;
                        $cRaw = $sale->customer->name ?? null;
                        $cName = is_array($cRaw) ? ($cRaw[app()->getLocale()] ?? ($cRaw['ar'] ?? (reset($cRaw)??''))) : $cRaw;
                    @endphp
                    <div class="p-3 border rounded-3">
                        <div class="fw-bold mb-2">{{ __('pos.parties_info') }}</div>
                        <div class="small text-muted">{{ __('pos.warehouse') }}: <strong>{{ $wName ?? '—' }}</strong></div>
                        <div class="small text-muted">{{ __('pos.customer') }}: <strong>{{ $cName ?? '—' }}</strong></div>
                        <div class="small text-muted">{{ __('pos.created_by') }}: <strong>{{ $sale->user->name ?? '—' }}</strong></div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-3">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('pos.product') }}</th>
                        <th class="text-center">{{ __('pos.unit') }}</th>
                        <th class="text-center">{{ __('pos.qty') }}</th>
                        <th class="text-center">{{ __('pos.unit_price') }}</th>
                        <th class="text-end">{{ __('pos.total') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sale->lines as $i => $line)
                        @php
                            $pRaw = $line->product->name ?? null;
                            $pName = is_array($pRaw) ? ($pRaw[app()->getLocale()] ?? ($pRaw['ar'] ?? (reset($pRaw)??''))) : $pRaw;
                            $uom = $line->uom_text ?? ($line->unit->name ?? '—');
                            if (is_array($uom)) { $uom = $uom[app()->getLocale()] ?? ($uom['ar'] ?? (reset($uom)??'—')); }
                            $qty   = (float)($line->qty ?? 0);
                            $price = (float)($line->unit_price ?? 0);
                            $total = $qty * $price;
                        @endphp
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $pName ?? '—' }}</td>
                            <td class="text-center">{{ $uom }}</td>
                            <td class="text-center">{{ number_format($qty,2) }}</td>
                            <td class="text-center">{{ number_format($price,2) }}</td>
                            <td class="text-end">{{ number_format($total,2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">{{ __('pos.no_items') }}</td></tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr><th colspan="5" class="text-end">{{ __('pos.subtotal') }}</th><th class="text-end">{{ number_format((float)$sale->subtotal,2) }}</th></tr>
                    <tr><th colspan="5" class="text-end">{{ __('pos.discount') }}</th><th class="text-end">{{ number_format((float)$sale->discount,2) }}</th></tr>
                    <tr><th colspan="5" class="text-end">{{ __('pos.tax') }}</th><th class="text-end">{{ number_format((float)$sale->tax,2) }}</th></tr>
                    <tr><th colspan="5" class="text-end">{{ __('pos.grand_total') }}</th><th class="text-end">{{ number_format((float)$sale->grand_total,2) }}</th></tr>
                    </tfoot>
                </table>
            </div>

            <div class="small text-muted">
                <i class="fa-regular fa-circle-question"></i> {{ __('pos.print_hint') }}
            </div>
        </div>
    </section>

    {{-- طباعة --}}
    <script>
        function printArea(){
            let el = document.getElementById('printArea').innerHTML;
            let old = document.body.innerHTML;
            document.body.innerHTML = el;
            window.print();
            document.body.innerHTML = old;
            location.reload();
        }
    </script>

    {{-- SweetAlert2 (نفس أسلوب الحذف) --}}
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
                    Swal.fire('تم الحذف!', '✅ تم الحذف بنجاح.', 'success');
                }
            })
        }
    </script>
</div>
