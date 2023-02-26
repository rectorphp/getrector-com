{% if post.isNew %}
    new post
{% elseif post.updated %}
    updated post
{% endif %}
-----
@if ($post->isNew)
    new post
@elseif ($post->updated)
    updated post
@endif
