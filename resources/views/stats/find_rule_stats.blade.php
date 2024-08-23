@extends('base')

@section('main')
    <div id="simple_page">
        <h1>Node Finder Stats</h1>

        <p>
            Anonymous search data from <a href="{{ action(\App\Controller\FindRuleController::class) }}">Find rule</a> page
        </p>

        <div class="row">
            <div class="col-12 col-md-6">
                <h3>Top {{ count($rulesToCount) }} Rules</h3>

                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Rule</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    @foreach ($rulesToCount as $rule => $count)
                        <tr>
                            <td>{{ $rule }}</td>
                            <td class="text-right">{{ $count }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="col-12 col-md-6">
                <h3>Top {{ count($setsToCount) }} Sets</h3>

                <table class="table table-bordered">
                    <thead class="table-dark">
                    <tr>
                        <th>Set</th>
                        <th>Count</th>
                    </tr>
                    </thead>
                    @foreach ($setsToCount as $set => $count)
                        <tr>
                            <td>{{ $set }}</td>
                            <td class="text-right">{{ $count }}</td>
                        </tr>
                    @endforeach
                </table>
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
