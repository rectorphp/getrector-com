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
                    <a href="{{ route('documentation') }}">Introduction</a>
                </li>
            </ul>

            @foreach ($documentations_sections_by_category as $category => $documentation_sections)
                <hr class="border-line">

                <h4 class="mt-4 mb-3">{{ $category }}</h4>

                <ul>
                    @foreach ($documentation_sections as $documentation_section)
                        @php
                            /** @var $documentation_section \Rector\Website\ValueObject\DocumentationSection */
                        @endphp

                        <li>
                            <a href="{{ route(\Rector\Website\ValueObject\Routing\RouteName::DOCUMENTATION, ['section' => $documentation_section->getSlug()]) }}">
                                {{ $documentation_section->getName() }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endforeach

            <ul>
                <li>
                    <a href="https://leanpub.com/rector-the-power-of-automated-refactoring">
                        Learn Rector in Depth from Book
                    </a>
                </li>
            </ul>
        </div>

        <div class="col-12 col-sm-9" id="documentation">
            <h1>{{ $section_title }}</h1>

            {!! $section_html_contents !!}
        </div>
    </div>
@endsection
