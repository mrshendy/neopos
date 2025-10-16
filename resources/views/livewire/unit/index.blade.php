<div>
    {{-- Filters --}}
    <div class="card shadow-sm rounded-4 mb-3 stylish-card">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label mb-1"><i class="mdi mdi-magnify"></i> {{ __('pos.filter_search') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search"
                           placeholder="{{ app()->getLocale()=='ar' ? 'ابحث بالاسم...' : 'Search by name...' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1"><i class="mdi mdi-view-grid-plus-outline"></i> {{ __('pos.filter_level') }}</label>
                    <select class="form-select" wire:model="filter_level">
                        <option value="">{{ app()->getLocale()=='ar' ? 'الكل' : 'All' }}</option>
                        <option value="minor">{{ __('pos.minor') }}</option>
                        <option value="middle">{{ __('pos.middle') }}</option>
                        <option value="major">{{ __('pos.major') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1"><i class="mdi mdi-toggle-switch"></i> {{ __('pos.filter_status') }}</label>
                    <select class="form-select" wire:model="filter_status">
                        <option value="">{{ app()->getLocale()=='ar' ? 'الكل' : 'All' }}</option>
                        <option value="active">{{ __('pos.active') }}</option>
                        <option value="inactive">{{ __('pos.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary rounded-pill shadow-sm" wire:click="render">
                        <i class="mdi mdi-magnify"></i> {{ __('pos.btn_search') }}
                    </button>
                    <button class="btn btn-outline-secondary rounded-pill shadow-sm" wire:click="resetFilters">
                        <i class="mdi mdi-refresh"></i> {{ __('pos.btn_reset') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('pos.th_id') }}</th>
                            <th>{{ __('pos.th_name') }}</th>
                            <th>{{ __('pos.th_level') }}</th>
                            <th>{{ __('pos.th_status') }}</th>
                            <th class="text-end">{{ __('pos.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($units as $u)
                            <tr>
                                <td>{{ $u->id }}</td>
                                <td>
                                    @php $lang = app()->getLocale(); @endphp
                                    {{ $u->getTranslation('name', $lang) }}
                                </td>
                                <td>
                                    @switch($u->level)
                                        @case('minor')  <span class="badge bg-secondary">{{ __('pos.minor') }}</span> @break
                                        @case('middle') <span class="badge bg-info text-dark">{{ __('pos.middle') }}</span> @break
                                        @case('major')  <span class="badge bg-primary">{{ __('pos.major') }}</span> @break
                                    @endswitch
                                </td>
                                <td>
                                    @if($u->status==='active')
                                        <span class="badge bg-success">{{ __('pos.active') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('pos.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('units.edit', $u->id) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                        <i class="mdi mdi-pencil-outline"></i> {{ __('pos.btn_edit') }}
                                    </a>
                                    <button class="btn btn-outline-warning btn-sm rounded-pill" wire:click="toggleStatus({{ $u->id }})">
                                        <i class="mdi mdi-toggle-switch-outline"></i> {{ __('pos.btn_toggle_status') }}
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm rounded-pill" onclick="confirmDelete({{ $u->id }})">
                                        <i class="mdi mdi-delete-outline"></i> {{ __('pos.btn_delete') }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="mdi mdi-information-outline"></i>
                                    {{ app()->getLocale()=='ar' ? 'لا توجد بيانات' : 'No data' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $units->links() }}
            </div>
        </div>
    </div>
</div>
