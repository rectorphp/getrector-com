<h2>Node finder stats</h2>

<h3>Queries</h3>

<ul>
@foreach ($queriesToCount as $query => $count)
    <li>
        {{ $query }}: {{ $count }}
    </li>
@endforeach
</ul>

<hr>

<h3>Node Types</h3>

<ul>
@foreach ($nodeTypesToCount as $nodeType => $count)
    <li>
        {{ $nodeType }}: {{ $count }}
    </li>
@endforeach
</ul>

<hr>

<h3>Sets</h3>

<ul>
    @foreach ($setsToCount as $set => $count)
        <li>
            {{ $set }}: {{ $count }}
        </li>
    @endforeach
</ul>

