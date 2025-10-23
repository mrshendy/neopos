@extends('layouts.master')
@section('title', 'edit_coupon')
@section('content')
    @livewire('coupons.manage', ['coupon' => $coupon])
@endsection
