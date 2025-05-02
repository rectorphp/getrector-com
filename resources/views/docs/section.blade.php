@extends('base')

@php
    $page_title = $section_title . ' | Documentation';
@endphp

@section('main')
    <div class="row mt-0" id="documentation">
        <div class="col-12 col-sm-3 mt-3 mt-sm-4" id="documentation_menu">
            {{-- @see https://github.com/rectorphp/rector/tree/main/docs --}}

            <ul class="mt-3">
                <li>
                    <a href="{{ action(\App\Controller\DocumentationController::class) }}">Introduction</a>
                </li>
            </ul>

            @foreach ($documentation_menu_categories as $category => $documentation_menu_items)
                <hr class="border-line">

                <h4 class="mt-4 mb-3">{{ $category }}</h4>

                <ul>
                    @foreach ($documentation_menu_items as $documentation_menu_item)
                        @php
                            /** @var $documentation_menu_item \App\Documentation\DocumentationMenuItem */
                        @endphp

                        <li>
                            @if ($documentation_menu_item->isNew())
                                <div class="badge text-white bg-danger">NEW</div>
                                &nbsp;
                            @endif

                            <a href="{{ $documentation_menu_item->getHref() }}"
                               class="{{ $documentation_menu_item->isNew() ? 'text-bold' : '' }}"
                            >
                                {{ $documentation_menu_item->getLabel() }}

                            </a>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>

        <div class="col-12 col-sm-9" id="documentation">
            <h1 class="mb-4">{{ $section_title }}</h1>

            {!! markdown($section_markdown_contents) !!}
        </div>
    </div>
@endsection
