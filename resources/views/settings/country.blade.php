@extends('layouts.master')

@section('title')
    {{ trans('main_trans.title') }}
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ trans('settings_trans.settings') }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">{{ trans('settings_trans.settings') }}</a></li>
                        <li class="breadcrumb-item active">{{ trans('settings_trans.nationalities_settings') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif

    <!-- Add button -->
    <div class="d-grid gap-2 mb-3">
        <button data-bs-toggle="modal" data-bs-target="#modal-create-country" class="btn btn-primary waves-effect waves-light" type="button">
            {{ trans('settings_trans.add_new_country') }}
        </button>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ trans('settings_trans.country') }}</h5>
        </div>

        <div class="card-body">
            <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle w-100">
                <thead>
                <tr>
                    <th data-ordering="false">{{ trans('settings_trans.sr_no') }}</th>
                    <th data-ordering="false">{{ trans('settings_trans.id') }}</th>
                    <th data-ordering="false">{{ trans('settings_trans.name_ar') }}</th>
                    <th data-ordering="false">{{ trans('settings_trans.name_en') }}</th>
                    <th>{{ trans('settings_trans.create_date') }}</th>
                    <th>{{ trans('settings_trans.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($country as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->getTranslation('name', 'ar') }}</td>
                        <td>{{ $row->getTranslation('name', 'en') }}</td>
                        <td>{{ $row->created_at }}</td>
                        <td>
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-fill align-middle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <button data-bs-toggle="modal"
                                                data-bs-target="#modal-edit-country-{{ $row->id }}"
                                                class="dropdown-item">
                                            <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> {{ __('Edit') }}
                                        </button>
                                    </li>
                                    <li>
                                        <button data-bs-toggle="modal"
                                                data-bs-target="#modal-delete-country-{{ $row->id }}"
                                                class="dropdown-item">
                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> {{ __('Delete') }}
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div id="modal-edit-country-{{ $row->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 overflow-hidden">
                                <div class="modal-header p-3">
                                    <h4 class="card-title mb-0">{{ trans('settings_trans.update_country') }}</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('country.update', $row->id) }}" method="post">
                                        @csrf
                                        @method('PATCH')

                                        <div class="mb-3">
                                            <label for="name_ar_{{ $row->id }}" class="form-label">{{ trans('settings_trans.name_ar') }}</label>
                                            <input type="text" class="form-control" id="name_ar_{{ $row->id }}" name="name_ar"
                                                   value="{{ $row->getTranslation('name','ar') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="name_en_{{ $row->id }}" class="form-label">{{ trans('settings_trans.name_en') }}</label>
                                            <input type="text" class="form-control" id="name_en_{{ $row->id }}" name="name_en"
                                                   value="{{ $row->getTranslation('name','en') }}">
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">{{ trans('settings_trans.submit') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div id="modal-delete-country-{{ $row->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 overflow-hidden">
                                <div class="modal-header p-3">
                                    <h4 class="card-title mb-0">{{ trans('settings_trans.massagedelete_country') ?? trans('settings_trans.massagedelete') }}</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                                </div>
                                <div class="modal-body text-center p-5">
                                    <lord-icon src="{{ URL::asset('assets/images/icon/tdrtiskw.json') }}"
                                               trigger="loop"
                                               colors="primary:#f7b84b,secondary:#405189"
                                               style="width:130px;height:130px">
                                    </lord-icon>
                                    <div class="mt-4 pt-4">
                                        <h4>{{ trans('settings_trans.massagedelete_d') }}!</h4>
                                        <p class="text-muted">
                                            {{ trans('settings_trans.massagedelete_p') }}
                                            {{ $row->getTranslation('name', app()->getLocale()) }}
                                        </p>
                                        <form action="{{ route('country.destroy', $row->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-warning" data-bs-dismiss="modal">
                                                {{ trans('settings_trans.massagedelete') }}
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

    <!-- Create Modal -->
    <div id="modal-create-country" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 overflow-hidden">
                <div class="modal-header p-3">
                    <h4 class="card-title mb-0">{{ trans('settings_trans.add_new_country') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('country.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="name_ar_create" class="form-label">{{ trans('settings_trans.name_ar') }}</label>
                            <input type="text" class="form-control" id="name_ar_create" name="name_ar" placeholder="{{ trans('settings_trans.name_ar_enter') }}">
                        </div>
                        <div class="mb-3">
                            <label for="name_en_create" class="form-label">{{ trans('settings_trans.name_en') }}</label>
                            <input type="text" class="form-control" id="name_en_create" name="name_en" placeholder="{{ trans('settings_trans.name_en_enter') }}">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">{{ trans('settings_trans.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@endsection
