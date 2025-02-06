@extends('layouts.base')
@section('content')
<div class="p-5">
    <livewire:datatable.datatable :data="$data" />
</div>
@endsection
