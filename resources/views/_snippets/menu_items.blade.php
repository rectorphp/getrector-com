<li class="nav-item">
    <a href="{{ action(\Rector\Website\Controller\AboutController::class) }}"
       class="nav-link">About Rector</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Controller\HireTeamController::class) }}"
       class="nav-link">Hire Team</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Controller\Demo\DemoController::class) }}"
       class="nav-link">Try
        Rector</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Controller\ContactController::class) }}"
       class="nav-link">Contact</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Controller\Blog\BlogController::class) }}"
       class="nav-link">Blog</a>
</li>

<li class="nav-item">
    <a href="{{ action(\Rector\Website\Controller\DocumentationController::class) }}"
       class="nav-link">Docs</a>
</li>

@if (isset($includeFooterLinks))
    <li class="nav-item">
        <a href="{{ action(\Rector\Website\Controller\BookController::class) }}"
           class="nav-link">Book</a>
    </li>

    <li class="nav-item">
        <a href="{{ action(\Rector\Website\Controller\Ast\AstController::class) }}"
           class="nav-link">Play with AST</a>
    </li>

    <li class="nav-item">
        <a href="{{ action(\Rector\Website\Controller\FilterRectorController::class) }}"
           class="nav-link">Find rule</a>
    </li>
@endif
