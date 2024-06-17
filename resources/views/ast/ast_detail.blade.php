@extends('base')

@php
    /** @var $ast_run \Rector\Website\Entity\AstRun */
@endphp

@section('main')
    @livewire('ast-component', ['astRun' => $astRun])
@endsection
