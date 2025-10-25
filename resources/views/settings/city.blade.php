@extends('layouts.master')
@section('title')
{{ trans('main_trans.title') }}
@stop

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between"><!-- fix: align-products-center -->
            <h4 class="mb-sm-0">{{ trans('settings_trans.settings') }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('settings_trans.settings') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('settings_trans.nationalities_settings') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- end page title -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ trans('settings_trans.city') }}</h5>

                {{-- Message --}}
                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="fa fa-times"></i>
                        </button>
                        <strong>Success !</strong> {{ session('success') }}
                    </div>
                @endif

                @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="fa fa-times"></i>
                        </button>
                        <strong>Error !</strong> {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Buttons Grid -->
            <div class="d-grid gap-2">
                <button data-bs-toggle="modal" data-bs-target="#signupModals" class="btn btn-primary waves-effect waves-light" type="button">
                    {{ trans('settings_trans.add_new_city') }}
                </button>
            </div>

            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th data-ordering="false">{{ trans('settings_trans.sr_no') }}</th>
                            <th data-ordering="false">{{ trans('settings_trans.id') }}</th>
                            <th data-ordering="false">{{ trans('settings_trans.name_ar') }}</th>
                            <th data-ordering="false">{{ trans('settings_trans.name_en') }}</th>
                            <th data-ordering="false">{{ trans('settings_trans.name_country') }}</th>
                            <th data-ordering="false">{{ trans('settings_trans.name_governoratees') }}</th>
                            <th>{{ trans('settings_trans.create_date') }}</th>
                            <th>{{ trans('settings_trans.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; ?>
                        @foreach($citys as $city)
                            <tr>
                                <?php $i++; ?>
                                <td>{{ $i }}</td>
                                <td>{{ $city->id }}</td>
                                <td>{{ $city->getTranslation('name','ar') }}</td>
                                <td>{{ $city->getTranslation('name','en') }}</td>
                                <td>{{ $city->country->name }}</td>
                                <td>{{ $city->governoratees->name }}</td>
                                <td>{{ $city->created_at }}</td>
                                <td>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button data-bs-toggle="modal"
                                                        data-bs-target="#signupModals{{ $city->id }}"
                                                        class="dropdown-item edit-item-btn">
                                                    <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button data-bs-toggle="modal"
                                                        data-bs-target="#signupModal{{ $city->id }}"
                                                        class="dropdown-item edit-item-btn">
                                                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <!-- edit -->
                            <div id="signupModals{{ $city->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 overflow-hidden">
                                        <div class="modal-header p-3">
                                            <h4 class="card-title mb-0">{{ trans('settings_trans.update_city') }}</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('city.update','test') }}" method="post">
                                                {{ method_field('patch') }}
                                                @csrf

                                                <input type="hidden" name="id" value="{{ $city->id }}">

                                                <div class="form-group">
                                                    <label for="name_ar_{{ $city->id }}" class="form-label">{{ trans('settings_trans.name_ar') }}</label>
                                                    <input type="text" class="form-control" id="name_ar_{{ $city->id }}" name="name_ar"
                                                           value="{{ $city->getTranslation('name','ar') }}">
                                                </div>

                                                <div class="form-group">
                                                    <label for="name_en_{{ $city->id }}" class="form-label">{{ trans('settings_trans.name_en') }}</label>
                                                    <input type="text" class="form-control" id="name_en_{{ $city->id }}" name="name_en"
                                                           value="{{ $city->getTranslation('name','en') }}">
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="my-1 mr-2">{{ trans('settings_trans.country_select') }}</label>
                                                            <select name="id_country"
                                                                    id="id_country_{{ $city->id }}"
                                                                    class="form-control js-country"
                                                                    data-url="{{ url('/settings/governorates/by-country') }}">
                                                                <option value="" selected disabled>{{ trans('settings_trans.country_select') }}</option>
                                                                @foreach ($country as $c)
                                                                    <option value="{{ $c->id }}" {{ $c->id == $city->id_country ? 'selected' : '' }}>
                                                                        {{ $c->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="my-1 mr-2">{{ trans('settings_trans.governorate_select') }}</label>
                                                            <select id="id_governoratees_{{ $city->id }}"
                                                                    name="id_governoratees"
                                                                    class="form-control js-governorate"
                                                                    data-selected="{{ $city->id_governoratees }}">
                                                                <option value="" selected disabled>{{ trans('settings_trans.governorate_select') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">{{ trans('settings_trans.submit') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- delete -->
                            <div id="signupModal{{ $city->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 overflow-hidden">
                                        <div class="modal-header p-3">
                                            <h4 class="card-title mb-0">{{ trans('settings_trans.massagedelete_city') }}</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center p-5">
                                            <lord-icon src="{{ URL::asset('assets/images/icon/tdrtiskw.json') }}"
                                                       trigger="loop" colors="primary:#f7b84b,secondary:#405189"
                                                       style="width:130px;height:130px"></lord-icon>
                                            <div class="mt-4 pt-4">
                                                <h4>{{ trans('settings_trans.massagedelete_d') }}!</h4>
                                                <p class="text-muted">{{ trans('settings_trans.massagedelete_p') }} {{ $city->name }}</p>
                                                <form action="{{ route('city.destroy','test') }}" method="post">
                                                    {{ method_field('delete') }}
                                                    {{ csrf_field() }}
                                                    <input class="form-control" name="id" value="{{ $city->id }}" type="hidden">
                                                    <button class="btn btn-warning" data-bs-target="#secondmodal" data-bs-toggle="modal" data-bs-dismiss="modal">
                                                        {{ trans('settings_trans.massagedelete_city') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- create -->
<div id="signupModals" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0">{{ trans('settings_trans.add_new_city') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('city.store') }}" method="post">
                    {{ csrf_field() }}

                    <div class="mb-3">
                        <label for="name_ar" class="form-label">{{ trans('settings_trans.name_ar') }}</label>
                        <input type="text" class="form-control" id="name_ar" name="name_ar" placeholder="{{ trans('settings_trans.name_ar_enter') }}">
                    </div>
                    <div class="mb-3">
                        <label for="name_en" class="form-label">{{ trans('settings_trans.name_en') }}</label>
                        <input type="text" class="form-control" id="name_en" name="name_en" placeholder="{{ trans('settings_trans.name_en_enter') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="my-1 mr-2">{{ trans('settings_trans.country_select') }}</label>
                            <select id="create_country"
                                    name="id_country"
                                    class="form-control SlectBox"
                                    data-url="{{ url('/settings/governorates/by-country') }}">
                                <option value="" selected disabled>{{ trans('settings_trans.country_select') }}</option>
                                @foreach ($country as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="my-1 mr-2">{{ trans('settings_trans.governorate_select') }}</label>
                            <select id="create_governorate" name="id_governoratees" class="form-control">
                                <option value="" selected disabled>{{ trans('settings_trans.governorate_select') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">{{ trans('settings_trans.submit') }}</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    function t(key, fallback){
        // لو المفتاح غير موجود في الترجمة، استخدم fallback
        return @json(__('settings_trans.loading')) && key === 'loading' ? @json(__('settings_trans.loading')) :
               @json(__('settings_trans.failed_load')) && key === 'failed' ? @json(__('settings_trans.failed_load')) :
               fallback;
    }

    function fillGovernorates(countrySelect, govSelect, selectedVal) {
        if (!countrySelect || !govSelect) return;

        const url = countrySelect.dataset.url || '{{ url('/settings/governorates/by-country') }}';
        const countryId = countrySelect.value;
        if (!countryId) return;

        govSelect.innerHTML = `<option value="" selected disabled>${t('loading','جاري التحميل...')}</option>`;

        fetch(`${url}?country_id=${countryId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(list => {
            govSelect.innerHTML = `<option value="" selected disabled>{{ trans('settings_trans.governorate_select') }}</option>`;
            (list || []).forEach(g => {
                const opt = document.createElement('option');
                const loc = '{{ app()->getLocale() }}';
                let label = g.name;

                // دعم json translatable
                if (typeof g.name === 'object' && g.name !== null) {
                    label = g.name[loc] ?? g.name.ar ?? g.name.en ?? Object.values(g.name)[0];
                }

                opt.value = g.id;
                opt.textContent = label ?? g.name ?? `#${g.id}`;
                if (selectedVal && String(selectedVal) === String(g.id)) {
                    opt.selected = true;
                }
                govSelect.appendChild(opt);
            });
        })
        .catch(() => {
            govSelect.innerHTML = `<option value="" selected disabled>${t('failed','تعذّر تحميل المحافظات')}</option>`;
        });
    }

    // إنشاء مدينة: عند تغيير الدولة
    const createCountry = document.getElementById('create_country');
    const createGov     = document.getElementById('create_governorate');
    if (createCountry) {
        createCountry.addEventListener('change', function(){
            fillGovernorates(createCountry, createGov, null);
        });
    }

    // تعديل مدينة: عند فتح أي مودال تعديل، حمّل المحافظات وحدّد الحالية
    document.querySelectorAll('div[id^="signupModals"]').forEach(function (modal) {
        if (modal.id === 'signupModals') return; // استثناء مودال الإنشاء

        modal.addEventListener('shown.bs.modal', function () {
            const countrySelect = modal.querySelector('.js-country');
            const govSelect     = modal.querySelector('.js-governorate');
            const selectedVal   = govSelect ? govSelect.getAttribute('data-selected') : null;

            if (countrySelect && govSelect) {
                fillGovernorates(countrySelect, govSelect, selectedVal);

                // إعادة تعبئة عند تغيير الدولة داخل المودال
                countrySelect.addEventListener('change', function () {
                    fillGovernorates(countrySelect, govSelect, null);
                }, { once: true });
            }
        });
    });

})();
</script>
@endpush
