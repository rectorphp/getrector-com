@extends('base')

@section('main')
    <div id="simple_page">
        <h1>Node Finder Stats</h1>


        <p>
            Anonymous search data from <a href="{{ action(\App\Controller\FindRuleController::class) }}">Find rule</a> page
        </p>

        <div class="row">
            <div class="col-md-4">
            <h3>Queries</h3>


        <ul>
        @foreach ($queriesToCount as $query => $count)
            <li>
                {{ $query }}: {{ $count }}
            </li>
        @endforeach
        </ul>

            </div>
            <div class="col-md-4">


        <h3>Node Types</h3>

        <ul>
        @foreach ($nodeTypesToCount as $nodeType => $count)
            <li>
                {{ $nodeType }}: {{ $count }}
            </li>
        @endforeach
        </ul>

            </div>
            <div class="col-md-4">


            <h3>Sets</h3>

            <ul>
                @foreach ($setsToCount as $set => $count)
                    <li>
                        {{ $set }}: {{ $count }}
                    </li>
                @endforeach
            </ul>
            </div>
        </div>
    </div>
@endsection
