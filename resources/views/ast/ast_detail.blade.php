@extends('base')

@php
    /** @var $ast_run \App\Entity\AstRun */
@endphp

@section('main')
    @livewire('ast-component', ['astRun' => $astRun])
@endsection
