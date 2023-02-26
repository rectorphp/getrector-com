<time datetime="{{ post.dateTime|date('Y-m-D') }}">
    {{ post.dateTime|date }}
</time>
-----
<time datetime="{{ $post->dateTime->format('Y-m-D') }}">
    {{ $post->dateTime->format() }}
</time>
