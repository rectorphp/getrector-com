<li class="nav-item">
    <a href="{{ action(\Rector\Website\Http\Controller\AboutController::class) }}"
       class="nav-link">About Rector</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Http\Controller\ForCompaniesController::class) }}"
       class="nav-link">Hire Team</a>
</li>

<li class="nav-item">
<<<<<<< HEAD
<<<<<<< HEAD
    <a href="{{ action(\Rector\Website\Http\Controller\Demo\DemoController::class) }}"
=======
    <a href="{{ action(\Rector\Website\Http\Controllers\Demo\DemoController::class) }}"
>>>>>>> 3b46dec (be toelrant about uiud)
=======
    <a href="{{ action(\Rector\Website\Http\Controller\Demo\DemoController::class) }}"
>>>>>>> b18d765 (lock carbon to keep compatbility with laravel)
       class="nav-link">Try
        Rector</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Http\Controller\ContactController::class) }}"
       class="nav-link">Contact</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Http\Controller\Blog\BlogController::class) }}"
       class="nav-link">Blog</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Http\Controller\DocumentationController::class) }}"
       class="nav-link">Docs</a>
</li>

@if (isset($includeBook))
    <li class="nav-item">
        <a href="{{ action(\Rector\Website\Http\Controller\BookController::class) }}"
           class="nav-link">Book</a>
    </li>
@endif
