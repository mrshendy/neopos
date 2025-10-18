@extends('layouts.master')

@section('title', 'تعديل فرع')

@section('content')
<div class="page-wrap">
    {{-- Livewire Component (يمرر الـ ID) --}}
    <livewire:general.branches.edit :branch_id="$branch_id" />
</div>
@endsection
