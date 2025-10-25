<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ trans('settings_trans.area') }}</h4>
        <button class="btn btn-primary" wire:click="openCreate">
            {{ trans('settings_trans.add_new_area') }}
        </button>
    </div>

    {{-- فلاتر أعلى الجدول --}}
    <div class="row g-2 mb-3">
        <div class="col-md-3">
            <input class="form-control" placeholder="{{ __('Search by name (AR/EN) ...') }}"
                   wire:model.debounce.300ms="search">
        </div>
        <div class="col-md-3">
            <select class="form-select" wire:model="filterCountry">
                <option value="">{{ trans('settings_trans.country_select') }}</option>
                @foreach($countries as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" wire:model="filterGovernorate">
                <option value="">{{ trans('settings_trans.governorate_select') }}</option>
                @foreach($govsFilterOptions as $g)
                    <option value="{{ $g->id }}">{{ is_array($g->name) ? ($g->name[app()->getLocale()] ?? $g->name['ar'] ?? $g->name['en']) : $g->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" wire:model="filterCity">
                <option value="">{{ trans('settings_trans.city_select') }}</option>
                @foreach($citiesFilterOptions as $ct)
                    <option value="{{ $ct->id }}">{{ is_array($ct->name) ? ($ct->name[app()->getLocale()] ?? $ct->name['ar'] ?? $ct->name['en']) : $ct->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1">
            <select class="form-select" wire:model="perPage">
                <option>10</option><option>15</option><option>25</option><option>50</option>
            </select>
        </div>
    </div>

    <div class="card"><div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('settings_trans.name_ar') }}</th>
                <th>{{ trans('settings_trans.name_en') }}</th>
                <th>{{ trans('settings_trans.name_country') }}</th>
                <th>{{ trans('settings_trans.name_governoratees') }}</th>
                <th>{{ trans('settings_trans.name_city') }}</th>
                <th style="width:160px">{{ trans('settings_trans.action') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($rows as $i => $row)
                <tr>
                    <td>{{ $rows->firstItem() + $i }}</td>
                    <td>{{ $row->getTranslation('name','ar') }}</td>
                    <td>{{ $row->getTranslation('name','en') }}</td>
                    <td>{{ $row->country->name ?? '-' }}</td>
                    <td>{{ $row->governoratees->name ?? '-' }}</td>
                    <td>{{ $row->city->name ?? '-' }}</td>
                    <td>
                       <div class="btn-group btn-group-sm">
  <button type="button" class="btn btn-outline-secondary" wire:click="openEdit({{ $row->id }})" title="Edit" aria-label="Edit">
    <i class="ri-pencil-line me-1"></i> Edit
  </button>
  <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $row->id }})" title="Delete" aria-label="Delete">
    <i class="ri-delete-bin-6-line me-1"></i> Delete
  </button>
</div>

                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No data</td></tr>
            @endforelse
            </tbody>
        </table>
    </div></div>

    <div class="mt-2">{{ $rows->links() }}</div>

    {{-- Modal --}}
    <div class="modal fade @if($showModal) show d-block @endif" tabindex="-1" style="@if($showModal)display:block;@endif">
        <div class="modal-dialog"><div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $editingId ? trans('settings_trans.update_area') : trans('settings_trans.add_new_area') }}</h5>
                <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label">{{ trans('settings_trans.name_ar') }}</label>
                    <input class="form-control" wire:model.defer="name_ar">
                    @error('name_ar') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="mb-2">
                    <label class="form-label">{{ trans('settings_trans.name_en') }}</label>
                    <input class="form-control" wire:model.defer="name_en">
                    @error('name_en') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">{{ trans('settings_trans.country_select') }}</label>
                        <select class="form-select" wire:model="id_country">
                            <option value="">{{ trans('settings_trans.country_select') }}</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('id_country') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ trans('settings_trans.governorate_select') }}</label>
                        <select class="form-select" wire:model="id_governoratees">
                            <option value="">{{ trans('settings_trans.governorate_select') }}</option>
                            @foreach($governoratesOptions as $g)
                                <option value="{{ $g->id }}">{{ is_array($g->name) ? ($g->name[app()->getLocale()] ?? $g->name['ar'] ?? $g->name['en']) : $g->name }}</option>
                            @endforeach
                        </select>
                        @error('id_governoratees') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ trans('settings_trans.city_select') }}</label>
                        <select class="form-select" wire:model="id_city">
                            <option value="">{{ trans('settings_trans.city_select') }}</option>
                            @foreach($citiesOptions as $ct)
                                <option value="{{ $ct->id }}">{{ is_array($ct->name) ? ($ct->name[app()->getLocale()] ?? $ct->name['ar'] ?? $ct->name['en']) : $ct->name }}</option>
                            @endforeach
                        </select>
                        @error('id_city') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" wire:click="$set('showModal', false)">{{ __('Close') }}</button>
                <button class="btn btn-primary" wire:click="save">{{ trans('settings_trans.submit') }}</button>
            </div>
        </div></div>
    </div>
    @if($showModal)<div class="modal-backdrop fade show"></div>@endif
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

