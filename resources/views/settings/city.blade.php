@extends('layouts.master')
@section('title') {{ trans('main_trans.title') }} @stop

@section('content')
    {{-- مناداة لايفواير فقط --}}
    @livewire('city.manage')
@endsection
