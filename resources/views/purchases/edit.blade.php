@extends('layouts.master')

@section('content')
    {{-- إدارة فاتورة شراء (تعديل) --}}
    @livewire('purchases.manage', ['id' => $id])
@endsection
