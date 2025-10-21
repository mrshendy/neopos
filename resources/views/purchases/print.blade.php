{{-- لو عندك layout للطباعة استعمله، وإلا استخدم master عادي --}}
@extends('layouts.master')

@section('content')
    @livewire('purchases.print-a4', ['purchaseId' => $id])
@endsection
