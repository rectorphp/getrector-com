<h3>Featured</h3>

@foreach ($recentPosts as $recentPost)
    @php /** @var \App\Entity\Post $recentPost */ @endphp

    <a href="{{ action(\App\Controller\Blog\PostController::class, ['postSlug' => $recentPost->getSlug() ]) }}">
        <li style="list-style: none; font-size: 1.25em; line-height: 1.9em">
            <div class="d-flex" style="justify-content: space-between">
                {{ $recentPost->getTitle() }}

                <div style="width:5em; justify-content: space-between; white-space: nowrap; color:#BBB;">
                    {{ $recentPost->getDateTime()->format('M j, Y') }}
                </div>
            </div>
        </li>

    </a>
@endforeach
