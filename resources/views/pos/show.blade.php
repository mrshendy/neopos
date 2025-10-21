@extends('layouts.master')
@section('content')
    @livewire('pos.show', ['id' => $id])
@endsection
