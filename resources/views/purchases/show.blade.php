@extends('layouts.master')

@section('content')
    @livewire('purchases.show', ['purchaseId' => $id])
@endsection
