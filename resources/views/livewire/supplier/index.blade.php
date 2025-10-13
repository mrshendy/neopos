<div>
    {{-- ✅ تنبيهات --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- فلاتر --}}
    <div class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" wire:model.debounce.500ms="search" class="form-control"
                placeholder="{{ __('pos.search_supplier') }}">
        </div>
        <div class="col-md-2">
            <select wire:model="filter_category" class="form-select">
                <option value="">{{ __('pos.filter_category') }}</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">
                        {{ app()->getLocale() == 'ar' ? $cat->getTranslation('name', 'ar') : $cat->getTranslation('name', 'en') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select wire:model="filter_governorate" class="form-select">
                <option value="">{{ __('pos.filter_governorate') }}</option>
                @foreach ($governorates as $g)
                    <option value="{{ $g->id }}">
                        {{ $g->name ?? ($g->getTranslation('name', 'ar') ?? $g->getTranslation('name', 'en')) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select wire:model="filter_city" class="form-select">
                <option value="">{{ __('pos.filter_city') }}</option>
                @foreach ($cities as $c)
                    <option value="{{ $c->id }}">
                        {{ $c->name ?? ($c->getTranslation('name', 'ar') ?? $c->getTranslation('name', 'en')) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select wire:model="filter_status" class="form-select">
                <option value="">{{ __('pos.filter_status') }}</option>
                <option value="active">{{ __('pos.status_active') }}</option>
                <option value="inactive">{{ __('pos.status_inactive') }}</option>
            </select>
        </div>
    </div>

    {{-- جدول --}}
    <div class="card border-0 shadow-lg rounded-4 stylish-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('pos.supplier_code') }}</th>
                            <th>{{ __('pos.supplier_name') }}</th>
                            <th>{{ __('pos.category') }}</th>
                            <th>{{ __('pos.governorate') }}</th>
                            <th>{{ __('pos.city') }}</th>
                            <th>{{ __('pos.status') }}</th>
                            <th class="text-end">{{ __('pos.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->code }}</td>
                                <td>{{ app()->getLocale() == 'ar' ? $row->getTranslation('name', 'ar') : $row->getTranslation('name', 'en') }}
                                </td>
                                <td>{{ optional($row->category) ? (app()->getLocale() == 'ar' ? $row->category->getTranslation('name', 'ar') : $row->category->getTranslation('name', 'en')) : '-' }}
                                </td>
                                <td>{{ optional($row->governorate)->name ?? '-' }}</td>
                                <td>{{ optional($row->city)->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $row->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $row->status == 'active' ? __('pos.status_active') : __('pos.status_inactive') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('suppliers.edit', $row->id) }}"
                                        class="btn btn-primary rounded-pill px-3 shadow-sm">
                                        <i class="mdi mdi-square-edit-outline"></i>
                                    </a>
                                    <button wire:click="toggleStatus({{ $row->id }})"
                                        class="btn btn-success rounded-pill px-3 shadow-sm"
                                        title="{{ __('pos.status') }}">
                                        <i class="mdi mdi-toggle-switch"></i>
                                    </button>
                                    <button onclick="confirmDelete({{ $row->id }})"
                                        class="btn btn-danger rounded-pill px-3 shadow-sm">
                                        <i class="mdi mdi-trash-can-outline"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted p-4">{{ __('pos.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $suppliers->links() }}</div>
        </div>
    </div>

    {{-- ربط حدث SweetAlert2 --}}
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            Livewire.on('confirmDelete', (id) => {
                confirmDelete(id);
            });
        });
    </script>

</div>
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
