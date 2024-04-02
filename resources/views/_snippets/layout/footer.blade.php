<div id="footer">
    <div class="container">
        <div class="row pt-3">
            <div class="col-6 col-md-2 mt-2">
                Rector Team {{ now()->year }}
            </div>

            <div class="col-6 col-md-3 mt-2">
                <a href="http://github.com/rectorphp/rector" class="p-2">
                    <em class="fab fa-github fa-fw fa-lg"></em>
                </a>

                <a href="https://twitter.com/rectorphp" class="p-2">
                    <em class="fab fa-twitter fa-fw fa-lg"></em>
                </a>

                <a href="https://www.linkedin.com/company/rectorphp" class="p-2">
                    <em class="fab fa-linkedin fa-fw fa-lg"></em>
                </a>

                <a href="{{ action(\Rector\Website\Http\Controller\RssController::class) }}"
                   class="p-2">
                    <em class="fas fa-rss fa-fw fa-lg"></em>
                </a>
            </div>

            <div class="col-12 col-md-7 mt-5 mt-md-0">
                <div class="navbar">
                    <ul class="navbar-nav">
                        @include('_snippets/menu_items', ['includeBook' => true])
                    </ul>
                </div>
            </div>
        </div>

        <br>

        <div class="text-start mt-5 mb-5 text-medium text-sm-center">
            We've helped <strong>50+ companies</strong> to speed-up work and reduce technical debt
        </div>
    </div>
</div>
