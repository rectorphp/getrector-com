<li class="nav-item">
    <a href="{{ action(\Rector\Website\Http\Controllers\AboutController::class) }}"
       class="nav-link">About Rector</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Http\Controllers\ForCompaniesController::class) }}"
       class="nav-link">Hire Team</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Http\Controllers\DemoController::class) }}" class="nav-link">Try
        Rector</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Http\Controllers\ContactController::class) }}"
       class="nav-link">Contact</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Http\Controllers\BlogController::class) }}" class="nav-link">Blog</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Http\Controllers\DocumentationController::class) }}"
       class="nav-link">Docs</a>
</li>

@if (isset($includeBook))
    <li class="nav-item">
        <a href="{{ action(\Rector\Website\Http\Controllers\BookController::class) }}"
           class="nav-link">Book</a>
    </li>
@endif
