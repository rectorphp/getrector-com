<li class="nav-item">
    <a href="{{ action(\App\Controller\Demo\DemoController::class) }}"
       class="nav-link">Try Rector</a>
</li>

<li class="nav-item">
    <a href="{{ action(\App\Controller\HireTeamController::class) }}"
       class="nav-link">Hire Team</a>
</li>

<li class="nav-item">
    <a href="{{ action(\App\Controller\FindRuleController::class) }}"
       class="nav-link">Find Rule</a>
    @if (!isset($includeFooterLinks))
        <div class="badge text-white bg-danger"
             style="float:right; margin-top: -4.7em; margin-right: -.3em; font-size: .6em">NEW
        </div>
    @endif
</li>

<li class="nav-item">
    <a href="{{ action(\App\Controller\InteractiveController::class) }}"
       class="nav-link">Play & Learn</a>
    @if (!isset($includeFooterLinks))
        <div class="badge text-white bg-danger"
             style="float:right; margin-top: -4.7em; margin-right: -.3em; font-size: .6em">NEW
        </div>
    @endif
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
        <a href="{{ action(\App\Ast\Controller\AstController::class) }}"
           class="nav-link">Play with AST</a>
    </li>

    <li class="nav-item">
        <a href="{{ action(\App\Controller\FindRuleController::class) }}"
           class="nav-link">Find rule</a>
    </li>

    <li class="nav-item">
        <a href="{{ action(\App\Controller\Demo\CustomRuleController::class) }}"
           class="nav-link">Custom Rule</a>
    </li>

    <li class="nav-item">
        <a href="{{ action(\App\Controller\CodebaseRenovationController::class) }}"
           class="nav-link">Codebase Renovation</a>
    </li>

    <li class="nav-item">
        <a onclick="toggleTheme()" class="nav-link" id="theme_toggle" style="cursor: pointer">Dark/Light Theme</a>
    </li>
@endif
