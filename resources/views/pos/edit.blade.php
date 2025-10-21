@extends('layouts.master')
@section('content')
    @livewire('pos.manage', ['id' => $id])
@endsection
