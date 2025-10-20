@extends('layouts.master')
@section('title', 'edit_offer')
@section('content')
    @livewire('offers.manage', ['offer' => $offer])
@endsection
