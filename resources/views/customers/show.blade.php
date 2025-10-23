@extends('layouts.master')

@section('content')
    <livewire:customer.show :customerId="$id" />
@endsection
