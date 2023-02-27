<nav class="navbar navbar-expand-md navbar-dark bg-dark mobile-fixed-top">
    <div class="container">
        <button class="navbar-toggler ms-2 ms-md-0" type="button" data-toggle="collapse"
                data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <ul class="navbar-nav me-auto text-left text-md-center">
                <li class="nav-item me-0 me-md-5 mb-3 mb-md-0" id="top_right_logo">
                    <a href="{{ route(\Rector\Website\ValueObject\RouteName::HOMEPAGE) }}">
                        <img src="{{ asset('assets/images/logo/rector-white.svg') }}" alt="">
                    </a>
                </li>

                @include('_snippets/menu_items')
            </ul>
        </div>

        <div>
            <ul class="navbar-nav me-auto text-md-center">
                <li class="nav-item">
                    <a href="http://github.com/rectorphp/rector" class="nav-link">
                        <em class="fab fa-github fa-lg"></em>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
