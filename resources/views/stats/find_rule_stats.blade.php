@extends('base')

@section('main')
    <div id="simple_page">
        <h1>Node Finder Stats</h1>

        <p>
            Anonymous search data from <a href="{{ action(\App\Controller\FindRuleController::class) }}">Find rule</a> page
        </p>

        <div class="row">
            <div class="col-md-5">
                <h3>Rules</h3>

                <ul>
                    @foreach ($rulesToCount as $rule => $count)
                        <li>
                            {{ $rule }}: {{ $count }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-3">
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
                <h3>Node Types ({{ $nonEmptyNodeTypes }})</h3>

                <ul>
                    @foreach ($nodeTypesToCount as $nodeType => $count)
                        <li>
                            {{ $nodeType }}: {{ $count }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-4">
                <h3>Sets ({{ $nonEmptySets }})</h3>

                <ul>
                    @foreach ($setsToCount as $set => $count)
                        <li>
                            {{ $set }}:&nbsp;&nbsp;<strong>{{ $count }}</strong>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="clearfix"></div>

        <hr>

        <h3>Day by day stats</h3>

        <ul>
            @foreach ($datesToCount as $date => $count)
                <li>
                    {{ $date }}:&nbsp;&nbsp;<strong>{{ $count }}</strong>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
