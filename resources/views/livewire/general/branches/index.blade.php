<div>
    <div class="card shadow-sm rounded-4 mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label mb-1"><i class="mdi mdi-magnify"></i> بحث</label>
                    <input type="text" wire:model.debounce.400ms="search" class="form-control"
                           placeholder="ابحث بالاسم (عربي/English) أو العنوان">
                </div>
                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-filter-variant"></i> الحالة</label>
                    <select class="form-select" wire:model="status">
                        <option value="">الكل</option>
                        <option value="active">نشط</option>
                        <option value="inactive">متوقف</option>
                    </select>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <a href="{{ route('branches.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-plus-circle-outline"></i> إضافة فرع
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm rounded-4">
        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم (ع / En)</th>
                        <th>العنوان</th>
                        <th>الحالة</th>
                        <th>المستودعات</th>
                        <th class="text-end">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>
                                <div>{{ $row->getTranslation('name','ar') }}</div>
                                <small class="text-muted">{{ $row->getTranslation('name','en') }}</small>
                            </td>
                            <td>{{ $row->address }}</td>
                            <td>
                                <span class="badge {{ $row->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $row->status === 'active' ? 'نشط' : 'متوقف' }}
                                </span>
                            </td>
                            <td>
                                <i class="mdi mdi-warehouse"></i>
                                <span class="ms-1">{{ $row->warehouses_count }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('branches.edit', $row->id) }}"
                                   class="btn btn-success rounded-pill px-4 shadow-sm btn-sm">
                                    <i class="mdi mdi-pencil-outline"></i> تعديل
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">لا توجد بيانات</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $branches->links() }}
            </div>
        </div>
    </div>
</div>
