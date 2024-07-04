<li class="nav-item">
    <a href="{{ action(\App\Controller\AboutController::class) }}"
       class="nav-link">About</a>
</li>

<li class="nav-item">
    <a href="{{ action(\App\Controller\HireTeamController::class) }}"
       class="nav-link">Hire Team</a>
</li>

<li class="nav-item">
    <a href="{{ action(\App\Controller\Demo\DemoController::class) }}"
       class="nav-link">Try
        Rector</a>
</li>

<li class="nav-item">
    <a href="{{ action(\App\Controller\InteractiveController::class) }}"
       class="nav-link">Play & Learn</a>
</li>

<li class="nav-item">
    <a href="{{ action(\App\Controller\ContactController::class) }}"
       class="nav-link">Contact</a>
</li>

<li class="nav-item">
    <a href="{{ action(\App\Controller\Blog\BlogController::class) }}"
       class="nav-link">Blog</a>
</li>

<li class="nav-item">
    <a href="{{ action(\App\Controller\DocumentationController::class) }}"
       class="nav-link">Docs</a>
</li>

@if (isset($includeFooterLinks))
    <li class="nav-item">
        <a href="{{ action(\App\Controller\BookController::class) }}"
           class="nav-link">Book</a>
    </li>

    <li class="nav-item">
        <a href="{{ action(\App\Controller\InteractiveController::class) }}"
           class="nav-link">Play and Learn</a>
    </li>

    <li class="nav-item">
        <a href="{{ action(\App\Ast\Controller\AstController::class) }}"
           class="nav-link">Play with AST</a>
    </li>

    <li class="nav-item">
        <a href="{{ action(\App\Controller\FilterRectorController::class) }}"
           class="nav-link">Find rule</a>
    </li>
@endif
